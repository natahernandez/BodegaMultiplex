@extends('layouts.app')

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
          <div class="col-sm-3">
            <div class="text-center">
              @if($producto->imagen)
                <img class="img-fluid rounded" src="{{ Storage::url($producto->imagen) }}" alt="{{ $producto->nombre }}" style="max-height: 200px;">
              @else
                <div class="avatar avatar-xxl avatar-soft-primary">
                  <span class="avatar-initials">{{ strtoupper(substr($producto->nombre, 0, 2)) }}</span>
                </div>
              @endif
            </div>
          </div>

          <div class="col-sm-9">
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
                  <dd class="col-sm-8">{{ $producto->marca ?? 'Sin marca' }}</dd>

                  <dt class="col-sm-4">Categoría:</dt>
                  <dd class="col-sm-8">
                    <span class="badge bg-soft-info text-info">{{ $producto->categoria }}</span>
                  </dd>
                </dl>
              </div>

              <div class="col-sm-6">
                <dl class="row">
                  <dt class="col-sm-5">Precio de Venta:</dt>
                  <dd class="col-sm-7">
                    <span class="text-success fw-semibold fs-4">${{ number_format($producto->precio_venta, 2) }}</span>
                  </dd>

                  <dt class="col-sm-5">Precio de Compra:</dt>
                  <dd class="col-sm-7">${{ number_format($producto->precio_compra, 2) }}</dd>

                  @if($producto->precio_mayoreo)
                  <dt class="col-sm-5">Precio Mayoreo:</dt>
                  <dd class="col-sm-7">${{ number_format($producto->precio_mayoreo, 2) }}</dd>
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

    <!-- Quick Actions Card -->
    <div class="card mt-3">
      <div class="card-header">
        <h4 class="card-header-title">Acciones Rápidas</h4>
      </div>
      <div class="card-body">
        <div class="list-group list-group-flush">
          <a class="list-group-item list-group-item-action" href="#">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0">
                <i class="bi-graph-up text-primary"></i>
              </div>
              <div class="flex-grow-1 ms-3">
                <div class="row align-items-center">
                  <div class="col">
                    <span class="d-block text-dark">Ver Historial de Ventas</span>
                    <span class="d-block text-body fs-6">Análisis de ventas del producto</span>
                  </div>
                  <div class="col-auto">
                    <i class="bi-chevron-right text-body"></i>
                  </div>
                </div>
              </div>
            </div>
          </a>

          <a class="list-group-item list-group-item-action" href="#">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0">
                <i class="bi-arrow-clockwise text-info"></i>
              </div>
              <div class="flex-grow-1 ms-3">
                <div class="row align-items-center">
                  <div class="col">
                    <span class="d-block text-dark">Historial de Stock</span>
                    <span class="d-block text-body fs-6">Movimientos de inventario</span>
                  </div>
                  <div class="col-auto">
                    <i class="bi-chevron-right text-body"></i>
                  </div>
                </div>
              </div>
            </div>
          </a>

          <a class="list-group-item list-group-item-action" href="#">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0">
                <i class="bi-printer text-secondary"></i>
              </div>
              <div class="flex-grow-1 ms-3">
                <div class="row align-items-center">
                  <div class="col">
                    <span class="d-block text-dark">Imprimir Etiqueta</span>
                    <span class="d-block text-body fs-6">Código de barras y precio</span>
                  </div>
                  <div class="col-auto">
                    <i class="bi-chevron-right text-body"></i>
                  </div>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
    <!-- End Quick Actions Card -->
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
        alert('Por favor ingrese el nuevo stock');
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
            location.reload(); // Reload the page to show updated data
        },
        error: function() {
            alert('Error al actualizar el stock');
        }
    });
}

// Toggle status function
function toggleStatus(newStatus) {
    const action = newStatus ? 'activar' : 'desactivar';
    if (confirm(`¿Está seguro de ${action} este producto?`)) {
        // Make AJAX call to update status
        $.ajax({
            url: `/productos/{{ $producto->id }}`,
            method: 'PUT',
            data: {
                activo: newStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert('Error al cambiar el estado del producto');
            }
        });
    }
}
</script>
@endsection 