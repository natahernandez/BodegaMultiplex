@extends('layouts.app')

@section('content')
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
      <div class="d-flex gap-2">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
          <i class="bi-arrow-left me-1"></i> Volver a Órdenes
        </a>
        
        @if($order->estado === 'completado')
          <a href="{{ route('orders.generate-pdf', $order) }}" class="btn btn-success" target="_blank">
            <i class="bi-file-earmark-pdf me-1"></i> 
            @if($order->tipo_pago === 'linea')
              Generar Comprobante de Pago
            @else
              Generar Factura
            @endif
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
  <div class="col-lg-8">
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
                      @if($item->producto && $item->producto->imagen_principal_url)
                        <img src="{{ $item->producto->imagen_principal_url }}" 
                             alt="{{ $item->nombre_producto }}" 
                             class="rounded me-3" 
                             style="width: 60px; height: 60px; object-fit: contain; background: #f8f9fa;">
                      @else
                        <div class="d-flex align-items-center justify-content-center me-3 bg-light rounded" 
                             style="width: 60px; height: 60px;">
                          <i class="bi-box text-muted"></i>
                        </div>
                      @endif
                      <div>
                        <h6 class="mb-0">{{ $item->nombre_producto }}</h6>
                        <small class="text-muted">Código: {{ $item->codigo_producto }}</small>
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

        <!-- Totales -->
        <div class="row justify-content-end">
          <div class="col-md-4">
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
  <div class="col-lg-4">
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
              <button type="button" class="btn btn-danger" onclick="changeStatus('cancelado')">
                <i class="bi-x-circle me-2"></i>Cancelar Orden
              </button>
              
              <button type="button" class="btn btn-success" onclick="showCompleteModal()">
                <i class="bi-check-circle me-2"></i>Marcar como Completado
              </button>
            </div>
            
            <input type="hidden" name="estado" id="estadoInput">
          </form>
        @endif</thinking>

Tienes razón, hay varios problemas. Vamos a solucionarlos:

## 1. Primero arreglo el error de ruta

<function_calls>
<invoke name="read_file">
<parameter name="target_file">resources/views/pages/orders/index.blade.php

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
</script>

@endsection 