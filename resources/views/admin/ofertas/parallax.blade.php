@extends('layouts.app')

@section('styles')
<style>
.parallax-preview {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 40px;
    color: white;
    position: relative;
    overflow: hidden;
    min-height: 300px;
}

.parallax-preview::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.2);
    z-index: 1;
}

.parallax-content {
    position: relative;
    z-index: 2;
}

.offer-card-preview {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 20px;
    color: #333;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease;
}

.offer-card-preview:hover {
    transform: translateY(-5px);
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.config-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}

.preview-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}

.discount-badge-preview {
    position: absolute;
    top: 10px;
    right: 10px;
    background: linear-gradient(45deg, #ff6b6b, #ff8787);
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8em;
    font-weight: bold;
}

.savings-badge-preview {
    position: absolute;
    bottom: 10px;
    left: 10px;
    background: linear-gradient(45deg, #51cf66, #69db7c);
    color: white;
    padding: 3px 8px;
    border-radius: 10px;
    font-size: 0.75em;
    font-weight: 600;
}
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-sm mb-2 mb-sm-0">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-no-gutter">
                    <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('ofertas.index') }}">Ofertas</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Configurar Parallax</li>
                </ol>
            </nav>
            <h1 class="page-header-title">Configurar Parallax de Ofertas</h1>
            <p class="page-header-text">Personaliza cómo se muestran las ofertas en la sección parallax de la página principal</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('welcome') }}" target="_blank" class="btn btn-outline-primary">
                <i class="bi-eye me-1"></i> Ver Página Principal
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Vista Previa del Parallax -->
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-header-title">Vista Previa del Parallax</h4>
            </div>
            <div class="card-body p-0">
                <div class="parallax-preview">
                    <div class="parallax-content text-center">
                        <h2 class="display-4 fw-bold mb-3">
                            <i class="bi-fire text-warning me-3"></i>
                            ¡Ofertas Especiales!
                        </h2>
                        <p class="lead mb-4">Descubre nuestras mejores ofertas con descuentos increíbles</p>
                        
                        @if($productosEnOferta->count() > 0)
                            <div class="product-grid">
                                @foreach($productosEnOferta->take(3) as $producto)
                                    <div class="offer-card-preview position-relative">
                                        @if($producto->descuento_calculado > 0)
                                            <div class="discount-badge-preview">
                                                -{{ $producto->descuento_calculado }}%
                                            </div>
                                        @endif
                                        
                                        @if($producto->ahorro > 0)
                                            <div class="savings-badge-preview">
                                                Ahorras Q{{ number_format($producto->ahorro, 2) }}
                                            </div>
                                        @endif
                                        
                                        <div class="d-flex align-items-center mb-3">
                                            @if($producto->imagen_principal_url)
                                                <img src="{{ $producto->imagen_principal_url }}" alt="{{ $producto->nombre }}" class="preview-image me-3">
                                            @else
                                                <div class="preview-image me-3 bg-light d-flex align-items-center justify-content-center">
                                                    <i class="bi-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="text-start">
                                                <h6 class="mb-1">{{ Str::limit($producto->nombre, 30) }}</h6>
                                                <small class="text-muted">{{ $producto->categoria }}</small>
                                            </div>
                                        </div>
                                        
                                        <div class="price-section mb-3">
                                            @if($producto->es_oferta_activa)
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="text-decoration-line-through text-muted">Q{{ number_format($producto->precio_venta, 2) }}</span>
                                                    <span class="h5 mb-0 text-danger">Q{{ number_format($producto->precio_final, 2) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="d-grid">
                                            <button class="btn btn-primary btn-sm">
                                                <i class="bi-cart-plus me-1"></i> Agregar
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi-tag display-1 text-white-50 mb-3"></i>
                                <h4>No hay ofertas disponibles</h4>
                                <p class="text-white-50">Crea ofertas para que aparezcan en esta sección</p>
                                <a href="{{ route('ofertas.create') }}" class="btn btn-outline-light">
                                    <i class="bi-plus me-1"></i> Crear Primera Oferta
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Productos en Oferta Disponibles -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-header-title">Productos en Oferta ({{ $productosEnOferta->count() }})</h4>
                <small class="text-muted">Estos productos aparecerán automáticamente en la sección parallax</small>
            </div>
            <div class="card-body">
                @if($productosEnOferta->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-borderless table-thead-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Descuento</th>
                                    <th>Precios</th>
                                    <th>Vigencia</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productosEnOferta as $producto)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($producto->imagen_principal_url)
                                                    <img src="{{ $producto->imagen_principal_url }}" alt="{{ $producto->nombre }}" class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                                                @else
                                                    <div class="me-3 bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 6px;">
                                                        <i class="bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $producto->nombre }}</h6>
                                                    <small class="text-muted">{{ $producto->codigo_interno }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $producto->categoria }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $producto->descuento_calculado }}%</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-decoration-line-through text-muted small">Q{{ number_format($producto->precio_venta, 2) }}</span>
                                                <span class="fw-bold text-danger">Q{{ number_format($producto->precio_final, 2) }}</span>
                                            </div>
                                            <small class="text-success">Ahorras Q{{ number_format($producto->ahorro, 2) }}</small>
                                        </td>
                                        <td>
                                            @if($producto->fecha_inicio_oferta || $producto->fecha_fin_oferta)
                                                <div class="small">
                                                    @if($producto->fecha_inicio_oferta)
                                                        <div>Desde: {{ $producto->fecha_inicio_oferta->format('d/m/Y') }}</div>
                                                    @endif
                                                    @if($producto->fecha_fin_oferta)
                                                        <div>Hasta: {{ $producto->fecha_fin_oferta->format('d/m/Y') }}</div>
                                                    @else
                                                        <div class="text-muted">Sin límite</div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted small">Permanente</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($producto->es_oferta_activa)
                                                <span class="badge bg-success">Activa</span>
                                            @elseif($producto->fecha_inicio_oferta && $producto->fecha_inicio_oferta > now())
                                                <span class="badge bg-info">Programada</span>
                                            @else
                                                <span class="badge bg-warning">Inactiva</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi-fire display-4 text-muted mb-3"></i>
                        <h5>No hay productos en oferta</h5>
                        <p class="text-muted mb-4">Crea ofertas para que aparezcan en la sección parallax de la página principal</p>
                        <a href="{{ route('ofertas.create') }}" class="btn btn-primary">
                            <i class="bi-plus me-1"></i> Crear Nueva Oferta
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Información del Parallax -->
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-header-title">Información del Parallax</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-bold">¿Qué es la sección parallax?</h6>
                    <p class="small text-muted">Es una sección visual atractiva en la página principal que muestra automáticamente los productos en oferta con efectos de parallax y animaciones.</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-bold">Características:</h6>
                    <ul class="small text-muted">
                        <li>Se muestra entre los productos y el footer</li>
                        <li>Aparece automáticamente cuando hay ofertas activas</li>
                        <li>Máximo 6 productos mostrados</li>
                        <li>Efectos visuales atractivos</li>
                        <li>Responsive para móviles</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-bold">Productos mostrados:</h6>
                    <p class="small text-muted">Solo los productos con ofertas activas aparecerán en esta sección. Los productos se ordenan por fecha de creación de la oferta.</p>
                </div>
            </div>
        </div>
        
        <!-- Estadísticas -->
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-header-title">Estadísticas</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h3 mb-0 text-primary">{{ $productosEnOferta->count() }}</div>
                            <small class="text-muted">Ofertas Activas</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h3 mb-0 text-success">{{ min(6, $productosEnOferta->count()) }}</div>
                            <small class="text-muted">En Parallax</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h3 mb-0 text-info">
                                @if($productosEnOferta->count() > 0)
                                    {{ number_format($productosEnOferta->avg('descuento_calculado'), 1) }}%
                                @else
                                    0%
                                @endif
                            </div>
                            <small class="text-muted">Descuento Promedio</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h3 mb-0 text-warning">
                                Q{{ number_format($productosEnOferta->sum('ahorro'), 2) }}
                            </div>
                            <small class="text-muted">Ahorro Total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Acciones Rápidas -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-header-title">Acciones Rápidas</h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('ofertas.create') }}" class="btn btn-primary">
                        <i class="bi-plus me-1"></i> Nueva Oferta
                    </a>
                    <a href="{{ route('ofertas.index') }}" class="btn btn-outline-secondary">
                        <i class="bi-list me-1"></i> Gestionar Ofertas
                    </a>
                    <a href="{{ route('welcome') }}" target="_blank" class="btn btn-outline-info">
                        <i class="bi-eye me-1"></i> Ver Página Principal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Actualizar vista previa cada 30 segundos
setInterval(function() {
    // Aquí podrías agregar lógica para actualizar dinámicamente
    // la vista previa si hay cambios en las ofertas
}, 30000);

// Mostrar tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
