@extends('layouts.shop')

@section('title', 'Mis Pedidos')

@push('styles')
<style>
/* Variables de Color */
:root {
  --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  --warning-gradient: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%);
  --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
  --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

body {
  font-family: 'Inter', sans-serif;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  min-height: 100vh;
}

/* Header Section */
.page-header {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  padding: 2rem;
  margin-bottom: 2rem;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.page-title {
  background: var(--primary-gradient);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  font-weight: 800;
  font-size: 2.5rem;
  margin-bottom: 0.5rem;
}

.page-subtitle {
  color: #64748b;
  font-size: 1.1rem;
  font-weight: 500;
}

/* Stats Cards */
.stats-container {
  margin-bottom: 2rem;
}

.stat-card {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  padding: 2rem;
  text-align: center;
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
  height: 100%;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.stat-icon {
  width: 4rem;
  height: 4rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem;
  font-size: 1.5rem;
  color: white;
}

.stat-icon.total { background: var(--primary-gradient); }
.stat-icon.pending { background: var(--warning-gradient); }
.stat-icon.delivered { background: var(--success-gradient); }
.stat-icon.spent { background: var(--info-gradient); }

.stat-number {
  font-size: 2.5rem;
  font-weight: 800;
  color: #1e293b;
  margin-bottom: 0.5rem;
}

.stat-label {
  color: #64748b;
  font-weight: 600;
  font-size: 0.95rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Filter Section */
.filter-section {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  padding: 2rem;
  margin-bottom: 2rem;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.filter-title {
  color: #1e293b;
  font-weight: 700;
  font-size: 1.25rem;
  margin-bottom: 1.5rem;
}

.form-control, .form-select {
  border-radius: 12px;
  border: 2px solid #e2e8f0;
  padding: 0.75rem 1rem;
  transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-search {
  background: var(--primary-gradient);
  border: none;
  border-radius: 12px;
  padding: 0.75rem 2rem;
  font-weight: 600;
  color: white;
  transition: all 0.3s ease;
}

.btn-search:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
  color: white;
}

/* Order Cards */
.order-card {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  margin-bottom: 2rem;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  overflow: hidden;
  transition: all 0.3s ease;
}

.order-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.order-header {
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  padding: 1.5rem 2rem;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.order-number {
  font-weight: 800;
  font-size: 1.1rem;
  color: #1e293b;
}

.order-date {
  color: #64748b;
  font-size: 0.9rem;
  font-weight: 500;
}

.order-body {
  padding: 2rem;
}

/* Status Badges */
.status-badge {
  padding: 0.5rem 1rem;
  border-radius: 50px;
  font-weight: 600;
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.status-badge.pendiente {
  background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
  color: white;
}

.status-badge.proceso {
  background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
  color: white;
}

.status-badge.enviado {
  background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
  color: white;
}

.status-badge.entregado, .status-badge.completado {
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  color: white;
}

.status-badge.cancelado {
  background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
  color: white;
}

/* Order Total */
.order-total {
  font-size: 1.5rem;
  font-weight: 800;
  color: #1e293b;
}

.order-items-count {
  color: #64748b;
  font-size: 0.9rem;
}

/* View Button */
.btn-view-order {
  background: var(--primary-gradient);
  border: none;
  border-radius: 12px;
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  color: white;
  transition: all 0.3s ease;
  text-decoration: none;
}

.btn-view-order:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
  color: white;
}

/* Info Sections */
.info-section {
  background: rgba(248, 250, 252, 0.5);
  border-radius: 12px;
  padding: 1.5rem;
  margin-bottom: 1rem;
}

.info-title {
  color: #1e293b;
  font-weight: 700;
  font-size: 1rem;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
}

.info-title i {
  margin-right: 0.5rem;
  color: #667eea;
}

/* Product Mini Cards */
.product-mini {
  display: flex;
  align-items: center;
  background: rgba(255, 255, 255, 0.7);
  border-radius: 12px;
  padding: 1rem;
  margin-bottom: 0.75rem;
  transition: all 0.3s ease;
}

.product-mini:hover {
  background: rgba(255, 255, 255, 0.9);
  transform: translateX(5px);
}

.product-image {
  width: 50px;
  height: 50px;
  border-radius: 10px;
  object-fit: cover;
  margin-right: 1rem;
}

.product-placeholder {
  width: 50px;
  height: 50px;
  border-radius: 10px;
  background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 1rem;
}

.product-name {
  font-weight: 600;
  color: #1e293b;
  font-size: 0.95rem;
  margin-bottom: 0.25rem;
}

.product-details {
  color: #64748b;
  font-size: 0.85rem;
}

/* Empty State */
.empty-state {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  padding: 4rem 2rem;
  text-align: center;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.empty-icon {
  font-size: 5rem;
  color: #cbd5e1;
  margin-bottom: 2rem;
}

.empty-title {
  color: #1e293b;
  font-weight: 700;
  font-size: 1.5rem;
  margin-bottom: 1rem;
}

.empty-text {
  color: #64748b;
  font-size: 1.1rem;
  margin-bottom: 2rem;
  line-height: 1.6;
}

/* Responsive Design */
@media (max-width: 768px) {
  .page-title {
    font-size: 2rem;
  }
  
  .stat-card {
    padding: 1.5rem;
  }
  
  .stat-number {
    font-size: 2rem;
  }
  
  .order-header {
    padding: 1rem;
  }
  
  .order-body {
    padding: 1rem;
  }
  
  .filter-section {
    padding: 1.5rem;
  }
}

/* Pagination Styling */
.pagination .page-link {
  border-radius: 10px;
  border: none;
  margin: 0 2px;
  padding: 0.75rem 1rem;
  color: #667eea;
  background: rgba(255, 255, 255, 0.8);
  transition: all 0.3s ease;
}

.pagination .page-link:hover {
  background: var(--primary-gradient);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.pagination .page-item.active .page-link {
  background: var(--primary-gradient);
  color: white;
  box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}
</style>
@endpush

@section('content')
<div class="container py-4">
  <!-- Page Header -->
  <div class="page-header">
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none">🏠 Inicio</a></li>
        <li class="breadcrumb-item active">Mis Pedidos</li>
      </ol>
    </nav>
    
    <div class="d-flex justify-content-between align-items-center flex-wrap">
      <div>
        <h1 class="page-title">Mis Pedidos</h1>
        <p class="page-subtitle">Seguimiento completo de tus compras</p>
      </div>
      <a href="{{ route('welcome') }}" class="btn btn-search">
        <i class="bi-arrow-left me-2"></i>Seguir Comprando
      </a>
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="stats-container">
    <div class="row g-4">
      <div class="col-lg-3 col-md-6">
        <div class="stat-card">
          <div class="stat-icon total">
            <i class="bi-receipt"></i>
          </div>
          <div class="stat-number">{{ $stats['total_ordenes'] }}</div>
          <div class="stat-label">Total Pedidos</div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="stat-card">
          <div class="stat-icon pending">
            <i class="bi-clock-history"></i>
          </div>
          <div class="stat-number">{{ $stats['ordenes_pendientes'] }}</div>
          <div class="stat-label">En Proceso</div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="stat-card">
          <div class="stat-icon delivered">
            <i class="bi-check-circle"></i>
          </div>
          <div class="stat-number">{{ $stats['ordenes_entregadas'] }}</div>
          <div class="stat-label">Entregados</div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="stat-card">
          <div class="stat-icon spent">
            <i class="bi-currency-dollar"></i>
          </div>
          <div class="stat-number">Q{{ number_format($stats['total_gastado'], 0) }}</div>
          <div class="stat-label">Total Gastado</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Search and Filters -->
  <div class="filter-section">
    <h5 class="filter-title">
      <i class="bi-funnel me-2"></i>Filtros de Búsqueda
    </h5>
    <form method="GET" class="row g-3">
      <div class="col-lg-4 col-md-6">
        <label class="form-label fw-semibold">Buscar pedidos</label>
        <div class="input-group">
          <span class="input-group-text bg-white border-end-0">
            <i class="bi-search text-muted"></i>
          </span>
          <input name="search" type="search" class="form-control border-start-0" 
                 placeholder="Número de orden, código de seguimiento..." 
                 value="{{ request('search') }}">
        </div>
      </div>

      <div class="col-lg-2 col-md-6">
        <label class="form-label fw-semibold">Estado</label>
        <select name="estado" class="form-select">
          <option value="">Todos</option>
          <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
          <option value="proceso" {{ request('estado') == 'proceso' ? 'selected' : '' }}>En Proceso</option>
          <option value="enviado" {{ request('estado') == 'enviado' ? 'selected' : '' }}>Enviado</option>
          <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
          <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
          <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
        </select>
      </div>

      <div class="col-lg-2 col-md-6">
        <label class="form-label fw-semibold">Desde</label>
        <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
      </div>

      <div class="col-lg-2 col-md-6">
        <label class="form-label fw-semibold">Hasta</label>
        <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
      </div>

      <div class="col-lg-2 col-md-12 d-flex align-items-end">
        <button type="submit" class="btn btn-search w-100">
          <i class="bi-search me-2"></i>Buscar
        </button>
      </div>
    </form>
    
    @if(request()->hasAny(['search', 'estado', 'fecha_inicio', 'fecha_fin']))
      <div class="mt-3">
        <a href="{{ route('user.orders.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
          <i class="bi-x-circle me-1"></i>Limpiar filtros
        </a>
      </div>
    @endif
  </div>

  <!-- Orders List -->
  @forelse($orders as $order)
    <div class="order-card">
      <div class="order-header">
        <div class="row align-items-center">
          <div class="col-lg-3 col-md-4 mb-2 mb-md-0">
            <div class="d-flex align-items-center">
              <div class="stat-icon total me-3" style="width: 3rem; height: 3rem; font-size: 1rem;">
                <i class="bi-receipt"></i>
              </div>
              <div>
                <div class="order-number">{{ $order->numero_orden }}</div>
                <div class="order-date">{{ $order->fecha_pedido->format('M d, Y - H:i') }}</div>
              </div>
            </div>
          </div>
          <div class="col-lg-2 col-md-3 mb-2 mb-md-0">
            <span class="status-badge {{ $order->estado }}">
              {{ $order->estado_texto }}
            </span>
          </div>
          <div class="col-lg-3 col-md-3 mb-2 mb-md-0">
            <div class="order-total">Q{{ number_format($order->total, 2) }}</div>
            <div class="order-items-count">{{ $order->items->count() }} {{ Str::plural('producto', $order->items->count()) }}</div>
          </div>
          <div class="col-lg-4 col-md-2 text-end">
            <a href="{{ route('user.orders.show', $order) }}" class="btn btn-view-order">
              <i class="bi-eye me-2"></i>Ver Detalle
            </a>
          </div>
        </div>
      </div>
      
      <div class="order-body">
        <div class="row">
          <!-- Shipping Info -->
          <div class="col-lg-6">
            <div class="info-section">
              <h6 class="info-title">
                <i class="bi-truck"></i>Información de Envío
              </h6>
              <div class="mb-2">
                <strong>Dirección:</strong>
                <div class="text-muted">{{ $order->direccion_entrega }}</div>
                <div class="text-muted">{{ $order->ciudad }}, {{ $order->departamento }}</div>
              </div>
              @if($order->codigo_seguimiento)
                <div class="mb-2">
                  <strong>Código de Seguimiento:</strong>
                  <div class="text-primary fw-semibold">{{ $order->codigo_seguimiento }}</div>
                  @if($order->empresa_envio)
                    <small class="text-muted">{{ $order->empresa_envio }}</small>
                  @endif
                </div>
              @endif
              @if($order->guia_envio)
                <div>
                  <strong>Guía de Envío:</strong>
                  <div class="text-success fw-semibold">{{ $order->guia_envio }}</div>
                </div>
              @endif
            </div>
          </div>
          
          <!-- Payment Info -->
          <div class="col-lg-6">
            <div class="info-section">
              <h6 class="info-title">
                <i class="bi-credit-card"></i>Información de Pago
              </h6>
              <div class="mb-2">
                <strong>Método:</strong>
                <div class="d-flex align-items-center">
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
              </div>
              <div>
                <strong>Estado de Pago:</strong>
                <div>
                  <span class="status-badge {{ $order->estado_pago }}">
                    {{ $order->estado_pago_texto }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Products Preview -->
        @if($order->items->count() > 0)
          <div class="info-section">
            <h6 class="info-title">
              <i class="bi-box-seam"></i>Productos ({{ $order->items->count() }})
            </h6>
            <div class="row">
              @foreach($order->items->take(4) as $item)
                <div class="col-lg-6 col-md-12">
                  <div class="product-mini">
                    @if($item->producto && $item->producto->imagen_principal_url)
                      <img src="{{ $item->producto->imagen_principal_url }}" 
                           alt="{{ $item->nombre_producto }}" 
                           class="product-image">
                    @else
                      <div class="product-placeholder">
                        <i class="bi-image text-muted"></i>
                      </div>
                    @endif
                    <div class="flex-grow-1">
                      <div class="product-name">{{ $item->nombre_producto }}</div>
                      <div class="product-details">
                        {{ $item->cantidad }}x • Q{{ number_format($item->precio_unitario, 2) }} 
                        = <strong>Q{{ number_format($item->subtotal, 2) }}</strong>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
              
              @if($order->items->count() > 4)
                <div class="col-12 text-center">
                  <small class="text-muted">+ {{ $order->items->count() - 4 }} productos más</small>
                </div>
              @endif
            </div>
          </div>
        @endif
      </div>
    </div>
  @empty
    <!-- Empty State -->
    <div class="empty-state">
      <div class="empty-icon">
        <i class="bi-bag-x"></i>
      </div>
      <h3 class="empty-title">No tienes pedidos aún</h3>
      <p class="empty-text">
        Cuando realices tu primera compra, aparecerá aquí con toda la información de seguimiento.<br>
        ¡Explora nuestros productos y encuentra lo que necesitas!
      </p>
      <a href="{{ route('welcome') }}" class="btn btn-view-order btn-lg">
        <i class="bi-shop me-2"></i>Explorar Productos
      </a>
    </div>
  @endforelse

  <!-- Pagination -->
  @if($orders->hasPages())
    <div class="d-flex justify-content-center mt-4">
      {{ $orders->links() }}
    </div>
  @endif
</div>
@endsection