<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Bodegas Multiphlex - Tienda Online</title>

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilos-pagina-principal.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>

    <header class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand text-primary d-flex align-items-center" href="/">
                <img src="{{ asset('svg/logos/logo.png') }}" alt="Bodegas Multiphlex" style="height: 40px; width: auto; margin-right: 10px;">
                Bodegas <span class="text-warning">Multiphlex</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="ms-auto d-flex align-items-center">

                    <!-- Carrito -->
                    <a href="{{ route('shop.cart') }}" class="btn btn-outline-primary position-relative me-3">
                        <i class="bi-cart3 fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            id="cartCount">
                            {{ session('carrito') ? array_sum(array_column(session('carrito'), 'cantidad')) : 0 }}
                        </span>
                    </a>

                    <!-- Botones de usuario -->
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="bi-person-circle"></i> {{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/mis-pedidos">Mis Pedidos</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">Cerrar Sesión</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Registrarse</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="hero-section">
        <h1> <strong class="text-white">Bienvenidos a Bodegas Multiphlex</strong></h1>
        <p>Encuentra los mejores productos con envío a toda Guatemala</p>
        <span class="badge"><i class="bi-truck"></i> Envío Gratis en compras mayores a Q200</span>
        <span class="badge"><i class="bi-clock"></i> Entrega en 24-48 horas</span>
    </div>

    <main class="container">
        <div class="filters-section shadow-sm p-4 rounded bg-white mb-4">
            <form method="GET" class="row g-3 align-items-end">
                <!-- Buscador -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Buscar Producto</label>
                    <div class="input-group">
                        <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Buscar productos...">
                        <button class="btn btn-primary px-3" type="submit">
                            <i class="bi-search fs-5"></i>
                        </button>
                    </div>
                </div>

                <!-- Filtro de Categoría -->
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Categoría</label>
                    <select name="categoria" class="form-select">
                        <option value="">Todas</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria }}"
                                {{ request('categoria') == $categoria ? 'selected' : '' }}>
                                {{ $categoria }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro de Ofertas -->
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Ofertas</label>
                    <select name="en_oferta" class="form-select">
                        <option value="">Todos</option>
                        <option value="1" {{ request('en_oferta') == '1' ? 'selected' : '' }}>Solo Ofertas</option>
                    </select>
                </div>

                <!-- Precio Mín -->
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Precio Mín.</label>
                    <input type="number" name="precio_min" class="form-control" value="{{ request('precio_min') }}"
                        placeholder="0">
                </div>

                <!-- Precio Máx -->
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Precio Máx.</label>
                    <input type="number" name="precio_max" class="form-control" value="{{ request('precio_max') }}"
                        placeholder="1000">
                </div>

                <!-- Ordenar -->
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Ordenar</label>
                    <select name="order_by" class="form-select">
                        <option value="created_at" {{ request('order_by') == 'created_at' ? 'selected' : '' }}>Más
                            recientes</option>
                        <option value="precio_asc" {{ request('order_by') == 'precio_asc' ? 'selected' : '' }}>Precio:
                            Menor a Mayor</option>
                        <option value="precio_desc" {{ request('order_by') == 'precio_desc' ? 'selected' : '' }}>
                            Precio: Mayor a Menor</option>
                    </select>
                </div>

                <!-- Boton de Filtrar-->
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-50">
                        <i class="bi-funnel me-1"></i> Filtrar
                    </button>

                    <!-- Boton de Limpiar -->
                    @if (request('search') || request('categoria') || request('en_oferta') || request('precio_min') || request('precio_max') || request('order_by'))
                        <a href="/" class="btn btn-outline-secondary w-50">
                            <i class="bi-arrow-clockwise me-1"></i> Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Productos -->
        <div class="row">
            @foreach ($productos as $producto)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card product-card position-relative">
                        @if ($producto->imagen_principal_url)
                            <img src="{{ $producto->imagen_principal_url }}" class="card-img-top product-image"
                                alt="{{ $producto->nombre }}">
                        @else
                            <div class="product-image d-flex align-items-center justify-content-center">
                                <i class="bi-image fs-3 text-muted"></i>
                            </div>
                        @endif
                        
                        {{-- Badge de descuento --}}
                        @if($producto->es_oferta_activa && $producto->descuento_calculado > 0)
                            <span class="discount-badge-small">-{{ $producto->descuento_calculado }}%</span>
                        @elseif($producto->en_oferta && !$producto->es_oferta_activa)
                            <span class="badge bg-warning text-dark position-absolute" style="top: 10px; right: 10px; z-index: 5;">Oferta Vencida</span>
                        @endif
                        
                        <div class="card-body">
                            <span class="category-badge">{{ optional($producto->category)->nombre ?? $producto->categoria }}</span>
                            <h5 class="card-title fw-bold">{{ $producto->nombre }}</h5>
                            <p class="text-muted small">{{ Str::limit($producto->descripcion, 70) }}</p>
                            
                            {{-- Precios --}}
                            @if($producto->es_oferta_activa)
                                <div class="mb-3">
                                    <span class="text-decoration-line-through text-muted me-2">Q{{ number_format($producto->precio_venta, 2) }}</span>
                                    <span class="h5 text-danger mb-0">Q{{ number_format($producto->precio_final, 2) }}</span>
                                </div>
                            @else
                                <div class="mb-3">
                                    <span class="h5 text-primary mb-0">Q{{ number_format($producto->precio_venta, 2) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent">
                            <button class="btn btn-add-cart w-100 mb-2"
                                onclick="addToCart(event, {{ $producto->id }}, '{{ $producto->nombre }}', '{{ route('shop.cart.add') }}')">
                                <i class="bi-cart-plus"></i> Agregar al Carrito
                            </button>
                            <a href="{{ route('shop.product.show', $producto) }}"
                                class="btn btn-outline-primary w-100 btn-sm">
                                <i class="bi-eye"></i> Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="pagination-section mt-5">
            {{ $productos->links('vendor.pagination.advanced') }}
        </div>
    </main>

    

    <!-- Footer -->
    <footer>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3    ">
                    <h5>Bodegas Multiphlex</h5>
                    <p>Tu tienda de confianza con los mejores productos.</p>
                </div>
                <div class="col-md-3">
                    <h6>Categorías</h6>
                    <ul class="list-unstyled">
                        @foreach ($categorias->take(5) as $categoria)
                            <li><a href="?categoria={{ $categoria }}">{{ $categoria }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Contacto</h6>
                    <p><i class="bi-telephone"></i> +502 5928-9905</p>
                    <p><i class="bi-envelope"></i> bdgsmultiphlex@gmail.com</p>
                </div>
                <div class="col-md-3">
                    <h6>Certificado por:</h6>
                    <script type="text/javascript"
                        src="https://comercios.pagadito.com/validate/index.php?merchant=07f67001af3242bc11ea755ed69c30e2&size=m&_idioma=es"></script>
                </div>
            </div>
            <hr>
            <div class="text-center">&copy; {{ date('Y') }} Bodegas Multiplex. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/funciones-pagina-principal.js') }}"></script>
</body>

</html>
