<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Producto::with(['imagenes', 'imagenPrincipal', 'brand', 'category'])
                         ->where('activo', true);

        // Filtros
        if ($request->filled('filtro')) {
            switch ($request->filtro) {
                case 'en_oferta':
                    $query->enOferta();
                    break;
                case 'sin_oferta':
                    $query->where('en_oferta', false);
                    break;
                case 'ofertas_vencidas':
                    $query->where('en_oferta', true)
                          ->where('fecha_fin_oferta', '<', now());
                    break;
                case 'ofertas_futuras':
                    $query->where('en_oferta', true)
                          ->where('fecha_inicio_oferta', '>', now());
                    break;
            }
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigo_interno', 'like', "%{$search}%");
            });
        }

        $productos = $query->orderBy('created_at', 'desc')->paginate(15);

        // Estadísticas
        $stats = [
            'total_productos' => Producto::where('activo', true)->count(),
            'productos_en_oferta' => Producto::enOferta()->count(),
            'productos_sin_oferta' => Producto::where('activo', true)->where('en_oferta', false)->count(),
            'ofertas_vencidas' => Producto::where('en_oferta', true)
                                         ->where('fecha_fin_oferta', '<', now())
                                         ->count(),
        ];

        // Categorías para el filtro
        $categorias = Producto::select('categoria')
                             ->where('activo', true)
                             ->distinct()
                             ->orderBy('categoria')
                             ->pluck('categoria');

        return view('admin.ofertas.index', compact('productos', 'stats', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = Producto::where('activo', true)
                            ->orderBy('nombre')
                            ->get(['id', 'nombre', 'precio_venta', 'categoria']);

        return view('admin.ofertas.create', compact('productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*' => 'exists:productos,id',
            'tipo_descuento' => 'required|in:porcentaje,precio_fijo',
            'descuento_porcentaje' => 'required_if:tipo_descuento,porcentaje|numeric|min:1|max:90',
            'precio_oferta' => 'required_if:tipo_descuento,precio_fijo|numeric|min:0',
            'fecha_inicio_oferta' => 'nullable|date|after_or_equal:today',
            'fecha_fin_oferta' => 'nullable|date|after:fecha_inicio_oferta',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->productos as $productoId) {
                $producto = Producto::findOrFail($productoId);
                
                $data = [
                    'en_oferta' => true,
                    'fecha_inicio_oferta' => $request->fecha_inicio_oferta,
                    'fecha_fin_oferta' => $request->fecha_fin_oferta,
                ];

                if ($request->tipo_descuento === 'porcentaje') {
                    $data['descuento_porcentaje'] = $request->descuento_porcentaje;
                    $data['precio_oferta'] = $producto->precio_venta * (1 - $request->descuento_porcentaje / 100);
                } else {
                    $data['precio_oferta'] = $request->precio_oferta;
                    $data['descuento_porcentaje'] = round((($producto->precio_venta - $request->precio_oferta) / $producto->precio_venta) * 100, 2);
                }

                $producto->update($data);
            }

            DB::commit();

            return redirect()->route('ofertas.index')
                           ->with('success', 'Ofertas creadas exitosamente para ' . count($request->productos) . ' productos.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear las ofertas: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $oferta)
    {
        $oferta->load(['imagenes', 'imagenPrincipal', 'brand', 'category']);
        return view('admin.ofertas.show', compact('oferta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $oferta)
    {
        return view('admin.ofertas.edit', compact('oferta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $oferta)
    {
        $request->validate([
            'tipo_descuento' => 'required|in:porcentaje,precio_fijo',
            'descuento_porcentaje' => 'required_if:tipo_descuento,porcentaje|numeric|min:1|max:90',
            'precio_oferta' => 'required_if:tipo_descuento,precio_fijo|numeric|min:0',
            'fecha_inicio_oferta' => 'nullable|date',
            'fecha_fin_oferta' => 'nullable|date|after:fecha_inicio_oferta',
        ]);

        try {
            $data = [
                'en_oferta' => true,
                'fecha_inicio_oferta' => $request->fecha_inicio_oferta,
                'fecha_fin_oferta' => $request->fecha_fin_oferta,
            ];

            if ($request->tipo_descuento === 'porcentaje') {
                $data['descuento_porcentaje'] = $request->descuento_porcentaje;
                $data['precio_oferta'] = $oferta->precio_venta * (1 - $request->descuento_porcentaje / 100);
            } else {
                $data['precio_oferta'] = $request->precio_oferta;
                $data['descuento_porcentaje'] = round((($oferta->precio_venta - $request->precio_oferta) / $oferta->precio_venta) * 100, 2);
            }

            $oferta->update($data);

            return redirect()->route('ofertas.index')
                           ->with('success', 'Oferta actualizada exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar la oferta: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $oferta)
    {
        try {
            $oferta->update([
                'en_oferta' => false,
                'descuento_porcentaje' => null,
                'precio_oferta' => null,
                'fecha_inicio_oferta' => null,
                'fecha_fin_oferta' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Oferta eliminada exitosamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la oferta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Configurar el módulo parallax
     */
    public function parallax()
    {
        $productosEnOferta = Producto::with(['imagenes', 'imagenPrincipal', 'brand', 'category'])
                                   ->enOferta()
                                   ->orderBy('created_at', 'desc')
                                   ->get();

        return view('admin.ofertas.parallax', compact('productosEnOferta'));
    }

    /**
     * Aplicar oferta masiva
     */
    public function masiva(Request $request)
    {
        $request->validate([
            'categoria' => 'required|string',
            'tipo_descuento' => 'required|in:porcentaje,precio_fijo',
            'descuento_porcentaje' => 'required_if:tipo_descuento,porcentaje|numeric|min:1|max:90',
            'precio_oferta' => 'required_if:tipo_descuento,precio_fijo|numeric|min:0',
            'fecha_inicio_oferta' => 'nullable|date|after_or_equal:today',
            'fecha_fin_oferta' => 'nullable|date|after:fecha_inicio_oferta',
        ]);

        try {
            DB::beginTransaction();

            $productos = Producto::where('activo', true)
                                ->where('categoria', $request->categoria)
                                ->get();

            if ($productos->isEmpty()) {
                return back()->with('error', 'No se encontraron productos en la categoría seleccionada.');
            }

            foreach ($productos as $producto) {
                $data = [
                    'en_oferta' => true,
                    'fecha_inicio_oferta' => $request->fecha_inicio_oferta,
                    'fecha_fin_oferta' => $request->fecha_fin_oferta,
                ];

                if ($request->tipo_descuento === 'porcentaje') {
                    $data['descuento_porcentaje'] = $request->descuento_porcentaje;
                    $data['precio_oferta'] = $producto->precio_venta * (1 - $request->descuento_porcentaje / 100);
                } else {
                    $data['precio_oferta'] = $request->precio_oferta;
                    $data['descuento_porcentaje'] = round((($producto->precio_venta - $request->precio_oferta) / $producto->precio_venta) * 100, 2);
                }

                $producto->update($data);
            }

            DB::commit();

            return redirect()->route('ofertas.index')
                           ->with('success', 'Oferta aplicada exitosamente a ' . $productos->count() . ' productos de la categoría "' . $request->categoria . '".');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al aplicar la oferta masiva: ' . $e->getMessage())->withInput();
        }
    }
}