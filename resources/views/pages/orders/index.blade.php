@extends('layouts.app')

@section('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}">
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center mb-3">
    <div class="col-sm">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-no-gutter">
          <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Órdenes</li>
        </ol>
      </nav>

      <h1 class="page-header-title">Gestión de Órdenes</h1>
      <p class="page-header-text">Administra tus órdenes de manera eficiente</p>

    </div>
  </div>
</div>

  <!-- Stats Cards -->
  <div class="row">
    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
      <div class="card h-100">
        <div class="card-body">
          <h6 class="card-subtitle mb-2">Total Órdenes</h6>
          <div class="row align-items-center gx-2">
            <div class="col">
              <span class="js-counter display-4 text-primary">{{ $stats['total_ordenes'] }}</span>
            </div>
            <div class="col-auto">
              <i class="bi-receipt text-primary" style="font-size: 2rem;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
      <div class="card h-100">
        <div class="card-body">
          <h6 class="card-subtitle mb-2">Pendientes</h6>
          <div class="row align-items-center gx-2">
            <div class="col">
              <span class="js-counter display-4 text-warning">{{ $stats['ordenes_pendientes'] }}</span>
            </div>
            <div class="col-auto">
              <i class="bi-clock text-warning" style="font-size: 2rem;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
      <div class="card h-100">
        <div class="card-body">
          <h6 class="card-subtitle mb-2">Entregadas</h6>
          <div class="row align-items-center gx-2">
            <div class="col">
              <span class="js-counter display-4 text-success">{{ $stats['ordenes_entregadas'] }}</span>
            </div>
            <div class="col-auto">
              <i class="bi-check-circle text-success" style="font-size: 2rem;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
      <div class="card h-100">
        <div class="card-body">
          <h6 class="card-subtitle mb-2">Ventas del Mes</h6>
          <div class="row align-items-center gx-2">
            <div class="col">
              <span class="js-counter display-4 text-info">Q{{ number_format($stats['ventas_mes'], 0) }}</span>
            </div>
            <div class="col-auto">
              <i class="bi-currency-dollar text-info" style="font-size: 2rem;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<!-- Alerts -->
@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
  <div class="d-flex">
    <div class="flex-shrink-0">
      <i class="bi-check-circle-fill"></i>
    </div>
    <div class="flex-grow-1 ms-3">
      {{ session('success') }}
    </div>
  </div>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Card -->
<div class="card">
  <!-- Header -->
  <div class="card-header card-header-content-md-between">
    <div class="mb-2 mb-md-0">
             <form method="GET" class="d-inline">
         @foreach(request()->except('search') as $key => $value)
           <input type="hidden" name="{{ $key }}" value="{{ $value }}">
         @endforeach
         <div class="input-group input-group-merge input-group-flush">
           <div class="input-group-prepend input-group-text">
             <i class="bi-search"></i>
           </div>
           <input name="search" type="search" class="form-control" placeholder="Buscar órdenes..." aria-label="Search orders" value="{{ request('search') }}">
         </div>
       </form>
      <div class="card-body">
        <form method="GET" class="row g-3">
          <div class="col-md-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
              <option value="">Todos los estados</option>
              <option value="pre_orden" {{ request('estado') == 'pre_orden' ? 'selected' : '' }}>🔄 Procesando Pago</option>
              <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
              <option value="confirmado" {{ request('estado') == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
              <option value="en_preparacion" {{ request('estado') == 'en_preparacion' ? 'selected' : '' }}>En Preparación</option>
              <option value="proceso" {{ request('estado') == 'proceso' ? 'selected' : '' }}>En Proceso</option>
              <option value="enviado" {{ request('estado') == 'enviado' ? 'selected' : '' }}>Enviado</option>
              <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
              <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
              <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
              <option value="expirado" {{ request('estado') == 'expirado' ? 'selected' : '' }}>💀 Expirado</option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">Estado de Pago</label>
            <select name="estado_pago" class="form-select">
              <option value="">Todos</option>
              <option value="pendiente" {{ request('estado_pago') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
              <option value="pagado" {{ request('estado_pago') == 'pagado' ? 'selected' : '' }}>Pagado</option>
              <option value="contra_entrega" {{ request('estado_pago') == 'contra_entrega' ? 'selected' : '' }}>Contra Entrega</option>
              <option value="cancelado" {{ request('estado_pago') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label">Fecha Inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
          </div>

          <div class="col-md-2">
            <label class="form-label">Fecha Fin</label>
            <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
          </div>

          <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Filtrar</button>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Limpiar</a>
          </div>
          
          <div class="col-12 mt-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="mostrar_pre_ordenes" value="1" 
                     id="mostrar_pre_ordenes" {{ request('mostrar_pre_ordenes') ? 'checked' : '' }}
                     onchange="this.form.submit()">
              <label class="form-check-label" for="mostrar_pre_ordenes">
                🔄 Mostrar pre-órdenes y órdenes expiradas
                <small class="text-muted">(incluye órdenes en proceso de pago y expiradas)</small>
              </label>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- End Header -->

  <!-- Table -->
  <div class="table-responsive datatable-custom">
    <table id="datatable" class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table" style="width: 100%">
      <thead class="thead-light">
        <tr>
          <th class="table-column-pe-0">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="datatableCheckAll">
              <label class="form-check-label" for="datatableCheckAll"></label>
            </div>
          </th>
          <th class="table-column-ps-0">Orden</th>
          <th>Fecha</th>
          <th>Cliente</th>
          <th>Estado</th>
          <th>Estado Pago</th>
          <th>Método Pago</th>
          <th>Total</th>
          <th>Acciones</th>
        </tr>
      </thead>

      <tbody>
        @forelse($orders as $order)
        <tr>
          <td class="table-column-pe-0">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="orderCheck{{ $order->id }}" value="{{ $order->id }}">
              <label class="form-check-label" for="orderCheck{{ $order->id }}"></label>
            </div>
          </td>
          <td class="table-column-ps-0">
            <a href="{{ route('orders.show', $order) }}" class="fw-semibold">{{ $order->numero_orden }}</a>
            @if($order->codigo_seguimiento)
              <br><small class="text-muted">Track: {{ $order->codigo_seguimiento }}</small>
            @endif
          </td>
          <td>{{ $order->fecha_pedido->format('M d, Y, H:i') }}</td>
          <td>
            <div>
              <span class="d-block fw-semibold">{{ $order->nombre_cliente }}</span>
              <small class="text-muted">{{ $order->email_cliente }}</small>
            </div>
          </td>
          <td>
            <span class="badge {{ $order->estado_badge }} text-white">
              <span class="legend-indicator bg-{{ str_replace('bg-', '', $order->estado_badge) }}"></span>{{ $order->estado_texto }}
            </span>
          </td>
          <td>
            <span class="badge {{ $order->estado_pago_badge }} text-white">
              <span class="legend-indicator bg-{{ str_replace('bg-', '', $order->estado_pago_badge) }}"></span>{{ $order->estado_pago_texto }}
            </span>
          </td>
          <td>
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
              <span class="text-dark">{{ ucfirst(str_replace('_', ' ', $order->metodo_pago)) }}</span>
            </div>
          </td>
          <td>Q{{ number_format($order->total, 2) }}</td>
          <td>
            <a class="btn btn-primary btn-sm" href="{{ route('orders.show', $order) }}">
              <i class="bi-eye me-1"></i> Ver Orden
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" class="text-center">
            <div class="py-4">
              <div class="mb-3">
                <i class="bi-receipt text-body" style="font-size: 3rem;"></i>
              </div>
              <h4 class="text-body">No hay órdenes</h4>
              <p class="text-body">Las órdenes aparecerán aquí cuando los clientes realicen pedidos.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  <!-- Footer -->
  <div class="card-footer">
    <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
      <div class="col-sm mb-2 mb-sm-0">
        <div class="d-flex justify-content-center justify-content-sm-start align-items-center">
          <span class="me-2">Mostrando:</span>
          <span class="text-secondary me-2">{{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} de</span>
          <span id="datatableWithPaginationInfoTotalQty">{{ $orders->total() }}</span>
        </div>
      </div>
      <div class="col-sm-auto">
        <div class="d-flex justify-content-center justify-content-sm-end">
          {{ $orders->links() }}
        </div>
      </div>
    </div>
  </div>
</div>


@endsection

@section('scripts')
<script>
// Export orders
function exportOrders(format = 'csv') {
    const url = new URL('{{ route("orders.export") }}');
    
    // Add current filters to export
    const searchParams = new URLSearchParams(window.location.search);
    searchParams.forEach((value, key) => {
        url.searchParams.set(key, value);
    });
    
    window.open(url.toString(), '_blank');
}
</script>
@endsection 