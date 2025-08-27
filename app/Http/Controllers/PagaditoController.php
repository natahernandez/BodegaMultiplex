<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

// Cargar manualmente la librería de Pagadito
require_once app_path('Libraries/Pagadito.php');

class PagaditoController extends Controller
{
    private function client(): \Pagadito
    {
        $pg = new \Pagadito(env('PAGADITO_UID'), env('PAGADITO_WSK'));
        if (filter_var(env('PAGADITO_SANDBOX', true), FILTER_VALIDATE_BOOLEAN)) {
            $pg->mode_sandbox_on(); // Sandbox
        }
        return $pg;
    }

    public function iniciar(Order $order)
    {
        // Verificar que la orden pertenece al usuario autenticado
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('shop.checkout')->withErrors(['error' => 'Orden no válida.']);
        }

        // Verificar que la orden esté en estado correcto para pago
        if ($order->estado !== 'pre_orden') {
            return redirect()->route('shop.checkout')->withErrors(['error' => 'Esta orden no está disponible para pago.']);
        }

        // Verificar si la pre-orden ha expirado
        if ($order->hasExpired()) {
            $order->expire();
            return redirect()->route('shop.checkout')->withErrors(['error' => 'Esta orden ha expirado. Por favor, crea una nueva orden.']);
        }

        // 1) Conectar
        $pg = $this->client();
        if (!$pg->connect()) {
            Log::error('Pagadito connect() failed', [
                'code' => $pg->get_rs_code(),
                'message' => $pg->get_rs_message(),
                'order_id' => $order->id
            ]);
            return redirect()->back()->withErrors(['pagadito' => 'No se pudo conectar con Pagadito. Intenta de nuevo.']);
        }
        // Forzar moneda USD explícitamente (los importes enviados estarán en USD)
        $pg->change_currency_usd();

        // 2) Obtener tasa de cambio GTQ->USD y convertir importes mostrados al usuario (GTQ) a USD para Pagadito
        $gtqToUsdRate = (float) $pg->get_exchange_rate_gtq();
        if ($gtqToUsdRate <= 0) {
            Log::warning('Fallo al obtener tasa de cambio GTQ desde Pagadito, usando respaldo de entorno.', [
                'rs_code' => $pg->get_rs_code(),
                'rs_message' => $pg->get_rs_message(),
                'order_id' => $order->id
            ]);
            $gtqToUsdRate = (float) env('GTQ_USD_RATE', 7.8);
        }

        // Nota: evitamos enviar custom_params para reducir rechazos de la API

        // 3) Enviar una sola línea con el total en USD (simplifica y evita rechazos por descuentos negativos)
        $totalUsd = round(((float) $order->total) / $gtqToUsdRate, 2);
        if ($totalUsd <= 0) {
            Log::error('Total USD inválido para Pagadito', [
                'order_id' => $order->id,
                'total_gtq' => (float) $order->total,
                'rate' => $gtqToUsdRate,
                'total_usd' => $totalUsd
            ]);
            return redirect()->route('shop.checkout')->with('error', 'No se pudo iniciar el pago.');
        }
        $pg->add_detail(1, 'Orden ' . $order->numero_orden . ' - Bodegas Multiplex', $totalUsd);

        // 3) ERN propio (número de pedido)
        $ern = $order->numero_orden;

        // 4) Ejecutar transacción (variante URL para integrar en iframe/modal)
        $paymentUrl = $pg->exec_trans_url($ern);
        if (!$paymentUrl) {
            Log::error('Pagadito exec_trans() failed', [
                'code' => $pg->get_rs_code(),
                'message' => $pg->get_rs_message(),
                'order_id' => $order->id,
                'ern' => $ern
            ]);
            return redirect()->route('shop.checkout')->with('error', 'No se pudo iniciar el pago.');
        }

        // Guardar token_trans (viene en el querystring de la URL) para poder verificar sin esperar retorno/IPN
        try {
            $query = parse_url($paymentUrl, PHP_URL_QUERY);
            parse_str($query, $params);
            if (!empty($params['token'])) {
                $payload = [
                    'tipo' => 'pagadito',
                    'estado' => 'iniciado',
                    'token_trans' => $params['token'],
                    'fecha_inicio' => now(),
                ];
                $info = json_decode($order->info_pago ?? '{}', true);
                $order->info_pago = json_encode(array_merge($info ?: [], $payload));
                $order->save();
            }
        } catch (\Throwable $t) {
            \Log::warning('No se pudo guardar token_trans de Pagadito', ['err' => $t->getMessage()]);
        }

