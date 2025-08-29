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
        'brand_id',
        'category_id',
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
        'en_oferta',
        'descuento_porcentaje',
        'precio_oferta',
        'fecha_inicio_oferta',
        'fecha_fin_oferta'
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'precio_mayoreo' => 'decimal:2',
        'precio_oferta' => 'decimal:2',
        'descuento_porcentaje' => 'decimal:2',
        'fecha_vencimiento' => 'date',
        'fecha_inicio_oferta' => 'datetime',
        'fecha_fin_oferta' => 'datetime',
        'activo' => 'boolean',
        'requiere_receta' => 'boolean',
        'en_oferta' => 'boolean',
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

    public function scopePorBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    public function scopePorCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeEnOferta($query)
    {
        return $query->where('en_oferta', true)
                     ->where(function($q) {
                         $q->whereNull('fecha_inicio_oferta')
                           ->orWhere('fecha_inicio_oferta', '<=', now());
                     })
                     ->where(function($q) {
                         $q->whereNull('fecha_fin_oferta')
                           ->orWhere('fecha_fin_oferta', '>=', now());
                     });
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

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Accessors
    public function getPrecioVentaFormateadoAttribute()
    {
        return 'Q' . number_format($this->precio_venta, 2);
    }

    public function getPrecioCompraFormateadoAttribute()
    {
        return 'Q' . number_format($this->precio_compra, 2);
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

    // Métodos para manejo de ofertas
    public function getEsOfertaActivaAttribute()
    {
        if (!$this->en_oferta) {
            return false;
        }

        $now = now();
        
        // Verificar fechas de inicio y fin
        if ($this->fecha_inicio_oferta && $this->fecha_inicio_oferta > $now) {
            return false;
        }
        
        if ($this->fecha_fin_oferta && $this->fecha_fin_oferta < $now) {
            return false;
        }

        return true;
    }

    public function getPrecioFinalAttribute()
    {
        if ($this->es_oferta_activa && $this->precio_oferta) {
            return $this->precio_oferta;
        }
        
        return $this->precio_venta;
    }

    public function getDescuentoCalculadoAttribute()
    {
        if (!$this->es_oferta_activa) {
            return 0;
        }

        if ($this->descuento_porcentaje) {
            return $this->descuento_porcentaje;
        }

        if ($this->precio_oferta && $this->precio_venta > 0) {
            return round((($this->precio_venta - $this->precio_oferta) / $this->precio_venta) * 100, 2);
        }

        return 0;
    }

    public function getAhorroAttribute()
    {
        if (!$this->es_oferta_activa) {
            return 0;
        }

        return $this->precio_venta - $this->precio_final;
    }
}
