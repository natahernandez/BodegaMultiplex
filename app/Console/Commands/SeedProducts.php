<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\ProductoSeeder;

class SeedProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:products {--count=120 : Número de productos a generar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera productos de prueba para la base de datos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando generación de productos...');
        
        // Verificar que existan marcas y categorías
        if (!\App\Models\Brand::exists() || !\App\Models\Category::exists()) {
            $this->error('Error: Debes ejecutar primero BrandSeeder y CategorySeeder');
            $this->error('Ejecuta: php artisan db:seed --class=BrandSeeder');
            $this->error('Luego: php artisan db:seed --class=CategorySeeder');
            return 1;
        }

        try {
            $seeder = new ProductoSeeder();
            $seeder->run();
            
            $this->info('¡Productos generados exitosamente!');
            $this->info('Total de productos en la base de datos: ' . \App\Models\Producto::count());
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Error al generar productos: ' . $e->getMessage());
            return 1;
        }
    }
}
