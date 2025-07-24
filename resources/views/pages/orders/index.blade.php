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

      <h1 class="page-header-title">Órdenes <span class="badge bg-soft-dark text-dark ms-2">{{ $orders->total() }}</span></h1>

      <div class="d-flex mt-2">
        <a class="text-body me-3" href="javascript:;" onclick="exportOrders()">
          <i class="bi-download me-1"></i> Exportar
        </a>

        <!-- Dropdown -->
        <div class="dropdown">
          <a class="text-body" href="javascript:;" id="moreOptionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            Más opciones <i class="bi-chevron-down"></i>
          </a>

          <div class="dropdown-menu mt-1" aria-labelledby="moreOptionsDropdown">
            <a class="dropdown-item" href="javascript:;" onclick="exportOrders()">
              <i class="bi-download dropdown-item-icon"></i> Exportar datos
            </a>
          </div>
        </div>
        <!-- End Dropdown -->
      </div>
    </div>
    <!-- End Col -->
  </div>
  <!-- End Row -->

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
  <!-- End Stats Cards -->

  <!-- Nav Tabs -->
  <div class="js-nav-scroller hs-nav-scroller-horizontal mt-4">
    <ul class="nav nav-tabs page-header-tabs">
      <li class="nav-item">
        <a class="nav-link active" href="{{ route('orders.index') }}">Todas las órdenes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('orders.index', ['estado' => 'pendiente']) }}">Pendientes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('orders.index', ['estado_pago' => 'pendiente']) }}">Sin pagar</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('orders.index', ['estado' => 'entregado']) }}">Entregadas</a>
      </li>
    </ul>
  </div>
  <!-- End Nav Tabs -->
</div>
<!-- End Page Header -->

<!-- Filters -->
<div class="row justify-content-end mb-3">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Filtros</h5>
      </div>
      <div class="card-body">
        <form method="GET" class="row g-3">
          <div class="col-md-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
              <option value="">Todos los estados</option>
              <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
              <option value="confirmado" {{ request('estado') == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
              <option value="en_preparacion" {{ request('estado') == 'en_preparacion' ? 'selected' : '' }}>En Preparación</option>
              <option value="enviado" {{ request('estado') == 'enviado' ? 'selected' : '' }}>Enviado</option>
              <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
              <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
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
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End Filters -->

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
      <div class="input-group input-group-merge input-group-flush">
        <div class="input-group-prepend input-group-text">
          <i class="bi-search"></i>
        </div>
        <input id="datatableSearch" type="search" class="form-control" placeholder="Buscar órdenes..." aria-label="Search orders" value="{{ request('search') }}">
      </div>
    </div>

    <div class="d-grid d-sm-flex gap-2">
      <!-- Export Dropdown -->
      <div class="dropdown">
        <button type="button" class="btn btn-white btn-sm dropdown-toggle w-100" id="ordersExportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi-download me-2"></i> Exportar
        </button>

        <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="ordersExportDropdown">
          <span class="dropdown-header">Opciones</span>
          <a class="dropdown-item" href="javascript:;" onclick="exportOrders('csv')">
            <i class="bi-filetype-csv me-2"></i> CSV
          </a>
          <a class="dropdown-item" href="javascript:;" onclick="exportOrders('excel')">
            <i class="bi-file-earmark-excel me-2"></i> Excel
          </a>
        </div>
      </div>
      <!-- End Export Dropdown -->


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
  <!-- End Table -->

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
  <!-- End Footer -->
</div>
<!-- End Card -->

@endsection

@section('scripts')
<!-- DataTables JS -->
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Search functionality
    $('#datatableSearch').on('keyup', function() {
        const searchValue = $(this).val();
        if (searchValue.length > 2 || searchValue.length === 0) {
            const url = new URL(window.location);
            if (searchValue) {
                url.searchParams.set('search', searchValue);
            } else {
                url.searchParams.delete('search');
            }
            window.location.href = url.toString();
        }
    });
});

// No se necesitan funciones adicionales ya que todo se maneja en la vista show

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