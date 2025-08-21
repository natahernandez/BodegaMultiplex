<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            [
                'codigo_barras' => '7501234567890',
                'codigo_interno' => 'COCA-355ML',
                'nombre' => 'Coca-Cola 355ml',
                'descripcion' => 'Refresco de cola sabor original en lata de 355ml',
                'marca' => 'Coca-Cola',
                'categoria' => 'Bebidas',
                'precio_compra' => 12.50,
                'precio_venta' => 18.00,
                'precio_mayoreo' => 16.00,
                'stock_actual' => 48,
                'stock_minimo' => 24,
                'stock_maximo' => 120,
                'unidad_medida' => 'Unidad',
                'ubicacion' => 'Pasillo 1, Estante A',
                'proveedor' => 'FEMSA',
                'activo' => true
            ],
            [
                'codigo_barras' => '7501030405060',
                'codigo_interno' => 'LECHE-LALA-1L',
                'nombre' => 'Leche Lala Entera 1L',
                'descripcion' => 'Leche entera ultrapasteurizada en envase tetrapak de 1 litro',
                'marca' => 'Lala',
                'categoria' => 'Lácteos',
                'precio_compra' => 22.00,
                'precio_venta' => 28.50,
                'precio_mayoreo' => 26.00,
                'stock_actual' => 15,
                'stock_minimo' => 20,
                'stock_maximo' => 60,
                'unidad_medida' => 'Litro',
                'ubicacion' => 'Refrigerador 1',
                'proveedor' => 'Grupo Lala',
                'fecha_vencimiento' => now()->addDays(15),
                'activo' => true
            ],
            [
                'codigo_barras' => '7501110203040',
                'codigo_interno' => 'JABON-ZOT-800G',
                'nombre' => 'Jabón Zote Rosa 800g',
                'descripcion' => 'Jabón para lavar ropa en barra color rosa de 800 gramos',
                'marca' => 'Zote',
                'categoria' => 'Limpieza',
                'precio_compra' => 18.00,
                'precio_venta' => 25.00,
                'precio_mayoreo' => 22.00,
                'stock_actual' => 8,
                'stock_minimo' => 12,
                'stock_maximo' => 48,
                'unidad_medida' => 'Unidad',
                'ubicacion' => 'Pasillo 3, Estante B',
                'proveedor' => 'Fabrica de Jabón La Corona',
                'activo' => true
            ],
            [
                'codigo_barras' => '7801234567891',
                'codigo_interno' => 'ARROZ-VERDE-1KG',
                'nombre' => 'Arroz Verde Valle 1kg',
                'descripcion' => 'Arroz blanco grano largo premium en bolsa de 1 kilogramo',
                'marca' => 'Verde Valle',
                'categoria' => 'Abarrotes',
                'precio_compra' => 24.00,
                'precio_venta' => 32.00,
                'precio_mayoreo' => 29.00,
                'stock_actual' => 36,
                'stock_minimo' => 18,
                'stock_maximo' => 72,
                'unidad_medida' => 'Kg',
                'ubicacion' => 'Pasillo 2, Estante A',
                'proveedor' => 'Distribuidora de Granos SA',
                'activo' => true
            ],
            [
                'codigo_barras' => '7502345678901',
                'codigo_interno' => 'SHAMPOO-H&S-400ML',
                'nombre' => 'Shampoo Head & Shoulders 400ml',
                'descripcion' => 'Shampoo anticaspa para cabello graso en envase de 400ml',
                'marca' => 'Head & Shoulders',
                'categoria' => 'Cuidado Personal',
                'precio_compra' => 45.00,
                'precio_venta' => 65.00,
                'precio_mayoreo' => 58.00,
                'stock_actual' => 24,
                'stock_minimo' => 12,
                'stock_maximo' => 48,
                'unidad_medida' => 'Unidad',
                'ubicacion' => 'Pasillo 4, Estante C',
                'proveedor' => 'Procter & Gamble',
                'activo' => true,
                'iva' => 16.00
            ],
            [
                'codigo_barras' => '7503456789012',
                'codigo_interno' => 'PAN-BIMBO-680G',
                'nombre' => 'Pan de Caja Bimbo Integral 680g',
                'descripcion' => 'Pan de caja integral con granos y semillas en bolsa de 680g',
                'marca' => 'Bimbo',
                'categoria' => 'Panadería',
                'precio_compra' => 35.00,
                'precio_venta' => 48.00,
                'precio_mayoreo' => 42.00,
                'stock_actual' => 12,
                'stock_minimo' => 15,
                'stock_maximo' => 30,
                'unidad_medida' => 'Unidad',
                'ubicacion' => 'Estante Pan',
                'proveedor' => 'Grupo Bimbo',
                'fecha_vencimiento' => now()->addDays(5),
                'activo' => true
            ],
            [
                'codigo_barras' => '7504567890123',
                'codigo_interno' => 'ACEITE-CAP-1L',
                'nombre' => 'Aceite Capullo 1L',
                'descripcion' => 'Aceite vegetal comestible puro de cártamo en botella de 1 litro',
                'marca' => 'Capullo',
                'categoria' => 'Abarrotes',
                'precio_compra' => 28.00,
                'precio_venta' => 38.50,
                'precio_mayoreo' => 34.00,
                'stock_actual' => 20,
                'stock_minimo' => 15,
                'stock_maximo' => 45,
                'unidad_medida' => 'Litro',
                'ubicacion' => 'Pasillo 2, Estante B',
                'proveedor' => 'Capullo',
                'activo' => true,
                'iva' => 0.00
            ],
            [
                'codigo_barras' => '7505678901234',
                'codigo_interno' => 'PASTA-BARILLA-500G',
                'nombre' => 'Pasta Barilla Spaghetti 500g',
                'descripcion' => 'Pasta italiana spaghetti #5 en caja de 500 gramos',
                'marca' => 'Barilla',
                'categoria' => 'Abarrotes',
                'precio_compra' => 22.00,
                'precio_venta' => 32.00,
                'precio_mayoreo' => 28.00,
                'stock_actual' => 0,
                'stock_minimo' => 24,
                'stock_maximo' => 72,
                'unidad_medida' => 'Unidad',
                'ubicacion' => 'Pasillo 2, Estante C',
                'proveedor' => 'Barilla México',
                'activo' => true,
                'iva' => 0.00
            ],
            [
                'codigo_barras' => '7506789012345',
                'codigo_interno' => 'PAPEL-REGIO-4R',
                'nombre' => 'Papel Higiénico Regio 4 rollos',
                'descripcion' => 'Papel higiénico doble hoja suave y resistente paquete de 4 rollos',
                'marca' => 'Regio',
                'categoria' => 'Higiene',
                'precio_compra' => 32.00,
                'precio_venta' => 45.00,
                'precio_mayoreo' => 40.00,
                'stock_actual' => 6,
                'stock_minimo' => 12,
                'stock_maximo' => 36,
                'unidad_medida' => 'Paquete',
                'ubicacion' => 'Pasillo 3, Estante A',
                'proveedor' => 'Kimberly Clark',
                'activo' => true
            ],
            [
                'codigo_barras' => '7507890123456',
                'codigo_interno' => 'ATUN-HERDEZ-140G',
                'nombre' => 'Atún Herdez en Agua 140g',
                'descripcion' => 'Atún en agua bajo en sodio en lata de 140 gramos',
                'marca' => 'Herdez',
                'categoria' => 'Enlatados',
                'precio_compra' => 18.50,
                'precio_venta' => 26.00,
                'precio_mayoreo' => 23.00,
                'stock_actual' => 42,
                'stock_minimo' => 24,
                'stock_maximo' => 96,
                'unidad_medida' => 'Unidad',
                'ubicacion' => 'Pasillo 2, Estante D',
                'proveedor' => 'Grupo Herdez',
                'activo' => true
            ]
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
