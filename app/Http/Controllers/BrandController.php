<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageHelper;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::withCount('productos')->orderBy('nombre')->paginate(10);
        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:brands',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'activo' => 'boolean'
        ]);

        $data = $request->all();
        $data['activo'] = $request->boolean('activo');

        // Procesar imagen si se subió
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = 'brand_' . time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->storeAs('public/brands', $nombreImagen);
            $data['imagen'] = 'brands/' . $nombreImagen;
        }

        Brand::create($data);

        return redirect()->route('brands.index')->with('success', 'Marca creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        $brand->load('productos');
        return view('admin.brands.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:brands,nombre,' . $brand->id,
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'activo' => 'nullable'
        ]);

        $data = [
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'activo' => $request->boolean('activo'),
        ];

        // Procesar imagen si se subió
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($brand->imagen && Storage::exists('public/' . $brand->imagen)) {
                Storage::delete('public/' . $brand->imagen);
            }

            $imagen = $request->file('imagen');
            $nombreImagen = 'brand_' . time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->storeAs('public/brands', $nombreImagen);
            $data['imagen'] = 'brands/' . $nombreImagen;
        }

        $brand->fill($data);
        $brand->save();

        return redirect()->route('brands.index')->with('success', 'Marca actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        // Verificar si tiene productos asociados
        if ($brand->productos()->count() > 0) {
            return redirect()->route('brands.index')->with('error', 'No se puede eliminar la marca porque tiene productos asociados.');
        }

        // Eliminar imagen si existe
        if ($brand->imagen && Storage::exists('public/' . $brand->imagen)) {
            Storage::delete('public/' . $brand->imagen);
        }

        $brand->delete();

        return redirect()->route('brands.index')->with('success', 'Marca eliminada exitosamente.');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Brand $brand)
    {
        $brand->update(['activo' => !$brand->activo]);
        
        $status = $brand->activo ? 'activada' : 'desactivada';
        return redirect()->route('brands.index')->with('success', "Marca {$status} exitosamente.");
    }
}
