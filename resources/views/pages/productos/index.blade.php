@extends('layouts.app')

@section('styles')
<!-- DataTables CSS via CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<style>
/* Ensure table doesn't wrap and stays in one row - IMPROVED */
#datatable {
    table-layout: fixed !important;
    width: 100% !important;
    font-size: 0.8rem;
    border-collapse: collapse !important;
}

#datatable th, #datatable td {
    white-space: nowrap !important;
    overflow: visible !important; /* Changed from hidden to visible for dropdowns */
    text-overflow: ellipsis !important;
    padding: 0.4rem 0.2rem !important;
    vertical-align: middle !important;
    border: none !important;
    position: relative !important; /* Added for dropdown positioning */
}

/* Make action buttons more compact */
#datatable .btn-sm {
    padding: 0.2rem 0.35rem !important;
    font-size: 0.7rem !important;
    margin: 0 !important;
    line-height: 1.2 !important;
}

/* Compact button group */
#datatable .btn-group {
    display: inline-flex !important;
    gap: 0 !important;
    position: relative !important; /* Added for dropdown positioning */
}

/* Dropdown menu fixes */
#datatable .dropdown-menu {
    position: absolute !important;
    z-index: 10000 !important;
    min-width: 140px !important;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    border: 1px solid rgba(0, 0, 0, 0.15) !important;
    background: white !important;
    display: none !important;
}

/* Ensure dropdown is visible when shown */
#datatable .dropdown-menu.show {
    display: block !important;
}

/* Estado badge more compact */
#datatable .badge {
    font-size: 0.65rem !important;
    padding: 0.2rem 0.4rem !important;
    white-space: nowrap !important;
}

/* Avatar smaller */
#datatable .avatar-xs {
    width: 1.25rem !important;
    height: 1.25rem !important;
    font-size: 0.6rem !important;
}

/* Product name and description more compact */
#datatable h6 {
    font-size: 0.8rem !important;
    margin-bottom: 0 !important;
    line-height: 1.2 !important;
}

#datatable small {
    font-size: 0.65rem !important;
    line-height: 1.1 !important;
}

/* Fixed column widths that work better */
#datatable th:nth-child(1), #datatable td:nth-child(1) { width: 40px !important; min-width: 40px !important; } /* Checkbox */
#datatable th:nth-child(2), #datatable td:nth-child(2) { width: 200px !important; min-width: 180px !important; } /* Producto */
#datatable th:nth-child(3), #datatable td:nth-child(3) { width: 90px !important; min-width: 80px !important; } /* Código */
#datatable th:nth-child(4), #datatable td:nth-child(4) { width: 80px !important; min-width: 70px !important; } /* Marca */
#datatable th:nth-child(5), #datatable td:nth-child(5) { width: 90px !important; min-width: 80px !important; } /* Categoría */
#datatable th:nth-child(6), #datatable td:nth-child(6) { width: 90px !important; min-width: 80px !important; } /* Precio */
#datatable th:nth-child(7), #datatable td:nth-child(7) { width: 100px !important; min-width: 90px !important; } /* Stock */
#datatable th:nth-child(8), #datatable td:nth-child(8) { width: 70px !important; min-width: 65px !important; } /* Estado */
#datatable th:nth-child(9), #datatable td:nth-child(9) { 
    width: 110px !important; 
    min-width: 105px !important; 
    overflow: visible !important; /* Special case for actions column */
} /* Acciones */

/* Container adjustments */
.table-responsive {
    overflow-x: auto !important;
    overflow-y: visible !important; /* Allow dropdowns to show */
}

/* DataTables wrapper adjustments */
.dataTables_wrapper {
    overflow: visible !important;
    padding-bottom: 60px !important; /* Extra space for dropdowns */
}

/* Card adjustments to prevent clipping */
.card {
    overflow: visible !important;
}

.card-body {
    overflow: visible !important;
}

/* Force minimum table width */
#datatable {
    min-width: 950px !important;
}

