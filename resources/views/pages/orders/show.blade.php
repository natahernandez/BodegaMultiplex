@extends('layouts.app')

@section('content')
<style>
/* Estilos responsivos adicionales */
@media (max-width: 768px) {
  .page-header-title {
    font-size: 1.5rem;
  }
  
  .card-title {
    font-size: 1.1rem;
  }
  
  .table-responsive {
    font-size: 0.9rem;
  }
  
  .btn {
    font-size: 0.9rem;
  }
  
  .badge {
    font-size: 0.8rem;
  }
  
  /* Mejorar espaciado en móviles */
  .card-body {
    padding: 1rem;
  }
  
  .mb-4 {
    margin-bottom: 1rem !important;
  }
  
  /* Ajustar el header en móviles */
  .page-header .row {
    margin-bottom: 1rem;
  }
  
  .page-header .col-sm-auto {
    margin-top: 1rem;
  }
}

@media (max-width: 576px) {
  .page-header-title {
    font-size: 1.25rem;
  }
  
  .card-title {
    font-size: 1rem;
  }
  
  .btn {
    padding: 0.5rem 0.75rem;
    font-size: 0.85rem;
  }
  
  /* Hacer que los botones ocupen todo el ancho en móviles muy pequeños */
  .d-grid .btn {
    width: 100%;
  }
}
</style>
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center mb-3">
    <div class="col-sm">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-no-gutter">
          <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('orders.index') }}">Órdenes</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ $order->numero_orden }}</li>
        </ol>
      </nav>

      <h1 class="page-header-title">Orden {{ $order->numero_orden }}</h1>

      <div class="d-flex mt-2">
        @php
          $estadoBadge = match($order->estado) {
            'proceso' => 'bg-warning',
            'completado' => 'bg-success', 
            'cancelado' => 'bg-danger',
            default => 'bg-secondary'
          };
          $estadoTexto = match($order->estado) {
            'proceso' => 'En Proceso',
            'completado' => 'Completado',
            'cancelado' => 'Cancelado',
            default => ucfirst($order->estado)
          };
        @endphp
        <span class="badge {{ $estadoBadge }} text-white me-2">
          {{ $estadoTexto }}
        </span>
        <span class="text-muted me-3">{{ $order->created_at->format('d/m/Y H:i') }}</span>
      </div>
    </div>
    <!-- End Col -->

    <div class="col-sm-auto">
        <div class="d-flex flex-column flex-sm-row gap-2">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
          <i class="bi-arrow-left me-1"></i> Volver a Órdenes
        </a>
        
        @if($order->estado === 'completado')
          <a href="{{ route('orders.generate-pdf', $order) }}" class="btn btn-success" target="_blank">
            <i class="bi-file-earmark-pdf me-1"></i> 
            <span class="d-none d-sm-inline">
              @if($order->tipo_pago === 'linea')
                Generar Comprobante de Pago
              @else
                Generar Factura
              @endif
            </span>
            <span class="d-sm-none">
              @if($order->tipo_pago === 'linea')
                Comprobante
              @else
                Factura
              @endif
            </span>
          </a>
        @endif
      </div>
    </div>
  </div>
  <!-- End Row -->
</div>
<!-- End Page Header -->

