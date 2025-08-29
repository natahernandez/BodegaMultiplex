<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\ProductoImagen;

class ProductoImagenesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Imágenes disponibles en public/storage/productos/
        $imagenesDisponibles = [
            '1756259849_1.webp',
            '1756264154_1.jpeg',
        ];

        // Obtener productos en oferta
        $productosEnOferta = Producto::where('en_oferta', true)->get();

        foreach ($productosEnOferta as $index => $producto) {
            // Asignar imagen existente o usar una por defecto
            $imagenIndex = $index % count($imagenesDisponibles);
            $rutaImagen = $imagenesDisponibles[$imagenIndex];

            // Verificar si el producto ya tiene imágenes
            if ($producto->imagenes()->count() == 0) {
                ProductoImagen::create([
                    'producto_id' => $producto->id,
                    'ruta_imagen' => $rutaImagen,
                    'nombre_original' => $rutaImagen,
                    'es_principal' => true,
                    'orden' => 1,
                ]);

                $this->command->info("Imagen asignada a: {$producto->nombre}");
            }
        }

        // También asignar imágenes a productos regulares si no tienen
        $productosSinImagen = Producto::where('activo', true)
                                     ->whereDoesntHave('imagenes')
                                     ->limit(10)
                                     ->get();

        foreach ($productosSinImagen as $index => $producto) {
            $imagenIndex = $index % count($imagenesDisponibles);
            $rutaImagen = $imagenesDisponibles[$imagenIndex];

            ProductoImagen::create([
                'producto_id' => $producto->id,
                'ruta_imagen' => $rutaImagen,
                'nombre_original' => $rutaImagen,
                'es_principal' => true,
                'orden' => 1,
            ]);

            $this->command->info("Imagen asignada a producto regular: {$producto->nombre}");
        }

        $this->command->info('Seeder de imágenes completado.');
    }
}