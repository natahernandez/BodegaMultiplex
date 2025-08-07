<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_orden',
        'user_id',
        'nombre_cliente',
        'email_cliente',
        'telefono_cliente',
        'dpi',
        'nit',
        'direccion_entrega',
        'ciudad',
        'departamento',
        'codigo_postal',
        'estado',
        'estado_pago',
        'metodo_pago',
        'tipo_pago',
        'info_pago',
        'subtotal',
        'impuestos',
        'envio',
        'descuento',
        'total',
        'fecha_pedido',
        'fecha_entrega_estimada',
        'fecha_entrega_real',
        'notas_cliente',
        'notas_admin',
        'codigo_seguimiento',
        'guia_envio',
        'empresa_envio',
    ];

    protected $casts = [
        'fecha_pedido' => 'datetime',
        'fecha_entrega_estimada' => 'datetime',
        'fecha_entrega_real' => 'datetime',
        'subtotal' => 'decimal:2',
        'impuestos' => 'decimal:2',
        'envio' => 'decimal:2',
        'descuento' => 'decimal:2',
        'total' => 'decimal:2',
        'info_pago' => 'array',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Eventos del modelo
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->numero_orden)) {
                $order->numero_orden = static::generateOrderNumber();
            }
        });
    }

    // Métodos auxiliares
    public static function generateOrderNumber()
    {
        do {
            $number = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (static::where('numero_orden', $number)->exists());

        return $number;
    }

    public function getEstadoBadgeAttribute()
    {
        $badges = [
            'pendiente' => 'bg-warning',
            'confirmado' => 'bg-info',
            'en_preparacion' => 'bg-primary',
            'proceso' => 'bg-warning',
            'enviado' => 'bg-secondary',
            'entregado' => 'bg-success',
            'completado' => 'bg-success',
            'cancelado' => 'bg-danger',
        ];

        return $badges[$this->estado] ?? 'bg-secondary';
    }

    public function getEstadoPagoBadgeAttribute()
    {
        $badges = [
            'pendiente' => 'bg-warning',
            'pagado' => 'bg-success',
            'contra_entrega' => 'bg-info',
            'cancelado' => 'bg-danger',
        ];

        return $badges[$this->estado_pago] ?? 'bg-secondary';
    }

    public function getEstadoTextoAttribute()
    {
        $estados = [
            'pendiente' => 'Pendiente',
            'confirmado' => 'Confirmado',
            'en_preparacion' => 'En Preparación',
            'proceso' => 'En Proceso',
            'enviado' => 'Enviado',
            'entregado' => 'Entregado',
            'completado' => 'Completado',
            'cancelado' => 'Cancelado',
        ];

        return $estados[$this->estado] ?? 'Desconocido';
    }

    public function getEstadoPagoTextoAttribute()
    {
        $estados = [
            'pendiente' => 'Pendiente',
            'pagado' => 'Pagado',
            'contra_entrega' => 'Contra Entrega',
            'cancelado' => 'Cancelado',
        ];

        return $estados[$this->estado_pago] ?? 'Desconocido';
    }

    public function getTotalItemsAttribute()
    {
        return $this->items->sum('cantidad');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopePagadas($query)
    {
        return $query->where('estado_pago', 'pagado');
    }

    public function scopeEntregadas($query)
    {
        return $query->where('estado', 'entregado');
    }
}