        // Responder vista ligera que abre modal/iframe, o JSON si se llama vía AJAX
        if (request()->wantsJson()) {
            return response()->json(['url' => $paymentUrl, 'ern' => $ern]);
        }
        // Como fallback, redirigir (comportamiento anterior)
        return redirect()->away($paymentUrl);
    }

    public function retorno(Request $request)
    {
        $token = $request->query('token'); // viene desde {value}
        $ern   = $request->query('ern');   // viene desde {ern_value} (si lo incluiste)

        Log::info('Pagadito retorno', [
            'token' => $token,
            'ern' => $ern,
            'all_params' => $request->all()
        ]);

        if (!$token) {
            return redirect()->route('shop.checkout')->with('error', 'Falta token de pago.');
        }

        $pg = $this->client();
        if (!$pg->connect()) {
            return redirect()->route('shop.checkout')->with('error', 'No se pudo verificar el pago (conexión).');
        }

        if ($pg->get_status($token)) {
            $estado = $pg->get_rs_status(); // REGISTERED | COMPLETED | VERIFYING | REVOKED | FAILED | CANCELED | EXPIRED
            $referencia = $pg->get_rs_reference();
            $fecha = $pg->get_rs_date_trans();
            
            Log::info('Pagadito estado', [
                'ern' => $ern, 
                'estado' => $estado,
                'referencia' => $referencia,
                'fecha' => $fecha
            ]);

            // Busca tu orden por ERN
            $order = Order::where('numero_orden', $ern)->first();

            if (!$order) {
                Log::error('Orden no encontrada', ['ern' => $ern]);
                return redirect()->route('shop.checkout')->with('error', 'Orden no encontrada.');
            }

            switch ($estado) {
                case 'COMPLETED':
                    // Confirmar pago de pre-orden
                    $paymentData = [
                        'tipo' => 'pagadito',
                        'referencia' => $referencia,
                        'fecha_procesamiento' => $fecha,
                        'token' => $token,
                        'estado_pagadito' => $estado
                    ];
                    
                    $order->confirmPayment($paymentData);
                    // Limpiar carrito del usuario tras pago exitoso
                    try { session()->forget('carrito'); } catch (\Throwable $t) {}
                    
                    Log::info('Pago completado exitosamente', [
                        'order_id' => $order->id,
                        'numero_orden' => $order->numero_orden,
                        'referencia' => $referencia
                    ]);
                    
                    return redirect()->route('shop.order.success', $order->numero_orden)
                        ->with('success', 'Pago procesado exitosamente. Referencia: ' . $referencia);

                case 'VERIFYING':
                    // Mantener como pre_orden mientras se verifica
                    $order->estado_pago = 'pendiente';
                    $order->estado = 'pre_orden';
                    $order->info_pago = json_encode([
                        'tipo' => 'pagadito',
                        'referencia' => $referencia,
                        'fecha_procesamiento' => $fecha,
                        'token' => $token,
                        'estado' => 'verificando',
                        'estado_pagadito' => $estado
                    ]);
                    $order->save();
                    
                    return redirect()->route('shop.order.success', $order->numero_orden)
                        ->with('info', 'Tu pago está siendo verificado. Te notificaremos cuando esté confirmado.');

                case 'REGISTERED':
                    // Aún en proceso (usuario no completó): muéstrale estado pendiente
                    return redirect()->route('shop.checkout')
                        ->with('info', 'Tu proceso de pago está pendiente. Puedes intentar nuevamente.');

                case 'REVOKED':
                case 'FAILED':
                case 'CANCELED':
                case 'EXPIRED':
                default:
                    // Expirar la pre-orden si el pago falla
                    $order->estado_pago = 'cancelado';
                    $order->estado = 'expirado';
                    $order->info_pago = json_encode([
                        'tipo' => 'pagadito',
                        'referencia' => $referencia,
                        'fecha_procesamiento' => $fecha,
                        'token' => $token,
                        'estado_pagadito' => $estado,
                        'motivo' => 'Pago fallido en Pagadito'
                    ]);
                    $order->notas_admin = "Pago fallido en Pagadito. Estado: $estado";
                    $order->save();
                    
                    Log::warning('Pago fallido', [
                        'order_id' => $order->id,
                        'numero_orden' => $order->numero_orden,
                        'estado_pagadito' => $estado,
                        'referencia' => $referencia
                    ]);
                    
                    return redirect()->route('shop.checkout')
                        ->with('error', 'El pago no se completó. Puedes intentar crear una nueva orden.');
            }
        } else {
            Log::error('Pagadito get_status() error', [
                'code' => $pg->get_rs_code(),
                'message' => $pg->get_rs_message(),
                'token' => $token
            ]);
            return redirect()->route('shop.checkout')->with('error', 'No se pudo verificar el pago.');
        }
    }

    /**
     * Endpoint para polling del estado desde el frontend (modal abierto)
     * ?ern=ORD-...
     */
    public function status(Request $request)
    {
        $ern = $request->query('ern');
        if (!$ern) {
            return response()->json(['error' => 'ERN requerido'], 400);
        }
        $order = Order::where('numero_orden', $ern)->first();
        if (!$order) {
            return response()->json(['error' => 'Orden no encontrada'], 404);
        }
        // Si aún no está pagada, intentamos verificar directamente con Pagadito usando el token_trans guardado
        if (!in_array($order->estado_pago, ['pagado'])) {
            try {
                $info = json_decode($order->info_pago ?? '{}', true);
                $tokenTrans = $info['token_trans'] ?? null;
                if ($tokenTrans) {
                    $pg = $this->client();
                    if ($pg->connect() && $pg->get_status($tokenTrans)) {
                        $estado = $pg->get_rs_status();
                        $referencia = $pg->get_rs_reference();
                        $fecha = $pg->get_rs_date_trans();
                        if ($estado === 'COMPLETED') {
                            $order->confirmPayment([
                                'tipo' => 'pagadito',
                                'referencia' => $referencia,
                                'fecha_procesamiento' => $fecha,
                                'token' => $tokenTrans,
                                'estado_pagadito' => $estado
                            ]);
                            try { session()->forget('carrito'); } catch (\Throwable $t) {}
                        } elseif (in_array($estado, ['REVOKED','FAILED','CANCELED','EXPIRED'])) {
                            $order->estado = 'expirado';
                            $order->estado_pago = 'cancelado';
                            $order->save();
                        }
                    }
                }
            } catch (\Throwable $t) {
                \Log::warning('Pagadito status polling error', ['err' => $t->getMessage(), 'ern' => $ern]);
            }
        }

        return response()->json([
            'estado' => $order->estado,
            'estado_pago' => $order->estado_pago,
            'paid' => $order->estado_pago === 'pagado' || $order->estado === 'confirmado',
        ]);
    }

    /**
     * Webhook/IPN de Pagadito.
     * NOTA: Ajustar validación de firma/seguridad según documentación de Pagadito.
     */
    public function webhook(Request $request)
    {
        // Log básico para sandbox
        Log::info('Pagadito webhook recibido', ['payload' => $request->all()]);

        $ern = $request->input('ern') ?? $request->input('reference') ?? $request->input('numero_orden');
        $estado = $request->input('status') ?? $request->input('estado');
        $token = $request->input('token');
        $referencia = $request->input('reference');

        if (!$ern) {
            return response()->json(['error' => 'Falta ERN'], 400);
        }
        $order = Order::where('numero_orden', $ern)->first();
        if (!$order) {
            return response()->json(['error' => 'Orden no encontrada'], 404);
        }

        try {
            switch ($estado) {
                case 'COMPLETED':
                    $order->confirmPayment([
                        'tipo' => 'pagadito',
                        'referencia' => $referencia,
                        'token' => $token,
                        'estado_pagadito' => $estado,
                        'fecha_procesamiento' => now(),
                    ]);
                    break;
                case 'VERIFYING':
                case 'REGISTERED':
                    $order->estado = 'pre_orden';
                    $order->estado_pago = 'pendiente';
                    $order->save();
                    break;
                case 'REVOKED':
                case 'FAILED':
                case 'CANCELED':
                case 'EXPIRED':
                default:
                    $order->estado = 'expirado';
                    $order->estado_pago = 'cancelado';
                    $order->save();
                    break;
            }
        } catch (\Throwable $t) {
            Log::error('Error procesando webhook Pagadito', ['err' => $t->getMessage(), 'ern' => $ern]);
            return response()->json(['ok' => false]);
        }

        return response()->json(['ok' => true]);
    }
}
