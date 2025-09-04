@extends('layouts.app')

@section('styles')
<style>
.product-card {
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 20px;
    background: #f8f9fa;
    margin-bottom: 20px;
}
.product-image {
    height: 120px;
    width: 120px;
    object-fit: cover;
    border-radius: 8px;
}
.preview-section {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
}
.price-comparison {
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 1.2em;
}
.price-original {
    text-decoration: line-through;
    color: #6c757d;
}
.price-offer {
    color: #dc3545;
    font-weight: bold;
}
.savings-badge {
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
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
                    <li class="breadcrumb-item active" aria-current="page">Nueva Oferta</li>
                </ol>
            </nav>
            <h1 class="page-header-title">Crear Oferta para Producto</h1>
            <p class="page-header-text">Configura una oferta especial para este producto</p>
        </div>
    </div>
</div>

<!-- Alertas de éxito/error -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi-exclamation-triangle me-2"></i>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('ofertas.store') }}" method="POST" onsubmit="return validateForm()">
    @csrf
    <input type="hidden" name="producto_id" value="{{ $producto->id }}">
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Información del Producto -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-header-title">Producto Seleccionado</h4>
                </div>
                <div class="card-body">
                    <div class="product-card">
                        <div class="d-flex align-items-center">
                            @if($producto->imagen_principal_url)
                                <img src="{{ $producto->imagen_principal_url }}" alt="{{ $producto->nombre }}" class="product-image me-4">
                            @else
                                <div class="product-image me-4 bg-light d-flex align-items-center justify-content-center">
                                    <i class="bi-image text-muted fs-1"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h5 class="mb-2">{{ $producto->nombre }}</h5>
                                <div class="mb-2">
                                    <span class="badge bg-secondary me-2">{{ $producto->categoria }}</span>
                                    @if($producto->brand)
                                        <span class="badge bg-info">{{ $producto->brand->nombre }}</span>
                                    @endif
                                </div>
                                <p class="text-muted mb-2">{{ $producto->descripcion }}</p>
                                <div class="d-flex align-items-center gap-3">
                                    <div>
                                        <small class="text-muted d-block">Código</small>
                                        <strong>{{ $producto->codigo_interno }}</strong>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Stock</small>
                                        <strong>{{ $producto->stock_actual }}</strong>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Precio Actual</small>
                                        <strong class="text-primary fs-5">Q{{ number_format($producto->precio_venta, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Configuración de Oferta -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-header-title">Configuración de Oferta</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Descuento</label>
                        <select name="tipo_descuento" class="form-select" required onchange="toggleDescuentoFields(this)">
                            <option value="porcentaje" {{ old('tipo_descuento') == 'porcentaje' ? 'selected' : '' }}>Porcentaje</option>
                            <option value="precio_fijo" {{ old('tipo_descuento') == 'precio_fijo' ? 'selected' : '' }}>Precio Fijo</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="porcentaje_field">
                        <label class="form-label">Descuento (%)</label>
                        <input type="number" name="descuento_porcentaje" class="form-control" min="1" max="90" step="0.01" value="{{ old('descuento_porcentaje') }}" onchange="updatePreview()">
                        @error('descuento_porcentaje')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3" id="precio_field" style="display: none;">
                        <label class="form-label">Precio de Oferta</label>
                        <input type="number" name="precio_oferta" class="form-control" min="0" step="0.01" value="{{ old('precio_oferta') }}" onchange="updatePreview()">
                        @error('precio_oferta')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <label class="form-label">Fecha de Inicio (Opcional)</label>
                        <input type="datetime-local" name="fecha_inicio_oferta" class="form-control" value="{{ old('fecha_inicio_oferta') }}">
                        <small class="text-muted">Si no se especifica, la oferta será activa inmediatamente</small>
                        @error('fecha_inicio_oferta')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Fecha de Fin (Opcional)</label>
                        <input type="datetime-local" name="fecha_fin_oferta" class="form-control" value="{{ old('fecha_fin_oferta') }}">
                        <small class="text-muted">Si no se especifica, la oferta no tendrá fecha límite</small>
                        @error('fecha_fin_oferta')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Vista Previa -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-header-title">Vista Previa</h4>
                </div>
                <div class="card-body">
                    <div id="offerPreview">
                        <p class="text-muted text-center">Configura el descuento para ver la vista previa</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Botones de Acción -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('ofertas.index') }}" class="btn btn-outline-secondary">
                    <i class="bi-arrow-left me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="bi-check-lg me-1"></i> Crear Oferta
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
const productPrice = {{ $producto->precio_venta }};
const productName = '{{ $producto->nombre }}';

// Toggle campos de descuento
function toggleDescuentoFields(select) {
    const porcentajeField = document.getElementById('porcentaje_field');
    const precioField = document.getElementById('precio_field');
    const porcentajeInput = porcentajeField.querySelector('input');
    const precioInput = precioField.querySelector('input');
    
    if (select.value === 'porcentaje') {
        porcentajeField.style.display = 'block';
        precioField.style.display = 'none';
        porcentajeInput.required = true;
        precioInput.required = false;
        precioInput.value = ''; // Limpiar valor del campo oculto
        precioInput.removeAttribute('required');
    } else {
        porcentajeField.style.display = 'none';
        precioField.style.display = 'block';
        porcentajeInput.required = false;
        precioInput.required = true;
        porcentajeInput.value = ''; // Limpiar valor del campo oculto
        porcentajeInput.removeAttribute('required');
    }
    
    updatePreview();
}

// Actualizar vista previa
function updatePreview() {
    const preview = document.getElementById('offerPreview');
    const tipoDescuento = document.querySelector('select[name="tipo_descuento"]').value;
    const descuentoPorcentaje = parseFloat(document.querySelector('input[name="descuento_porcentaje"]').value) || 0;
    const precioOferta = parseFloat(document.querySelector('input[name="precio_oferta"]').value) || 0;
    
    let precioFinal, ahorro, porcentajeAhorro;
    
    if (tipoDescuento === 'porcentaje' && descuentoPorcentaje > 0) {
        precioFinal = productPrice * (1 - descuentoPorcentaje / 100);
        ahorro = productPrice - precioFinal;
        porcentajeAhorro = descuentoPorcentaje;
    } else if (tipoDescuento === 'precio_fijo' && precioOferta > 0) {
        precioFinal = precioOferta;
        ahorro = productPrice - precioOferta;
        porcentajeAhorro = ((productPrice - precioOferta) / productPrice) * 100;
    } else {
        preview.innerHTML = '<p class="text-muted text-center">Configura el descuento para ver la vista previa</p>';
        return;
    }
    
    if (precioFinal >= productPrice) {
        preview.innerHTML = '<div class="alert alert-warning"><i class="bi-exclamation-triangle me-2"></i>El precio de oferta debe ser menor al precio original</div>';
        return;
    }
    
    preview.innerHTML = `
        <div class="text-center">
            <h5 class="mb-3">${productName}</h5>
            <div class="price-comparison justify-content-center mb-3">
                <span class="price-original">Q${productPrice.toFixed(2)}</span>
                <i class="bi-arrow-right text-muted"></i>
                <span class="price-offer">Q${precioFinal.toFixed(2)}</span>
            </div>
            <div class="savings-badge">
                Ahorras Q${ahorro.toFixed(2)} (${porcentajeAhorro.toFixed(1)}%)
            </div>
        </div>
    `;
}

// Validar formulario antes de enviar
function validateForm() {
    console.log('Validando formulario...');
    
    const submitBtn = document.getElementById('submitBtn');
    const tipoDescuento = document.querySelector('select[name="tipo_descuento"]').value;
    const descuentoPorcentaje = document.querySelector('input[name="descuento_porcentaje"]').value;
    const precioOferta = document.querySelector('input[name="precio_oferta"]').value;
    
    console.log('Tipo descuento:', tipoDescuento);
    console.log('Descuento porcentaje:', descuentoPorcentaje);
    console.log('Precio oferta:', precioOferta);
    
    if (tipoDescuento === 'porcentaje') {
        if (!descuentoPorcentaje || descuentoPorcentaje <= 0 || descuentoPorcentaje > 90) {
            alert('Por favor ingresa un porcentaje de descuento válido (1-90%).');
            return false;
        }
    }
    
    if (tipoDescuento === 'precio_fijo') {
        if (!precioOferta || precioOferta <= 0) {
            alert('Por favor ingresa un precio de oferta válido.');
            return false;
        }
        
        if (parseFloat(precioOferta) >= productPrice) {
            alert('El precio de oferta debe ser menor al precio original (Q' + productPrice.toFixed(2) + ').');
            return false;
        }
    }
    
    console.log('Formulario válido, enviando...');
    
    // Cambiar el botón para mostrar que se está procesando
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creando oferta...';
    
    return true;
}

// Limpiar campos antes de enviar
function cleanFormFields() {
    const tipoDescuento = document.querySelector('select[name="tipo_descuento"]').value;
    const porcentajeInput = document.querySelector('input[name="descuento_porcentaje"]');
    const precioInput = document.querySelector('input[name="precio_oferta"]');
    
    if (tipoDescuento === 'porcentaje') {
        precioInput.value = '';
        precioInput.removeAttribute('name');
    } else {
        porcentajeInput.value = '';
        porcentajeInput.removeAttribute('name');
    }
}

// Inicializar campos según el valor seleccionado
document.addEventListener('DOMContentLoaded', function() {
    const tipoDescuento = document.querySelector('select[name="tipo_descuento"]');
    toggleDescuentoFields(tipoDescuento);
    
    // Agregar evento al formulario para limpiar campos antes de enviar
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // No limpiar campos, solo asegurar que los valores estén correctos
        const tipoDescuentoVal = document.querySelector('select[name="tipo_descuento"]').value;
        const porcentajeInput = document.querySelector('input[name="descuento_porcentaje"]');
        const precioInput = document.querySelector('input[name="precio_oferta"]');
        
        if (tipoDescuentoVal === 'porcentaje') {
            precioInput.value = '';
        } else {
            porcentajeInput.value = '';
        }
    });
});
</script>
@endsection