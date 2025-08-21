@extends('layouts.app')

@section('styles')
    <!-- DataTables CSS via CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

    <style>
        #datatable .dropdown-menu {
            position: fixed !important;
            z-index: 10000;
            min-width: auto;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(0, 0, 0, 0.15);
            background: white;
            /* display: none !important;  <-- QUÍTALO */
        }

        #datatable .dropdown-menu.show {
            display: block;
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
                            <span
                                class="js-counter display-4 text-warning">{{ $productos->where('estado_stock', 'stock_bajo')->count() }}</span>
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
                            <span
                                class="js-counter display-4 text-danger">{{ $productos->where('estado_stock', 'sin_stock')->count() }}</span>
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
    @if (session('success'))
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
                    <div class="input-group input-group-merge navbar-input-group">
                        <div class="input-group-prepend input-group-text">
                            <i class="bi-search"></i>
                        </div>
                        <input name="search" type="search" class="form-control" placeholder="Buscar por nombre, código, marca, proveedor o ubicación..." aria-label="Buscar productos" value="{{ request('search') }}">
                    </div>
                </form>
            </div>

            <div class="d-grid d-sm-flex gap-2">
                <!-- Botón Crear Producto -->
                <a class="btn btn-primary" href="{{ route('productos.create') }}">
                    <i class="bi-plus me-1"></i> Nuevo Producto
                </a>
            </div>
        </div>
        <div class="card-body">
        <form method="GET" class="row g-3">
          <div class="col-md-2">
            <label class="form-label">Categoría</label>
            <select name="categoria" class="form-select">
              <option value="">Todas las categorías</option>
              @foreach($categorias as $categoria)
                <option value="{{ $categoria }}" {{ request('categoria') == $categoria ? 'selected' : '' }}>{{ $categoria }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label">Estado</label>
            <select name="activo" class="form-select">
              <option value="">Todos</option>
              <option value="1" {{ request('activo') == '1' ? 'selected' : '' }}>Activo</option>
              <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivo</option>
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label">Estado Stock</label>
            <select name="estado_stock" class="form-select">
              <option value="">Todos</option>
              <option value="agotado" {{ request('estado_stock') == 'agotado' ? 'selected' : '' }}>Agotado (0)</option>
              <option value="critico" {{ request('estado_stock') == 'critico' ? 'selected' : '' }}>Crítico (≤5)</option>
              <option value="bajo" {{ request('estado_stock') == 'bajo' ? 'selected' : '' }}>Bajo (6-10)</option>
              <option value="normal" {{ request('estado_stock') == 'normal' ? 'selected' : '' }}>Normal (11-50)</option>
              <option value="alto" {{ request('estado_stock') == 'alto' ? 'selected' : '' }}>Alto (>50)</option>
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label">Requiere Receta</label>
            <select name="requiere_receta" class="form-select">
              <option value="">Todos</option>
              <option value="1" {{ request('requiere_receta') == '1' ? 'selected' : '' }}>Sí</option>
              <option value="0" {{ request('requiere_receta') == '0' ? 'selected' : '' }}>No</option>
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label">Vencimiento</label>
            <select name="vencimiento" class="form-select">
              <option value="">Todos</option>
              <option value="vencido" {{ request('vencimiento') == 'vencido' ? 'selected' : '' }}>Vencidos</option>
              <option value="proximo" {{ request('vencimiento') == 'proximo' ? 'selected' : '' }}>Próximos (30 días)</option>
              <option value="vigente" {{ request('vencimiento') == 'vigente' ? 'selected' : '' }}>Vigentes</option>
            </select>
          </div>

          <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Filtrar</button>
            <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Limpiar</a>
          </div>
        </form>
      </div>
        <!-- End Header -->

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
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
                                    <input class="form-check-input" type="checkbox" value="{{ $producto->id }}"
                                        id="productosCheck{{ $producto->id }}">
                                    <label class="form-check-label" for="productosCheck{{ $producto->id }}"></label>
                                </div>
                            </td>
                            <td class="table-column-ps-0">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        @if ($producto->imagen_principal_url)
                                            <div class="avatar avatar-xs">
                                                <img class="avatar-img" src="{{ $producto->imagen_principal_url }}"
                                                    alt="{{ $producto->nombre }}"
                                                    style="object-fit: contain; background: #f8f9fa;">
                                            </div>
                                        @else
                                            <div class="avatar avatar-xs avatar-soft-primary">
                                                <span
                                                    class="avatar-initials">{{ strtoupper(substr($producto->nombre, 0, 2)) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h6 class="text-inherit mb-0 small">{{ Str::limit($producto->nombre, 20) }}</h6>
                                        @if ($producto->descripcion)
                                            <small class="text-body">{{ Str::limit($producto->descripcion, 25) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span class="d-block fw-semibold">{{ $producto->codigo_interno }}</span>
                                    @if ($producto->codigo_barras)
                                        <small class="text-muted">{{ $producto->codigo_barras }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php($brandName = optional($producto->brand)->nombre ?? $producto->marca)
                                @if ($brandName)
                                    <span class="badge bg-soft-secondary text-secondary">{{ $brandName }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @php($categoryName = optional($producto->category)->nombre ?? $producto->categoria)
                                <span class="badge bg-soft-primary text-primary">{{ $categoryName }}</span>
                            </td>
                            <td>
                                <span
                                    class="text-dark fw-semibold">Q{{ number_format($producto->precio_venta, 2) }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span
                                        class="badge bg-soft-{{ $producto->estado_stock_color }} text-{{ $producto->estado_stock_color }} me-2">
                                        {{ $producto->stock_actual }} {{ $producto->unidad_medida }}
                                    </span>
                                    @if ($producto->estado_stock !== 'stock_normal')
                                        <i
                                            class="bi-exclamation-triangle-fill text-{{ $producto->estado_stock_color }}"></i>
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
                                    <a class="btn btn-white btn-sm" href="{{ route('productos.show', $producto) }}"
                                        data-bs-toggle="tooltip" title="Ver">
                                        <i class="bi-eye"></i>
                                    </a>
                                    <a class="btn btn-white btn-sm" href="{{ route('productos.edit', $producto) }}"
                                        data-bs-toggle="tooltip" title="Editar">
                                        <i class="bi-pencil"></i>
                                    </a>
                                    <div class="btn-group btn-group-sm position-relative" role="group">
                                        <button type="button"
                                            class="btn btn-white btn-sm dropdown-toggle dropdown-toggle-empty"
                                            id="productsEditDropdown{{ $producto->id }}" data-bs-toggle="dropdown"
                                            data-bs-boundary="viewport" data-bs-auto-close="true" aria-expanded="false">
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end"
                                            aria-labelledby="productsEditDropdown{{ $producto->id }}">
                                            <li>
                                                <a class="dropdown-item" href="#"
                                                    onclick="updateStock({{ $producto->id }}, '{{ $producto->nombre }}', {{ $producto->stock_actual }}, '{{ $producto->unidad_medida }}')">
                                                    <i class="bi-arrow-up-circle dropdown-item-icon"></i> Actualizar Stock
                                                </a>
                                            </li>
                                            @if ($producto->activo)
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                        onclick="toggleStatus({{ $producto->id }}, false)">
                                                        <i class="bi-eye-slash dropdown-item-icon"></i> Desactivar
                                                    </a>
                                                </li>
                                            @else
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                        onclick="toggleStatus({{ $producto->id }}, true)">
                                                        <i class="bi-eye dropdown-item-icon"></i> Activar
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
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
        
        <!-- Footer -->
        <div class="card-footer">
            <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <div class="d-flex justify-content-center justify-content-sm-start align-items-center">
                        <span class="me-2">Mostrando:</span>
                        <span class="text-secondary me-2">{{ $productos->firstItem() ?? 0 }} - {{ $productos->lastItem() ?? 0 }} de</span>
                        <span id="datatableWithPaginationInfoTotalQty">{{ $productos->total() }}</span>
                    </div>
                </div>
                <div class="col-sm-auto">
                    <div class="d-flex justify-content-center justify-content-sm-end">
                        {{ $productos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>






    <!-- Modal para actualizar stock -->
    <div class="modal fade" id="updateStockModal" tabindex="-1" aria-labelledby="updateStockModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStockModalLabel">Actualizar Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateStockForm">
                        <div class="mb-3">
                            <label for="stock_actual" class="form-label">Nuevo Stock</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="stock_actual" min="0" required>
                                <span class="input-group-text" id="unidad_medida"></span>
                            </div>
                            <div class="form-text">Stock actual: <span id="stock_actual_text"></span></div>
                        </div>
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones (Opcional)</label>
                            <textarea class="form-control" id="observaciones" rows="3" placeholder="Motivo del cambio de stock..."></textarea>
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
    <script>
        // Simple checkbox select all functionality
        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('datatableCheckAll');
            const checkboxes = document.querySelectorAll('input[type="checkbox"][id^="productosCheck"]');
            
            if (checkAll) {
                checkAll.addEventListener('change', function() {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            }
        });

        // Toggle status function
        function toggleStatus(productId, newStatus) {
            const action = newStatus ? 'activar' : 'desactivar';
            const exec = () => $.ajax({
                    url: `/productos/${productId}`,
                    method: 'PUT',
                    data: {
                        activo: newStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (window.Swal) {
                          Swal.fire({ icon: 'success', title: `Producto ${newStatus ? 'activado' : 'desactivado'}`, timer: 1000, showConfirmButton: false }).then(()=>location.reload());
                        } else {
                          location.reload();
                        }
                    },
                    error: function() {
                        if (window.Swal) Swal.fire({ icon: 'error', title: 'Error al cambiar el estado' });
                        else alert('Error al cambiar el estado del producto');
                    }
                });

            if (window.Swal) {
              Swal.fire({ icon: 'question', title: `¿Está seguro de ${action} este producto?`, showCancelButton: true, confirmButtonText: 'Sí, continuar', cancelButtonText: 'Cancelar' }).then(r=>{ if (r.isConfirmed) exec(); });
            } else {
              if (confirm(`¿Está seguro de ${action} este producto?`)) exec();
            }
        }

        // Update stock function
        function updateStock(productId, productName, currentStock, unidadMedida) {
            // Set modal content
            $('#updateStockModalLabel').text(`Actualizar Stock - ${productName}`);
            $('#stock_actual').val(currentStock);
            $('#unidad_medida').text(unidadMedida);
            $('#stock_actual_text').text(`${currentStock} ${unidadMedida}`);
            $('#observaciones').val('');

            // Store product ID for save function
            $('#updateStockModal').data('productId', productId);

            // Show modal
            $('#updateStockModal').modal('show');
        }

        function saveStock() {
            const productId = $('#updateStockModal').data('productId');
            const stock = $('#stock_actual').val();
            const observaciones = $('#observaciones').val();

            if (!stock) {
                if (window.Swal) Swal.fire({ icon: 'warning', title: 'Ingrese el nuevo stock' }); else alert('Por favor ingrese el nuevo stock');
                return;
            }

            // Make AJAX call to update stock
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
                    if (window.Swal) {
                      Swal.fire({ icon: 'success', title: 'Stock actualizado', timer: 1000, showConfirmButton: false }).then(()=>location.reload());
                    } else {
                      location.reload();
                    }
                },
                error: function() {
                    if (window.Swal) Swal.fire({ icon: 'error', title: 'Error al actualizar el stock' }); else alert('Error al actualizar el stock');
                }
            });
        }
    </script>
@endsection
