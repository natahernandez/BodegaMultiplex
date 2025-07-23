<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->setSimplePage('Gestión de Productos', 'Administra tu inventario de productos de manera eficiente');
        
        $query = Producto::query();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigo_barras', 'like', "%{$search}%")
                  ->orWhere('codigo_interno', 'like', "%{$search}%")
                  ->orWhere('marca', 'like', "%{$search}%");
            });
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('estado_stock')) {
            switch ($request->estado_stock) {
                case 'sin_stock':
                    $query->where('stock_actual', '<=', 0);
                    break;
                case 'stock_bajo':
                    $query->whereColumn('stock_actual', '<=', 'stock_minimo');
                    break;
            }
        }

        $productos = $query->orderBy('nombre')->get();
        $categorias = Producto::distinct()->pluck('categoria');

        return view('productos.index', compact('productos', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Producto::distinct()->pluck('categoria');
        $unidades = ['Unidad', 'Kg', 'Gramos', 'Litro', 'Mililitro', 'Caja', 'Paquete', 'Metro'];
        
        return view('productos.create', compact('categorias', 'unidades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo_barras' => 'nullable|unique:productos,codigo_barras',
            'codigo_interno' => 'required|unique:productos,codigo_interno',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'marca' => 'nullable|string|max:255',
            'categoria' => 'required|string|max:255',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'precio_mayoreo' => 'nullable|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'stock_maximo' => 'nullable|integer|min:0',
            'unidad_medida' => 'required|string',
            'ubicacion' => 'nullable|string|max:255',
            'proveedor' => 'nullable|string|max:255',
            'fecha_vencimiento' => 'nullable|date',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'requiere_receta' => 'boolean',
            'iva' => 'nullable|numeric|min:0|max:100'
        ]);

        // Manejar la imagen
        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        // Generar código interno si no se proporciona
        if (empty($validated['codigo_interno'])) {
            $validated['codigo_interno'] = 'PROD-' . strtoupper(Str::random(8));
        }

        Producto::create($validated);

        return redirect()->route('productos.index')
                        ->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        $this->setSimplePage('Detalles del Producto', "Información completa de {$producto->nombre}");
        
        return view('productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $categorias = Producto::distinct()->pluck('categoria');
        $unidades = ['Unidad', 'Kg', 'Gramos', 'Litro', 'Mililitro', 'Caja', 'Paquete', 'Metro'];
        
        return view('productos.edit', compact('producto', 'categorias', 'unidades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'codigo_barras' => 'nullable|unique:productos,codigo_barras,' . $producto->id,
            'codigo_interno' => 'required|unique:productos,codigo_interno,' . $producto->id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'marca' => 'nullable|string|max:255',
            'categoria' => 'required|string|max:255',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'precio_mayoreo' => 'nullable|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'stock_maximo' => 'nullable|integer|min:0',
            'unidad_medida' => 'required|string',
            'ubicacion' => 'nullable|string|max:255',
            'proveedor' => 'nullable|string|max:255',
            'fecha_vencimiento' => 'nullable|date',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'activo' => 'boolean',
            'requiere_receta' => 'boolean',
            'iva' => 'nullable|numeric|min:0|max:100'
        ]);

        // Manejar la imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($validated);

        return redirect()->route('productos.index')
                        ->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        // Eliminar imagen si existe
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('productos.index')
                        ->with('success', 'Producto eliminado exitosamente.');
    }

    /**
     * Actualizar stock de un producto
     */
    public function updateStock(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'stock_actual' => 'required|integer|min:0',
            'observaciones' => 'nullable|string'
        ]);

        $producto->update(['stock_actual' => $validated['stock_actual']]);

        return response()->json([
            'success' => true,
            'message' => 'Stock actualizado correctamente',
            'nuevo_stock' => $producto->stock_actual
        ]);
    }

    /**
     * Obtener productos para DataTables via AJAX
     */
    public function datatable(Request $request)
    {
        $query = Producto::query();

        // Búsqueda global
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigo_barras', 'like', "%{$search}%")
                  ->orWhere('codigo_interno', 'like', "%{$search}%")
                  ->orWhere('marca', 'like', "%{$search}%")
                  ->orWhere('categoria', 'like', "%{$search}%");
            });
        }

        // Ordenamiento
        if ($request->has('order')) {
            $columns = ['id', 'codigo_interno', 'nombre', 'marca', 'categoria', 'precio_venta', 'stock_actual'];
            $columnIndex = $request->order[0]['column'];
            $columnName = $columns[$columnIndex] ?? 'id';
            $direction = $request->order[0]['dir'] ?? 'asc';
            
            $query->orderBy($columnName, $direction);
        }

        $recordsTotal = Producto::count();
        $recordsFiltered = $query->count();

        // Paginación
        if ($request->has('start') && $request->has('length')) {
            $query->skip($request->start)->take($request->length);
        }

        $productos = $query->get();

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $productos
        ]);
    }
}
