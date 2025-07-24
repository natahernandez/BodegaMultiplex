<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'producto_id',
        'nombre_producto',
        'codigo_producto',
        'descripcion_producto',
        'categoria_producto',
        'precio_unitario',
        'cantidad',
        'subtotal',
        'descuento_unitario',
        'descuento_total',
        'atributos_producto',
        'notas_item',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'descuento_unitario' => 'decimal:2',
        'descuento_total' => 'decimal:2',
        'atributos_producto' => 'array',
    ];

    // Relaciones
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Métodos auxiliares
    public function getTotalConDescuentoAttribute()
    {
        return $this->subtotal - $this->descuento_total;
    }

    public function getPrecioFinalAttribute()
    {
        return $this->precio_unitario - $this->descuento_unitario;
    }
}