/* Responsive font scaling */
@media (max-width: 1400px) {
    #datatable {
        font-size: 0.75rem !important;
        min-width: 900px !important;
    }
    
    #datatable .btn-sm {
        padding: 0.15rem 0.3rem !important;
        font-size: 0.65rem !important;
    }
    
    #datatable .badge {
        font-size: 0.6rem !important;
        padding: 0.15rem 0.3rem !important;
    }
}

@media (max-width: 1200px) {
    #datatable {
        font-size: 0.7rem !important;
        min-width: 850px !important;
    }
    
    #datatable th, #datatable td {
        padding: 0.3rem 0.15rem !important;
    }
    
    #datatable .btn-sm {
        padding: 0.1rem 0.25rem !important;
        font-size: 0.6rem !important;
    }
}
</style>
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
  </div>
</div>
<!-- End Page Header -->

<!-- Stats Cards -->
<div class="row">
  <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
    <div class="card h-100">
      <div class="card-body">
        <h6 class="card-subtitle mb-2">Total Productos</h6>
        <div class="row align-items-center gx-2">
          <div class="col">
            <span class="js-counter display-4 text-primary">{{ $productos->count() }}</span>
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
      <!-- Botón Crear Producto -->
      <a class="btn btn-primary" href="{{ route('productos.create') }}">
        <i class="bi-plus me-1"></i> Nuevo Producto
                    </a>

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
                <div class="row">
                  <div class="col-sm-12">
                    <div class="mb-4">
                      <span class="text-cap text-body">Categoría</span>
                      <select class="form-select" name="categoria" id="filterCategoria">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                          <option value="{{ $categoria }}">{{ $categoria }}</option>
                        @endforeach
                      </select>
                  </div>
                    </div>

                  <div class="col-sm-12">
                    <div class="mb-4">
                      <span class="text-cap text-body">Estado de Stock</span>
                      <select class="form-select" name="estado_stock" id="filterStock">
                        <option value="">Todos los estados</option>
                        <option value="sin_stock">Sin Stock</option>
                        <option value="stock_bajo">Stock Bajo</option>
                        <option value="stock_normal">Stock Normal</option>
                      </select>
                      </div>
                    </div>
                  </div>

                <div class="d-flex justify-content-end">
                  <button type="button" class="btn btn-white me-2" id="clearFilters">Limpiar</button>
                  <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                  </div>
              </form>
                    </div>
                    </div>
                  </div>
                      </div>
      <!-- End Filter -->
                    </div>
                  </div>
  <!-- End Header -->

  <!-- Table -->
  <div class="table-responsive datatable-custom">
    <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table table-sm" data-hs-datatables-options='{
                    "columnDefs": [{
                       "targets": [0, 8],
                       "orderable": false
                     }],
                    "order": [],
                    "info": {
                      "totalQty": "#datatableWithPaginationInfoTotalQty"
                    },
                    "search": "#datatableSearch",
                    "entries": "#datatableEntries",
                    "pageLength": 15,
                    "scrollX": true,
                    "autoWidth": false,
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
          <th class="table-column-ps-0">Producto</th>
          <th>Código</th>
          <th>Marca</th>
          <th>Categoría</th>
          <th>Precio Venta</th>
          <th>Stock</th>
          <th>Estado</th>
          <th>Acciones</th>
              </tr>
      </thead>

      <tbody>
        @forelse($productos as $producto)
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
                @if($producto->imagen_principal_url)
                  <div class="avatar avatar-xs">
                    <img class="avatar-img" src="{{ $producto->imagen_principal_url }}" alt="{{ $producto->nombre }}" style="object-fit: contain; background: #f8f9fa;">
                    </div>
                @else
                  <div class="avatar avatar-xs avatar-soft-primary">
                    <span class="avatar-initials">{{ strtoupper(substr($producto->nombre, 0, 2)) }}</span>
                    </div>
                @endif
                    </div>
                    <div class="flex-grow-1 ms-2">
                <h6 class="text-inherit mb-0 small">{{ Str::limit($producto->nombre, 20) }}</h6>
                @if($producto->descripcion)
                  <small class="text-body">{{ Str::limit($producto->descripcion, 25) }}</small>
                @endif
                    </div>
                  </div>
                </td>
                <td>
            <div>
              <span class="d-block fw-semibold">{{ $producto->codigo_interno }}</span>
              @if($producto->codigo_barras)
                <small class="text-muted">{{ $producto->codigo_barras }}</small>
              @endif
                  </div>
                </td>
          <td>
            @if($producto->marca)
              <span class="badge bg-soft-secondary text-secondary">{{ $producto->marca }}</span>
            @else
              <span class="text-muted">—</span>
            @endif
                </td>
          <td>
            <span class="badge bg-soft-primary text-primary">{{ $producto->categoria }}</span>
                </td>
          <td>
              <span class="text-dark fw-semibold">Q{{ number_format($producto->precio_venta, 2) }}</span>
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
            <span class="badge {{ $producto->activo ? 'bg-success' : 'bg-danger' }}">
              {{ $producto->activo ? 'Activo' : 'Inactivo' }}
            </span>
                </td>
                <td>
            <div class="btn-group btn-group-sm" role="group">
              <a class="btn btn-white btn-sm" href="{{ route('productos.show', $producto) }}" data-bs-toggle="tooltip" title="Ver">
                <i class="bi-eye"></i>
                        </a>
              <a class="btn btn-white btn-sm" href="{{ route('productos.edit', $producto) }}" data-bs-toggle="tooltip" title="Editar">
                <i class="bi-pencil"></i>
                    </a>
              <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-white btn-sm dropdown-toggle dropdown-toggle-empty" 
                        id="productsEditDropdown{{ $producto->id }}" 
                        data-bs-toggle="dropdown" 
                        data-bs-boundary="viewport"
                        data-bs-auto-close="true"
                        aria-expanded="false">
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="productsEditDropdown{{ $producto->id }}">
                  @if($producto->activo)
                    <li><a class="dropdown-item" href="#" onclick="toggleStatus({{ $producto->id }}, false)">
                      <i class="bi-eye-slash dropdown-item-icon"></i> Desactivar
                    </a></li>
                  @else
                    <li><a class="dropdown-item" href="#" onclick="toggleStatus({{ $producto->id }}, true)">
                      <i class="bi-eye dropdown-item-icon"></i> Activar
                    </a></li>
                  @endif
                </ul>
                    </div>
                  </div>
                </td>
              </tr>
        @empty
              <tr>
          <td colspan="9" class="text-center">
            <div class="py-4">
              <div class="mb-3">
                <i class="bi-box-seam text-body" style="font-size: 3rem;"></i>
                  </div>
              <h4 class="text-body">No hay productos registrados</h4>
              <p class="text-body">Comienza agregando tu primer producto al inventario.</p>
              <a href="{{ route('productos.create') }}" class="btn btn-primary">
                <i class="bi-plus me-1"></i> Agregar Primer Producto
              </a>
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

                <!-- Select -->
                <div class="tom-select-custom">
            <select id="datatableEntries" class="js-select form-select form-select-borderless w-auto" autocomplete="off" data-hs-tom-select-options='{
                            "searchInDropdown": false,
                            "hideSearch": true
                          }'>
                    <option value="12">12</option>
                    <option value="14" selected>14</option>
                    <option value="16">16</option>
                    <option value="18">18</option>
                  </select>
                </div>
                <!-- End Select -->

          <span class="text-secondary me-2">de</span>

                <!-- Pagination Quantity -->
          <span id="datatableWithPaginationInfoTotalQty">{{ $productos->count() }}</span>
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

