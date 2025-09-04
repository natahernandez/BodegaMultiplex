@extends('layouts.shop')

@section('title', 'Detalle del Pedido - ' . $order->numero_orden)

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center mb-3">
    <div class="col-sm">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-no-gutter">
          <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('user.orders.index') }}">Mis Pedidos</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ $order->numero_orden }}</li>
        </ol>
      </nav>
      <h1 class="page-header-title">Detalle del Pedido</h1>
      <p class="page-header-text">{{ $order->numero_orden }}</p>
    </div>
    <div class="col-sm-auto">
      <span class="badge {{ $order->estado_badge }} text-white fs-6 px-3 py-2">
        {{ $order->estado_texto }}
      </span>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-8">
    <!-- Timeline Card -->
    <div class="card mb-4">
      <div class="card-header">
        <h4 class="card-title">
          <i class="bi-clock-history me-2"></i>Seguimiento del Pedido
        </h4>
      </div>
      <div class="card-body">
        <!-- Timeline -->
        <div class="timeline">
          @foreach($timeline as $index => $step)
          <div class="timeline-item {{ $step['completed'] ? 'timeline-item-success' : 'timeline-item-pending' }} {{ isset($step['error']) && $step['error'] ? 'timeline-item-danger' : '' }}">
            <div class="timeline-marker">
              <div class="timeline-marker-node {{ $step['completed'] ? 'timeline-marker-node-success' : 'timeline-marker-node-pending' }} {{ isset($step['error']) && $step['error'] ? 'timeline-marker-node-danger' : '' }}">
                <i class="{{ $step['icon'] }}"></i>
              </div>
            </div>
            <div class="timeline-content">
              <div class="timeline-content-header">
                <h5 class="timeline-content-title mb-1">{{ $step['title'] }}</h5>
                <small class="text-muted">{{ $step['date']->format('M d, Y, H:i') }}</small>
              </div>
              <p class="timeline-content-text mb-2">{{ $step['description'] }}</p>
              
              @if(isset($step['tracking']) && $step['tracking'])
              <div class="alert alert-soft-info">
                <div class="row align-items-center">
                  <div class="col">
                    <h6 class="alert-heading mb-1">Información de Envío</h6>
                    <p class="mb-0">
                      <strong>Empresa:</strong> {{ $step['empresa'] ?? 'No especificada' }}<br>
                      <strong>Código de Seguimiento:</strong> {{ $step['tracking'] }}<br>
                      @if(isset($step['guia']) && $step['guia'])
                      <strong>Guía de Envío:</strong> {{ $step['guia'] }}
                      @endif
                    </p>
                  </div>
                  <div class="col-auto">
                    <i class="bi-truck fs-2 text-info"></i>
                  </div>
                </div>
              </div>
              @endif
            </div>
          </div>
          @endforeach
        </div>
        <!-- End Timeline -->
      </div>
    </div>

    <!-- Order Items Card -->
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">
          <i class="bi-box-seam me-2"></i>Productos del Pedido
        </h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle">
            <thead class="thead-light">
              <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order->items as $item)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                      @if($item->producto && $item->producto->imagen_principal_url)
                        <div class="avatar">
                          <img class="avatar-img" src="{{ $item->producto->imagen_principal_url }}" alt="{{ $item->nombre_producto }}" style="object-fit: contain;">
                        </div>
                      @else
                        <div class="avatar avatar-soft-primary">
                          <span class="avatar-initials">{{ strtoupper(substr($item->nombre_producto, 0, 2)) }}</span>
                        </div>
                      @endif
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <h6 class="text-inherit mb-0">{{ $item->nombre_producto }}</h6>
                      <small class="text-muted">{{ $item->codigo_producto }}</small>
                    </div>
                  </div>
                </td>
                <td>Q{{ number_format($item->precio_unitario, 2) }}</td>
                <td>{{ $item->cantidad }}</td>
                <td>Q{{ number_format($item->subtotal, 2) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <!-- Order Summary -->
    <div class="card mb-4">
      <div class="card-header">
        <h4 class="card-title">
          <i class="bi-receipt me-2"></i>Resumen del Pedido
        </h4>
      </div>
      <div class="card-body">
        <dl class="row">
          <dt class="col-sm-6">Subtotal:</dt>
          <dd class="col-sm-6 text-end">Q{{ number_format($order->subtotal, 2) }}</dd>

          

          @if($order->envio > 0)
          <dt class="col-sm-6">Envío:</dt>
          <dd class="col-sm-6 text-end">Q{{ number_format($order->envio, 2) }}</dd>
          @endif

          @if($order->descuento > 0)
          <dt class="col-sm-6">Descuento:</dt>
          <dd class="col-sm-6 text-end text-success">-Q{{ number_format($order->descuento, 2) }}</dd>
          @endif
        </dl>
        <hr>
        <dl class="row">
          <dt class="col-sm-6 fs-5">Total:</dt>
          <dd class="col-sm-6 text-end fs-5 fw-bold">Q{{ number_format($order->total, 2) }}</dd>
        </dl>
      </div>
    </div>

    <!-- Customer Info -->
    <div class="card mb-4">
      <div class="card-header">
        <h4 class="card-title">
          <i class="bi-person me-2"></i>Información del Cliente
        </h4>
      </div>
      <div class="card-body">
        <dl class="row">
          <dt class="col-sm-5">Nombre:</dt>
          <dd class="col-sm-7">{{ $order->nombre_cliente }}</dd>

          <dt class="col-sm-5">Email:</dt>
          <dd class="col-sm-7">{{ $order->email_cliente }}</dd>

          @if($order->telefono_cliente)
          <dt class="col-sm-5">Teléfono:</dt>
          <dd class="col-sm-7">{{ $order->telefono_cliente }}</dd>
          @endif

          @if($order->dpi)
          <dt class="col-sm-5">DPI:</dt>
          <dd class="col-sm-7">{{ $order->dpi }}</dd>
          @endif

          @if($order->nit)
          <dt class="col-sm-5">NIT:</dt>
          <dd class="col-sm-7">{{ $order->nit }}</dd>
          @endif
        </dl>
      </div>
    </div>

    <!-- Shipping Info -->
    <div class="card mb-4">
      <div class="card-header">
        <h4 class="card-title">
          <i class="bi-geo-alt me-2"></i>Información de Envío
        </h4>
      </div>
      <div class="card-body">
        <p class="mb-1">{{ $order->direccion_entrega }}</p>
        <p class="mb-1">{{ $order->ciudad }}, {{ $order->departamento }}</p>
        @if($order->codigo_postal)
        <p class="mb-1">{{ $order->codigo_postal }}</p>
        @endif
        
        @if($order->fecha_entrega_estimada)
        <hr>
        <small class="text-muted">
          <i class="bi-calendar-event me-1"></i>
          Entrega estimada: {{ $order->fecha_entrega_estimada->format('M d, Y') }}
        </small>
        @endif
      </div>
    </div>

    <!-- Payment Info -->
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">
          <i class="bi-credit-card me-2"></i>Información de Pago
        </h4>
      </div>
      <div class="card-body">
        <dl class="row">
          <dt class="col-sm-6">Método:</dt>
          <dd class="col-sm-6">
            <div class="d-flex align-items-center">
              @switch($order->metodo_pago)
                @case('tarjeta')
                  <i class="bi-credit-card me-2"></i>
                  @break
                @case('efectivo')
                  <i class="bi-cash me-2"></i>
                  @break
                @case('transferencia')
                  <i class="bi-bank me-2"></i>
                  @break
                @default
                  <i class="bi-truck me-2"></i>
              @endswitch
              {{ ucfirst(str_replace('_', ' ', $order->metodo_pago)) }}
            </div>
          </dd>

          <dt class="col-sm-6">Estado:</dt>
          <dd class="col-sm-6">
            <span class="badge {{ $order->estado_pago_badge }} text-white">
              {{ $order->estado_pago_texto }}
            </span>
          </dd>
        </dl>
      </div>
    </div>
  </div>
</div>

@endsection

@push('styles')
<style>
/* Timeline Container */
.timeline {
  position: relative;
  padding: 0;
  margin: 0;
}

/* Timeline Item */
.timeline-item {
  position: relative;
  display: flex;
  align-items: flex-start;
  margin-bottom: 2.5rem;
  padding: 0;
}

.timeline-item:last-child {
  margin-bottom: 0;
}

/* Timeline Line */
.timeline-item:not(:last-child)::after {
  content: '';
  position: absolute;
  left: 2.5rem;
  top: 5rem;
  width: 3px;
  height: calc(100% - 3rem);
  background: linear-gradient(180deg, #e9ecef 0%, #dee2e6 100%);
  border-radius: 2px;
  z-index: 1;
}

.timeline-item-success:not(:last-child)::after {
  background: linear-gradient(180deg, #28a745 0%, #20c997 100%);
  box-shadow: 0 0 10px rgba(40, 167, 69, 0.3);
}

.timeline-item-danger:not(:last-child)::after {
  background: linear-gradient(180deg, #dc3545 0%, #e74c3c 100%);
  box-shadow: 0 0 10px rgba(220, 53, 69, 0.3);
}

/* Timeline Marker */
.timeline-marker {
  position: relative;
  z-index: 2;
  margin-right: 1.5rem;
  flex-shrink: 0;
}

.timeline-marker-node {
  width: 5rem;
  height: 5rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border: 4px solid #fff;
  color: #6c757d;
  font-size: 1.5rem;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.timeline-marker-node::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(circle at center, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
  border-radius: 50%;
}

.timeline-marker-node-success {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
  box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
  animation: pulse-success 2s infinite;
}

.timeline-marker-node-danger {
  background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
  color: white;
  box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
  animation: pulse-danger 2s infinite;
}

.timeline-marker-node-pending {
  background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
  color: white;
  box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
}

/* Animations */
@keyframes pulse-success {
  0% {
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4), 0 0 0 0 rgba(40, 167, 69, 0.7);
  }
  70% {
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4), 0 0 0 10px rgba(40, 167, 69, 0);
  }
  100% {
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4), 0 0 0 0 rgba(40, 167, 69, 0);
  }
}

@keyframes pulse-danger {
  0% {
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4), 0 0 0 0 rgba(220, 53, 69, 0.7);
  }
  70% {
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4), 0 0 0 10px rgba(220, 53, 69, 0);
  }
  100% {
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4), 0 0 0 0 rgba(220, 53, 69, 0);
  }
}

