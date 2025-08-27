<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetAndSeedDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset-and-seed {--force : Forzar la operación sin confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia la base de datos y ejecuta todos los seeders desde cero';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('¿Estás seguro de que quieres limpiar toda la base de datos? Esta acción no se puede deshacer.')) {
                $this->info('Operación cancelada.');
                return 0;
            }
        }

        $this->info('Iniciando limpieza de la base de datos...');

        try {
            // Desactivar verificación de claves foráneas
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Obtener todas las tablas
            $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

            // Limpiar todas las tablas
            foreach ($tables as $table) {
                if ($table !== 'migrations') {
                    DB::table($table)->truncate();
                    $this->info("Tabla {$table} limpiada.");
                }
            }

            // Reactivar verificación de claves foráneas
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->info('Base de datos limpiada exitosamente.');

            // Ejecutar seeders en orden
            $this->info('Ejecutando seeders...');

            $this->call('db:seed', ['--class' => 'InitialAdminSeeder']);
            $this->call('db:seed', ['--class' => 'BrandSeeder']);
            $this->call('db:seed', ['--class' => 'CategorySeeder']);
            $this->call('db:seed', ['--class' => 'ProductoSeeder']);

            $this->info('¡Base de datos reseteada y poblada exitosamente!');
            
            return 0;

        } catch (\Exception $e) {
            $this->error('Error durante la operación: ' . $e->getMessage());
            return 1;
        }
    }
}
