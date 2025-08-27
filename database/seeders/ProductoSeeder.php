<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener marcas y categorías existentes
        $brands = Brand::all();
        $categories = Category::all();
        
        if ($brands->isEmpty() || $categories->isEmpty()) {
            $this->command->error('Debes ejecutar primero BrandSeeder y CategorySeeder');
            return;
        }

        $productos = [
            // Productos originales
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
                'activo' => true,
                'brand_id' => $brands->where('nombre', 'Coca-Cola')->first()?->id ?? $brands->random()->id,
                'category_id' => $categories->where('nombre', 'Bebidas')->first()?->id ?? $categories->random()->id,
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
                'activo' => true,
                'brand_id' => $brands->where('nombre', 'Lala')->first()?->id ?? $brands->random()->id,
                'category_id' => $categories->where('nombre', 'Lácteos')->first()?->id ?? $categories->random()->id,
            ],
        ];

        // Generar productos adicionales
        $nombres = [
            'Aceite', 'Arroz', 'Azúcar', 'Café', 'Chocolate', 'Detergente', 'Enjuague', 'Galletas',
            'Harina', 'Jabón', 'Jugo', 'Leche', 'Mantequilla', 'Mayonesa', 'Mermelada', 'Mostaza',
            'Néctar', 'Pasta', 'Queso', 'Refresco', 'Sal', 'Salsa', 'Shampoo', 'Sopa', 'Té', 'Vinagre',
            'Yogurt', 'Zumo', 'Aceitunas', 'Atún', 'Cereales', 'Conservas', 'Desodorante', 'Enlatados',
            'Frijoles', 'Gelatina', 'Helado', 'Huevos', 'Jalea', 'Ketchup', 'Limpieza', 'Mantequilla',
            'Nueces', 'Oregano', 'Pan', 'Papel', 'Queso', 'Refresco', 'Salsa', 'Shampoo', 'Sopa',
            'Té', 'Vinagre', 'Yogurt', 'Zumo', 'Aceitunas', 'Atún', 'Cereales', 'Conservas'
        ];

        $marcas = ['Nestlé', 'Kraft', 'Unilever', 'P&G', 'Coca-Cola', 'Pepsi', 'Frito-Lay', 'General Mills'];
        $categorias = ['Bebidas', 'Lácteos', 'Abarrotes', 'Limpieza', 'Cuidado Personal', 'Enlatados', 'Panadería', 'Higiene'];
        $unidades = ['Unidad', 'Kg', 'Litro', 'Paquete', 'Botella', 'Caja', 'Bolsa', 'Frasco'];
        $proveedores = ['Distribuidora Central', 'Importadora del Norte', 'Comercial del Sur', 'Mayorista Express', 'Distribuidora Nacional'];

        // Generar 120 productos adicionales
        for ($i = 1; $i <= 120; $i++) {
            $nombre = $nombres[array_rand($nombres)];
            $marca = $marcas[array_rand($marcas)];
            $categoria = $categorias[array_rand($categorias)];
            $unidad = $unidades[array_rand($unidades)];
            $proveedor = $proveedores[array_rand($proveedores)];
            
            $precio_compra = rand(500, 5000) / 100; // Precio entre 5.00 y 50.00
            $precio_venta = $precio_compra * (1 + (rand(20, 50) / 100)); // Margen del 20% al 50%
            $precio_mayoreo = $precio_compra * (1 + (rand(15, 35) / 100)); // Margen del 15% al 35%
            
            $stock_actual = rand(0, 100);
            $stock_minimo = rand(10, 30);
            $stock_maximo = $stock_minimo * rand(3, 6);
            // Código interno seguro (solo ASCII / alfanumérico)
            $nombreAscii = Str::upper(substr(Str::ascii($nombre), 0, 3));
            $marcaAscii = Str::upper(substr(Str::ascii(preg_replace('/[^A-Za-z0-9]/', '', $marca)), 0, 3));
            if (strlen($nombreAscii) < 3) { $nombreAscii = str_pad($nombreAscii, 3, 'X'); }
            if (strlen($marcaAscii) < 3) { $marcaAscii = str_pad($marcaAscii, 3, 'X'); }

            $productos[] = [
                'codigo_barras' => '75' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT),
                'codigo_interno' => $nombreAscii . '-' . $marcaAscii . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nombre' => $nombre . ' ' . $marca . ' ' . $unidad,
                'descripcion' => 'Producto ' . $nombre . ' de la marca ' . $marca . ' en presentación de ' . $unidad,
                'marca' => $marca,
                'categoria' => $categoria,
                'precio_compra' => $precio_compra,
                'precio_venta' => round($precio_venta, 2),
                'precio_mayoreo' => round($precio_mayoreo, 2),
                'stock_actual' => $stock_actual,
                'stock_minimo' => $stock_minimo,
                'stock_maximo' => $stock_maximo,
                'unidad_medida' => $unidad,
                'ubicacion' => 'Pasillo ' . rand(1, 5) . ', Estante ' . chr(65 + rand(0, 3)),
                'proveedor' => $proveedor,
                'activo' => rand(0, 10) > 1, // 90% de probabilidad de estar activo
                'brand_id' => $brands->random()->id,
                'category_id' => $categories->random()->id,
                'fecha_vencimiento' => rand(0, 10) > 7 ? now()->addDays(rand(1, 365)) : null, // 30% de probabilidad de tener fecha de vencimiento
                'requiere_receta' => rand(0, 100) > 95, // 5% de probabilidad de requerir receta
            ];
        }

        $productosCreados = 0;
        $productosExistentes = 0;

        foreach ($productos as $producto) {
            // Verificar si el producto ya existe por código interno
            if (!Producto::where('codigo_interno', $producto['codigo_interno'])->exists()) {
                Producto::create($producto);
                $productosCreados++;
            } else {
                $productosExistentes++;
            }
        }

        $this->command->info("Se han creado {$productosCreados} productos nuevos.");
        if ($productosExistentes > 0) {
            $this->command->info("Se omitieron {$productosExistentes} productos que ya existían.");
        }
        $this->command->info('Total de productos en la base de datos: ' . Producto::count());
    }
}
