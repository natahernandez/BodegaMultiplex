<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\ProductoImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->setSimplePage('Gestión de Productos', 'Administra tu inventario de productos de manera eficiente');
        
        $query = Producto::with(['imagenes', 'imagenPrincipal']);

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

        return view('pages.productos.index', compact('productos', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->setSimplePage('Nuevo Producto', 'Agregar un nuevo producto al inventario');
        
        $categorias = Producto::distinct()->pluck('categoria');
        $unidades = ['Unidad', 'Kg', 'Gramos', 'Litro', 'Mililitro', 'Caja', 'Paquete', 'Metro'];
        
        return view('pages.productos.create', compact('categorias', 'unidades'));
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
            'imagenes.*' => 'nullable|image|max:5120', // 5MB = 5120KB, all image formats allowed
            'requiere_receta' => 'boolean',
            'iva' => 'nullable|numeric|min:0|max:100'
        ]);

        // Generar código interno si no se proporciona
        if (empty($validated['codigo_interno'])) {
            $validated['codigo_interno'] = 'PROD-' . strtoupper(Str::random(8));
        }

        DB::beginTransaction();
        
        try {
            // Crear el producto
            $producto = Producto::create($validated);

            // Manejar múltiples imágenes
            if ($request->hasFile('imagenes')) {
                $this->guardarImagenes($request->file('imagenes'), $producto);
            }

            DB::commit();

            return redirect()->route('productos.index')
                            ->with('success', 'Producto creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al crear el producto: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        $this->setSimplePage('Detalles del Producto', "Información completa de {$producto->nombre}");
        
        $producto->load(['imagenes', 'imagenPrincipal']);
        
        return view('pages.productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $this->setSimplePage('Editar Producto', "Modificar información de {$producto->nombre}");
        
        $producto->load(['imagenes', 'imagenPrincipal']);
        $categorias = Producto::distinct()->pluck('categoria');
        $unidades = ['Unidad', 'Kg', 'Gramos', 'Litro', 'Mililitro', 'Caja', 'Paquete', 'Metro'];
        
        return view('pages.productos.edit', compact('producto', 'categorias', 'unidades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        // Si es una request AJAX para cambiar solo el estado
        if ($request->ajax() && $request->has('activo')) {
            $producto->update(['activo' => $request->boolean('activo')]);
            
            return response()->json([
                'success' => true,
                'message' => 'Estado del producto actualizado correctamente',
                'activo' => $producto->activo
            ]);
        }

        // Si es una request AJAX para eliminar imagen individual
        if ($request->ajax() && $request->has('eliminar_imagenes')) {
            try {
                $imagenesIds = $request->eliminar_imagenes;
                $this->eliminarImagenes($imagenesIds);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Imagen eliminada correctamente'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la imagen: ' . $e->getMessage()
                ], 500);
            }
        }

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
            'imagenes.*' => 'nullable|image|max:5120', // 5MB = 5120KB, all image formats allowed
            'activo' => 'boolean',
            'requiere_receta' => 'boolean',
            'iva' => 'nullable|numeric|min:0|max:100',
            'eliminar_imagenes' => 'nullable|array',
            'eliminar_imagenes.*' => 'integer|exists:producto_imagens,id'
        ]);

        DB::beginTransaction();
        
        try {
            // Actualizar el producto
            $producto->update($validated);

            // Eliminar imágenes seleccionadas
            if ($request->has('eliminar_imagenes')) {
                $this->eliminarImagenes($request->eliminar_imagenes);
            }

            // Agregar nuevas imágenes
            if ($request->hasFile('imagenes')) {
                $this->guardarImagenes($request->file('imagenes'), $producto);
            }

            DB::commit();

            return redirect()->route('productos.index')
                            ->with('success', 'Producto actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al actualizar el producto: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        DB::beginTransaction();
        
        try {
            // Eliminar todas las imágenes del producto
            foreach ($producto->imagenes as $imagen) {
                if (Storage::disk('public')->exists($imagen->ruta_imagen)) {
                    Storage::disk('public')->delete($imagen->ruta_imagen);
                }
            }

            // Eliminar imagen antigua si existe
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }

            $producto->delete();

            DB::commit();

            return redirect()->route('productos.index')
                            ->with('success', 'Producto eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar el producto: ' . $e->getMessage()]);
        }
    }

    /**
     * Guardar múltiples imágenes
     */
    private function guardarImagenes($imagenes, $producto)
    {
        $orden = $producto->imagenes()->max('orden') ?? 0;
        $esPrimera = $producto->imagenes()->count() === 0;
        
        foreach ($imagenes as $imagen) {
            if ($imagen && $imagen->isValid()) {
                $orden++;
                
                $nombreArchivo = time() . '_' . $orden . '.' . $imagen->getClientOriginalExtension();
                $rutaImagen = $imagen->storeAs('productos', $nombreArchivo, 'public');
                
                // Copiar también al directorio público para acceso directo
                $this->syncImageToPublic($rutaImagen);
                
                ProductoImagen::create([
                    'producto_id' => $producto->id,
                    'ruta_imagen' => $rutaImagen,
                    'nombre_original' => $imagen->getClientOriginalName(),
                    'es_principal' => $esPrimera && $orden === 1,
                    'orden' => $orden
                ]);
            }
        }
    }

    /**
     * Eliminar imágenes específicas
     */
    private function eliminarImagenes($imagenesIds)
    {
        $imagenes = ProductoImagen::whereIn('id', $imagenesIds)->get();
        
        foreach ($imagenes as $imagen) {
            if (Storage::disk('public')->exists($imagen->ruta_imagen)) {
                Storage::disk('public')->delete($imagen->ruta_imagen);
            }
            
            // Eliminar también del directorio público
            $this->removeImageFromPublic($imagen->ruta_imagen);
            
            $imagen->delete();
        }
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
        $query = Producto::with(['imagenes', 'imagenPrincipal']);

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

    /**
     * Sincronizar imagen al directorio público
     */
    private function syncImageToPublic($rutaImagen)
    {
        try {
            $sourceFile = storage_path('app/public/' . $rutaImagen);
            $publicFile = public_path('storage/' . $rutaImagen);
            
            // Crear el directorio si no existe
            $publicDir = dirname($publicFile);
            if (!is_dir($publicDir)) {
                mkdir($publicDir, 0755, true);
            }
            
            // Copiar el archivo
            if (file_exists($sourceFile)) {
                copy($sourceFile, $publicFile);
            }
        } catch (\Exception $e) {
            \Log::error('Error sincronizando imagen al directorio público: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar imagen del directorio público
     */
    private function removeImageFromPublic($rutaImagen)
    {
        try {
            $publicFile = public_path('storage/' . $rutaImagen);
            if (file_exists($publicFile)) {
                unlink($publicFile);
            }
        } catch (\Exception $e) {
            \Log::error('Error eliminando imagen del directorio público: ' . $e->getMessage());
        }
    }
}
