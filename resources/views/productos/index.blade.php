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
  <div class="row align-items-center">
    <div class="col-sm mb-2 mb-sm-0">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-no-gutter">
          <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Productos</li>
        </ol>
      </nav>

      <h1 class="page-header-title">Gestión de Productos</h1>
      <p class="page-header-text">Administra tu inventario de productos de manera eficiente</p>
    </div>
    
    <div class="col-sm-auto">
      <div class="d-grid d-sm-flex gap-2">
        <a class="btn btn-primary" href="{{ route('productos.create') }}">
          <i class="bi-plus me-1"></i> Agregar Producto
        </a>
        <div class="dropdown">
          <button type="button" class="btn btn-white dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi-gear me-1"></i> Más opciones
          </button>
          <div class="dropdown-menu dropdown-menu-end">
            <a class="dropdown-item" href="#">
              <i class="bi-upload me-2"></i> Importar productos
            </a>
            <a class="dropdown-item" href="#">
              <i class="bi-tags me-2"></i> Gestionar categorías
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <i class="bi-graph-up me-2"></i> Reportes de inventario
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Page Header -->

<!-- Stats Cards -->
<div class="row mb-4">
  <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
    <div class="card h-100">
      <div class="card-body">
        <h6 class="card-subtitle mb-2">Total Productos</h6>
        <div class="row align-items-center gx-2">
          <div class="col">
            <span class="js-counter display-4 text-dark">{{ $productos->count() }}</span>
          </div>
          <div class="col-auto">
            <i class="bi-box-seam text-primary" style="font-size: 2rem;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
    <div class="card h-100">
      <div class="card-body">
        <h6 class="card-subtitle mb-2">Stock Bajo</h6>
        <div class="row align-items-center gx-2">
          <div class="col">
            <span class="js-counter display-4 text-warning">{{ $productos->where('estado_stock', 'stock_bajo')->count() }}</span>
          </div>
          <div class="col-auto">
            <i class="bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
    <div class="card h-100">
      <div class="card-body">
        <h6 class="card-subtitle mb-2">Sin Stock</h6>
        <div class="row align-items-center gx-2">
          <div class="col">
            <span class="js-counter display-4 text-danger">{{ $productos->where('estado_stock', 'sin_stock')->count() }}</span>
          </div>
          <div class="col-auto">
            <i class="bi-x-circle text-danger" style="font-size: 2rem;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
    <div class="card h-100">
      <div class="card-body">
        <h6 class="card-subtitle mb-2">Categorías</h6>
        <div class="row align-items-center gx-2">
          <div class="col">
            <span class="js-counter display-4 text-info">{{ $categorias->count() }}</span>
          </div>
          <div class="col-auto">
            <i class="bi-tags text-info" style="font-size: 2rem;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Stats Cards -->

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
      <div class="input-group input-group-merge navbar-input-group">
        <div class="input-group-prepend input-group-text">
          <i class="bi-search"></i>
        </div>
        <input type="search" class="form-control" placeholder="Buscar productos..." aria-label="Buscar productos" id="datatableSearch">
      </div>
    </div>

    <div class="d-grid d-sm-flex gap-2">
      <!-- Filter -->
      <div class="dropdown">
        <button type="button" class="btn btn-white btn-sm dropdown-toggle" id="usersFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi-filter me-1"></i> Filtrar
        </button>

        <div class="dropdown-menu dropdown-menu-sm-end dropdown-card card-dropdown-filter-centered" aria-labelledby="usersFilterDropdown" style="min-width: 22rem;">
          <div class="card">
            <div class="card-header card-header-content-between">
              <h5 class="card-header-title">Filtros</h5>
              <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm ms-2" id="clearFilters">
                <i class="bi-x-lg"></i>
              </button>
            </div>

            <div class="card-body">
              <form id="filterForm">
                <div class="mb-4">
                  <small class="text-cap text-body">Categoría</small>
                  <select class="form-select" name="categoria" id="filtroCategoria">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $categoria)
                      <option value="{{ $categoria }}">{{ $categoria }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="mb-4">
                  <small class="text-cap text-body">Estado de Stock</small>
                  <select class="form-select" name="estado_stock" id="filtroStock">
                    <option value="">Todos los estados</option>
                    <option value="sin_stock">Sin Stock</option>
                    <option value="stock_bajo">Stock Bajo</option>
                    <option value="stock_normal">Stock Normal</option>
                  </select>
                </div>

                <div class="d-grid">
                  <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- End Filter -->

      <!-- Export -->
      <div class="dropdown">
        <button type="button" class="btn btn-white btn-sm dropdown-toggle" id="usersExportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi-download me-2"></i> Exportar
        </button>

        <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="usersExportDropdown">
          <span class="dropdown-header">Opciones</span>
          <a class="dropdown-item" href="#" id="export-excel">
            <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('front-dashboard-v2.1.1/src/assets/svg/brands/excel-icon.svg') }}" alt="Excel">
            Excel
          </a>
          <a class="dropdown-item" href="#" id="export-csv">
            <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('front-dashboard-v2.1.1/src/assets/svg/components/placeholder-csv-format.svg') }}" alt="CSV">
            CSV
          </a>
          <a class="dropdown-item" href="#" id="export-pdf">
            <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('front-dashboard-v2.1.1/src/assets/svg/brands/pdf-icon.svg') }}" alt="PDF">
            PDF
          </a>
        </div>
      </div>
      <!-- End Export -->
    </div>
  </div>
  <!-- End Header -->

  <!-- Table -->
  <div class="table-responsive datatable-custom">
    <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table" data-hs-datatables-options='{
                    "columnDefs": [{
                       "targets": [0, 7],
                       "orderable": false
                     }],
                    "order": [],
                    "info": {
                      "totalQty": "#datatableWithPaginationInfoTotalQty"
                    },
                    "search": "#datatableSearch",
                    "entries": "#datatableEntries",
                    "pageLength": 15,
                    "isResponsive": false,
                    "isShowPaging": false,
                    "pagination": "datatablePagination"
                  }'>
      <thead class="thead-light">
        <tr>
          <th class="table-column-pe-0">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="datatableCheckAll">
              <label class="form-check-label" for="datatableCheckAll"></label>
            </div>
          </th>
          <th class="table-column-ps-0">Código</th>
          <th>Producto</th>
          <th>Marca</th>
          <th>Categoría</th>
          <th>Precio</th>
          <th>Stock</th>
          <th>Acciones</th>
        </tr>
      </thead>

      <tbody>
        @foreach($productos as $producto)
        <tr>
          <td class="table-column-pe-0">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="{{ $producto->id }}" id="productosCheck{{ $producto->id }}">
              <label class="form-check-label" for="productosCheck{{ $producto->id }}"></label>
            </div>
          </td>
          <td class="table-column-ps-0">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0">
                @if($producto->imagen)
                  <div class="avatar avatar-xs">
                    <img class="avatar-img" src="{{ Storage::url($producto->imagen) }}" alt="{{ $producto->nombre }}">
                  </div>
                @else
                  <div class="avatar avatar-xs avatar-soft-primary">
                    <span class="avatar-initials">{{ strtoupper(substr($producto->nombre, 0, 1)) }}</span>
                  </div>
                @endif
              </div>
              <div class="flex-grow-1 ms-3">
                <h5 class="text-inherit mb-0">{{ $producto->codigo_interno }}</h5>
                @if($producto->codigo_barras)
                  <span class="d-block fs-6 text-body">{{ $producto->codigo_barras }}</span>
                @endif
              </div>
            </div>
          </td>
          <td>
            <div>
              <span class="d-block h5 text-inherit mb-0">{{ $producto->nombre }}</span>
              @if($producto->descripcion)
                <span class="d-block fs-6 text-body">{{ Str::limit($producto->descripcion, 50) }}</span>
              @endif
            </div>
          </td>
          <td>
            <span class="badge bg-soft-secondary text-secondary">{{ $producto->marca ?? 'Sin marca' }}</span>
          </td>
          <td>
            <span class="badge bg-soft-primary text-primary">{{ $producto->categoria }}</span>
          </td>
          <td>
            <div>
              <span class="text-dark fw-semibold">${{ number_format($producto->precio_venta, 2) }}</span>
              @if($producto->precio_mayoreo)
                <span class="d-block fs-6 text-body">Mayor: ${{ number_format($producto->precio_mayoreo, 2) }}</span>
              @endif
            </div>
          </td>
          <td>
            <div class="d-flex align-items-center">
              <span class="badge bg-soft-{{ $producto->estado_stock_color }} text-{{ $producto->estado_stock_color }} me-2">
                {{ $producto->stock_actual }} {{ $producto->unidad_medida }}
              </span>
              @if($producto->estado_stock !== 'stock_normal')
                <i class="bi-exclamation-triangle-fill text-{{ $producto->estado_stock_color }}"></i>
              @endif
            </div>
            <small class="text-body">Mín: {{ $producto->stock_minimo }}</small>
          </td>
          <td>
            <div class="btn-group" role="group">
              <a class="btn btn-white btn-sm" href="{{ route('productos.show', $producto) }}" data-bs-toggle="tooltip" title="Ver detalles">
                <i class="bi-eye"></i>
              </a>
              <a class="btn btn-white btn-sm" href="{{ route('productos.edit', $producto) }}" data-bs-toggle="tooltip" title="Editar">
                <i class="bi-pencil"></i>
              </a>
              
              <!-- Dropdown -->
              <div class="btn-group">
                <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="productsEditDropdown{{ $producto->id }}" data-bs-toggle="dropdown" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="productsEditDropdown{{ $producto->id }}">
                  <a class="dropdown-item" href="#" onclick="updateStock({{ $producto->id }})">
                    <i class="bi-arrow-up-circle dropdown-item-icon"></i> Actualizar Stock
                  </a>
                  @if($producto->activo)
                    <a class="dropdown-item" href="#" onclick="toggleStatus({{ $producto->id }}, false)">
                      <i class="bi-eye-slash dropdown-item-icon"></i> Desactivar
                    </a>
                  @else
                    <a class="dropdown-item" href="#" onclick="toggleStatus({{ $producto->id }}, true)">
                      <i class="bi-eye dropdown-item-icon"></i> Activar
                    </a>
                  @endif
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item text-danger" href="#" onclick="deleteProduct({{ $producto->id }}, '{{ $producto->nombre }}')">
                    <i class="bi-trash dropdown-item-icon"></i> Eliminar
                  </a>
                </div>
              </div>
            </div>
          </td>
        </tr>
        @endforeach
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

          <!-- Select -->
          <div class="tom-select-custom">
            <select id="datatableEntries" class="js-select form-select form-select-borderless w-auto" autocomplete="off" data-hs-tom-select-options='{
                            "searchInDropdown": false,
                            "hideSearch": true
                          }'>
              <option value="10">10</option>
              <option value="15" selected>15</option>
              <option value="20">20</option>
            </select>
          </div>
          <!-- End Select -->

          <span class="text-secondary me-2">de</span>

          <!-- Pagination Quantity -->
          <span id="datatableWithPaginationInfoTotalQty"></span>
        </div>
      </div>
      <!-- End Col -->

      <div class="col-sm-auto">
        <div class="d-flex justify-content-center justify-content-sm-end">
          <!-- Pagination -->
          <nav id="datatablePagination" aria-label="Activity pagination"></nav>
        </div>
      </div>
      <!-- End Col -->
    </div>
    <!-- End Row -->
  </div>
  <!-- End Footer -->
