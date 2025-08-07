<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Mis Pedidos - Bodegas Multiplex</title>

  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap-icons/font/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/css/theme.min.css') }}">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8f9fa;
    }
    
    .orders-header {
      background: white;
      border-bottom: 1px solid #e9ecef;
      margin-bottom: 2rem;
    }
    
    .orders-content {
      background: white;
      border-radius: 0.5rem;
      border: 1px solid #e9ecef;
      padding: 2rem;
    }
    
    .order-card {
      background: white;
      border: 1px solid #e9ecef;
      border-radius: 0.5rem;
      margin-bottom: 1.5rem;
      transition: all 0.3s ease;
    }
    
    .order-card:hover {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transform: translateY(-2px);
    }
    
    .order-header {
      background: #f8f9fa;
      border-bottom: 1px solid #e9ecef;
      padding: 1rem 1.5rem;
      border-radius: 0.5rem 0.5rem 0 0;
    }
    
    .order-body {
      padding: 1.5rem;
    }
    
    .btn-view-order {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      padding: 0.5rem 1.5rem;
      font-weight: 500;
      border-radius: 0.375rem;
      color: white;
      transition: all 0.3s ease;
    }
    
    .btn-view-order:hover {
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
      color: white;
    }
    
    .stats-summary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 0.5rem;
      padding: 2rem;
      margin-bottom: 2rem;
    }
    
    .filter-section {
      background: white;
      border: 1px solid #e9ecef;
      border-radius: 0.5rem;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }
  </style>
</head>