@endsection

@section('scripts')
<!-- DataTables JS via CDN -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#datatable').DataTable({
        responsive: false,
        pageLength: 15,
        scrollX: true,
        autoWidth: false,
        language: {
            search: "",
            searchPlaceholder: "Buscar productos...",
            lengthMenu: "Mostrar _MENU_ productos",
            info: "Mostrando _START_ a _END_ de _TOTAL_ productos",
            infoEmpty: "Mostrando 0 a 0 de 0 productos",
            infoFiltered: "(filtrado de _MAX_ productos totales)",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            },
            emptyTable: "No hay productos disponibles"
        },
        columnDefs: [{
            targets: [0, 8], // checkbox and actions columns
            orderable: false
        }],
        drawCallback: function() {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Remove any existing dropdown initialization
            $('[data-bs-toggle="dropdown"]').removeClass('dropdown-initialized');
            
            // Force re-initialization of all dropdowns
            setTimeout(function() {
                $('[data-bs-toggle="dropdown"]').each(function() {
                    try {
                        // Destroy existing dropdown instance if any
                        const existingDropdown = bootstrap.Dropdown.getInstance(this);
                        if (existingDropdown) {
                            existingDropdown.dispose();
                        }
                        // Create new dropdown instance
                        new bootstrap.Dropdown(this);
                    } catch (e) {
                        console.log('Dropdown initialization:', e);
                    }
                });
            }, 100);
        }
    });

    // Custom search input
    $('#datatableSearch').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Page length change
    $('#datatableEntries').on('change', function() {
        table.page.len($(this).val()).draw();
    });

    // Category filter
    $('#filterCategoria').on('change', function() {
        const value = $(this).val();
        if (value) {
            table.column(4).search('^' + value + '$', true, false).draw(); // Category column (index 4)
        } else {
            table.column(4).search('').draw();
        }
    });

    // Stock status filter
    $('#filterStock').on('change', function() {
        const value = $(this).val();
        if (value) {
            let searchTerm = '';
            switch(value) {
                case 'sin_stock':
                    searchTerm = '0 ';
                    break;
                case 'stock_bajo':
                    searchTerm = 'exclamation-triangle';
                    break;
                case 'stock_normal':
                    searchTerm = '^(?!.*exclamation-triangle)(?!.*0 ).*';
                    break;
            }
            table.column(6).search(searchTerm, true, false).draw(); // Stock column (index 6)
        } else {
            table.column(6).search('').draw();
        }
    });

    // Filter form submit
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        // Filters are already applied via change events above
    });

    // Clear filters
    $('#clearFilters').on('click', function() {
        $('#filterForm')[0].reset();
        table.columns().search('').draw();
    });

    // Update pagination info
    table.on('draw', function() {
        const info = table.page.info();
        $('#datatableWithPaginationInfoTotalQty').text(info.recordsDisplay);
    });

    // Handle dropdown clicks specifically for DataTables
    $(document).on('click', '[data-bs-toggle="dropdown"]', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Close all other dropdowns first
        $('.dropdown-menu.show').removeClass('show');
        
        // Toggle this dropdown
        const dropdownMenu = $(this).next('.dropdown-menu');
        dropdownMenu.toggleClass('show');
        
        // Close dropdown when clicking outside
        $(document).on('click.dropdown-close', function(event) {
            if (!$(event.target).closest('.btn-group').length) {
                $('.dropdown-menu.show').removeClass('show');
                $(document).off('click.dropdown-close');
            }
        });
    });

    // Handle clicks on dropdown items
    $(document).on('click', '.dropdown-item', function(e) {
        // Allow the click to proceed, then close the dropdown
        setTimeout(function() {
            $('.dropdown-menu.show').removeClass('show');
        }, 100);
    });
});

// Toggle status function
function toggleStatus(productId, newStatus) {
    const action = newStatus ? 'activar' : 'desactivar';
    if (confirm(`¿Está seguro de ${action} este producto?`)) {
        $.ajax({
            url: `/productos/${productId}`,
            method: 'PUT',
            data: {
                activo: newStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert('Error al cambiar el estado del producto');
            }
        });
    }
}
</script>
@endsection