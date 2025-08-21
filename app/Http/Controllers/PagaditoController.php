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

        // 4) Ejecutar transacción: Pagadito redirige a su página de pago
        if (!$pg->exec_trans($ern)) {
            Log::error('Pagadito exec_trans() failed', [
                'code' => $pg->get_rs_code(),
                'message' => $pg->get_rs_message(),
                'order_id' => $order->id,
                'ern' => $ern
            ]);
            return redirect()->route('shop.checkout')->with('error', 'No se pudo iniciar el pago.');
        }

        // Importante: la librería ya envía la redirección. Este return es por si acaso.
        return response('Redirigiendo a Pagadito...', 302);
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
}