</div>
<!-- End Card -->

<!-- Modal para actualizar stock -->
<div class="modal fade" id="updateStockModal" tabindex="-1" aria-labelledby="updateStockModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateStockModalLabel">Actualizar Stock</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="updateStockForm">
          <input type="hidden" id="producto_id">
          <div class="mb-3">
            <label for="stock_actual" class="form-label">Nuevo Stock</label>
            <input type="number" class="form-control" id="stock_actual" min="0" required>
          </div>
          <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones (Opcional)</label>
            <textarea class="form-control" id="observaciones" rows="3"></textarea>
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
<!-- DataTables JS -->
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

<!-- DataTables Initialization -->
<script>
$(document).ready(function() {
    // Initialize DataTable
    const datatable = $('#datatable').DataTable({
        responsive: true,
        searching: true,
        ordering: true,
        info: true,
        lengthChange: true,
        pageLength: 15,
        language: {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="bi-file-earmark-excel me-1"></i> Excel',
                className: 'btn btn-white btn-sm'
            },
            {
                extend: 'csv',
                text: '<i class="bi-file-earmark-text me-1"></i> CSV',
                className: 'btn btn-white btn-sm'
            },
            {
                extend: 'pdf',
                text: '<i class="bi-file-earmark-pdf me-1"></i> PDF',
                className: 'btn btn-white btn-sm'
            }
        ]
    });

    // Custom search
    $('#datatableSearch').on('keyup', function() {
        datatable.search(this.value).draw();
    });

    // Filter form
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        // Apply filters here - you can implement custom filtering logic
        const categoria = $('#filtroCategoria').val();
        const stock = $('#filtroStock').val();
        
        // For now, we'll use simple column filtering
        if (categoria) {
            datatable.column(4).search(categoria);
        } else {
            datatable.column(4).search('');
        }
        
        datatable.draw();
    });

    // Clear filters
    $('#clearFilters').on('click', function() {
        $('#filterForm')[0].reset();
        datatable.search('').columns().search('').draw();
    });

    // Export buttons
    $('#export-excel').on('click', function(e) {
        e.preventDefault();
        datatable.button('.buttons-excel').trigger();
    });

    $('#export-csv').on('click', function(e) {
        e.preventDefault();
        datatable.button('.buttons-csv').trigger();
    });

    $('#export-pdf').on('click', function(e) {
        e.preventDefault();
        datatable.button('.buttons-pdf').trigger();
    });

    // Tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});

// Update stock function
function updateStock(productId) {
    $('#producto_id').val(productId);
    $('#updateStockModal').modal('show');
}

function saveStock() {
    const productId = $('#producto_id').val();
    const stock = $('#stock_actual').val();
    const observaciones = $('#observaciones').val();
    
    if (!stock) {
        alert('Por favor ingrese el nuevo stock');
        return;
    }
    
    // Here you would make an AJAX call to update the stock
    $.ajax({
        url: `/productos/${productId}/stock`,
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

// Delete product function
function deleteProduct(productId, productName) {
    if (confirm(`¿Está seguro de eliminar el producto "${productName}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/productos/${productId}`;
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        const tokenField = document.createElement('input');
        tokenField.type = 'hidden';
        tokenField.name = '_token';
        tokenField.value = '{{ csrf_token() }}';
        
        form.appendChild(methodField);
        form.appendChild(tokenField);
        document.body.appendChild(form);
        form.submit();
    }
}

// Toggle status function
function toggleStatus(productId, newStatus) {
    // Implementation for toggling product active status
    const action = newStatus ? 'activar' : 'desactivar';
    if (confirm(`¿Está seguro de ${action} este producto?`)) {
        // Make AJAX call to update status
        location.reload();
    }
}
</script>
@endsection 