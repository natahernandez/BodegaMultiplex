<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Relaciones
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Accessors
    public function getImagenUrlAttribute()
    {
        if ($this->imagen && \App\Helpers\ImageHelper::imageExists($this->imagen)) {
            return \App\Helpers\ImageHelper::getProductImageUrl($this->imagen);
        }
        return null;
    }
}