<!-- Mensajes de éxito/error -->
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi-exclamation-triangle me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="row">
  <!-- Información de la Orden -->
  <div class="col-lg-8 order-lg-1 order-2">
    <!-- Datos del Cliente -->
    <div class="card mb-4">
      <div class="card-header">
        <h4 class="card-title">
          <i class="bi-person-lines-fill text-primary me-2"></i>
          Información del Cliente
        </h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Nombre Completo</label>
              <p class="fw-semibold mb-0">{{ $order->nombre_cliente }}</p>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted">Email</label>
              <p class="mb-0">{{ $order->email_cliente }}</p>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted">Teléfono</label>
              <p class="mb-0">{{ $order->telefono_cliente }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">DPI</label>
              <p class="mb-0">{{ $order->dpi }}</p>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted">NIT</label>
              <p class="mb-0">{{ $order->nit }}</p>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted">Dirección de Entrega</label>
              <p class="mb-0">{{ $order->direccion_entrega }}</p>
              <small class="text-muted">{{ $order->ciudad }}, {{ $order->departamento }}</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Productos Ordenados -->
    <div class="card mb-4">
      <div class="card-header">
        <h4 class="card-title">
          <i class="bi-box-seam text-primary me-2"></i>
          Productos Ordenados
        </h4>
      </div>
      <div class="card-body">
        <!-- Vista de escritorio -->
        <div class="d-none d-md-block">
          <div class="table-responsive">
            <table class="table table-borderless">
              <thead class="thead-light">
                <tr>
                  <th>Producto</th>
                  <th>Precio Unitario</th>
                  <th>Cantidad</th>
                  <th class="text-end">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @foreach($order->items as $item)
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        @php
                          $imagenUrl = null;
                          if ($item->producto) {
                            // Solo obtener la imagen principal
                            $imagenUrl = $item->producto->imagen_principal_url;
                          }
                        @endphp
                        
                        @if($imagenUrl)
                          <img src="{{ $imagenUrl }}" 
                               alt="{{ $item->nombre_producto }}" 
                               class="rounded border me-3"
                               style="width: 60px; height: 60px; object-fit: contain; background: #f8f9fa;">
                        @else
                          <!-- Sin imagen: mostrar iniciales del producto -->
                          <div class="rounded d-flex align-items-center justify-content-center fw-bold text-white me-3" 
                               style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 1.2rem;">
                            {{ strtoupper(substr($item->nombre_producto, 0, 2)) }}
                          </div>
                        @endif
                        <div>
                          <h6 class="mb-0">{{ $item->nombre_producto }}</h6>
                          <small class="text-muted">Código: {{ $item->codigo_producto }}</small>
                          @if($item->descripcion_producto)
                            <small class="text-muted d-block">{{ Str::limit($item->descripcion_producto, 50) }}</small>
                          @endif
                        </div>
                      </div>
                    </td>
                    <td>Q{{ number_format($item->precio_unitario, 2) }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td class="text-end fw-semibold">Q{{ number_format($item->subtotal, 2) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <!-- Vista móvil -->
        <div class="d-md-none">
          @foreach($order->items as $item)
            <div class="card mb-3 border">
              <div class="card-body p-3">
                <div class="d-flex align-items-start">
                  @php
                    $imagenUrl = null;
                    if ($item->producto) {
                      $imagenUrl = $item->producto->imagen_principal_url;
                    }
                  @endphp
                  
                  @if($imagenUrl)
                    <img src="{{ $imagenUrl }}" 
                         alt="{{ $item->nombre_producto }}" 
                         class="rounded border me-3"
                         style="width: 50px; height: 50px; object-fit: contain; background: #f8f9fa;">
                  @else
                    <div class="rounded d-flex align-items-center justify-content-center fw-bold text-white me-3" 
                         style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 1rem;">
                      {{ strtoupper(substr($item->nombre_producto, 0, 2)) }}
                    </div>
                  @endif
                  
                  <div class="flex-grow-1">
                    <h6 class="mb-1 fw-semibold">{{ $item->nombre_producto }}</h6>
                    <small class="text-muted d-block">Código: {{ $item->codigo_producto }}</small>
                    @if($item->descripcion_producto)
                      <small class="text-muted d-block">{{ Str::limit($item->descripcion_producto, 40) }}</small>
                    @endif
                    
                    <div class="row mt-2">
                      <div class="col-6">
                        <small class="text-muted">Precio:</small>
                        <div class="fw-semibold">Q{{ number_format($item->precio_unitario, 2) }}</div>
                      </div>
                      <div class="col-3">
                        <small class="text-muted">Cantidad:</small>
                        <div class="fw-semibold">{{ $item->cantidad }}</div>
                      </div>
                      <div class="col-3 text-end">
                        <small class="text-muted">Subtotal:</small>
                        <div class="fw-bold text-primary">Q{{ number_format($item->subtotal, 2) }}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <!-- Totales -->
        <div class="row justify-content-end">
          <div class="col-md-4 col-12">
            <div class="border-top pt-3">
              <div class="d-flex justify-content-between mb-2">
                <span>Subtotal:</span>
                <span>Q{{ number_format($order->subtotal, 2) }}</span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span>Envío:</span>
                <span>Q{{ number_format($order->envio, 2) }}</span>
              </div>
              <div class="d-flex justify-content-between fw-bold border-top pt-2">
                <span>Total:</span>
                <span class="text-primary">Q{{ number_format($order->total, 2) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Notas -->
    @if($order->notas_cliente)
      <div class="card mb-4">
        <div class="card-header">
          <h4 class="card-title">
            <i class="bi-chat-text text-primary me-2"></i>
            Notas del Cliente
          </h4>
        </div>
        <div class="card-body">
          <p class="mb-0">{{ $order->notas_cliente }}</p>
        </div>
      </div>
    @endif
  </div>

  <!-- Panel de Estado y Acciones -->
  <div class="col-lg-4 order-lg-2 order-1">
    <!-- Estado Actual -->
    <div class="card mb-4">
      <div class="card-header">
        <h4 class="card-title">
          <i class="bi-gear text-primary me-2"></i>
          Gestión de Estado
        </h4>
      </div>
      <div class="card-body">
        <div class="mb-4">
          <label class="form-label">Estado Actual</label>
          <div>
            <span class="badge {{ $estadoBadge }} fs-6 px-3 py-2">
              {{ $estadoTexto }}
            </span>
          </div>
        </div>

        <!-- Cambiar Estado -->
        @if($order->estado !== 'completado' && $order->estado !== 'cancelado')
          <form action="{{ route('orders.updateStatus', $order) }}" method="POST" id="statusForm">
            @csrf
            @method('PUT')
            
            <div class="d-grid gap-2">
              <button type="button" class="btn btn-success" onclick="showCompleteModal()">
                <i class="bi-check-circle me-2"></i>
                <span class="d-none d-sm-inline">Marcar como Completado</span>
                <span class="d-sm-none">Completar</span>
              </button>
              
              <button type="button" class="btn btn-danger" onclick="cancelOrder()">
                <i class="bi-x-circle me-2"></i>
                <span class="d-none d-sm-inline">Cancelar Orden</span>
                <span class="d-sm-none">Cancelar</span>
              </button>
            </div>
            
            <input type="hidden" name="estado" id="estadoInput">
          </form>
        @endif

        <!-- Información de Pago -->
        <div class="mt-4">
          <h6 class="text-muted mb-3">Información de Pago</h6>
          <div class="mb-2">
            <strong>Método:</strong> 
            @if($order->tipo_pago === 'linea')
              <i class="bi-credit-card me-1"></i>Pago en Línea
            @else
              <i class="bi-cash me-1"></i>Contra Entrega
            @endif
          </div>
          <div class="mb-2">
            <strong>Estado de Pago:</strong>
            <span class="badge {{ $order->estado_pago === 'pagado' ? 'bg-success' : 'bg-warning' }}">
              {{ ucfirst(str_replace('_', ' ', $order->estado_pago)) }}
            </span>
          </div>
        </div>

        <!-- Información de Envío -->
        @if($order->guia_envio || $order->empresa_envio)
          <div class="mt-4">
            <h6 class="text-muted mb-3">Información de Envío</h6>
            @if($order->empresa_envio)
              <div class="mb-2">
                <strong>Empresa:</strong> {{ $order->empresa_envio }}
              </div>
            @endif
            @if($order->guia_envio)
              <div class="mb-2">
                <strong>Guía:</strong> {{ $order->guia_envio }}
              </div>
            @endif
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Modal para Completar Orden -->
<div class="modal fade" id="completeModal" tabindex="-1" aria-labelledby="completeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="completeModalLabel">
          <i class="bi-check-circle text-success me-2"></i>
          Completar Orden
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('orders.complete', $order) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="alert alert-info">
            <i class="bi-info-circle me-2"></i>
            Al completar la orden, se actualizará automáticamente el stock de los productos.
          </div>
          
          <div class="mb-3">
            <label for="empresa_envio" class="form-label">Empresa de Envío *</label>
            <input type="text" class="form-control" id="empresa_envio" name="empresa_envio" 
                   placeholder="Ej: Cargo Express, Guate Envíos, etc." required>
          </div>
          
          <div class="mb-3">
            <label for="guia_envio" class="form-label">Guía de Envío *</label>
            <input type="text" class="form-control" id="guia_envio" name="guia_envio" 
                   placeholder="Ej: GE123456789GT" required>
            <div class="form-text">Código de seguimiento proporcionado por la empresa de envío</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">
            <i class="bi-check-circle me-2"></i>Completar Orden
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function changeStatus(estado) {
  if (confirm('¿Estás seguro de cambiar el estado a ' + estado + '?')) {
    document.getElementById('estadoInput').value = estado;
    document.getElementById('statusForm').submit();
  }
}

function showCompleteModal() {
  var modal = new bootstrap.Modal(document.getElementById('completeModal'));
  modal.show();
}

function cancelOrder() {
  // Mostrar modal de confirmación personalizado
  const confirmModal = `
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="cancelOrderModalLabel">
              <i class="bi-exclamation-triangle me-2"></i>
              Confirmar Cancelación
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-warning">
              <i class="bi-info-circle me-2"></i>
              <strong>¿Estás seguro de que deseas cancelar esta orden?</strong>
            </div>
            <p class="mb-2">Al cancelar la orden:</p>
            <ul class="mb-0">
              <li>Se restaurará el stock de los productos automáticamente</li>
              <li>El estado de pago se marcará como "cancelado"</li>
              <li>Esta acción no se puede deshacer</li>
              <li>El cliente será notificado del cambio de estado</li>
            </ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="bi-arrow-left me-2"></i>No, mantener orden
            </button>
            <button type="button" class="btn btn-danger" onclick="confirmCancelOrder()">
              <i class="bi-x-circle me-2"></i>Sí, cancelar orden
            </button>
          </div>
        </div>
      </div>
    </div>
  `;
  
  // Agregar el modal al DOM si no existe
  if (!document.getElementById('cancelOrderModal')) {
    document.body.insertAdjacentHTML('beforeend', confirmModal);
  }
  
  // Mostrar el modal
  const modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
  modal.show();
}

function confirmCancelOrder() {
  // Cerrar el modal de confirmación
  const modal = bootstrap.Modal.getInstance(document.getElementById('cancelOrderModal'));
  modal.hide();
  
  // Establecer el estado como cancelado y enviar el formulario
  document.getElementById('estadoInput').value = 'cancelado';
  document.getElementById('statusForm').submit();
}
</script>

@endsection 