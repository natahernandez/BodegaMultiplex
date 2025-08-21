<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageHelper;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('productos')->orderBy('nombre')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categories',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'activo' => 'boolean'
        ]);

        $data = $request->all();
        $data['activo'] = $request->has('activo');

        // Procesar imagen si se subió
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = 'category_' . time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->storeAs('public/categories', $nombreImagen);
            $data['imagen'] = 'categories/' . $nombreImagen;
        }

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load('productos');
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:categories,nombre,' . $category->id,
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
            if ($category->imagen && Storage::exists('public/' . $category->imagen)) {
                Storage::delete('public/' . $category->imagen);
            }

            $imagen = $request->file('imagen');
            $nombreImagen = 'category_' . time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->storeAs('public/categories', $nombreImagen);
            $data['imagen'] = 'categories/' . $nombreImagen;
        }

        $category->fill($data);
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Verificar si tiene productos asociados
        if ($category->productos()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'No se puede eliminar la categoría porque tiene productos asociados.');
        }

        // Eliminar imagen si existe
        if ($category->imagen && Storage::exists('public/' . $category->imagen)) {
            Storage::delete('public/' . $category->imagen);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Categoría eliminada exitosamente.');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Category $category)
    {
        $category->update(['activo' => !$category->activo]);
        
        $status = $category->activo ? 'activada' : 'desactivada';
        return redirect()->route('categories.index')->with('success', "Categoría {$status} exitosamente.");
    }
}
