<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class ExpirePreOrdenes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:expire-pre-ordenes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expirar pre-órdenes que no fueron pagadas en el tiempo límite';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Buscando pre-órdenes expiradas...');
        
        // Buscar pre-órdenes expiradas
        $expiradas = Order::expiradas()->get();
        
        if ($expiradas->isEmpty()) {
            $this->info('✅ No hay pre-órdenes expiradas.');
            return 0;
        }
        
        $total = $expiradas->count();
        $this->warn("⚠️ Encontradas {$total} pre-órdenes expiradas");
        
        $bar = $this->output->createProgressBar($total);
        $bar->start();
        
        $expiradosCount = 0;
        
        foreach ($expiradas as $order) {
            if ($order->expire()) {
                $expiradosCount++;
                
                Log::info('Pre-orden expirada automáticamente', [
                    'order_id' => $order->id,
                    'numero_orden' => $order->numero_orden,
                    'user_id' => $order->user_id,
                    'total' => $order->total,
                    'fecha_creacion' => $order->fecha_pedido,
                    'fecha_expiracion' => $order->fecha_entrega_estimada
                ]);
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info("✅ Proceso completado:");
        $this->line("   - Pre-órdenes encontradas: {$total}");
        $this->line("   - Pre-órdenes expiradas: {$expiradosCount}");
        
        if ($expiradosCount > 0) {
            $this->warn("💡 Tip: Las pre-órdenes expiradas no afectan el stock y pueden ser ignoradas por los administradores.");
        }
        
        return 0;
    }
}