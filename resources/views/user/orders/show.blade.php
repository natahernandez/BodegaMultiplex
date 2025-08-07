@extends('layouts.user')

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

          @if($order->impuestos > 0)
          <dt class="col-sm-6">Impuestos:</dt>
          <dd class="col-sm-6 text-end">Q{{ number_format($order->impuestos, 2) }}</dd>
          @endif

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

@section('styles')
<style>
.timeline {
  position: relative;
  padding-left: 2rem;
}

.timeline-item {
  position: relative;
  padding-bottom: 2rem;
}

.timeline-item:not(:last-child)::before {
  content: '';
  position: absolute;
  left: -1.5rem;
  top: 2.5rem;
  width: 2px;
  height: calc(100% - 1rem);
  background-color: #e3e6f0;
}

.timeline-item-success:not(:last-child)::before {
  background-color: #1cc88a;
}

.timeline-item-danger:not(:last-child)::before {
  background-color: #e74a3b;
}

.timeline-marker {
  position: absolute;
  left: -2rem;
  top: 0;
}

.timeline-marker-node {
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #e3e6f0;
  border: 3px solid #fff;
  color: #6c757d;
  font-size: 0.875rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.timeline-marker-node-success {
  background-color: #1cc88a;
  color: white;
  font-size: 0.875rem;
}

.timeline-marker-node-danger {
  background-color: #e74a3b;
  color: white;
  font-size: 0.875rem;
}

.timeline-marker-node-pending {
  background-color: #f8f9fc;
  border-color: #e3e6f0;
}

.timeline-content {
  margin-left: 1rem;
}

.timeline-content-title {
  color: #5a5c69;
}

.timeline-content-text {
  color: #858796;
}
</style>
@endsection