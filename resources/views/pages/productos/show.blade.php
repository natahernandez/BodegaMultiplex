@extends('layouts.app')

@section('styles')
<style>
/* Improved image container for better horizontal/vertical adaptation */
.main-image-container {
  position: relative;
  overflow: hidden;
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  cursor: zoom-in;
  min-height: 300px;
  max-height: 500px;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f8f9fa;
}

.main-image {
  max-width: 100%;
  max-height: 100%;
  width: auto;
  height: auto;
  object-fit: contain;
  transition: transform 0.4s ease-in-out;
  transform-origin: center center;
}

.main-image-container:hover .main-image {
  transform: scale(1.15);
}

/* Improved thumbnails */
.miniatura {
  height: 80px !important;
  width: 100% !important;
  max-width: 120px !important;
  object-fit: cover !important;
  background: #f8f9fa !important;
  cursor: pointer !important;
  transition: all 0.3s ease !important;
  border-radius: 8px !important;
}

.miniatura:hover {
  opacity: 0.8 !important;
  transform: scale(1.05) !important;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
}

.miniatura-modal {
  height: 80px !important;
  width: 80px !important;
  object-fit: cover !important;
  background: #f8f9fa !important;
  cursor: pointer !important;
  transition: all 0.3s ease !important;
  border-radius: 8px !important;
}

.miniatura-modal:hover {
  opacity: 0.8 !important;
  transform: scale(1.05) !important;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
}

.no-image-placeholder {
  height: 400px;
  width: 100%;
  background: #f8f9fa;
  border-radius: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px dashed #dee2e6;
}

/* Modal image improvements */
#imagenModalAmpliada {
  max-width: 100%;
  max-height: 70vh;
  width: auto;
  height: auto;
  object-fit: contain;
}
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col-sm mb-2 mb-sm-0">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-no-gutter">
          <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('productos.index') }}">Productos</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ $producto->nombre }}</li>
        </ol>
      </nav>

      <h1 class="page-header-title">Detalles del Producto</h1>
    </div>
    
    <div class="col-sm-auto">
      <div class="btn-group" role="group">
        <a class="btn btn-primary" href="{{ route('productos.edit', $producto) }}">
          <i class="bi-pencil me-1"></i> Editar
        </a>
        <a class="btn btn-white" href="{{ route('productos.index') }}">
          <i class="bi-arrow-left me-1"></i> Volver
        </a>
      </div>
    </div>
  </div>
</div>
<!-- End Page Header -->

