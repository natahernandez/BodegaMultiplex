<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Orden Exitosa - Bodegas Multiplex</title>

  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap-icons/font/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/css/theme.min.css') }}">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8f9fa;
    }
    
    .success-header {
      background: white;
      border-bottom: 1px solid #e9ecef;
      margin-bottom: 2rem;
    }
  </style>
</head>

<body>
  <!-- Header -->
  <header class="success-header py-3">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <h2 class="h4 mb-0 text-primary me-3">
            <i class="bi-shop me-2"></i>Bodegas Multiplex
          </h2>
          <span class="text-muted">|</span>
          <span class="ms-3 text-success">Orden Completada</span>
        </div>
        <div class="d-flex align-items-center">
          <a href="/" class="btn btn-outline-primary btn-sm">
            <i class="bi-house me-1"></i>Ir al Inicio
          </a>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <!-- Success Message -->
      <div class="text-center mb-5">
        <div class="mb-4">
          <i class="bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
        </div>
        <h1 class="display-5 text-success mb-3">¡Orden Exitosa!</h1>
        <p class="lead text-muted">Tu pedido ha sido procesado correctamente</p>
      </div>

      <!-- Order Details -->
      <div class="card mb-4">
        <div class="card-header bg-success text-white">
          <h5 class="card-title mb-0">
            <i class="bi-receipt me-2"></i>
            Detalles de la Orden
          </h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h6 class="text-muted">Número de Orden</h6>
              <p class="h5 text-primary">{{ $order->numero_orden }}</p>
            </div>
            <div class="col-md-6">
              <h6 class="text-muted">Fecha</h6>
              <p class="h6">{{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="col-md-6">
              <h6 class="text-muted">Estado</h6>
              <span class="badge bg-warning">En Proceso</span>
            </div>
            <div class="col-md-6">
              <h6 class="text-muted">Método de Pago</h6>
              <p class="h6">
                @if($order->tipo_pago === 'linea')
                  <i class="bi-credit-card me-1"></i> Pago en Línea
                @else
                  <i class="bi-cash me-1"></i> Pago Contra Entrega
                @endif
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Customer Information -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0">
            <i class="bi-person-lines-fill me-2"></i>
            Información de Entrega
          </h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <p><strong>Nombre:</strong> {{ $order->nombre_cliente }}</p>
              <p><strong>Email:</strong> {{ $order->email_cliente }}</p>
              <p><strong>Teléfono:</strong> {{ $order->telefono_cliente }}</p>
              <p><strong>DPI:</strong> {{ $order->dpi }}</p>
              <p><strong>NIT:</strong> {{ $order->nit }}</p>
            </div>
            <div class="col-md-6">
              <p><strong>Dirección:</strong></p>
              <p>{{ $order->direccion_entrega }}</p>
              <p><strong>Ciudad:</strong> {{ $order->ciudad }}</p>
              <p><strong>Departamento:</strong> {{ $order->departamento }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Items -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0">
            <i class="bi-box-seam me-2"></i>
            Productos Ordenados
          </h5>
        </div>
        <div class="card-body">
          @foreach($order->items as $item)
            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
              <div class="flex-grow-1">
                <h6 class="mb-0">{{ $item->nombre_producto }}</h6>
                <small class="text-muted">{{ $item->codigo_producto }}</small>
                <br>
                <small class="text-muted">Cantidad: {{ $item->cantidad }}</small>
              </div>
              <div class="text-end">
                <p class="mb-0 fw-bold">Q{{ number_format($item->subtotal, 2) }}</p>
                <small class="text-muted">Q{{ number_format($item->precio_unitario, 2) }} c/u</small>
              </div>
            </div>
          @endforeach
          
          <div class="row mt-3">
            <div class="col-md-6 offset-md-6">
              <div class="d-flex justify-content-between">
                <span>Subtotal:</span>
                <span>Q{{ number_format($order->subtotal, 2) }}</span>
              </div>
              <div class="d-flex justify-content-between">
                <span>Envío:</span>
                <span>Q{{ number_format($order->envio, 2) }}</span>
              </div>
              <hr>
              <div class="d-flex justify-content-between">
                <strong>Total:</strong>
                <strong class="text-primary">Q{{ number_format($order->total, 2) }}</strong>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Next Steps -->
      <div class="card mb-4">
        <div class="card-header bg-info text-white">
          <h5 class="card-title mb-0">
            <i class="bi-info-circle me-2"></i>
            ¿Qué sigue?
          </h5>
        </div>
        <div class="card-body">
          @if($order->tipo_pago === 'linea')
            <div class="alert alert-success">
              <i class="bi-check-circle me-2"></i>
              <strong>Pago Procesado:</strong> Tu pago ha sido procesado exitosamente.
            </div>
          @else
            <div class="alert alert-warning">
              <i class="bi-truck me-2"></i>
              <strong>Pago Contra Entrega:</strong> Pagarás cuando recibas tu pedido.
            </div>
          @endif
          
          <ul class="list-unstyled">
            <li class="mb-2">
              <i class="bi-1-circle-fill text-primary me-2"></i>
              Procesaremos tu orden en las próximas 24 horas
            </li>
            <li class="mb-2">
              <i class="bi-2-circle-fill text-primary me-2"></i>
              Te enviaremos un email con la información de envío
            </li>
            <li class="mb-2">
              <i class="bi-3-circle-fill text-primary me-2"></i>
              Recibirás tu pedido en 2-5 días hábiles
            </li>
          </ul>
        </div>
      </div>

      <!-- Notes -->
      @if($order->notas_cliente)
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">
              <i class="bi-chat-text me-2"></i>
              Notas del Cliente
            </h5>
          </div>
          <div class="card-body">
            <p class="mb-0">{{ $order->notas_cliente }}</p>
          </div>
        </div>
      @endif

      <!-- Actions -->
      <div class="text-center">
        <a href="{{ route('welcome') }}" class="btn btn-primary btn-lg me-3">
          <i class="bi-house me-2"></i>
          Volver al Inicio
        </a>
        <a href="{{ route('shop.cart') }}" class="btn btn-outline-primary btn-lg">
          <i class="bi-cart me-2"></i>
          Seguir Comprando
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/js/theme.min.js') }}"></script>

</body>
</html> 