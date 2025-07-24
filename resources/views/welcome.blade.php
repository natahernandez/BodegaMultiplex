<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Bodegas Multiplex - Tienda Online</title>

  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap-icons/font/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/hs-img-compare/hs-img-compare.css') }}">
  <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
  <link rel="preload" href="{{ asset('css/theme.css') }}" data-hs-appearance="default" as="style">
  <link rel="preload" href="{{ asset('css/theme-dark.css') }}" data-hs-appearance="dark" as="style">

  <style>
    /* Estilos personalizados */
    .navbar {
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    
    .product-card {
      transition: all 0.3s ease;
      border: none;
      box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    }
    
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .product-image {
      height: 250px;
      object-fit: cover;
      border-radius: 8px 8px 0 0;
    }
    
    .price-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background: linear-gradient(45deg, #007bff, #6c757d);
      color: white;
      padding: 5px 12px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 0.9rem;
    }
    
    .stock-badge {
      position: absolute;
      top: 10px;
      left: 10px;
      padding: 4px 8px;
      border-radius: 15px;
      font-size: 0.75rem;
      font-weight: 600;
    }
    
    .category-badge {
      background: linear-gradient(45deg, #28a745, #20c997);
      color: white;
      font-size: 0.8rem;
      padding: 4px 12px;
      border-radius: 12px;
      display: inline-block;
      margin-bottom: 8px;
    }
    
    .btn-add-cart {
      background: linear-gradient(45deg, #007bff, #0056b3);
      border: none;
      border-radius: 25px;
      padding: 10px 25px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .btn-add-cart:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,123,255,0.4);
    }
    
    .filters-section {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      border-radius: 15px;
      padding: 20px;
      margin-bottom: 30px;
    }
    
    .cart-counter {
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }
    
    .hero-section {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 60px 0;
      border-radius: 0 0 30px 30px;
      margin-bottom: 40px;
    }
    
    .search-suggestions {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      background: white;
      border: 1px solid #ddd;
      border-radius: 0 0 8px 8px;
      max-height: 300px;
      overflow-y: auto;
      z-index: 1000;
      display: none;
    }
    
    .suggestion-item {
      padding: 10px 15px;
      cursor: pointer;
      border-bottom: 1px solid #f0f0f0;
    }
    
    .suggestion-item:hover {
      background: #f8f9fa;
    }
    
    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #6c757d;
    }
    
    .pagination .page-link {
      border-radius: 20px;
      margin: 0 2px;
      border: none;
      background: #f8f9fa;
    }
    
    .pagination .page-item.active .page-link {
      background: linear-gradient(45deg, #007bff, #0056b3);
      border: none;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <header class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
      <!-- Logo -->
      <a class="navbar-brand" href="/">
        <h3 class="mb-0 text-primary fw-bold">Bodegas <span class="text-warning">Multiplex</span></h3>
      </a>

      <!-- Botón hamburguesa para móvil -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menú principal -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active fw-semibold" href="/">
              <i class="bi-house-door me-1"></i>Inicio
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi-box-seam me-1"></i>Categorías
            </a>
            <ul class="dropdown-menu">
              @foreach($categorias as $categoria)
                <li><a class="dropdown-item" href="?categoria={{ $categoria }}">{{ $categoria }}</a></li>
              @endforeach
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="/">Ver Todos</a></li>
            </ul>
          </li>
        </ul>

        <!-- Búsqueda -->
        <form class="d-flex me-3 position-relative" role="search" method="GET">
          <div class="input-group">
            <input class="form-control" type="search" name="search" value="{{ request('search') }}" 
                   placeholder="Buscar productos..." aria-label="Search" id="searchInput">
            <button class="btn btn-outline-primary" type="submit">
              <i class="bi-search"></i>
            </button>
          </div>
          <div id="searchSuggestions" class="search-suggestions"></div>
        </form>

        <!-- Carrito de compras -->
        <div class="me-3 position-relative">
          <a href="{{ route('shop.cart') }}" class="btn btn-outline-primary position-relative" id="cartButton">
            <i class="bi-cart3 fs-5"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-counter" id="cartCount">
              {{ session('carrito') ? array_sum(array_column(session('carrito'), 'cantidad')) : 0 }}
            </span>
          </a>
        </div>

        <!-- Botones de autenticación -->
        <div class="d-flex">
          @auth
            @if(auth()->user()->role == 1)
              <a href="{{ route('home') }}" class="btn btn-success me-2">
                <i class="bi-speedometer2 me-1"></i>Dashboard
              </a>
            @endif
            <div class="dropdown">
              <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi-person-circle me-1"></i>{{ auth()->user()->name }}
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="/perfil"><i class="bi-person me-2"></i>Mi Perfil</a></li>
                <li><a class="dropdown-item" href="/mis-pedidos"><i class="bi-bag me-2"></i>Mis Pedidos</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                      <i class="bi-box-arrow-right me-2"></i>Cerrar Sesión
                    </button>
                  </form>
                </li>
              </ul>
            </div>
          @else
            <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
              <i class="bi-box-arrow-in-right me-1"></i>Iniciar Sesión
            </a>
            @if (Route::has('register'))
              <a href="{{ route('register') }}" class="btn btn-primary">
                <i class="bi-person-plus me-1"></i>Registrarse
              </a>
            @endif
          @endauth
        </div>
      </div>
    </div>
  </header>

  <main class="main">
    <!-- Mensajes de alerta -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <!-- Hero Section -->
    <div class="hero-section">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <h1 class="display-4 fw-bold mb-3">Bienvenidos a Bodegas Multiplex</h1>
            <p class="lead">Encuentra los mejores productos con envío a toda Guatemala</p>
            <div class="mt-4">
              <span class="badge bg-light text-dark p-2 me-2">
                <i class="bi-truck me-1"></i>Envío Gratis en compras mayores a Q200
              </span>
              <span class="badge bg-light text-dark p-2">
                <i class="bi-clock me-1"></i>Entrega en 24-48 horas
              </span>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="row text-center text-light">
              <div class="col-6">
                <h3 class="display-6">{{ $productos->total() }}+</h3>
                <p>Productos</p>
              </div>
              <div class="col-6">
                <h3 class="display-6">{{ $categorias->count() }}+</h3>
                <p>Categorías</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <!-- Filtros -->
      <div class="filters-section">
        <form method="GET" class="row g-3 align-items-end">
          <div class="col-md-3">
            <label class="form-label fw-semibold">Categoría</label>
            <select name="categoria" class="form-select">
              <option value="">Todas las categorías</option>
              @foreach($categorias as $categoria)
                <option value="{{ $categoria }}" {{ request('categoria') == $categoria ? 'selected' : '' }}>
                  {{ $categoria }}
                </option>
              @endforeach
            </select>
          </div>
          
          <div class="col-md-2">
            <label class="form-label fw-semibold">Precio Mín.</label>
            <input type="number" name="precio_min" class="form-control" value="{{ request('precio_min') }}" 
                   placeholder="Q{{ number_format($precioMin ?? 0, 0) }}" min="0" step="0.01">
          </div>
          
          <div class="col-md-2">
            <label class="form-label fw-semibold">Precio Máx.</label>
            <input type="number" name="precio_max" class="form-control" value="{{ request('precio_max') }}" 
                   placeholder="Q{{ number_format($precioMax ?? 1000, 0) }}" min="0" step="0.01">
          </div>
          
          <div class="col-md-2">
            <label class="form-label fw-semibold">Ordenar por</label>
            <select name="order_by" class="form-select">
              <option value="created_at" {{ request('order_by') == 'created_at' ? 'selected' : '' }}>Más recientes</option>
              <option value="precio_asc" {{ request('order_by') == 'precio_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
              <option value="precio_desc" {{ request('order_by') == 'precio_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
              <option value="nombre" {{ request('order_by') == 'nombre' ? 'selected' : '' }}>Nombre A-Z</option>
              <option value="popular" {{ request('order_by') == 'popular' ? 'selected' : '' }}>Más Popular</option>
            </select>
          </div>
          
          <div class="col-md-3">
            <button type="submit" class="btn btn-primary me-2">
              <i class="bi-funnel me-1"></i>Filtrar
            </button>
            <a href="/" class="btn btn-outline-secondary">
              <i class="bi-arrow-clockwise me-1"></i>Limpiar
            </a>
          </div>
        </form>
      </div>

      <!-- Resultados -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
          @if(request()->hasAny(['search', 'categoria', 'precio_min', 'precio_max']))
            Resultados de búsqueda 
            @if(request('search'))
              para "{{ request('search') }}"
            @endif
            @if(request('categoria'))
              en {{ request('categoria') }}
            @endif
          @else
            Nuestros Productos
          @endif
          <span class="text-muted fs-6">({{ $productos->total() }} productos)</span>
        </h2>
      </div>

      <!-- Lista de Productos -->
      @if($productos->count() > 0)
        <div class="row">
          @foreach($productos as $producto)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
              <div class="card product-card h-100 position-relative">
                <!-- Imagen del producto -->
                <div class="position-relative">
                  @if($producto->imagen_principal_url)
                    <img src="{{ $producto->imagen_principal_url }}" 
                         class="card-img-top product-image" alt="{{ $producto->nombre }}"
                         style="height: 200px; object-fit: contain; background: #f8f9fa;">
                  @else
                    <div class="card-img-top product-image bg-light d-flex align-items-center justify-content-center">
                      <i class="bi-image text-muted" style="font-size: 3rem;"></i>
                    </div>
                  @endif
                  
                  <!-- Badge de precio -->
                  <span class="price-badge">Q{{ number_format($producto->precio_venta, 2) }}</span>
                  
                  <!-- Badge de stock -->
                  @if($producto->stock_actual <= 0)
                    <span class="stock-badge bg-danger text-white">Agotado</span>
                  @elseif($producto->stock_actual <= $producto->stock_minimo)
                    <span class="stock-badge bg-warning text-dark">Stock Bajo</span>
                  @else
                    <span class="stock-badge bg-success text-white">Disponible</span>
                  @endif
                </div>

                <div class="card-body d-flex flex-column">
                  <!-- Categoría -->
                  <span class="category-badge">{{ $producto->categoria }}</span>
                  
                  <!-- Nombre del producto -->
                  <h5 class="card-title fw-bold">{{ $producto->nombre }}</h5>
                  
                  <!-- Descripción -->
                  @if($producto->descripcion)
                    <p class="card-text text-muted small">{{ Str::limit($producto->descripcion, 80) }}</p>
                  @endif
                  
                  <!-- Información adicional -->
                  <div class="mt-auto">
                    @if($producto->marca)
                      <p class="small text-muted mb-1">
                        <i class="bi-tag me-1"></i>{{ $producto->marca }}
                      </p>
                    @endif
                    
                    <p class="small text-muted mb-2">
                      <i class="bi-box me-1"></i>Stock: {{ $producto->stock_actual }} {{ $producto->unidad_medida }}
                    </p>
                    
                    @if($producto->precio_mayoreo && $producto->precio_mayoreo < $producto->precio_venta)
                      <p class="small text-success mb-2">
                        <i class="bi-currency-dollar me-1"></i>Precio mayoreo: Q{{ number_format($producto->precio_mayoreo, 2) }}
                      </p>
                    @endif
                  </div>
                </div>

                <div class="card-footer bg-transparent">
                  <div class="d-grid gap-2">
                    @if($producto->stock_actual > 0)
                      <button class="btn btn-add-cart btn-primary" 
                              onclick="addToCart({{ $producto->id }}, '{{ $producto->nombre }}')">
                        <i class="bi-cart-plus me-1"></i>Agregar al Carrito
                      </button>
                    @else
                      <button class="btn btn-secondary" disabled>
                        <i class="bi-x-circle me-1"></i>Sin Stock
                      </button>
                    @endif
                    
                    <a href="{{ route('shop.product.show', $producto) }}" class="btn btn-outline-primary btn-sm">
                      <i class="bi-eye me-1"></i>Ver Detalles
                    </a>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center">
          {{ $productos->links() }}
        </div>
      @else
        <!-- Estado vacío -->
        <div class="empty-state">
          <i class="bi-search text-muted mb-3" style="font-size: 4rem;"></i>
          <h4 class="text-muted">No se encontraron productos</h4>
          <p class="text-muted">Intenta ajustar tus filtros o buscar algo diferente</p>
          <a href="/" class="btn btn-primary">
            <i class="bi-arrow-left me-1"></i>Ver Todos los Productos
          </a>
        </div>
      @endif
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-dark text-light py-5 mt-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 mb-4">
          <h5 class="text-warning">Bodegas Multiplex</h5>
          <p>Tu tienda de confianza con los mejores productos y envío a toda Guatemala.</p>
          <div class="d-flex gap-3">
            <a href="#" class="text-light fs-4"><i class="bi-facebook"></i></a>
            <a href="#" class="text-light fs-4"><i class="bi-instagram"></i></a>
            <a href="#" class="text-light fs-4"><i class="bi-whatsapp"></i></a>
          </div>
        </div>
        <div class="col-lg-2 mb-4">
          <h6>Categorías</h6>
          <ul class="list-unstyled">
            @foreach($categorias->take(5) as $categoria)
              <li><a href="?categoria={{ $categoria }}" class="text-light text-decoration-none">{{ $categoria }}</a></li>
            @endforeach
          </ul>
        </div>
        <div class="col-lg-3 mb-4">
          <h6>Información</h6>
          <ul class="list-unstyled">
            <li><a href="#" class="text-light text-decoration-none">Sobre Nosotros</a></li>
            <li><a href="#" class="text-light text-decoration-none">Términos y Condiciones</a></li>
            <li><a href="#" class="text-light text-decoration-none">Política de Privacidad</a></li>
            <li><a href="#" class="text-light text-decoration-none">Envíos y Devoluciones</a></li>
          </ul>
        </div>
        <div class="col-lg-3">
          <h6>Contacto</h6>
          <p><i class="bi-telephone me-2"></i>+502 1234-5678</p>
          <p><i class="bi-envelope me-2"></i>info@bodegasmultiplex.com</p>
          <p><i class="bi-geo-alt me-2"></i>Guatemala, Guatemala</p>
        </div>
      </div>
      <hr class="my-4">
      <div class="text-center">
        <p class="mb-0">&copy; {{ date('Y') }} Bodegas Multiplex. Todos los derechos reservados.</p>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

  <script>
    // Configuración CSRF para AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Función para agregar al carrito
    function addToCart(productId, productName) {
        const button = event.target;
        const originalText = button.innerHTML;
        
        // Cambiar el botón a estado de carga
        button.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i>Agregando...';
        button.disabled = true;

        $.ajax({
            url: '{{ route("shop.cart.add") }}',
            method: 'POST',
            data: {
                producto_id: productId,
                cantidad: 1
            },
            success: function(response) {
                // Actualizar contador del carrito
                $('#cartCount').text(response.totalItems);
                
                // Mostrar mensaje de éxito
                showAlert('success', `${productName} agregado al carrito exitosamente`);
                
                // Restaurar botón
                button.innerHTML = originalText;
                button.disabled = false;
                
                // Animar el contador del carrito
                $('#cartCount').addClass('cart-counter');
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert('danger', response.error || 'Error al agregar al carrito');
                
                // Restaurar botón
                button.innerHTML = originalText;
                button.disabled = false;
            }
        });
    }

    // Función para mostrar alertas
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                <i class="bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('body').append(alertHtml);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 5000);
    }

    // Búsqueda con sugerencias
    let searchTimeout;
    $('#searchInput').on('input', function() {
        const query = $(this).val();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            $('#searchSuggestions').hide();
            return;
        }
        
        searchTimeout = setTimeout(() => {
            $.ajax({
                url: '{{ route("shop.search.api") }}',
                data: { q: query },
                success: function(products) {
                    let html = '';
                    
                    if (products.length > 0) {
                        products.forEach(product => {
                            html += `
                                <div class="suggestion-item" onclick="selectSuggestion('${product.nombre}')">
                                    <strong>${product.nombre}</strong><br>
                                    <small class="text-muted">Q${parseFloat(product.precio_venta).toFixed(2)} - Código: ${product.codigo_interno}</small>
                                </div>
                            `;
                        });
                    } else {
                        html = '<div class="suggestion-item text-muted">No se encontraron productos</div>';
                    }
                    
                    $('#searchSuggestions').html(html).show();
                }
            });
        }, 300);
    });

    function selectSuggestion(productName) {
        $('#searchInput').val(productName);
        $('#searchSuggestions').hide();
        $('form[role="search"]').submit();
    }

    // Ocultar sugerencias al hacer clic fuera
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.position-relative').length) {
            $('#searchSuggestions').hide();
        }
    });

    // Actualizar contador del carrito al cargar la página
    $(document).ready(function() {
        @if(session('carrito'))
            const cartCount = {{ array_sum(array_column(session('carrito'), 'cantidad')) }};
            $('#cartCount').text(cartCount);
        @endif
    });
  </script>
</body>
</html>