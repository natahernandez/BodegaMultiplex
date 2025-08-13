<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends Controller
{
    /**
     * Display a listing of user's orders.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Order::with(['items.producto'])
            ->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('email_cliente', $user->email);
            })
            ->whereNotIn('estado', ['expirado']); // No mostrar órdenes expiradas a usuarios

        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_pedido', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_pedido', '<=', $request->fecha_fin);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_orden', 'like', "%{$search}%")
                  ->orWhere('codigo_seguimiento', 'like', "%{$search}%")
                  ->orWhere('guia_envio', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderBy('fecha_pedido', 'desc')->paginate(10)->appends($request->query());

        // Estadísticas del usuario (excluyendo pre-órdenes y expiradas)
        $baseQuery = function() use ($user) {
            return Order::where(function($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('email_cliente', $user->email);
            })->whereNotIn('estado', ['pre_orden', 'expirado']);
        };
        
        $stats = [
            'total_ordenes' => $baseQuery()->count(),
            'ordenes_pendientes' => $baseQuery()->whereIn('estado', ['pendiente', 'confirmado', 'en_preparacion', 'proceso'])->count(),
            'ordenes_entregadas' => $baseQuery()->whereIn('estado', ['entregado', 'completado'])->count(),
            'total_gastado' => $baseQuery()->where('estado', '!=', 'cancelado')->sum('total'),
        ];

        return view('user.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified order with tracking timeline.
     */
    public function show(Order $order)
    {
        $user = Auth::user();
        
        // Verificar que la orden pertenece al usuario
        if ($order->user_id !== $user->id && $order->email_cliente !== $user->email) {
            abort(403, 'No tienes permisos para ver esta orden.');
        }

        $order->load(['items.producto']);

        // Timeline de seguimiento
        $timeline = $this->getOrderTimeline($order);

        return view('user.orders.show', compact('order', 'timeline'));
    }

    /**
     * Generate order tracking timeline
     */
    private function getOrderTimeline(Order $order)
    {
        $timeline = [];
        
        $timeline[] = [
            'status' => 'pendiente',
            'title' => 'Pedido Realizado',
            'description' => 'Tu pedido ha sido recibido y está siendo procesado',
            'date' => $order->fecha_pedido,
            'completed' => true,
            'icon' => 'bi-cart-check'
        ];

        if (in_array($order->estado, ['confirmado', 'en_preparacion', 'proceso', 'enviado', 'entregado', 'completado'])) {
            $timeline[] = [
                'status' => 'confirmado',
                'title' => 'Pedido Confirmado',
                'description' => 'Tu pedido ha sido confirmado y está en preparación',
                'date' => $order->updated_at,
                'completed' => true,
                'icon' => 'bi-check-circle'
            ];
        }

        if (in_array($order->estado, ['en_preparacion', 'proceso', 'enviado', 'entregado', 'completado'])) {
            $timeline[] = [
                'status' => 'en_preparacion',
                'title' => 'En Preparación',
                'description' => 'Estamos preparando tu pedido para el envío',
                'date' => $order->updated_at,
                'completed' => true,
                'icon' => 'bi-box-seam'
            ];
        }

        if (in_array($order->estado, ['enviado', 'entregado', 'completado'])) {
            $description = 'Tu pedido está en camino';
            if ($order->empresa_envio) {
                $description = "Enviado con {$order->empresa_envio}";
                if ($order->guia_envio) {
                    $description .= " - Guía: {$order->guia_envio}";
                }
            }
            $timeline[] = [
                'status' => 'enviado',
                'title' => 'Enviado',
                'description' => $description,
                'date' => $order->updated_at,
                'completed' => true,
                'icon' => 'bi-truck',
                'tracking' => $order->codigo_seguimiento,
                'guia' => $order->guia_envio,
                'empresa' => $order->empresa_envio
            ];
        }

        if (in_array($order->estado, ['entregado', 'completado'])) {
            $timeline[] = [
                'status' => 'entregado',
                'title' => 'Entregado',
                'description' => 'Tu pedido ha sido entregado exitosamente',
                'date' => $order->fecha_entrega_real ?? $order->updated_at,
                'completed' => true,
                'icon' => 'bi-house-check'
            ];
        }

        if ($order->estado === 'cancelado') {
            $timeline[] = [
                'status' => 'cancelado',
                'title' => 'Pedido Cancelado',
                'description' => 'Este pedido ha sido cancelado',
                'date' => $order->updated_at,
                'completed' => true,
                'icon' => 'bi-x-circle',
                'error' => true
            ];
        }

        return $timeline;
    }
}