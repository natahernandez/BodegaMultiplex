@extends('layouts.app')

@section('styles')
    <!-- DataTables CSS via CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

    <style>
        .category-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .status-badge {
            font-size: 0.75rem;
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
                        <li class="breadcrumb-item active" aria-current="page">Categorías</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">Gestión de Categorías</h1>
                <p class="page-header-text">Administra las categorías de productos de tu inventario</p>
            </div>
            
            <div class="col-sm-auto">
                <a class="btn btn-primary" href="{{ route('categories.create') }}">
                    <i class="bi-plus"></i> Nueva Categoría
                </a>
            </div>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Total Categorías</h6>
                    <div class="row align-items-center gx-2">
                        <div class="col">
                            <span class="js-counter display-4 text-dark">{{ $categories->total() }}</span>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-soft-success text-success">
                                <i class="bi-graph-up"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Categorías Activas</h6>
                    <div class="row align-items-center gx-2">
                        <div class="col">
                            <span class="js-counter display-4 text-dark">{{ $categories->where('activo', true)->count() }}</span>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-soft-success text-success">
                                <i class="bi-check-circle"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Categorías Inactivas</h6>
                    <div class="row align-items-center gx-2">
                        <div class="col">
                            <span class="js-counter display-4 text-dark">{{ $categories->where('activo', false)->count() }}</span>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-soft-warning text-warning">
                                <i class="bi-pause-circle"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Stats Cards -->

    <!-- Card -->
    <div class="card">
        <!-- Header -->
        <div class="card-header card-header-content-md-between">
            <div class="mb-2 mb-md-0">
                <form>
                    <!-- Search -->
                    <div class="input-group input-group-merge input-group-flush">
                        <div class="input-group-prepend input-group-text">
                            <i class="bi-search"></i>
                        </div>
                        <input id="datatableSearch" type="search" class="form-control" placeholder="Buscar categorías" aria-label="Buscar categorías">
                    </div>
                    <!-- End Search -->
                </form>
            </div>

            <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">
                <!-- Dropdown -->
                <div class="dropdown">
                    <button type="button" class="btn btn-white btn-sm dropdown-toggle w-100" id="usersExportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi-download me-2"></i> Exportar
                    </button>

                    <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="usersExportDropdown">
                        <span class="dropdown-header">Opciones</span>
                        <a id="export-copy" class="dropdown-item" href="#">
                            <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('svg/illustrations/copy-icon.svg') }}" alt="Image Description">
                            Copiar
                        </a>
                        <a id="export-print" class="dropdown-item" href="#">
                            <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('svg/illustrations/print-icon.svg') }}" alt="Image Description">
                            Imprimir
                        </a>
                        <div class="dropdown-divider"></div>
                        <span class="dropdown-header">Descargar opciones</span>
                        <a id="export-excel" class="dropdown-item" href="#">
                            <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('svg/brands/excel-icon.svg') }}" alt="Image Description">
                            Excel
                        </a>
                        <a id="export-csv" class="dropdown-item" href="#">
                            <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('svg/components/placeholder-csv-format.svg') }}" alt="Image Description">
                            .CSV
                        </a>
                        <a id="export-pdf" class="dropdown-item" href="#">
                            <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('svg/brands/pdf-icon.svg') }}" alt="Image Description">
                            PDF
                        </a>
                    </div>
                </div>
                <!-- End Dropdown -->
            </div>
        </div>
        <!-- End Header -->

        <!-- Table -->
        <div class="table-responsive datatable-custom">
            <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table" data-hs-datatables-options='{
                "columnDefs": [{
                    "targets": [0],
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
                        <th class="table-column-ps-0">Categoría</th>
                        <th>Descripción</th>
                        <th>Productos</th>
                        <th>Estado</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td class="table-column-pe-0">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $category->id }}" id="datatableCheckAll{{ $category->id }}">
                                <label class="form-check-label" for="datatableCheckAll{{ $category->id }}"></label>
                            </div>
                        </td>
                        <td class="table-column-ps-0">
                            <a class="d-flex align-items-center" href="{{ route('categories.show', $category) }}">
                                @if($category->imagen_url)
                                    <div class="flex-shrink-0">
                                        <img class="category-image" src="{{ $category->imagen_url }}" alt="{{ $category->nombre }}">
                                    </div>
                                @else
                                    <div class="flex-shrink-0">
                                        <div class="avatar avatar-soft-info avatar-circle">
                                            <span class="avatar-initials">{{ strtoupper(substr($category->nombre, 0, 2)) }}</span>
                                        </div>
                                    </div>
                                @endif
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="text-inherit mb-0">{{ $category->nombre }}</h5>
                                </div>
                            </a>
                        </td>
                        <td>
                            <span class="d-block h5 text-inherit mb-0">{{ $category->descripcion ?? 'Sin descripción' }}</span>
                        </td>
                        <td>
                            <span class="badge bg-soft-secondary text-secondary">{{ $category->productos_count ?? 0 }} productos</span>
                        </td>
                        <td>
                            @if($category->activo)
                                <span class="badge bg-soft-success text-success status-badge">
                                    <span class="legend-indicator bg-success"></span>Activa
                                </span>
                            @else
                                <span class="badge bg-soft-secondary text-secondary status-badge">
                                    <span class="legend-indicator bg-secondary"></span>Inactiva
                                </span>
                            @endif
                        </td>
                        <td>{{ $category->created_at->format('d/m/Y') }}</td>
                        <td>
                            <!-- Dropdown -->
                            <div class="dropdown">
                                <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm rounded-circle" id="settingsDropdown{{ $category->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi-three-dots-vertical"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="settingsDropdown{{ $category->id }}">
                                    <span class="dropdown-header">Configuración</span>

                                    <a class="dropdown-item" href="{{ route('categories.show', $category) }}">
                                        <i class="bi-eye dropdown-item-icon"></i> Ver detalles
                                    </a>
                                    <a class="dropdown-item" href="{{ route('categories.edit', $category) }}">
                                        <i class="bi-pencil dropdown-item-icon"></i> Editar
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <form action="{{ route('categories.toggleStatus', $category) }}" method="POST" style="display: inline;" data-confirm="¿Cambiar el estado de esta categoría?">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="dropdown-item">
                                            @if($category->activo)
                                                <i class="bi-pause dropdown-item-icon"></i> Desactivar
                                            @else
                                                <i class="bi-play dropdown-item-icon"></i> Activar
                                            @endif
                                        </button>
                                    </form>

                                    <div class="dropdown-divider"></div>

                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline;" data-confirm="¿Eliminar esta categoría de forma permanente?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi-trash dropdown-item-icon"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <!-- End Dropdown -->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- End Table -->

        <!-- Footer -->
        <div class="card-footer">
            <div class="d-flex justify-content-center">
                {{ $categories->links() }}
            </div>
        </div>
        <!-- End Footer -->
    </div>
    <!-- End Card -->
@endsection

@section('scripts')
    <!-- DataTables JS via CDN -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#datatable').DataTable({
                dom: 'Brt',
                paging: false,
                info: false,
                searching: false,
                buttons: [
                    {
                        extend: 'copy',
                        className: 'd-none',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'd-none',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'csv',
                        className: 'd-none',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'd-none',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'print',
                        className: 'd-none',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5]
                        }
                    }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                }
            });

            // Export button handlers
            $('#export-copy').click(function(e) {
                e.preventDefault();
                table.button('.buttons-copy').trigger();
            });

            $('#export-excel').click(function(e) {
                e.preventDefault();
                table.button('.buttons-excel').trigger();
            });

            $('#export-csv').click(function(e) {
                e.preventDefault();
                table.button('.buttons-csv').trigger();
            });

            $('#export-pdf').click(function(e) {
                e.preventDefault();
                table.button('.buttons-pdf').trigger();
            });

            $('#export-print').click(function(e) {
                e.preventDefault();
                table.button('.buttons-print').trigger();
            });
        });
    </script>
@endsection
