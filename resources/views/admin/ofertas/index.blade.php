@extends('layouts.app')

@section('styles')
<style>
.stats-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}
.stats-card:hover {
    transform: translateY(-2px);
}
.offer-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 5;
}
.product-image {
    height: 80px;
    width: 80px;
    object-fit: cover;
    border-radius: 8px;
}
.price-comparison {
    display: flex;
    align-items: center;
    gap: 10px;
}
.price-original {
    text-decoration: line-through;
    color: #6c757d;
    font-size: 0.9em;
}
.price-offer {
    color: #dc3545;
    font-weight: bold;
}
.filter-card {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
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
                    <li class="breadcrumb-item active" aria-current="page">Gestión de Ofertas</li>
                </ol>
            </nav>
            <h1 class="page-header-title">Gestión de Ofertas</h1>
            <p class="page-header-text">Administra descuentos y ofertas especiales para tus productos</p>
        </div>
        <div class="col-auto">
            <a class="btn btn-primary" href="{{ route('ofertas.create') }}">
                <i class="bi-plus me-1"></i> Nueva Oferta
            </a>
        </div>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="fs-6 text-uppercase text-muted">Total Productos</span>
                        <span class="d-block h3 mb-0">{{ number_format($stats['total_productos']) }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-primary text-white">
                            <i class="bi-box-seam"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="fs-6 text-uppercase text-muted">En Oferta</span>
                        <span class="d-block h3 mb-0 text-success">{{ number_format($stats['productos_en_oferta']) }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-success text-white">
                            <i class="bi-fire"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="fs-6 text-uppercase text-muted">Sin Oferta</span>
                        <span class="d-block h3 mb-0 text-info">{{ number_format($stats['productos_sin_oferta']) }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-info text-white">
                            <i class="bi-tag"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="fs-6 text-uppercase text-muted">Ofertas Vencidas</span>
                        <span class="d-block h3 mb-0 text-warning">{{ number_format($stats['ofertas_vencidas']) }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-warning text-white">
                            <i class="bi-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="filter-card">
    <form method="GET" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Buscar Producto</label>
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nombre o código...">
        </div>
        
        <div class="col-md-3">
            <label class="form-label">Estado</label>
            <select name="filtro" class="form-select">
                <option value="">Todos los productos</option>
                <option value="en_oferta" {{ request('filtro') == 'en_oferta' ? 'selected' : '' }}>En oferta</option>
                <option value="sin_oferta" {{ request('filtro') == 'sin_oferta' ? 'selected' : '' }}>Sin oferta</option>
                <option value="ofertas_vencidas" {{ request('filtro') == 'ofertas_vencidas' ? 'selected' : '' }}>Ofertas vencidas</option>
                <option value="ofertas_futuras" {{ request('filtro') == 'ofertas_futuras' ? 'selected' : '' }}>Ofertas futuras</option>
            </select>
        </div>
        
        <div class="col-md-3">
            <label class="form-label">Categoría</label>
            <select name="categoria" class="form-select">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria }}" {{ request('categoria') == $categoria ? 'selected' : '' }}>
                        {{ $categoria }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">
                <i class="bi-funnel me-1"></i> Filtrar
            </button>
            <a href="{{ route('ofertas.index') }}" class="btn btn-outline-secondary">
                <i class="bi-arrow-clockwise"></i>
            </a>
        </div>
    </form>
</div>

<!-- Lista de Productos -->
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="card-header-title">Productos ({{ $productos->total() }})</h4>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ofertaMasivaModal">
                    <i class="bi-lightning me-1"></i> Oferta Masiva
                </button>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        @if($productos->count() > 0)
            <div class="table-responsive">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Estado Oferta</th>
                            <th>Vigencia</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($producto->imagen_principal_url)
                                            <img src="{{ $producto->imagen_principal_url }}" alt="{{ $producto->nombre }}" class="product-image me-3">
                                        @else
                                            <div class="product-image me-3 bg-light d-flex align-items-center justify-content-center">
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
                                    @if($producto->es_oferta_activa)
                                        <div class="price-comparison">
                                            <span class="price-original">Q{{ number_format($producto->precio_venta, 2) }}</span>
                                            <span class="price-offer">Q{{ number_format($producto->precio_final, 2) }}</span>
                                        </div>
                                        <small class="text-success">{{ $producto->descuento_calculado }}% descuento</small>
                                    @else
                                        <span class="fw-bold">Q{{ number_format($producto->precio_venta, 2) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($producto->en_oferta)
                                        @if($producto->es_oferta_activa)
                                            <span class="badge bg-success">Activa</span>
                                        @elseif($producto->fecha_inicio_oferta && $producto->fecha_inicio_oferta > now())
                                            <span class="badge bg-info">Programada</span>
                                        @elseif($producto->fecha_fin_oferta && $producto->fecha_fin_oferta < now())
                                            <span class="badge bg-warning">Vencida</span>
                                        @else
                                            <span class="badge bg-primary">Configurada</span>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-dark">Sin oferta</span>
                                    @endif
                                </td>
                                <td>
                                    @if($producto->en_oferta)
                                        <div class="text-sm">
                                            @if($producto->fecha_inicio_oferta)
                                                <div>Inicio: {{ $producto->fecha_inicio_oferta->format('d/m/Y') }}</div>
                                            @endif
                                            @if($producto->fecha_fin_oferta)
                                                <div>Fin: {{ $producto->fecha_fin_oferta->format('d/m/Y') }}</div>
                                            @else
                                                <div class="text-muted">Sin fecha límite</div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        @if($producto->en_oferta)
                                            <a href="{{ route('ofertas.edit', $producto) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarOferta({{ $producto->id }})">
                                                <i class="bi-trash"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('ofertas.create', ['producto' => $producto->id]) }}" class="btn btn-outline-success btn-sm">
                                                <i class="bi-plus"></i> Oferta
                                            </a>
                                        @endif
                                        <a href="{{ route('ofertas.show', $producto) }}" class="btn btn-outline-info btn-sm">
                                            <i class="bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $productos->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi-search display-4 text-muted mb-3"></i>
                <h5>No se encontraron productos</h5>
                <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal Oferta Masiva -->
<div class="modal fade" id="ofertaMasivaModal" tabindex="-1" aria-labelledby="ofertaMasivaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('ofertas.masiva') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="ofertaMasivaModalLabel">Aplicar Oferta Masiva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select name="categoria" class="form-select" required>
                            <option value="">Selecciona una categoría</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria }}">{{ $categoria }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tipo de Descuento</label>
                        <select name="tipo_descuento" class="form-select" required onchange="toggleDescuentoFields(this)">
                            <option value="porcentaje">Porcentaje</option>
                            <option value="precio_fijo">Precio Fijo</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="porcentaje_field">
                        <label class="form-label">Descuento (%)</label>
                        <input type="number" name="descuento_porcentaje" class="form-control" min="1" max="90" step="0.01">
                    </div>
                    
                    <div class="mb-3" id="precio_field" style="display: none;">
                        <label class="form-label">Precio de Oferta</label>
                        <input type="number" name="precio_oferta" class="form-control" min="0" step="0.01">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Fecha Inicio (Opcional)</label>
                            <input type="date" name="fecha_inicio_oferta" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha Fin (Opcional)</label>
                            <input type="date" name="fecha_fin_oferta" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Aplicar Oferta</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleDescuentoFields(select) {
    const porcentajeField = document.getElementById('porcentaje_field');
    const precioField = document.getElementById('precio_field');
    
    if (select.value === 'porcentaje') {
        porcentajeField.style.display = 'block';
        precioField.style.display = 'none';
        porcentajeField.querySelector('input').required = true;
        precioField.querySelector('input').required = false;
    } else {
        porcentajeField.style.display = 'none';
        precioField.style.display = 'block';
        porcentajeField.querySelector('input').required = false;
        precioField.querySelector('input').required = true;
    }
}

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
                location.reload();
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
@endsection