<body>
  <!-- Header -->
  <header class="orders-header py-3">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <h2 class="h4 mb-0 text-primary me-3">
            <i class="bi-shop me-2"></i>Bodegas Multiplex
          </h2>
        </div>
        <div class="d-flex align-items-center">
          <span class="text-muted me-3">{{ Auth::user()->name }}</span>
          <a href="{{ route('welcome') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi-house me-1"></i> Inicio
          </a>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <div class="container">
    <!-- Page Header -->
    <div class="row">
      <div class="col-12">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Mis Pedidos</li>
          </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h1 class="h3">Mis Pedidos</h1>
          <a href="{{ route('welcome') }}" class="btn btn-outline-primary">
            <i class="bi-arrow-left"></i> Seguir Comprando
          </a>
        </div>
      </div>
    </div>

    <!-- Mensajes de Alerta -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <!-- Resumen de Estadísticas -->
    <div class="stats-summary">
      <div class="row text-center">
        <div class="col-md-3 mb-3 mb-md-0">
          <div class="d-flex flex-column">
            <span class="h2 mb-0">{{ $stats['total_ordenes'] }}</span>
            <span class="opacity-75">Total Pedidos</span>
          </div>
        </div>
        <div class="col-md-3 mb-3 mb-md-0">
          <div class="d-flex flex-column">
            <span class="h2 mb-0">{{ $stats['ordenes_pendientes'] }}</span>
            <span class="opacity-75">En Proceso</span>
          </div>
        </div>
        <div class="col-md-3 mb-3 mb-md-0">
          <div class="d-flex flex-column">
            <span class="h2 mb-0">{{ $stats['ordenes_entregadas'] }}</span>
            <span class="opacity-75">Entregados</span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="d-flex flex-column">
            <span class="h2 mb-0">Q{{ number_format($stats['total_gastado'], 0) }}</span>
            <span class="opacity-75">Total Gastado</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Filtros de Búsqueda -->
    <div class="filter-section">
      <form method="GET" class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Buscar pedidos</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi-search"></i></span>
            <input name="search" type="search" class="form-control" 
                   placeholder="Número de orden, código de seguimiento..." 
                   value="{{ request('search') }}">
          </div>
        </div>

        <div class="col-md-3">
          <label class="form-label">Estado</label>
          <select name="estado" class="form-select">
            <option value="">Todos los estados</option>
            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="proceso" {{ request('estado') == 'proceso' ? 'selected' : '' }}>En Proceso</option>
            <option value="enviado" {{ request('estado') == 'enviado' ? 'selected' : '' }}>Enviado</option>
            <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
            <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
            <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label">Desde</label>
          <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
        </div>

        <div class="col-md-2">
          <label class="form-label">Hasta</label>
          <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
        </div>

        <div class="col-md-1 d-flex align-items-end">
          <button type="submit" class="btn btn-primary me-2">Buscar</button>
        </div>
      </form>
      
      @if(request()->hasAny(['search', 'estado', 'fecha_inicio', 'fecha_fin']))
        <div class="mt-3">
          <a href="{{ route('user.orders.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi-x-circle me-1"></i> Limpiar filtros
          </a>
        </div>
      @endif
    </div>

    <!-- Lista de Pedidos -->
    @forelse($orders as $order)
      <div class="order-card">
        <div class="order-header">
          <div class="row align-items-center">
            <div class="col-md-3">
              <div class="d-flex align-items-center">
                <i class="bi-receipt-cutoff text-primary me-2" style="font-size: 1.25rem;"></i>
                <div>
                  <span class="fw-semibold">{{ $order->numero_orden }}</span>
                  <small class="text-muted d-block">{{ $order->fecha_pedido->format('M d, Y - H:i') }}</small>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <span class="badge {{ $order->estado_badge }} fs-6 text-white">
                {{ $order->estado_texto }}
              </span>
            </div>
            <div class="col-md-3">
              <div class="text-end text-md-start">
                <span class="fw-semibold">Q{{ number_format($order->total, 2) }}</span>
                <small class="text-muted d-block">{{ $order->items->count() }} {{ Str::plural('producto', $order->items->count()) }}</small>
              </div>
            </div>
            <div class="col-md-3 text-end">
              <a href="{{ route('user.orders.show', $order) }}" class="btn btn-view-order">
                <i class="bi-eye me-1"></i> Ver Detalle
              </a>
            </div>
          </div>
        </div>
        
        <div class="order-body">
          <div class="row">
            <!-- Información de Envío -->
            <div class="col-md-6">
              <h6 class="text-primary mb-2">
                <i class="bi-truck me-1"></i> Información de Envío
              </h6>
              <div class="mb-2">
                <small class="text-muted">Dirección:</small>
                <div>{{ $order->direccion_entrega }}</div>
              </div>
              @if($order->codigo_seguimiento)
                <div class="mb-2">
                  <small class="text-muted">Código de seguimiento:</small>
                  <div class="fw-semibold">{{ $order->codigo_seguimiento }}</div>
                  @if($order->empresa_envio)
                    <small class="text-muted">{{ $order->empresa_envio }}</small>
                  @endif
                </div>
              @endif
              @if($order->guia_envio)
                <div class="mb-2">
                  <small class="text-muted">Guía de envío:</small>
                  <div class="fw-semibold text-primary">{{ $order->guia_envio }}</div>
                </div>
              @endif
            </div>
            
            <!-- Método de Pago -->
            <div class="col-md-6">
              <h6 class="text-primary mb-2">
                <i class="bi-credit-card me-1"></i> Método de Pago
              </h6>
              <div class="d-flex align-items-center mb-2">
                @switch($order->metodo_pago)
                  @case('tarjeta')
                    <i class="bi-credit-card text-info me-2"></i>
                    <span>Tarjeta de Crédito/Débito</span>
                    @break
                  @case('efectivo')
                    <i class="bi-cash text-success me-2"></i>
                    <span>Efectivo</span>
                    @break
                  @case('transferencia')
                    <i class="bi-bank text-warning me-2"></i>
                    <span>Transferencia Bancaria</span>
                    @break
                  @default
                    <i class="bi-truck text-secondary me-2"></i>
                    <span>Contra Entrega</span>
                @endswitch
              </div>
              <div>
                <span class="badge {{ $order->estado_pago_badge }} fs-6 text-white">
                  {{ $order->estado_pago_texto }}
                </span>
              </div>
            </div>
          </div>
          
          @if($order->items->count() > 0)
            <hr class="my-3">
            <h6 class="text-primary mb-2">
              <i class="bi-box-seam me-1"></i> Productos
            </h6>
            <div class="row">
              @foreach($order->items->take(3) as $item)
                <div class="col-md-4 mb-2">
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-2">
                      @if($item->producto && $item->producto->imagen_principal)
                        <img src="{{ $item->producto->imagen_principal }}" 
                             alt="{{ $item->nombre_producto }}" 
                             class="rounded" 
                             style="width: 40px; height: 40px; object-fit: cover;">
                      @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                             style="width: 40px; height: 40px;">
                          <i class="bi-image text-muted"></i>
                        </div>
                      @endif
                    </div>
                    <div class="flex-grow-1">
                      <div class="fw-semibold" style="font-size: 0.875rem;">{{ $item->nombre_producto }}</div>
                      <small class="text-muted">{{ $item->cantidad }}x - Q{{ number_format($item->precio_unitario, 2) }}</small>
                    </div>
                  </div>
                </div>
              @endforeach
              
              @if($order->items->count() > 3)
                <div class="col-12">
                  <small class="text-muted">+ {{ $order->items->count() - 3 }} productos más</small>
                </div>
              @endif
            </div>
          @endif
        </div>
      </div>
    @empty
      <!-- Estado Vacío -->
      <div class="text-center py-5">
        <div class="orders-content">
          <div class="mb-4">
            <i class="bi-bag-x text-muted" style="font-size: 4rem;"></i>
          </div>
          <h4 class="text-muted mb-3">No tienes pedidos aún</h4>
          <p class="text-muted mb-4">Cuando realices tu primera compra, aparecerá aquí con toda la información de seguimiento.</p>
          <a href="{{ route('welcome') }}" class="btn btn-view-order">
            <i class="bi-shop me-2"></i> Explorar Productos
          </a>
        </div>
      </div>
    @endforelse

    <!-- Paginación -->
    @if($orders->hasPages())
      <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
      </div>
    @endif
  </div>

  <!-- Scripts -->
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>