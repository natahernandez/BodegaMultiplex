<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nombre' => 'Electrónicos',
                'descripcion' => 'Dispositivos electrónicos y tecnología',
                'activo' => true,
            ],
            [
                'nombre' => 'Ropa y Accesorios',
                'descripcion' => 'Vestimenta y complementos',
                'activo' => true,
            ],
            [
                'nombre' => 'Deportes',
                'descripcion' => 'Artículos deportivos y fitness',
                'activo' => true,
            ],
            [
                'nombre' => 'Hogar y Jardín',
                'descripcion' => 'Productos para el hogar y jardinería',
                'activo' => true,
            ],
            [
                'nombre' => 'Alimentos y Bebidas',
                'descripcion' => 'Comida, bebidas y productos alimenticios',
                'activo' => true,
            ],
            [
                'nombre' => 'Salud y Belleza',
                'descripcion' => 'Productos de cuidado personal y salud',
                'activo' => true,
            ],
            [
                'nombre' => 'Automóviles',
                'descripcion' => 'Vehículos y accesorios automotrices',
                'activo' => true,
            ],
            [
                'nombre' => 'Libros y Medios',
                'descripcion' => 'Libros, música y entretenimiento',
                'activo' => true,
            ],
            [
                'nombre' => 'Juguetes',
                'descripcion' => 'Juguetes y juegos para niños',
                'activo' => true,
            ],
            [
                'nombre' => 'Oficina',
                'descripcion' => 'Suministros y equipos de oficina',
                'activo' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
