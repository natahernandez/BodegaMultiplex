@extends('layouts.app')

@section('styles')
<style>
.product-selector {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
}
.product-item {
    padding: 10px;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.product-item:hover {
    background-color: #f8f9fa;
}
.product-item.selected {
    background-color: #e3f2fd;
    border-color: #2196f3;
}
.preview-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
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
            <h1 class="page-header-title">Nueva Oferta</h1>
            <p class="page-header-text">Crea ofertas especiales para tus productos</p>
        </div>
    </div>
</div>

<form action="{{ route('ofertas.store') }}" method="POST">
    @csrf
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Selección de Productos -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-header-title">Seleccionar Productos</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" id="searchProducts" class="form-control" placeholder="Buscar productos...">
                    </div>
                    
                    <div class="product-selector" id="productSelector">
                        @foreach($productos as $producto)
                            <div class="product-item" data-id="{{ $producto->id }}" data-name="{{ strtolower($producto->nombre) }}" data-category="{{ strtolower($producto->categoria) }}">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="productos[]" value="{{ $producto->id }}" id="producto_{{ $producto->id }}">
                                    <label class="form-check-label" for="producto_{{ $producto->id }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $producto->nombre }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $producto->categoria }} - Q{{ number_format($producto->precio_venta, 2) }}</small>
                                            </div>
                                            <span class="badge bg-primary">Q{{ number_format($producto->precio_venta, 2) }}</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @error('productos')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
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
                        <p class="text-muted text-center">Selecciona productos y configura el descuento para ver la vista previa</p>
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
                <button type="submit" class="btn btn-primary">
                    <i class="bi-check-lg me-1"></i> Crear Oferta
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
// Productos seleccionados
let selectedProducts = [];

// Búsqueda de productos
document.getElementById('searchProducts').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const productItems = document.querySelectorAll('.product-item');
    
    productItems.forEach(item => {
        const name = item.dataset.name;
        const category = item.dataset.category;
        
        if (name.includes(searchTerm) || category.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

// Manejo de selección de productos
document.querySelectorAll('.product-item').forEach(item => {
    const checkbox = item.querySelector('input[type="checkbox"]');
    
    item.addEventListener('click', function(e) {
        if (e.target.type !== 'checkbox') {
            checkbox.checked = !checkbox.checked;
        }
        
        if (checkbox.checked) {
            item.classList.add('selected');
            selectedProducts.push({
                id: item.dataset.id,
                name: checkbox.nextElementSibling.querySelector('strong').textContent,
                price: parseFloat(checkbox.nextElementSibling.querySelector('.badge').textContent.replace('Q', '').replace(',', ''))
            });
        } else {
            item.classList.remove('selected');
            selectedProducts = selectedProducts.filter(p => p.id !== item.dataset.id);
        }
        
        updatePreview();
    });
    
    checkbox.addEventListener('change', function() {
        if (this.checked) {
            item.classList.add('selected');
        } else {
            item.classList.remove('selected');
        }
    });
});

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
    const preview = document.getElementById('offerPreview');
    const tipoDescuento = document.querySelector('select[name="tipo_descuento"]').value;
    const descuentoPorcentaje = parseFloat(document.querySelector('input[name="descuento_porcentaje"]').value) || 0;
    const precioOferta = parseFloat(document.querySelector('input[name="precio_oferta"]').value) || 0;
    
    if (selectedProducts.length === 0) {
        preview.innerHTML = '<p class="text-muted text-center">Selecciona productos para ver la vista previa</p>';
        return;
    }
    
    let html = '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>Producto</th><th>Precio Original</th><th>Precio Oferta</th><th>Ahorro</th></tr></thead><tbody>';
    
    selectedProducts.forEach(product => {
        let precioFinal, ahorro, porcentajeAhorro;
        
        if (tipoDescuento === 'porcentaje' && descuentoPorcentaje > 0) {
            precioFinal = product.price * (1 - descuentoPorcentaje / 100);
            ahorro = product.price - precioFinal;
            porcentajeAhorro = descuentoPorcentaje;
        } else if (tipoDescuento === 'precio_fijo' && precioOferta > 0) {
            precioFinal = precioOferta;
            ahorro = product.price - precioOferta;
            porcentajeAhorro = ((product.price - precioOferta) / product.price) * 100;
        } else {
            precioFinal = product.price;
            ahorro = 0;
            porcentajeAhorro = 0;
        }
        
        html += `
            <tr>
                <td><strong>${product.name}</strong></td>
                <td>Q${product.price.toFixed(2)}</td>
                <td class="text-success">Q${precioFinal.toFixed(2)}</td>
                <td class="text-primary">Q${ahorro.toFixed(2)} (${porcentajeAhorro.toFixed(1)}%)</td>
            </tr>
        `;
    });
    
    html += '</tbody></table></div>';
    
    const totalAhorro = selectedProducts.reduce((sum, product) => {
        if (tipoDescuento === 'porcentaje' && descuentoPorcentaje > 0) {
            return sum + (product.price * descuentoPorcentaje / 100);
        } else if (tipoDescuento === 'precio_fijo' && precioOferta > 0) {
            return sum + (product.price - precioOferta);
        }
        return sum;
    }, 0);
    
    html += `<div class="text-center mt-3"><strong>Ahorro Total Estimado: <span class="text-success">Q${totalAhorro.toFixed(2)}</span></strong></div>`;
    
    preview.innerHTML = html;
}

// Inicializar campos según el valor seleccionado
document.addEventListener('DOMContentLoaded', function() {
    const tipoDescuento = document.querySelector('select[name="tipo_descuento"]');
    toggleDescuentoFields(tipoDescuento);
});
</script>
@endsection
