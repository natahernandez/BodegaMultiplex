<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with(['imagenes', 'imagenPrincipal'])
                         ->where('activo', true)
                         ->where('stock_actual', '>', 0); // Solo productos con stock

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // Filtro por ofertas
        if ($request->filled('en_oferta')) {
            $query->enOferta();
        }

        // Filtro por búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%")
                  ->orWhere('marca', 'like', "%{$search}%")
                  ->orWhere('codigo_interno', 'like', "%{$search}%");
            });
        }

        // Filtro por rango de precios
        if ($request->filled('precio_min')) {
            $query->where('precio_venta', '>=', $request->precio_min);
        }
        if ($request->filled('precio_max')) {
            $query->where('precio_venta', '<=', $request->precio_max);
        }

        // Ordenamiento
        $orderBy = $request->get('order_by', 'created_at');
        $orderDirection = $request->get('order_direction', 'desc');
        
        switch ($orderBy) {
            case 'precio_asc':
                $query->orderBy('precio_venta', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('precio_venta', 'desc');
                break;
            case 'nombre':
                $query->orderBy('nombre', 'asc');
                break;
            case 'popular':
                // Ordenar por stock (simulando popularidad)
                $query->orderBy('stock_actual', 'desc');
                break;
            default:
                $query->orderBy($orderBy, $orderDirection);
        }

        $productos = $query->paginate(12)->appends($request->query());
        
        // Obtener categorías para el filtro
        $categorias = Producto::select('categoria')
            ->where('activo', true)
            ->distinct()
            ->orderBy('categoria')
            ->pluck('categoria');

        // Obtener rango de precios
        $precioMin = Producto::where('activo', true)->min('precio_venta');
        $precioMax = Producto::where('activo', true)->max('precio_venta');

        // Obtener productos en oferta para el parallax (máximo 6)
        $productosEnOferta = Producto::with(['imagenes', 'imagenPrincipal'])
            ->where('activo', true)
            ->where('stock_actual', '>', 0) // Solo productos con stock
            ->enOferta()
            ->limit(6)
            ->get();

        return view('welcome', compact('productos', 'categorias', 'precioMin', 'precioMax', 'productosEnOferta'));
    }

    public function show(Producto $producto)
    {
        if (!$producto->activo) {
            abort(404);
        }

        $producto->load(['imagenes', 'imagenPrincipal']);
        
        // Obtener las imágenes del producto
        $imagenes = $producto->imagenes;
        
        // Productos relacionados (misma categoría)
        $productosRelacionados = Producto::with(['imagenes', 'imagenPrincipal'])
            ->where('categoria', $producto->categoria)
            ->where('id', '!=', $producto->id)
            ->where('activo', true)
            ->where('stock_actual', '>', 0) // Solo productos con stock
            ->limit(4)
            ->get();

        return view('public.shop.show', compact('producto', 'productosRelacionados', 'imagenes'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1'
        ]);

        $producto = Producto::with(['imagenes', 'imagenPrincipal'])->findOrFail($request->producto_id);
        
        if (!$producto->activo) {
            return response()->json(['error' => 'Producto no disponible'], 400);
        }

        if ($producto->stock_actual < $request->cantidad) {
            return response()->json(['error' => 'Stock insuficiente'], 400);
        }

        $carrito = Session::get('carrito', []);
        $productoId = $request->producto_id;

        if (isset($carrito[$productoId])) {
            $nuevaCantidad = $carrito[$productoId]['cantidad'] + $request->cantidad;
            
            if ($producto->stock_actual < $nuevaCantidad) {
                return response()->json(['error' => 'Stock insuficiente para esta cantidad'], 400);
            }
            
            $carrito[$productoId]['cantidad'] = $nuevaCantidad;
            $carrito[$productoId]['subtotal'] = $nuevaCantidad * $producto->precio_final;
        } else {
            $carrito[$productoId] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio_final, // Usar precio final (con descuento si aplica)
                'precio_original' => $producto->precio_venta, // Mantener precio original para mostrar
                'cantidad' => $request->cantidad,
                'subtotal' => $request->cantidad * $producto->precio_final,
                'imagen' => $producto->imagen_principal_url,
                'codigo' => $producto->codigo_interno,
                'en_oferta' => $producto->es_oferta_activa,
                'descuento_porcentaje' => $producto->descuento_calculado,
            ];
        }

        Session::put('carrito', $carrito);

        $totalItems = array_sum(array_column($carrito, 'cantidad'));
        $totalCarrito = array_sum(array_column($carrito, 'subtotal'));

        return response()->json([
            'success' => true,
            'message' => 'Producto agregado al carrito',
            'totalItems' => $totalItems,
            'totalCarrito' => $totalCarrito,
            'carrito' => $carrito
        ]);
    }

    public function cart()
    {
        $carrito = Session::get('carrito', []);
        $total = array_sum(array_column($carrito, 'subtotal'));
        $totalItems = array_sum(array_column($carrito, 'cantidad'));

        return view('public.shop.cart', compact('carrito', 'total', 'totalItems'));
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:0'
        ]);

        $carrito = Session::get('carrito', []);
        $productoId = $request->producto_id;

        if ($request->cantidad == 0) {
            unset($carrito[$productoId]);
        } else {
            $producto = Producto::findOrFail($productoId);
            
            if ($producto->stock_actual < $request->cantidad) {
                return response()->json(['error' => 'Stock insuficiente'], 400);
            }

            if (isset($carrito[$productoId])) {
                $carrito[$productoId]['cantidad'] = $request->cantidad;
                $carrito[$productoId]['subtotal'] = $request->cantidad * $carrito[$productoId]['precio'];
                
                // Actualizar información de oferta si cambió
                $carrito[$productoId]['en_oferta'] = $producto->es_oferta_activa;
                $carrito[$productoId]['descuento_porcentaje'] = $producto->descuento_calculado;
                $carrito[$productoId]['precio'] = $producto->precio_final; // Actualizar precio por si cambió la oferta
                $carrito[$productoId]['precio_original'] = $producto->precio_venta;
                $carrito[$productoId]['subtotal'] = $request->cantidad * $producto->precio_final; // Recalcular con precio actualizado
            }
        }

        Session::put('carrito', $carrito);

        $totalItems = array_sum(array_column($carrito, 'cantidad'));
        $totalCarrito = array_sum(array_column($carrito, 'subtotal'));

        return response()->json([
            'success' => true,
            'totalItems' => $totalItems,
            'totalCarrito' => $totalCarrito,
            'carrito' => $carrito
        ]);
    }
    public function clearCart()
{
    session()->forget('carrito'); // Elimina toda la sesión del carrito
    return response()->json(['success' => true, 'message' => 'Carrito vaciado correctamente.']);
}


    public function removeFromCart(Request $request)
    {
        $carrito = Session::get('carrito', []);
        unset($carrito[$request->producto_id]);
        Session::put('carrito', $carrito);

        $totalItems = array_sum(array_column($carrito, 'cantidad'));
        $totalCarrito = array_sum(array_column($carrito, 'subtotal'));

        return response()->json([
            'success' => true,
            'totalItems' => $totalItems,
            'totalCarrito' => $totalCarrito
        ]);
    }

    public function checkout()
    {
        // Requerir autenticación para checkout
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Debes iniciar sesión para continuar con tu compra');
        }

        $carrito = Session::get('carrito', []);
        
        if (empty($carrito)) {
            return redirect()->route('shop.cart')->with('error', 'Tu carrito está vacío');
        }

        $total = array_sum(array_column($carrito, 'subtotal'));
        $totalItems = array_sum(array_column($carrito, 'cantidad'));

        return view('public.shop.checkout', compact('carrito', 'total', 'totalItems'));
    }

    public function processOrder(Request $request)
    {
        // Debug temporal
        \Log::info('ProcessOrder iniciado', [
            'user_id' => auth()->id(),
            'request_data' => $request->all(),
            'carrito' => Session::get('carrito', [])
        ]);

        // Requerir autenticación
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Debes iniciar sesión para continuar con tu compra');
        }

        $rules = [
            'nombre_completo' => 'required|string|max:255',
            'email_cliente' => 'required|email|max:255',
            'telefono_cliente' => 'required|string|max:20',
            'dpi' => 'required|string|max:20',
            'nit' => 'nullable|string|max:20',
            'direccion_entrega' => 'required|string',
            'ciudad' => 'required|string|max:100',
            'departamento' => 'required|string|max:100',
            'tipo_pago' => 'required|in:linea,contra_entrega',
            'notas_cliente' => 'nullable|string',
        ];

        // Para pago en línea, no necesitamos validar datos de tarjeta aquí
        // ya que se procesan directamente en Pagadito

        $request->validate($rules);

        $carrito = Session::get('carrito', []);
        
        if (empty($carrito)) {
            return redirect()->route('shop.cart')->with('error', 'Tu carrito está vacío');
        }

        try {
            DB::beginTransaction();

            // Verificar stock disponible
            foreach ($carrito as $item) {
                $producto = Producto::findOrFail($item['id']);
                if ($producto->stock_actual < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$producto->nombre}");
                }
            }

            $subtotal = array_sum(array_column($carrito, 'subtotal'));
            $envio = 0; // El envío se gestiona aparte; no sumar al total
            $total = $subtotal; // Total sin IVA ni envío

            // Generar número de orden único
            $numeroOrden = 'ORD-' . date('Y') . '-' . str_pad(Order::count() + 1, 6, '0', STR_PAD_LEFT);

            // Crear la orden
            $order = Order::create([
                'user_id' => auth()->id(),
                'numero_orden' => $numeroOrden,
                'nombre_cliente' => $request->nombre_completo,
                'email_cliente' => $request->email_cliente,
                'telefono_cliente' => $request->telefono_cliente,
                'dpi' => $request->dpi,
                'nit' => $request->nit ?? 'C/F',
                'direccion_entrega' => $request->direccion_entrega,
                'ciudad' => $request->ciudad,
                'departamento' => $request->departamento,
                'tipo_pago' => $request->tipo_pago,
                'metodo_pago' => $request->tipo_pago === 'linea' ? 'tarjeta' : 'contra_entrega',
                'estado' => $request->tipo_pago === 'linea' ? 'pre_orden' : 'pendiente',
                'estado_pago' => $request->tipo_pago === 'linea' ? 'pendiente' : 'contra_entrega',
                'subtotal' => $subtotal,
                'envio' => $envio,
                'total' => $total,
                'notas_cliente' => $request->notas_cliente,
                'fecha_pedido' => now(),
                // Pago en línea: expira en 30 minutos si no se completa
                'fecha_entrega_estimada' => $request->tipo_pago === 'linea' ? now()->addMinutes(30) : null,
                // Si es pago en línea, se procesará con Pagadito
                'info_pago' => $request->tipo_pago === 'linea' ? json_encode([
                    'tipo' => 'pagadito',
                    'estado' => 'iniciando',
                    'fecha_creacion' => now(),
                    'expira_en' => now()->addMinutes(30),
                ]) : null,
            ]);

            // Crear los items de la orden (NO actualizar stock aún, se hace cuando el admin marca como completado)
            foreach ($carrito as $item) {
                $producto = Producto::findOrFail($item['id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'producto_id' => $producto->id,
                    'nombre_producto' => $producto->nombre,
                    'codigo_producto' => $producto->codigo_interno,
                    'descripcion_producto' => $producto->descripcion,
                    'categoria_producto' => $producto->categoria,
                    'precio_unitario' => $item['precio'], // Usar precio del carrito (con descuento si aplica)
                    'cantidad' => $item['cantidad'],
                    'subtotal' => $item['subtotal'],
                ]);

                // NO actualizar stock aquí - se actualiza cuando admin marca como completado
            }

            DB::commit();

            // Si es pago en línea, mantener el carrito hasta confirmar pago
            if ($request->tipo_pago === 'linea') {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'order_id' => $order->id,
                        'ern' => $order->numero_orden,
                        'iniciar_url' => route('pagadito.iniciar', $order->id),
                    ]);
                }
                return redirect()->route('pagadito.iniciar', $order->id);
            } else {
                // Si es contra entrega, limpiar carrito e ir al éxito
                Session::forget('carrito');
                return redirect()->route('shop.order.success', $order->numero_orden)
                    ->with('success', 'Tu pedido ha sido procesado exitosamente');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error en processOrder', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);
            return back()->with('error', 'Error al procesar la orden: ' . $e->getMessage())->withInput();
        }
    }

    public function orderSuccess($numeroOrden)
    {
        $order = Order::where('numero_orden', $numeroOrden)->firstOrFail();
        $order->load('items.producto');

        return view('public.shop.order-success', compact('order'));
    }

    public function searchApi(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $productos = Producto::where('activo', true)
            ->where(function($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%")
                  ->orWhere('marca', 'like', "%{$query}%")
                  ->orWhere('codigo_interno', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'nombre', 'precio_venta', 'codigo_interno']);

        return response()->json($productos);
    }
}
