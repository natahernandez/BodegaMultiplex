<?php

namespace App\Console\Commands;

use App\Models\ProductoImagen;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SyncProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:sync-images {--force : Force sync even if files exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronizar imágenes de productos al directorio público para acceso web';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Iniciando sincronización de imágenes de productos...');
        
        $force = $this->option('force');
        $imagenes = ProductoImagen::all();
        $sincronizadas = 0;
        $errores = 0;
        
        if ($imagenes->isEmpty()) {
            $this->warn('⚠️  No se encontraron imágenes de productos para sincronizar.');
            return 0;
        }
        
        $this->line("📊 Se encontraron {$imagenes->count()} imágenes para procesar.");
        
        // Crear directorio público si no existe
        $publicDir = public_path('storage/productos');
        if (!is_dir($publicDir)) {
            mkdir($publicDir, 0755, true);
            $this->info("📁 Directorio creado: {$publicDir}");
        }
        
        $progressBar = $this->output->createProgressBar($imagenes->count());
        $progressBar->start();
        
        foreach ($imagenes as $imagen) {
            try {
                $sourceFile = storage_path('app/public/' . $imagen->ruta_imagen);
                $publicFile = public_path('storage/' . $imagen->ruta_imagen);
                
                // Verificar si el archivo fuente existe
                if (!file_exists($sourceFile)) {
                    $this->newLine();
                    $this->error("❌ Archivo fuente no existe: {$sourceFile}");
                    $errores++;
                    $progressBar->advance();
                    continue;
                }
                
                // Verificar si necesita sincronización
                if (!$force && file_exists($publicFile) && filemtime($sourceFile) <= filemtime($publicFile)) {
                    $progressBar->advance();
                    continue;
                }
                
                // Crear directorio si no existe
                $publicFileDir = dirname($publicFile);
                if (!is_dir($publicFileDir)) {
                    mkdir($publicFileDir, 0755, true);
                }
                
                // Copiar archivo
                if (copy($sourceFile, $publicFile)) {
                    $sincronizadas++;
                } else {
                    $this->newLine();
                    $this->error("❌ Error copiando: {$imagen->ruta_imagen}");
                    $errores++;
                }
                
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("❌ Error procesando imagen ID {$imagen->id}: " . $e->getMessage());
                $errores++;
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        // Resumen
        $this->info("✅ Sincronización completada:");
        $this->line("   📸 Imágenes sincronizadas: {$sincronizadas}");
        $this->line("   ❌ Errores: {$errores}");
        $this->line("   📊 Total procesadas: {$imagenes->count()}");
        
        if ($errores > 0) {
            $this->warn("⚠️  Se encontraron {$errores} errores durante la sincronización.");
            return 1;
        }
        
        $this->info("🎉 Todas las imágenes han sido sincronizadas correctamente.");
        return 0;
    }
}
