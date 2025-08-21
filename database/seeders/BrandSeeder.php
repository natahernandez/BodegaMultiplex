<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'nombre' => 'Samsung',
                'descripcion' => 'Marca líder en tecnología y electrónicos',
                'activo' => true,
            ],
            [
                'nombre' => 'Apple',
                'descripcion' => 'Innovación en dispositivos móviles y computadoras',
                'activo' => true,
            ],
            [
                'nombre' => 'Nike',
                'descripcion' => 'Marca deportiva internacional',
                'activo' => true,
            ],
            [
                'nombre' => 'Adidas',
                'descripcion' => 'Equipamiento deportivo de alta calidad',
                'activo' => true,
            ],
            [
                'nombre' => 'Sony',
                'descripcion' => 'Electrónicos y entretenimiento',
                'activo' => true,
            ],
            [
                'nombre' => 'HP',
                'descripcion' => 'Computadoras e impresoras',
                'activo' => true,
            ],
            [
                'nombre' => 'Dell',
                'descripcion' => 'Soluciones tecnológicas empresariales',
                'activo' => true,
            ],
            [
                'nombre' => 'Coca Cola',
                'descripcion' => 'Bebidas refrescantes',
                'activo' => true,
            ],
            [
                'nombre' => 'Pepsi',
                'descripcion' => 'Bebidas y snacks',
                'activo' => true,
            ],
            [
                'nombre' => 'Nestlé',
                'descripcion' => 'Alimentos y bebidas',
                'activo' => true,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
