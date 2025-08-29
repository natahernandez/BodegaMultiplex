@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-sm mb-2 mb-sm-0">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-no-gutter">
                    <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('ofertas.index') }}">Ofertas</a></li>
                    <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('ofertas.show', $oferta) }}">{{ $oferta->nombre }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar</li>
                </ol>
            </nav>
            <h1 class="page-header-title">Editar Oferta</h1>
            <p class="page-header-text">Modifica la configuración de la oferta para "{{ $oferta->nombre }}"</p>
        </div>
    </div>
</div>

<form action="{{ route('ofertas.update', $oferta) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Información del Producto -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-header-title">Producto</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            @if($oferta->imagen_principal_url)
                                <img src="{{ $oferta->imagen_principal_url }}" alt="{{ $oferta->nombre }}" class="img-fluid rounded">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 150px;">
                                    <i class="bi-image display-6 text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h4 class="mb-3">{{ $oferta->nombre }}</h4>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-2"><strong>Código:</strong> {{ $oferta->codigo_interno }}</p>
                                    <p class="mb-2"><strong>Categoría:</strong> <span class="badge bg-secondary">{{ $oferta->categoria }}</span></p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-2"><strong>Precio Original:</strong> <span class="h5 text-primary">Q{{ number_format($oferta->precio_venta, 2) }}</span></p>
                                    <p class="mb-2"><strong>Stock:</strong> {{ $oferta->stock_actual }} {{ $oferta->unidad_medida }}</p>
                                </div>
                            </div>
                            @if($oferta->descripcion)
                                <p class="text-muted small mb-0">{{ Str::limit($oferta->descripcion, 150) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Configuración Actual -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-header-title">Configuración Actual de la Oferta</h4>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 text-center">
                            <div class="border rounded p-3 bg-light">
                                <div class="text-muted small">Estado</div>
                                @if($oferta->es_oferta_activa)
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-warning">Inactiva</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="border rounded p-3">
                                <div class="text-muted small">Descuento Actual</div>
                                <div class="fw-bold text-primary">{{ $oferta->descuento_calculado }}%</div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="border rounded p-3">
                                <div class="text-muted small">Precio Actual</div>
                                <div class="fw-bold text-success">Q{{ number_format($oferta->precio_final, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="border rounded p-3">
                                <div class="text-muted small">Ahorro Actual</div>
                                <div class="fw-bold text-warning">Q{{ number_format($oferta->ahorro, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Nueva Configuración -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-header-title">Nueva Configuración</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Descuento</label>
                        <select name="tipo_descuento" class="form-select" required onchange="toggleDescuentoFields(this)">
                            <option value="porcentaje" {{ old('tipo_descuento', $oferta->descuento_porcentaje ? 'porcentaje' : 'precio_fijo') == 'porcentaje' ? 'selected' : '' }}>Porcentaje</option>
                            <option value="precio_fijo" {{ old('tipo_descuento', $oferta->descuento_porcentaje ? 'porcentaje' : 'precio_fijo') == 'precio_fijo' ? 'selected' : '' }}>Precio Fijo</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="porcentaje_field">
                        <label class="form-label">Descuento (%)</label>
                        <input type="number" name="descuento_porcentaje" class="form-control" min="1" max="90" step="0.01" 
                               value="{{ old('descuento_porcentaje', $oferta->descuento_porcentaje) }}" onchange="updatePreview()">
                        @error('descuento_porcentaje')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3" id="precio_field" style="display: none;">
                        <label class="form-label">Precio de Oferta</label>
                        <input type="number" name="precio_oferta" class="form-control" min="0" step="0.01" 
                               value="{{ old('precio_oferta', $oferta->precio_oferta) }}" onchange="updatePreview()">
                        @error('precio_oferta')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <label class="form-label">Fecha de Inicio</label>
                        <input type="datetime-local" name="fecha_inicio_oferta" class="form-control" 
                               value="{{ old('fecha_inicio_oferta', $oferta->fecha_inicio_oferta ? $oferta->fecha_inicio_oferta->format('Y-m-d\TH:i') : '') }}">
                        <small class="text-muted">Dejar vacío para activar inmediatamente</small>
                        @error('fecha_inicio_oferta')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Fecha de Fin</label>
                        <input type="datetime-local" name="fecha_fin_oferta" class="form-control" 
                               value="{{ old('fecha_fin_oferta', $oferta->fecha_fin_oferta ? $oferta->fecha_fin_oferta->format('Y-m-d\TH:i') : '') }}">
                        <small class="text-muted">Dejar vacío para sin límite de tiempo</small>
                        @error('fecha_fin_oferta')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Vista Previa -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-header-title">Vista Previa</h4>
                </div>
                <div class="card-body">
                    <div id="offerPreview">
                        <div class="text-center">
                            <div class="mb-3">
                                <span class="text-decoration-line-through text-muted">Q{{ number_format($oferta->precio_venta, 2) }}</span>
                                <div class="h4 text-success mb-0" id="previewPrice">Q{{ number_format($oferta->precio_final, 2) }}</div>
                            </div>
                            <div class="mb-2">
                                <span class="badge bg-primary" id="previewDiscount">{{ $oferta->descuento_calculado }}% OFF</span>
                            </div>
                            <div class="small text-muted">
                                Ahorras: <span class="text-success fw-bold" id="previewSavings">Q{{ number_format($oferta->ahorro, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Botones de Acción -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi-check-lg me-1"></i> Actualizar Oferta
                        </button>
                        <a href="{{ route('ofertas.show', $oferta) }}" class="btn btn-outline-secondary">
                            <i class="bi-arrow-left me-1"></i> Cancelar
                        </a>
                        <button type="button" class="btn btn-outline-danger" onclick="eliminarOferta({{ $oferta->id }})">
                            <i class="bi-trash me-1"></i> Eliminar Oferta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
const precioOriginal = {{ $oferta->precio_venta }};

// Toggle campos de descuento
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
    
    updatePreview();
}

// Actualizar vista previa
function updatePreview() {
    const tipoDescuento = document.querySelector('select[name="tipo_descuento"]').value;
    const descuentoPorcentaje = parseFloat(document.querySelector('input[name="descuento_porcentaje"]').value) || 0;
    const precioOferta = parseFloat(document.querySelector('input[name="precio_oferta"]').value) || 0;
    
    let precioFinal, ahorro, porcentajeDescuento;
    
    if (tipoDescuento === 'porcentaje' && descuentoPorcentaje > 0) {
        precioFinal = precioOriginal * (1 - descuentoPorcentaje / 100);
        ahorro = precioOriginal - precioFinal;
        porcentajeDescuento = descuentoPorcentaje;
    } else if (tipoDescuento === 'precio_fijo' && precioOferta > 0) {
        precioFinal = precioOferta;
        ahorro = precioOriginal - precioOferta;
        porcentajeDescuento = ((precioOriginal - precioOferta) / precioOriginal) * 100;
    } else {
        precioFinal = precioOriginal;
        ahorro = 0;
        porcentajeDescuento = 0;
    }
    
    // Actualizar elementos de vista previa
    document.getElementById('previewPrice').textContent = 'Q' + precioFinal.toFixed(2);
    document.getElementById('previewDiscount').textContent = porcentajeDescuento.toFixed(1) + '% OFF';
    document.getElementById('previewSavings').textContent = 'Q' + ahorro.toFixed(2);
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

// Inicializar campos según el valor seleccionado
document.addEventListener('DOMContentLoaded', function() {
    const tipoDescuento = document.querySelector('select[name="tipo_descuento"]');
    toggleDescuentoFields(tipoDescuento);
    updatePreview();
});
</script>
@endsection
