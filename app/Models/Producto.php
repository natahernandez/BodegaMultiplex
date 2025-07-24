<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo_barras',
        'codigo_interno',
        'nombre',
        'descripcion',
        'marca',
        'categoria',
        'precio_compra',
        'precio_venta',
        'precio_mayoreo',
        'stock_actual',
        'stock_minimo',
        'stock_maximo',
        'unidad_medida',
        'ubicacion',
        'proveedor',
        'fecha_vencimiento',
        'imagen',
        'activo',
        'requiere_receta',
        'iva'
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'precio_mayoreo' => 'decimal:2',
        'iva' => 'decimal:2',
        'fecha_vencimiento' => 'date',
        'activo' => 'boolean',
        'requiere_receta' => 'boolean',
    ];

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeStockBajo($query)
    {
        return $query->whereColumn('stock_actual', '<=', 'stock_minimo');
    }

    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    // Relaciones
    public function imagenes()
    {
        return $this->hasMany(ProductoImagen::class)->orderBy('orden');
    }

    public function imagenPrincipal()
    {
        return $this->hasOne(ProductoImagen::class)->where('es_principal', true);
    }

    // Accessors
    public function getPrecioVentaFormateadoAttribute()
    {
        return '$' . number_format($this->precio_venta, 2);
    }

    public function getPrecioCompraFormateadoAttribute()
    {
        return '$' . number_format($this->precio_compra, 2);
    }

    public function getMargenGananciaAttribute()
    {
        if ($this->precio_compra > 0) {
            return (($this->precio_venta - $this->precio_compra) / $this->precio_compra) * 100;
        }
        return 0;
    }

    public function getEstadoStockAttribute()
    {
        if ($this->stock_actual <= 0) {
            return 'sin_stock';
        } elseif ($this->stock_actual <= $this->stock_minimo) {
            return 'stock_bajo';
        } elseif ($this->stock_maximo && $this->stock_actual >= $this->stock_maximo) {
            return 'stock_alto';
        }
        return 'stock_normal';
    }

    public function getEstadoStockColorAttribute()
    {
        return match($this->estado_stock) {
            'sin_stock' => 'danger',
            'stock_bajo' => 'warning',
            'stock_alto' => 'info',
            default => 'success'
        };
    }

    public function getEstadoStockTextoAttribute()
    {
        return match($this->estado_stock) {
            'sin_stock' => 'Sin Stock',
            'stock_bajo' => 'Stock Bajo',
            'stock_alto' => 'Stock Alto',
            default => 'Stock Normal'
        };
    }

    // Accessor para obtener la imagen principal
    public function getImagenPrincipalUrlAttribute()
    {
        try {
            // Intentar obtener la imagen principal de la relación cargada
            if ($this->relationLoaded('imagenPrincipal') && $this->imagenPrincipal) {
                return \App\Helpers\ImageHelper::getProductImageUrl($this->imagenPrincipal->ruta_imagen);
            }
            
            // Si no está cargada, buscar en las imágenes cargadas
            if ($this->relationLoaded('imagenes') && $this->imagenes->count() > 0) {
                $imagenPrincipal = $this->imagenes->where('es_principal', true)->first() ?? $this->imagenes->first();
                if ($imagenPrincipal) {
                    return \App\Helpers\ImageHelper::getProductImageUrl($imagenPrincipal->ruta_imagen);
                }
            }
            
            // Fallback a la imagen antigua si existe
            if ($this->imagen && \App\Helpers\ImageHelper::imageExists($this->imagen)) {
                return \App\Helpers\ImageHelper::getProductImageUrl($this->imagen);
            }
            
            return null;
        } catch (\Exception $e) {
            \Log::error('Error en getImagenPrincipalUrlAttribute: ' . $e->getMessage());
            return null;
        }
    }
}
