<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use Carbon\Carbon;

class OfertasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener algunos productos existentes para convertirlos en ofertas
        $productos = Producto::where('activo', true)->limit(8)->get();

        foreach ($productos as $index => $producto) {
            // Crear diferentes tipos de ofertas
            switch ($index % 4) {
                case 0: // Descuento por porcentaje
                    $descuento = rand(10, 50);
                    $producto->update([
                        'en_oferta' => true,
                        'descuento_porcentaje' => $descuento,
                        'precio_oferta' => $producto->precio_venta * (1 - $descuento / 100),
                        'fecha_inicio_oferta' => now(),
                        'fecha_fin_oferta' => now()->addDays(rand(7, 30)),
                    ]);
                    break;

                case 1: // Precio fijo de oferta
                    $precioOferta = $producto->precio_venta * 0.7; // 30% de descuento
                    $producto->update([
                        'en_oferta' => true,
                        'precio_oferta' => $precioOferta,
                        'fecha_inicio_oferta' => now(),
                        'fecha_fin_oferta' => now()->addDays(rand(5, 20)),
                    ]);
                    break;

                case 2: // Oferta sin fecha de fin
                    $descuento = rand(15, 35);
                    $producto->update([
                        'en_oferta' => true,
                        'descuento_porcentaje' => $descuento,
                        'precio_oferta' => $producto->precio_venta * (1 - $descuento / 100),
                        'fecha_inicio_oferta' => now(),
                        'fecha_fin_oferta' => null,
                    ]);
                    break;

                case 3: // Oferta que empieza en el futuro
                    $descuento = rand(20, 40);
                    $producto->update([
                        'en_oferta' => true,
                        'descuento_porcentaje' => $descuento,
                        'precio_oferta' => $producto->precio_venta * (1 - $descuento / 100),
                        'fecha_inicio_oferta' => now()->addDays(2),
                        'fecha_fin_oferta' => now()->addDays(rand(10, 25)),
                    ]);
                    break;
            }
        }

        $this->command->info('Se crearon ' . $productos->count() . ' productos con ofertas.');
    }
}