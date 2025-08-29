@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-sm mb-2 mb-sm-0">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-no-gutter">
                    <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('ofertas.index') }}">Ofertas</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $oferta->nombre }}</li>
                </ol>
            </nav>
            <h1 class="page-header-title">Detalle de Oferta</h1>
            <p class="page-header-text">Información completa del producto en oferta</p>
        </div>
        <div class="col-auto">
            <div class="btn-group">
                <a href="{{ route('ofertas.edit', $oferta) }}" class="btn btn-primary">
                    <i class="bi-pencil me-1"></i> Editar Oferta
                </a>
                <a href="{{ route('shop.product.show', $oferta) }}" target="_blank" class="btn btn-outline-info">
                    <i class="bi-eye me-1"></i> Ver en Tienda
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Información del Producto -->
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-header-title">Información del Producto</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        @if($oferta->imagen_principal_url)
                            <img src="{{ $oferta->imagen_principal_url }}" alt="{{ $oferta->nombre }}" class="img-fluid rounded mb-3">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded mb-3" style="height: 200px;">
                                <i class="bi-image display-4 text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h3 class="mb-3">{{ $oferta->nombre }}</h3>
                        
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <strong>Código:</strong> {{ $oferta->codigo_interno }}
                            </div>
                            <div class="col-sm-6">
                                <strong>Categoría:</strong> 
                                <span class="badge bg-secondary">{{ $oferta->categoria }}</span>
                            </div>
                        </div>
                        
                        @if($oferta->brand)
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <strong>Marca:</strong> {{ $oferta->brand->nombre }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Stock:</strong> {{ $oferta->stock_actual }} {{ $oferta->unidad_medida }}
                                </div>
                            </div>
                        @endif
                        
                        @if($oferta->descripcion)
                            <div class="mb-3">
                                <strong>Descripción:</strong>
                                <p class="text-muted mb-0">{{ $oferta->descripcion }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Estado de la Oferta -->
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-header-title">Estado de la Oferta</h4>
            </div>
            <div class="card-body text-center">
                @if($oferta->es_oferta_activa)
                    <div class="mb-3">
                        <span class="badge bg-success fs-6 px-3 py-2">
                            <i class="bi-check-circle me-1"></i> Oferta Activa
                        </span>
                    </div>
                @elseif($oferta->fecha_inicio_oferta && $oferta->fecha_inicio_oferta > now())
                    <div class="mb-3">
                        <span class="badge bg-info fs-6 px-3 py-2">
                            <i class="bi-clock me-1"></i> Oferta Programada
                        </span>
                    </div>
                @elseif($oferta->fecha_fin_oferta && $oferta->fecha_fin_oferta < now())
                    <div class="mb-3">
                        <span class="badge bg-warning fs-6 px-3 py-2">
                            <i class="bi-exclamation-triangle me-1"></i> Oferta Vencida
                        </span>
                    </div>
                @else
                    <div class="mb-3">
                        <span class="badge bg-secondary fs-6 px-3 py-2">
                            <i class="bi-pause me-1"></i> Oferta Inactiva
                        </span>
                    </div>
                @endif
                
                <!-- Información de Vigencia -->
                <div class="small text-muted">
                    @if($oferta->fecha_inicio_oferta)
                        <div><strong>Inicio:</strong> {{ $oferta->fecha_inicio_oferta->format('d/m/Y H:i') }}</div>
                    @else
                        <div><strong>Inicio:</strong> Inmediato</div>
                    @endif
                    
                    @if($oferta->fecha_fin_oferta)
                        <div><strong>Fin:</strong> {{ $oferta->fecha_fin_oferta->format('d/m/Y H:i') }}</div>
                    @else
                        <div><strong>Fin:</strong> Sin límite</div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Información de Precios -->
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-header-title">Información de Precios</h4>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-12">
                        <div class="border rounded p-3">
                            <div class="text-muted small">Precio Original</div>
                            <div class="h4 mb-0 text-decoration-line-through">Q{{ number_format($oferta->precio_venta, 2) }}</div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="border rounded p-3 bg-success-soft">
                            <div class="text-muted small">Precio con Oferta</div>
                            <div class="h3 mb-0 text-success">Q{{ number_format($oferta->precio_final, 2) }}</div>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <div class="border rounded p-3">
                            <div class="text-muted small">Descuento</div>
                            <div class="h5 mb-0 text-primary">{{ $oferta->descuento_calculado }}%</div>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <div class="border rounded p-3">
                            <div class="text-muted small">Ahorro</div>
                            <div class="h5 mb-0 text-warning">Q{{ number_format($oferta->ahorro, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Acciones -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-header-title">Acciones</h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('ofertas.edit', $oferta) }}" class="btn btn-primary">
                        <i class="bi-pencil me-1"></i> Editar Oferta
                    </a>
                    
                    @if($oferta->en_oferta)
                        <button type="button" class="btn btn-outline-danger" onclick="eliminarOferta({{ $oferta->id }})">
                            <i class="bi-trash me-1"></i> Eliminar Oferta
                        </button>
                    @endif
                    
                    <a href="{{ route('shop.product.show', $oferta) }}" target="_blank" class="btn btn-outline-info">
                        <i class="bi-eye me-1"></i> Ver en Tienda
                    </a>
                    
                    <a href="{{ route('ofertas.index') }}" class="btn btn-outline-secondary">
                        <i class="bi-arrow-left me-1"></i> Volver al Listado
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timeline de la Oferta (si tiene fechas) -->
@if($oferta->fecha_inicio_oferta || $oferta->fecha_fin_oferta)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-header-title">Timeline de la Oferta</h4>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @if($oferta->fecha_inicio_oferta)
                        <div class="timeline-item {{ $oferta->fecha_inicio_oferta <= now() ? 'timeline-item-success' : 'timeline-item-pending' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Inicio de Oferta</h6>
                                <p class="timeline-text">{{ $oferta->fecha_inicio_oferta->format('d/m/Y H:i') }}</p>
                                <small class="text-muted">
                                    @if($oferta->fecha_inicio_oferta <= now())
                                        Iniciada {{ $oferta->fecha_inicio_oferta->diffForHumans() }}
                                    @else
                                        Iniciará {{ $oferta->fecha_inicio_oferta->diffForHumans() }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    @endif
                    
                    @if($oferta->fecha_fin_oferta)
                        <div class="timeline-item {{ $oferta->fecha_fin_oferta <= now() ? 'timeline-item-danger' : 'timeline-item-warning' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Fin de Oferta</h6>
                                <p class="timeline-text">{{ $oferta->fecha_fin_oferta->format('d/m/Y H:i') }}</p>
                                <small class="text-muted">
                                    @if($oferta->fecha_fin_oferta <= now())
                                        Finalizó {{ $oferta->fecha_fin_oferta->diffForHumans() }}
                                    @else
                                        Finalizará {{ $oferta->fecha_fin_oferta->diffForHumans() }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
function eliminarOferta(productoId) {
    if (confirm('¿Estás seguro de que deseas eliminar esta oferta?')) {
        fetch(`/ofertas/${productoId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Oferta eliminada exitosamente');
                window.location.href = '{{ route("ofertas.index") }}';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error al eliminar la oferta');
            console.error('Error:', error);
        });
    }
}
</script>

<style>
.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -37px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #6c757d;
    border: 2px solid #fff;
    box-shadow: 0 0 0 3px #e9ecef;
}

.timeline-item-success .timeline-marker {
    background-color: #198754;
    box-shadow: 0 0 0 3px #d1e7dd;
}

.timeline-item-warning .timeline-marker {
    background-color: #ffc107;
    box-shadow: 0 0 0 3px #fff3cd;
}

.timeline-item-danger .timeline-marker {
    background-color: #dc3545;
    box-shadow: 0 0 0 3px #f8d7da;
}

.timeline-item-pending .timeline-marker {
    background-color: #0dcaf0;
    box-shadow: 0 0 0 3px #cff4fc;
}

.timeline-title {
    font-weight: 600;
    margin-bottom: 5px;
}

.timeline-text {
    margin-bottom: 5px;
}
</style>
@endsection