/* Timeline Content */
.timeline-content {
  flex: 1;
  background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
  border-radius: 15px;
  padding: 2rem;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
  border: 1px solid rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.timeline-content::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #e9ecef 0%, #dee2e6 100%);
}

.timeline-item-success .timeline-content::before {
  background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
}

.timeline-item-danger .timeline-content::before {
  background: linear-gradient(90deg, #dc3545 0%, #e74c3c 100%);
}

.timeline-content:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.timeline-content-header {
  margin-bottom: 1rem;
  display: flex;
  justify-content: between;
  align-items: flex-start;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.timeline-content-title {
  color: #2d3748;
  font-weight: 700;
  font-size: 1.25rem;
  margin: 0;
  flex: 1;
}

.timeline-content-title i {
  margin-right: 0.5rem;
  color: #6c757d;
}

.timeline-content .text-muted {
  background: rgba(108, 117, 125, 0.1);
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 500;
  white-space: nowrap;
}

.timeline-content-text {
  color: #4a5568;
  font-size: 1rem;
  line-height: 1.6;
  margin-bottom: 1rem;
}

/* Alert Styles */
.timeline-content .alert-soft-info {
  background: linear-gradient(135deg, rgba(13, 202, 240, 0.1) 0%, rgba(13, 202, 240, 0.05) 100%);
  border: 1px solid rgba(13, 202, 240, 0.2);
  border-radius: 12px;
  padding: 1.5rem;
  margin-top: 1rem;
}

.timeline-content .alert-heading {
  color: #0dcaf0;
  font-weight: 700;
  font-size: 1.1rem;
}

.timeline-content .alert p {
  color: #495057;
  margin-bottom: 0;
  line-height: 1.6;
}

.timeline-content .alert .bi-truck {
  color: #0dcaf0;
  opacity: 0.8;
}

/* Responsive Design */
@media (max-width: 768px) {
  .timeline-item {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }
  
  .timeline-item:not(:last-child)::after {
    left: 50%;
    transform: translateX(-50%);
    top: 6rem;
    height: 2rem;
  }
  
  .timeline-marker {
    margin-right: 0;
    margin-bottom: 1rem;
  }
  
  .timeline-marker-node {
    width: 4rem;
    height: 4rem;
    font-size: 1.25rem;
  }
  
  .timeline-content {
    width: 100%;
  }
  
  .timeline-content-header {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }
}

/* Card Header Enhancement */
.card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.card-title {
  color: #2d3748;
  font-weight: 700;
}

.card-title i {
  color: #6c757d;
}
</style>
@endpush