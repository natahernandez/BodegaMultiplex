<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $this->setSimplePage('Dashboard Principal', 'Panel de control y estadísticas generales');
        
        // Estadísticas generales
        $stats = [
            'ventas_hoy' => Order::whereDate('fecha_pedido', today())
                ->where('estado', '!=', 'cancelado')
                ->sum('total'),
            'productos_vendidos_hoy' => DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereDate('orders.fecha_pedido', today())
                ->where('orders.estado', '!=', 'cancelado')
                ->sum('order_items.cantidad'),
            'clientes_atendidos_hoy' => Order::whereDate('fecha_pedido', today())
                ->where('estado', '!=', 'cancelado')
                ->distinct('email_cliente')
                ->count(),
            'ganancia_neta_mes' => Order::whereMonth('fecha_pedido', now()->month)
                ->whereYear('fecha_pedido', now()->year)
                ->where('estado', '!=', 'cancelado')
                ->sum('total') * 0.3, // Asumiendo 30% de ganancia neta
        ];

        // Ventas por mes (últimos 12 meses) para gráfica principal
        $ventasPorMes = collect();
        for ($i = 11; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $ventas = Order::whereMonth('fecha_pedido', $fecha->month)
                ->whereYear('fecha_pedido', $fecha->year)
                ->where('estado', '!=', 'cancelado')
                ->sum('total');
            
            $ventasPorMes->push([
                'mes' => $fecha->locale('es')->translatedFormat('M'),
                'ventas' => $ventas
            ]);
        }

        // Productos más vendidos
        $productosMasVendidos = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('productos', 'order_items.producto_id', '=', 'productos.id')
            ->where('orders.estado', '!=', 'cancelado')
            ->select('productos.nombre', DB::raw('SUM(order_items.cantidad) as total_vendido'))
            ->groupBy('productos.id', 'productos.nombre')
            ->orderBy('total_vendido', 'desc')
            ->limit(10)
            ->get();

        // Stock bajo (productos con stock <= 10)
        $stockBajo = Producto::where('stock_actual', '<=', 10)
            ->where('stock_actual', '>', 0)
            ->orderBy('stock_actual', 'asc')
            ->limit(5)
            ->get();

        // Productos por categoría
        $productosPorCategoria = Producto::selectRaw('categoria, count(*) as total')
            ->groupBy('categoria')
            ->get()
            ->mapWithKeys(function ($item) {
                return [ucfirst($item->categoria) => $item->total];
            });

        // Tendencia de órdenes (últimos 30 días)
        $tendenciaOrdenes = collect();
        for ($i = 29; $i >= 0; $i--) {
            $fecha = now()->subDays($i);
            $total = Order::whereDate('fecha_pedido', $fecha->toDateString())->count();
            
            $tendenciaOrdenes->push([
                'fecha' => $fecha->format('d M'),
                'total' => $total
            ]);
        }

        // Valor total del inventario
        $valorInventario = Producto::selectRaw('SUM(stock_actual * precio_venta) as valor_total')->first()->valor_total ?? 0;

        // === GRÁFICAS DE ÓRDENES (movidas desde OrderController) ===
        // Estadísticas por estado para gráficas
        $ordenesPorEstado = Order::selectRaw('estado, count(*) as total')
            ->groupBy('estado')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->estado => $item->total];
            });

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

        // === NUEVAS GRÁFICAS DE PRODUCTOS ===
        // Productos con stock crítico (<=5)
        $productosCriticos = Producto::where('stock_actual', '<=', 5)
            ->where('stock_actual', '>', 0)
            ->selectRaw('nombre, stock_actual')
            ->orderBy('stock_actual', 'asc')
            ->limit(10)
            ->get();

        // Productos por estado de stock
        $productosEstadoStock = [
            'critico' => Producto::where('stock_actual', '<=', 5)->where('stock_actual', '>', 0)->count(),
            'bajo' => Producto::whereBetween('stock_actual', [6, 10])->count(),
            'normal' => Producto::whereBetween('stock_actual', [11, 50])->count(),
            'alto' => Producto::where('stock_actual', '>', 50)->count(),
            'agotado' => Producto::where('stock_actual', '=', 0)->count(),
        ];

        // Productos más valiosos en inventario
        $productosMasValiosos = Producto::selectRaw('nombre, (stock_actual * precio_venta) as valor_total')
            ->where('stock_actual', '>', 0)
            ->orderBy('valor_total', 'desc')
            ->limit(10)
            ->get();

        // Productos próximos a vencer (próximos 30 días)
        $productosProximosVencer = Producto::where('fecha_vencimiento', '!=', null)
            ->whereBetween('fecha_vencimiento', [now(), now()->addDays(30)])
            ->selectRaw('nombre, fecha_vencimiento, DATEDIFF(fecha_vencimiento, CURDATE()) as dias_restantes')
            ->orderBy('fecha_vencimiento', 'asc')
            ->limit(10)
            ->get();

        // Análisis de precios
        $analisisPrecios = [
            'precio_promedio' => Producto::avg('precio_venta') ?? 0,
            'precio_max' => Producto::max('precio_venta') ?? 0,
            'precio_min' => Producto::min('precio_venta') ?? 0,
            'productos_caros' => Producto::where('precio_venta', '>', 100)->count(),
            'productos_baratos' => Producto::where('precio_venta', '<=', 10)->count(),
        ];

        $chartData = [
            // Gráficas originales
            'ventas_por_mes' => $ventasPorMes,
            'productos_mas_vendidos' => $productosMasVendidos,
            'productos_por_categoria' => $productosPorCategoria,
            'tendencia_ordenes' => $tendenciaOrdenes,
            
            // Gráficas de órdenes (movidas desde OrderController)
            'ordenes_por_estado' => $ordenesPorEstado,
            'ordenes_por_dia' => $ordenesPorDia,
            'metodos_pago' => $metodosPago,
            
            // Nuevas gráficas de productos
            'productos_criticos' => $productosCriticos,
            'productos_estado_stock' => $productosEstadoStock,
            'productos_mas_valiosos' => $productosMasValiosos,
            'productos_proximos_vencer' => $productosProximosVencer,
            'analisis_precios' => $analisisPrecios,
        ];

        $dashboardData = [
            'stock_bajo' => $stockBajo,
            'valor_inventario' => $valorInventario,
        ];
        
        return view('home', compact('stats', 'chartData', 'dashboardData'));
    }
}