<div class="row">
  <div class="col-lg-8">
    <!-- Card -->
    <div class="card">
      <!-- Header -->
      <div class="card-header">
        <h4 class="card-header-title">Información del Producto</h4>
      </div>
      <!-- End Header -->

      <!-- Body -->
      <div class="card-body">
        <div class="row">
          <div class="col-sm-4">
            <!-- Galería de Imágenes -->
            @if($producto->imagenes->count() > 0)
              <div class="text-center">
                <!-- Imagen principal -->
                <div class="mb-3">
                  @if($producto->imagen_principal_url)
                    <div class="main-image-container" data-bs-toggle="modal" data-bs-target="#imageModal">
                      <img id="imagenPrincipal" class="main-image" 
                           src="{{ $producto->imagen_principal_url }}" 
                           alt="{{ $producto->nombre }}">
                    </div>
                  @else
                    <div class="no-image-placeholder">
                      <div class="text-center">
                        <i class="bi-image text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">Sin imagen</p>
                      </div>
                    </div>
                  @endif
                </div>
                
                <!-- Miniaturas -->
                @if($producto->imagenes->count() > 1)
                <div class="row g-2">
                  @foreach($producto->imagenes as $index => $imagen)
                    <div class="col-3">
                      <img class="miniatura border {{ $imagen->es_principal ? 'border-primary' : '' }}" 
                           src="{{ \App\Helpers\ImageHelper::getProductImageUrl($imagen->ruta_imagen) }}" 
                           alt="{{ $producto->nombre }} - Imagen {{ $index + 1 }}"
                           style="{{ $imagen->es_principal ? 'border-width: 2px !important;' : '' }}"
                           onclick="cambiarImagenPrincipal('{{ \App\Helpers\ImageHelper::getProductImageUrl($imagen->ruta_imagen) }}', this)">
                    </div>
                  @endforeach
                </div>
                @endif
              </div>
            @elseif($producto->imagen_principal_url)
              <div class="text-center">
                <div class="main-image-container">
                  <img class="main-image" src="{{ $producto->imagen_principal_url }}" alt="{{ $producto->nombre }}">
                </div>
              </div>
            @else
              <div class="text-center">
                <div class="no-image-placeholder">
                  <div class="text-center">
                    <i class="bi-image text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Sin imagen</p>
                  </div>
                </div>
              </div>
            @endif
          </div>

          <div class="col-sm-8">
            <div class="row">
              <div class="col-sm-6">
                <dl class="row">
                  <dt class="col-sm-4">Nombre:</dt>
                  <dd class="col-sm-8">{{ $producto->nombre }}</dd>

                  <dt class="col-sm-4">Código Interno:</dt>
                  <dd class="col-sm-8">
                    <span class="badge bg-soft-primary text-primary">{{ $producto->codigo_interno }}</span>
                  </dd>

                  @if($producto->codigo_barras)
                  <dt class="col-sm-4">Código de Barras:</dt>
                  <dd class="col-sm-8">{{ $producto->codigo_barras }}</dd>
                  @endif

                  <dt class="col-sm-4">Marca:</dt>
                  <dd class="col-sm-8">{{ optional($producto->brand)->nombre ?? ($producto->marca ?? 'Sin marca') }}</dd>

                  <dt class="col-sm-4">Categoría:</dt>
                  <dd class="col-sm-8">
                    <span class="badge bg-soft-info text-info">{{ optional($producto->category)->nombre ?? $producto->categoria }}</span>
                  </dd>
                </dl>
              </div>

              <div class="col-sm-6">
                <dl class="row">
                  <dt class="col-sm-5">Precio de Venta:</dt>
                  <dd class="col-sm-7">
                    <span class="text-success fw-semibold fs-4">Q{{ number_format($producto->precio_venta, 2) }}</span>
                  </dd>

                  <dt class="col-sm-5">Precio de Compra:</dt>
                                      <dd class="col-sm-7">Q{{ number_format($producto->precio_compra, 2) }}</dd>

                  @if($producto->precio_mayoreo)
                  <dt class="col-sm-5">Precio Mayoreo:</dt>
                                      <dd class="col-sm-7">Q{{ number_format($producto->precio_mayoreo, 2) }}</dd>
                  @endif

                  <dt class="col-sm-5">Margen:</dt>
                  <dd class="col-sm-7">
                    <span class="badge bg-soft-success text-success">{{ number_format($producto->margen_ganancia, 1) }}%</span>
                  </dd>

                  <dt class="col-sm-5">Unidad:</dt>
                  <dd class="col-sm-7">{{ $producto->unidad_medida }}</dd>
                </dl>
              </div>
            </div>

            @if($producto->descripcion)
            <div class="mt-3">
              <h6>Descripción:</h6>
              <p class="text-body">{{ $producto->descripcion }}</p>
            </div>
            @endif
          </div>
        </div>
      </div>
      <!-- End Body -->
    </div>
    <!-- End Card -->

    <!-- Additional Info Card -->
    <div class="card mt-3">
      <div class="card-header">
        <h4 class="card-header-title">Información Adicional</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-sm-6">
            <dl class="row">
              @if($producto->proveedor)
              <dt class="col-sm-4">Proveedor:</dt>
              <dd class="col-sm-8">{{ $producto->proveedor }}</dd>
              @endif

              @if($producto->ubicacion)
              <dt class="col-sm-4">Ubicación:</dt>
              <dd class="col-sm-8">{{ $producto->ubicacion }}</dd>
              @endif

              @if($producto->fecha_vencimiento)
              <dt class="col-sm-4">Vencimiento:</dt>
              <dd class="col-sm-8">
                <span class="badge bg-soft-warning text-warning">
                  {{ $producto->fecha_vencimiento->format('d/m/Y') }}
                </span>
              </dd>
              @endif
            </dl>
          </div>

          <div class="col-sm-6">
            <dl class="row">
              <dt class="col-sm-4">Estado:</dt>
              <dd class="col-sm-8">
                @if($producto->activo)
                  <span class="badge bg-soft-success text-success">Activo</span>
                @else
                  <span class="badge bg-soft-danger text-danger">Inactivo</span>
                @endif
              </dd>

              @if($producto->requiere_receta)
              <dt class="col-sm-4">Receta:</dt>
              <dd class="col-sm-8">
                <span class="badge bg-soft-warning text-warning">Requiere Receta</span>
              </dd>
              @endif

              @if($producto->iva > 0)
              <dt class="col-sm-4">IVA:</dt>
              <dd class="col-sm-8">{{ $producto->iva }}%</dd>
              @endif

              <dt class="col-sm-4">Creado:</dt>
              <dd class="col-sm-8">{{ $producto->created_at->format('d/m/Y H:i') }}</dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
    <!-- End Additional Info Card -->
  </div>

  <div class="col-lg-4">
    <!-- Stock Card -->
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-header-title">Control de Stock</h4>
        <span class="badge bg-soft-{{ $producto->estado_stock_color }} text-{{ $producto->estado_stock_color }}">
          {{ $producto->estado_stock_texto }}
        </span>
      </div>

      <div class="card-body">
        <div class="text-center mb-4">
          <div class="display-4 text-{{ $producto->estado_stock_color }}">
            {{ $producto->stock_actual }}
            <small class="fs-5 text-body">{{ $producto->unidad_medida }}</small>
          </div>
          <p class="text-body mb-0">Stock Actual</p>
        </div>

        <div class="row text-center">
          <div class="col-6">
            <div class="border-end">
              <div class="h4 mb-0">{{ $producto->stock_minimo }}</div>
              <span class="d-block text-body">Mínimo</span>
            </div>
          </div>
          <div class="col-6">
            <div class="h4 mb-0">{{ $producto->stock_maximo ?? 'N/A' }}</div>
            <span class="d-block text-body">Máximo</span>
          </div>
        </div>

        <hr>

        <div class="d-grid gap-2">
          <button type="button" class="btn btn-primary" onclick="updateStock()">
            <i class="bi-arrow-up-circle me-1"></i> Actualizar Stock
          </button>
          
          @if($producto->activo)
            <button type="button" class="btn btn-soft-danger" onclick="toggleStatus(false)">
              <i class="bi-eye-slash me-1"></i> Desactivar Producto
            </button>
          @else
            <button type="button" class="btn btn-soft-success" onclick="toggleStatus(true)">
              <i class="bi-eye me-1"></i> Activar Producto
            </button>
          @endif
        </div>
      </div>
    </div>
    <!-- End Stock Card -->
  </div>
