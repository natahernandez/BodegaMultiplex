<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Por defecto, no mostrar pre-órdenes sin procesar y órdenes expiradas
        $query = Order::with(['user', 'items']);
        
        // Mostrar pre-órdenes solo si se solicita específicamente
        if (!$request->filled('mostrar_pre_ordenes')) {
            $query->whereNotIn('estado', ['pre_orden', 'expirado']);
        }

        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('estado_pago')) {
            $query->where('estado_pago', $request->estado_pago);
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_pedido', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_pedido', '<=', $request->fecha_fin);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_orden', 'like', "%{$search}%")
                  ->orWhere('nombre_cliente', 'like', "%{$search}%")
                  ->orWhere('email_cliente', 'like', "%{$search}%")
                  ->orWhere('telefono_cliente', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderBy('fecha_pedido', 'desc')->paginate(15)->appends($request->query());

        // Estadísticas básicas
        $stats = [
            'total_ordenes' => Order::count(),
            'ordenes_pendientes' => Order::whereIn('estado', ['pendiente', 'confirmado', 'en_preparacion', 'proceso'])->count(),
            'ordenes_entregadas' => Order::whereIn('estado', ['entregado', 'completado'])->count(),
            'ventas_mes' => Order::whereMonth('fecha_pedido', now()->month)
                ->whereYear('fecha_pedido', now()->year)
                ->where('estado', '!=', 'cancelado')
                ->sum('total'),
        ];

        // Estadísticas por estado para gráficas
        $ordenesPorEstado = Order::selectRaw('estado, count(*) as total')
            ->groupBy('estado')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->estado => $item->total];
            });

        // Ventas por mes (últimos 6 meses)
        $ventasPorMes = collect();
        for ($i = 5; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $ventas = Order::whereMonth('fecha_pedido', $fecha->month)
                ->whereYear('fecha_pedido', $fecha->year)
                ->where('estado', '!=', 'cancelado')
                ->sum('total');
            
            $ventasPorMes->push([
                'mes' => $fecha->locale('es')->translatedFormat('M Y'),
                'ventas' => $ventas
            ]);
        }

        // Órdenes por día (últimos 7 días)
        $ordenesPorDia = collect();
        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i);
            $total = Order::whereDate('fecha_pedido', $fecha->toDateString())->count();
            
            $ordenesPorDia->push([
                'dia' => $fecha->locale('es')->translatedFormat('d M'),
                'total' => $total
            ]);
        }

        // Métodos de pago más utilizados
        $metodosPago = Order::selectRaw('metodo_pago, count(*) as total')
            ->where('estado', '!=', 'cancelado')
            ->groupBy('metodo_pago')
            ->get()
            ->mapWithKeys(function ($item) {
                $nombres = [
                    'efectivo' => 'Efectivo',
                    'tarjeta' => 'Tarjeta',
                    'transferencia' => 'Transferencia',
                    'contra_entrega' => 'Contra Entrega'
                ];
                return [$nombres[$item->metodo_pago] ?? $item->metodo_pago => $item->total];
            });

        // Estadísticas adicionales
        $statsExtendidas = [
            'promedio_orden' => Order::where('estado', '!=', 'cancelado')->avg('total') ?? 0,
            'ordenes_hoy' => Order::whereDate('fecha_pedido', today())->count(),
            'ordenes_semana' => Order::whereBetween('fecha_pedido', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'total_productos_vendidos' => DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.estado', '!=', 'cancelado')
                ->sum('order_items.cantidad'),
        ];

        $chartData = [
            'ordenes_por_estado' => $ordenesPorEstado,
            'ventas_por_mes' => $ventasPorMes,
            'ordenes_por_dia' => $ordenesPorDia,
            'metodos_pago' => $metodosPago,
        ];

        return view('pages.orders.index', compact('orders', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_cliente' => 'required|string|max:255',
            'email_cliente' => 'required|email|max:255',
            'telefono_cliente' => 'required|string|max:20',
            'direccion_entrega' => 'required|string',
            'ciudad' => 'required|string|max:100',
            'departamento' => 'required|string|max:100',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,contra_entrega',
            'estado' => 'required|in:pendiente,confirmado,en_preparacion,enviado,entregado,cancelado',
            'estado_pago' => 'required|in:pendiente,pagado,contra_entrega,cancelado',
            'notas_admin' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::create($request->all());

            DB::commit();

            return redirect()->route('orders.show', $order)->with('success', 'Orden creada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la orden: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.producto.imagenPrincipal']);
        
        return view('pages.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order->load(['items.producto']);
        
        return view('pages.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,confirmado,en_preparacion,proceso,enviado,entregado,completado,cancelado',
            'estado_pago' => 'required|in:pendiente,pagado,contra_entrega,cancelado',
            'nombre_cliente' => 'required|string|max:255',
            'email_cliente' => 'required|email|max:255',
            'telefono_cliente' => 'required|string|max:20',
            'dpi' => 'required|string|max:20',
            'nit' => 'nullable|string|max:20',
            'direccion_entrega' => 'required|string',
            'ciudad' => 'required|string|max:100',
            'departamento' => 'required|string|max:100',
            'codigo_seguimiento' => 'nullable|string|max:255',
            'guia_envio' => 'nullable|string|max:255',
            'empresa_envio' => 'nullable|string|max:255',
            'notas_admin' => 'nullable|string',
            'fecha_entrega_estimada' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            $estadoAnterior = $order->estado;

            // Si se marca como completado, establecer fecha de entrega real y actualizar stock
            if ($request->estado === 'completado' && $order->estado !== 'completado') {
                $request->merge(['fecha_entrega_real' => now()]);
                
                // Actualizar stock de productos
                foreach ($order->items as $item) {
                    if ($item->producto) {
                        $item->producto->decrement('stock_actual', $item->cantidad);
                    }
                }
            }

            // Si se cambia de completado a otro estado, restaurar stock
            if ($estadoAnterior === 'completado' && $request->estado !== 'completado') {
                foreach ($order->items as $item) {
                    if ($item->producto) {
                        $item->producto->increment('stock_actual', $item->cantidad);
                    }
                }
            }

            $order->update($request->all());

            DB::commit();

            return redirect()->route('orders.show', $order)->with('success', 'Orden actualizada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la orden: ' . $e->getMessage())->withInput();
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();

            // Restaurar stock de productos si la orden se cancela
            if ($order->estado !== 'cancelado') {
                foreach ($order->items as $item) {
                    if ($item->producto) {
                        $item->producto->increment('stock_actual', $item->cantidad);
                    }
                }
            }

            $order->delete();

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Orden eliminada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar la orden: ' . $e->getMessage());
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'estado' => 'required|in:cancelado,proceso',
        ]);

        try {
            DB::beginTransaction();
            
            $estadoAnterior = $order->estado;

            // Si se cancela una orden, restaurar stock si ya había sido descontado
            if ($request->estado === 'cancelado' && $estadoAnterior !== 'cancelado') {
                foreach ($order->items as $item) {
                    if ($item->producto) {
                        $item->producto->increment('stock_actual', $item->cantidad);
                    }
                }
                $order->estado_pago = 'cancelado';
            }

            $order->estado = $request->estado;
            $order->save();

            DB::commit();

            // Si es una petición AJAX, devolver JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Estado actualizado exitosamente',
                    'nuevo_estado' => ucfirst($order->estado),
                    'badge_class' => $order->estado === 'proceso' ? 'bg-warning' : 'bg-danger'
                ]);
            }

            // Si es una petición normal, redirigir
            return redirect()->route('orders.show', $order)
                ->with('success', 'Estado de la orden actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el estado: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error al actualizar el estado: ' . $e->getMessage());
        }
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'estado_pago' => 'required|in:pendiente,pagado,contra_entrega,cancelado',
        ]);

        try {
            $order->update(['estado_pago' => $request->estado_pago]);

            return response()->json([
                'success' => true,
                'message' => 'Estado de pago actualizado exitosamente',
                'nuevo_estado' => $order->estado_pago_texto,
                'badge_class' => $order->estado_pago_badge
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado de pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $query = Order::with(['user', 'items']);

        // Aplicar mismos filtros que en index
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('estado_pago')) {
            $query->where('estado_pago', $request->estado_pago);
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_pedido', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_pedido', '<=', $request->fecha_fin);
        }

        $orders = $query->orderBy('fecha_pedido', 'desc')->get();

        $filename = 'ordenes_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Encabezados CSV
            fputcsv($file, [
                'Número Orden',
                'Cliente',
                'Email',
                'Teléfono',
                'Estado',
                'Estado Pago',
                'Método Pago',
                'Subtotal',
                'Envío',
                'Total',
                'Items',
                'Fecha Pedido',
                'Ciudad'
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->numero_orden,
                    $order->nombre_cliente,
                    $order->email_cliente,
                    $order->telefono_cliente,
                    $order->estado_texto,
                    $order->estado_pago_texto,
                    ucfirst($order->metodo_pago),
                    'Q' . number_format($order->subtotal, 2),
                    'Q' . number_format($order->envio, 2),
                    'Q' . number_format($order->total, 2),
                    $order->total_items,
                    $order->fecha_pedido->format('d/m/Y H:i'),
                    $order->ciudad
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Dashboard data for orders
     */
    public function dashboardData()
    {
        $data = [
            'ordenes_hoy' => Order::whereDate('fecha_pedido', today())->count(),
            'ordenes_semana' => Order::whereBetween('fecha_pedido', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'ordenes_mes' => Order::whereMonth('fecha_pedido', now()->month)->count(),
            'ventas_mes' => Order::whereMonth('fecha_pedido', now()->month)->sum('total'),
            'ordenes_pendientes' => Order::where('estado', 'pendiente')->count(),
            'ordenes_por_estado' => Order::selectRaw('estado, count(*) as total')
                ->groupBy('estado')
                ->pluck('total', 'estado'),
        ];

        return response()->json($data);
    }

    /**
     * Completar orden con información de envío
     */
    public function complete(Request $request, Order $order)
    {
        $request->validate([
            'empresa_envio' => 'required|string|max:255',
            'guia_envio' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Actualizar orden
            $order->update([
                'estado' => 'completado',
                'empresa_envio' => $request->empresa_envio,
                'guia_envio' => $request->guia_envio,
                'fecha_entrega_real' => now(),
            ]);

            // Actualizar stock de productos
            foreach ($order->items as $item) {
                if ($item->producto) {
                    $item->producto->decrement('stock_actual', $item->cantidad);
                }
            }

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Orden completada exitosamente. El stock ha sido actualizado.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al completar la orden: ' . $e->getMessage());
        }
    }

    /**
     * Generar PDF de la orden
     */
    public function generatePdf(Order $order)
    {
        if ($order->estado !== 'completado') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Solo se puede generar PDF de órdenes completadas');
        }

        $order->load(['user', 'items.producto']);

        // Datos para el PDF
        $data = [
            'order' => $order,
            'empresa' => [
                'nombre' => 'Bodegas Multiplex',
                'direccion' => 'Guatemala, Guatemala',
                'telefono' => '(502) 2345-6789',
                'email' => 'info@bodegasmultiplex.com',
                'nit' => '123456789',
            ]
        ];

        // Generar nombre del archivo
        $filename = 'orden_' . $order->numero_orden . '_' . date('Y-m-d') . '.pdf';

        // Determinar el tipo de documento
        $view = $order->tipo_pago === 'linea' ? 'orders.pdf.comprobante' : 'orders.pdf.factura';

        // Generar PDF usando DomPDF
        $pdf = \PDF::loadView("pages.$view", $data);
        
        return $pdf->download($filename);
    }
}
