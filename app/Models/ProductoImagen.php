<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductoImagen extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'ruta_imagen',
        'nombre_original',
        'es_principal',
        'orden'
    ];

    protected $casts = [
        'es_principal' => 'boolean',
    ];

    // Relación con el producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
