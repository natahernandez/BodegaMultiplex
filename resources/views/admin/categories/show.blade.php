@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-no-gutter">
                        <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('categories.index') }}">Categorías</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $category->nombre }}</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">{{ $category->nombre }}</h1>
                <p class="page-header-text">Detalles de la categoría</p>
            </div>
            
            <div class="col-sm-auto">
                <a class="btn btn-primary" href="{{ route('categories.edit', $category) }}">
                    <i class="bi-pencil"></i> Editar Categoría
                </a>
            </div>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="row">
        <div class="col-lg-8">
            <!-- Card -->
            <div class="card">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Información de la Categoría</h4>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            @if($category->imagen_url)
                                <img src="{{ $category->imagen_url }}" alt="{{ $category->nombre }}" class="img-fluid rounded">
                            @else
                                <div class="d-flex justify-content-center align-items-center bg-light rounded" style="height: 200px;">
                                    <div class="text-center">
                                        <i class="bi-image fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">Sin imagen</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-sm-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Nombre:</dt>
                                        <dd class="col-sm-8">{{ $category->nombre }}</dd>

                                        <dt class="col-sm-4">Estado:</dt>
                                        <dd class="col-sm-8">
                                            @if($category->activo)
                                                <span class="badge bg-soft-success text-success">
                                                    <span class="legend-indicator bg-success"></span>Activa
                                                </span>
                                            @else
                                                <span class="badge bg-soft-secondary text-secondary">
                                                    <span class="legend-indicator bg-secondary"></span>Inactiva
                                                </span>
                                            @endif
                                        </dd>

                                        <dt class="col-sm-4">Productos:</dt>
                                        <dd class="col-sm-8">{{ $category->productos->count() }} productos</dd>
                                    </dl>
                                </div>
                                
                                <div class="col-sm-6">
                                    <dl class="row">
                                        <dt class="col-sm-5">Fecha creación:</dt>
                                        <dd class="col-sm-7">{{ $category->created_at->format('d/m/Y H:i') }}</dd>

                                        <dt class="col-sm-5">Última actualización:</dt>
                                        <dd class="col-sm-7">{{ $category->updated_at->format('d/m/Y H:i') }}</dd>
                                    </dl>
                                </div>
                            </div>

                            @if($category->descripcion)
                                <div class="mt-3">
                                    <h6>Descripción:</h6>
                                    <p class="text-body">{{ $category->descripcion }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- End Body -->
            </div>
            <!-- End Card -->

            @if($category->productos->count() > 0)
            <!-- Productos Card -->
            <div class="card">
                <!-- Header -->
                <div class="card-header card-header-content-between">
                    <h4 class="card-header-title">Productos de la Categoría</h4>
                    <span class="badge bg-soft-secondary text-secondary">{{ $category->productos->count() }} productos</span>
                </div>
                <!-- End Header -->

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th>Producto</th>
                                <th>Código</th>
                                <th>Precio Venta</th>
                                <th>Stock</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($category->productos as $producto)
                            <tr>
                                <td>
                                    <a class="d-flex align-items-center" href="{{ route('productos.show', $producto) }}">
                                        @if($producto->imagen_principal_url)
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-sm" src="{{ $producto->imagen_principal_url }}" alt="{{ $producto->nombre }}">
                                            </div>
                                        @else
                                            <div class="flex-shrink-0">
                                                <div class="avatar avatar-sm avatar-soft-primary avatar-circle">
                                                    <span class="avatar-initials">{{ strtoupper(substr($producto->nombre, 0, 2)) }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="text-inherit mb-0">{{ $producto->nombre }}</h5>
                                            @if($producto->descripcion)
                                                <span class="d-block fs-6 text-body">{{ Str::limit($producto->descripcion, 50) }}</span>
                                            @endif
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <span class="d-block h5 mb-0">{{ $producto->codigo_interno ?? $producto->codigo_barras }}</span>
                                </td>
                                <td>{{ $producto->precio_venta_formateado }}</td>
                                <td>
                                    <span class="badge bg-soft-{{ $producto->estado_stock_color }} text-{{ $producto->estado_stock_color }}">
                                        {{ $producto->stock_actual }} unidades
                                    </span>
                                </td>
                                <td>
                                    @if($producto->activo)
                                        <span class="badge bg-soft-success text-success">Activo</span>
                                    @else
                                        <span class="badge bg-soft-secondary text-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-white btn-sm" href="{{ route('productos.show', $producto) }}">
                                        <i class="bi-eye me-1"></i> Ver
                                    </a>
                                    <a class="btn btn-white btn-sm" href="{{ route('productos.edit', $producto) }}">
                                        <i class="bi-pencil me-1"></i> Editar
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- End Table -->
            </div>
            <!-- End Productos Card -->
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Card -->
            <div class="card">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Acciones Rápidas</h4>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a class="btn btn-primary" href="{{ route('categories.edit', $category) }}">
                            <i class="bi-pencil me-1"></i> Editar Categoría
                        </a>

                        <form action="{{ route('categories.toggleStatus', $category) }}" method="POST" data-confirm="¿Cambiar el estado de esta categoría?">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-{{ $category->activo ? 'warning' : 'success' }} w-100">
                                @if($category->activo)
                                    <i class="bi-pause me-1"></i> Desactivar Categoría
                                @else
                                    <i class="bi-play me-1"></i> Activar Categoría
                                @endif
                            </button>
                        </form>

                        <div class="dropdown">
                            <button class="btn btn-danger dropdown-toggle w-100" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi-trash me-1"></i> Eliminar Categoría
                            </button>
                            <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <h6 class="dropdown-header">¿Estás seguro?</h6>
                                </li>
                                <li>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" data-confirm="¿Eliminar esta categoría de forma permanente?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi-trash me-1"></i> Sí, eliminar categoría
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- End Body -->
            </div>
            <!-- End Card -->

            <!-- Stats Card -->
            <div class="card">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Estadísticas</h4>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <span class="display-4 text-dark d-block">{{ $category->productos->count() }}</span>
                                <span class="d-block">Productos</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <span class="display-4 text-dark d-block">{{ $category->productos->where('activo', true)->count() }}</span>
                                <span class="d-block">Activos</span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <span class="display-4 text-dark d-block">{{ $category->productos->sum('stock_actual') }}</span>
                                <span class="d-block">Stock Total</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <span class="display-4 text-dark d-block">${{ number_format($category->productos->sum('precio_venta'), 2) }}</span>
                                <span class="d-block">Valor Inventario</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Body -->
            </div>
            <!-- End Stats Card -->
        </div>
    </div>
@endsection
