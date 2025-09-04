<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class CleanExpiredPreOrders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'orders:clean-expired';

    /**
     * The console command description.
     */
    protected $description = 'Limpiar pre-órdenes expiradas que no fueron pagadas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Limpiando pre-órdenes expiradas...');
        
        // Buscar pre-órdenes que han expirado (más de 30 minutos sin pagar)
        $expiredOrders = Order::where('estado', 'pre_orden')
            ->where('created_at', '<', now()->subMinutes(30))
            ->get();
            
        $count = 0;
        
        foreach ($expiredOrders as $order) {
            // Marcar como expirada
            $order->update([
                'estado' => 'expirado',
                'estado_pago' => 'cancelado',
                'notas_admin' => 'Pre-orden expirada automáticamente - no se completó el pago en 30 minutos'
            ]);
            
            $count++;
            
            Log::info('Pre-orden expirada automáticamente', [
                'order_id' => $order->id,
                'numero_orden' => $order->numero_orden,
                'created_at' => $order->created_at,
                'expired_at' => now()
            ]);
        }
        
        $this->info("Se marcaron como expiradas {$count} pre-órdenes.");
        
        return Command::SUCCESS;
    }
}