</div>

<!-- Modal para actualizar stock -->
<div class="modal fade" id="updateStockModal" tabindex="-1" aria-labelledby="updateStockModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateStockModalLabel">Actualizar Stock - {{ $producto->nombre }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="updateStockForm">
          <div class="mb-3">
            <label for="stock_actual" class="form-label">Nuevo Stock</label>
            <div class="input-group">
              <input type="number" class="form-control" id="stock_actual" value="{{ $producto->stock_actual }}" min="0" required>
              <span class="input-group-text">{{ $producto->unidad_medida }}</span>
            </div>
            <div class="form-text">Stock actual: {{ $producto->stock_actual }} {{ $producto->unidad_medida }}</div>
          </div>
          <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones (Opcional)</label>
            <textarea class="form-control" id="observaciones" rows="3" placeholder="Motivo del cambio de stock..."></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-white" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="saveStock()">Actualizar Stock</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para ampliar imágenes -->
@if($producto->imagenes->count() > 0)
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">{{ $producto->nombre }} - Galería</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <div class="d-flex justify-content-center align-items-center" style="min-height: 400px;">
          <img id="imagenModalAmpliada" src="{{ $producto->imagen_principal_url }}" alt="{{ $producto->nombre }}">
        </div>
        
        @if($producto->imagenes->count() > 1)
        <div class="mt-3">
          <div class="row g-2 justify-content-center">
            @foreach($producto->imagenes as $index => $imagen)
              <div class="col-auto">
                <img class="miniatura-modal" 
                     src="{{ \App\Helpers\ImageHelper::getProductImageUrl($imagen->ruta_imagen) }}" 
                     alt="{{ $producto->nombre }} - Imagen {{ $index + 1 }}"
                     onclick="cambiarImagenModal('{{ \App\Helpers\ImageHelper::getProductImageUrl($imagen->ruta_imagen) }}')">
              </div>
            @endforeach
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endif

@endsection

@section('scripts')
<script>
// Update stock function
function updateStock() {
    $('#updateStockModal').modal('show');
}

function saveStock() {
    const stock = $('#stock_actual').val();
    const observaciones = $('#observaciones').val();
    
    if (!stock) {
        if (window.Swal) Swal.fire({ icon: 'warning', title: 'Ingrese el nuevo stock' }); else alert('Por favor ingrese el nuevo stock');
        return;
    }
    
    // Make AJAX call to update stock
    $.ajax({
        url: `/productos/{{ $producto->id }}/stock`,
        method: 'PUT',
        data: {
            stock_actual: stock,
            observaciones: observaciones,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            $('#updateStockModal').modal('hide');
            if (window.Swal) { Swal.fire({ icon: 'success', title: 'Stock actualizado', timer: 1000, showConfirmButton: false }).then(()=>location.reload()); }
            else { location.reload(); }
        },
        error: function() {
            if (window.Swal) Swal.fire({ icon: 'error', title: 'Error al actualizar el stock' }); else alert('Error al actualizar el stock');
        }
    });
}

// Toggle status function
function toggleStatus(newStatus) {
    const action = newStatus ? 'activar' : 'desactivar';
    const exec = () => $.ajax({
            url: `/productos/{{ $producto->id }}`,
            method: 'PUT',
            data: {
                activo: newStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (window.Swal) { Swal.fire({ icon: 'success', title: `Producto ${newStatus ? 'activado' : 'desactivado'}`, timer: 1000, showConfirmButton: false }).then(()=>location.reload()); }
                else { location.reload(); }
            },
            error: function() {
                if (window.Swal) Swal.fire({ icon: 'error', title: 'Error al cambiar el estado' }); else alert('Error al cambiar el estado del producto');
            }
        });

    if (window.Swal) {
      Swal.fire({ icon: 'question', title: `¿Está seguro de ${action} este producto?`, showCancelButton: true, confirmButtonText: 'Sí, continuar', cancelButtonText: 'Cancelar' }).then(r=>{ if (r.isConfirmed) exec(); });
    } else {
      if (confirm(`¿Está seguro de ${action} este producto?`)) exec();
    }
}

// Funciones para galería de imágenes
function cambiarImagenPrincipal(nuevaImagenUrl, elemento) {
    document.getElementById('imagenPrincipal').src = nuevaImagenUrl;
    const modalImage = document.getElementById('imagenModalAmpliada');
    if (modalImage) {
        modalImage.src = nuevaImagenUrl;
    }
    
    // Remover bordes de otras miniaturas y agregar al elemento actual
    document.querySelectorAll('.miniatura').forEach(img => {
        img.classList.remove('border-primary');
        img.style.borderWidth = '';
    });
    
    if (elemento) {
        elemento.classList.add('border-primary');
        elemento.style.borderWidth = '2px';
    }
}

function cambiarImagenModal(nuevaImagenUrl) {
    document.getElementById('imagenModalAmpliada').src = nuevaImagenUrl;
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Hover effects are now handled by CSS
});
</script>
@endsection 