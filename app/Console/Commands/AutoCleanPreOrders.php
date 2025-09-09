<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class AutoCleanPreOrders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'orders:auto-clean';

    /**
     * The console command description.
     */
    protected $description = 'Limpieza automática de pre-órdenes no pagadas (ejecutar cada 5 minutos)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Buscar pre-órdenes expiradas (más de 3 minutos)
        $expiredOrders = Order::where('estado', 'pre_orden')
            ->where('created_at', '<', now()->subMinutes(3))
            ->get();
            
        $deletedCount = 0;
        
        foreach ($expiredOrders as $order) {
            try {
                // Eliminar items primero
                $order->items()->delete();
                
                // Eliminar la orden completamente
                $order->delete();
                
                $deletedCount++;
                
                Log::info('Pre-orden auto-eliminada por no pagar', [
                    'numero_orden' => $order->numero_orden,
                    'user_id' => $order->user_id,
                    'total' => $order->total,
                    'minutos_sin_pagar' => $order->created_at->diffInMinutes(now())
                ]);
                
            } catch (\Exception $e) {
                Log::error('Error eliminando pre-orden', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        if ($deletedCount > 0) {
            $this->info("🧹 Limpieza automática: {$deletedCount} pre-órdenes eliminadas de la BD");
        }
        
        return Command::SUCCESS;
    }
}