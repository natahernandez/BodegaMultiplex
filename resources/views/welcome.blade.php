<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>Bodegas Multiplex - Inicio</title>

  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap-icons/font/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/hs-img-compare/hs-img-compare.css') }}">
  <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
  <link rel="preload" href="{{ asset('css/theme.css') }}" data-hs-appearance="default" as="style">
  <link rel="preload" href="{{ asset('css/theme-dark.css') }}" data-hs-appearance="dark" as="style">

  <style>
    /* Estilos personalizados para el navbar */
    .navbar {
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    
    .navbar-brand h3 {
      font-size: 1.5rem;
      font-weight: 700;
    }
    
    .nav-link {
      transition: all 0.3s ease;
      position: relative;
    }
    
    .nav-link:hover {
      color: var(--bs-primary) !important;
      transform: translateY(-1px);
    }
    
    .nav-link.active::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 30px;
      height: 2px;
      background: var(--bs-primary);
      border-radius: 1px;
    }
    
    .dropdown-menu {
      border: none;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      border-radius: 10px;
    }
    
    .dropdown-item {
      padding: 0.75rem 1.5rem;
      transition: all 0.3s ease;
    }
    
    .dropdown-item:hover {
      background: var(--bs-primary);
      color: white;
      transform: translateX(5px);
    }
    
    .btn-outline-primary {
      border-width: 2px;
      font-weight: 500;
    }
    
    .btn-outline-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,123,255,0.3);
    }
    
    .btn-primary {
      font-weight: 500;
      box-shadow: 0 3px 10px rgba(0,123,255,0.3);
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,123,255,0.4);
    }
    
    /* Animación para el badge del carrito */
    .badge {
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0% { transform: translate(-50%, -50%) scale(1); }
      50% { transform: translate(-50%, -50%) scale(1.1); }
      100% { transform: translate(-50%, -50%) scale(1); }
    }
    
    /* Responsive */
    @media (max-width: 991.98px) {
      .navbar-nav {
        padding: 1rem 0;
      }
      
      .nav-link {
        padding: 0.75rem 0;
        border-bottom: 1px solid #eee;
      }
      
      .nav-link:last-child {
        border-bottom: none;
      }
    }
    
    /* Estilos para alertas */
    .alert {
      border: none;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
      margin-bottom: 0;
    }
    
    .alert-success {
      background: linear-gradient(135deg, #d4edda, #c3e6cb);
      color: #155724;
    }
    
    .alert-danger {
      background: linear-gradient(135deg, #f8d7da, #f5c6cb);
      color: #721c24;
    }
    
    .btn-close {
      opacity: 0.7;
    }
    
    .btn-close:hover {
      opacity: 1;
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
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menú principal -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active fw-semibold" href="/">
              <i class="bi-house-door me-1"></i>Inicio
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle fw-semibold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi-box-seam me-1"></i>Productos
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/productos/bebidas">Bebidas</a></li>
              <li><a class="dropdown-item" href="/productos/snacks">Snacks</a></li>
              <li><a class="dropdown-item" href="/productos/limpieza">Limpieza</a></li>
              <li><a class="dropdown-item" href="/productos/cuidado-personal">Cuidado Personal</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="/productos">Ver Todos</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-semibold" href="/ofertas">
              <i class="bi-tags me-1"></i>Ofertas
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-semibold" href="/nosotros">
              <i class="bi-info-circle me-1"></i>Nosotros
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-semibold" href="/contacto">
              <i class="bi-envelope me-1"></i>Contacto
            </a>
          </li>
        </ul>

        <!-- Búsqueda -->
        <form class="d-flex me-3" role="search">
          <div class="input-group">
            <input class="form-control" type="search" placeholder="Buscar productos..." aria-label="Search">
            <button class="btn btn-outline-primary" type="submit">
              <i class="bi-search"></i>
            </button>
          </div>
        </form>

        <!-- Carrito de compras -->
        <div class="me-3 position-relative">
          <a href="/carrito" class="btn btn-outline-primary position-relative">
            <i class="bi-cart3 fs-5"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              3
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
              <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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

  <main id="content" role="main" class="main">
    <!-- Mensajes de alerta -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    <div class="overflow-hidden gradient-radial-sm-primary">
      <div class="container-lg content-space-t-3 content-space-t-lg-4 content-space-b-2">
        <div class="w-lg-75 text-center mx-lg-auto text-center mx-auto">

          <!-- Encabezado -->
          <div class="mb-7 animated fadeInUp">
            <h1 class="display-2 mb-3">Bienvenidos a Bodegas <span class="text-primary text-highlight-warning">Multiphlex</span></h1>
            <p class="fs-2">Ecommerce de productos de bodega, con envío a toda Guatemala.</p>
          </div>
        </div>

        <!-- Productos Destacados -->
        <div class="animated fadeInUp">
          <figure class="js-img-comp device-browser device-browser-lg">
            <div class="device-browser-header">
              <div class="device-browser-header-btn-list">
                <span class="device-browser-header-btn-list-btn"></span>
                <span class="device-browser-header-btn-list-btn"></span>
                <span class="device-browser-header-btn-list-btn"></span>
              </div>
            </div>

            <div class="position-relative">
              <!-- Cargador -->
              <div class="js-img-comp-loader position-absolute d-flex align-items-center justify-content-center bg-white w-100 h-100 zi-999">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>
              <div class="device-browser-frame">
                <div class="js-img-comp-container hs-img-comp-container">
                  <img class="hs-img-comp hs-img-comp-a" src="{{ asset('img/1618x1010/img1.jpg') }}" alt="Image Description">
                  <div class="js-img-comp-wrapper hs-img-comp-wrapper">
                    <img class="hs-img-comp hs-img-comp-b" src="{{ asset('img/1618x1010/img2.jpg') }}" alt="Image Description">
                  </div>
                </div>
              </div>
            </div>
          </figure>
        </div>
      </div>
    </div>
    
<!-- Estadísticas -->
<div class="container-lg content-space-b-2 content-space-b-lg-3">
      <div class="row">
        <div class="col-sm-6 col-lg-3 mb-5 mb-lg-0">
          <div class="text-center">
            <span class="display-3 fw-normal text-dark">60+</span>
            <p class="fs-3 mb-0">Productos</p>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-5 mb-lg-0">
          <div class="text-center">
            <span class="display-3 fw-normal text-dark">50+</span>
            <p class="fs-3 mb-0">Clientes</p>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-5 mb-sm-0">
          <div class="text-center">
            <span class="display-3 fw-normal text-dark">450+</span>
            <p class="fs-3 mb-0">Pedidos</p>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3">
          <div class="text-center">
            <span class="display-3 fw-normal text-dark">47k+</span>
            <p class="fs-3 mb-0">Envíos</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Lista de Productos -->
    <div class="container-lg content-space-t-lg-2 content-space-b-2 content-space-b-lg-3">

      <!-- Encabezado de la lista de productos -->
      <div class="w-lg-75 text-center mx-lg-auto mb-7 mb-md-10">
        <h2 class="display-4">Listado de <span class="text-primary">Productos</span></h2>
        <p class="lead">Aquí puedes ver los productos disponibles.</p>
      </div>

      <div class="row">

        <div class="col-md-6 mb-4">
          <!-- Tarjeta de Producto 1 -->
          <a class="card card-lg card-transition h-100 bg-light border-0 shadow-none overflow-hidden" href="@@autopath/index.html">
            <div class="card-body">
              <h2 class="card-title h1 text-inherit">Producto 1</h2>
              <p class="card-text lead">Descripción del producto 1</p>
            </div>
            <div class="card-footer border-0 pt-0 mb-n4 me-n6">
              <img class="img-fluid shadow-lg" src="{{ asset('img/900x562/img1.jpg') }}" alt="Image Description" data-hs-theme-appearance="default">
            </div>
          </a>
        </div>

        <div class="col-md-6 mb-4">
          <!-- Tarjeta de Producto 2 -->
          <a class="card card-lg card-transition h-100 bg-light border-0 shadow-none overflow-hidden" href="@@autopath/dashboard-default-dark.html">
            <div class="card-body">
              <h2 class="card-title h1 text-inherit">Producto 2</h2>
              <p class="card-text lead">Descripción del producto 2</p>
            </div>
            <div class="card-footer border-0 pt-0 mb-n4 me-n6">
              <img class="img-fluid shadow-lg" src="{{ asset('img/900x562/img6.jpg') }}" alt="Image Description">
            </div>
          </a>
        </div>

        <div class="col-md-6 mb-4">
          <!-- Tarjeta de Producto 3 -->
          <a class="card card-lg card-transition h-100 bg-light border-0 shadow-none overflow-hidden" href="@@autopath/dashboard-default-dark-sidebar.html">
            <div class="card-body">
              <h2 class="card-title h1 text-inherit">Producto 3</h2>
              <p class="card-text lead">Descripción del producto 3</p>
            </div>
            <div class="card-footer border-0 pt-0 mb-n4 me-n6">
              <img class="img-fluid shadow-lg" src="{{ asset('img/900x562/img12.jpg') }}" alt="Image Description" data-hs-theme-appearance="default">
            </div>
          </a>
        </div>

        <div class="col-md-6 mb-4">
          <!-- Tarjeta de Producto 4 -->
          <a class="card card-lg card-transition h-100 bg-light border-0 shadow-none overflow-hidden" href="@@autopath/dashboard-default-light-sidebar.html">
            <div class="card-body">
              <h2 class="card-title h1 text-inherit">Producto 4</h2>
              <p class="card-text lead">Descripción del producto 4</p>
            </div>
            <div class="card-footer border-0 pt-0 mb-n4 me-n6">
              <img class="img-fluid shadow-lg" src="{{ asset('img/900x562/img11.jpg') }}" alt="Image Description" data-hs-theme-appearance="default">
            </div>
          </a>
        </div>
      </div>
    </div>

    <!-- Testimonios -->
    <div class="container-lg">
      <div class="bg-light content-space-2 rounded-3 px-5">
        <div class="w-md-70 text-center mx-md-auto">
          <div class="mb-4">
            <img class="img-fluid mx-auto" src="{{ asset('svg/illustrations/oc-review.svg') }}" alt="Image Description" data-hs-theme-appearance="default" style="max-width: 10rem;">
            <img class="img-fluid mx-auto" src="{{ asset('svg/illustrations-light/oc-review.svg') }}" alt="Image Description" data-hs-theme-appearance="dark" style="max-width: 10rem;">
          </div>
          <p class="fs-2 text-dark mb-4"><em>Este es un tema perfecto para una aplicación web moderna. <span class="text-highlight-warning">Hubo mucho pensamiento que se puso en diseñar</span> todos los componentes para que se vean coherentes y funcionen bien juntos en diferentes disposiciones de cuadrícula.</em></p>
          <h3 class="mb-0">Gerson</h3>
          <p class="fs-4 mb-0">Cliente satisfecho</p>
        </div>
      </div>
    </div>

    <div class="container-lg">
      <div class="bg-light content-space-2 rounded-3 px-5">
        <div class="w-md-70 text-center mx-md-auto">
          <div class="mb-4">
            <img class="img-fluid mx-auto" src="{{ asset('svg/illustrations/oc-review.svg') }}" alt="Image Description" data-hs-theme-appearance="default" style="max-width: 10rem;">
            <img class="img-fluid mx-auto" src="{{ asset('svg/illustrations-light/oc-review.svg') }}" alt="Image Description" data-hs-theme-appearance="dark" style="max-width: 10rem;">
          </div>
          <p class="fs-2 text-dark mb-4"><em>El tema tiene un aspecto muy profesional, aportando un estilo más moderno y limpio a la aplicación. <span class="text-highlight-warning">La documentación es extraordinariamente rica y completa</span>, ayudando a la implementación.</em></p>
          <h3 class="mb-0">Marcos</h3>
          <p class="fs-4 mb-0">Cliente satisfecho</p>
        </div>
      </div>
    </div>

    <!-- Preguntas Frecuentes -->
    <div class="container-lg content-space-t-2 content-space-t-lg-3">
      <div class="w-lg-75 text-center mx-lg-auto mb-7 mb-md-10">
        <h2 class="display-4">Preguntas <span class="text-primary">Frecuentes</span></h2>
      </div>
      <div class="w-md-75 mx-md-auto">
        <ul class="list-unstyled list-py-3 mb-0">
          <li>
            <h2 class="h1">How can I get a refund?</h2>
            <p class="fs-4">If you'd like a refund please reach out to us at <a class="link" href="mailto:themes@getbootstrap.com">themes@getbootstrap.com</a>. If you need technical help with the theme before a refund please reach out to us first.</p>
          </li>
          <li>
            <h2 class="h1">How do I get access to a theme I purchased?</h2>
            <p class="fs-4">If you lose the link for a theme you purchased, don't panic! We've got you covered. You can login to your account, tap your avatar in the upper right corner, and tap Purchases. If you didn't create a <a class="link" href="https://marketplace.getbootstrap.com/signin/" target="_blank">login</a> or can't remember the information, you can use our handy <a class="link" href="https://themes.getbootstrap.com/redownload/" target="_blank">Redownload page</a>, just remember to use the same email you originally made your purchases with.</p>
          </li>
          <li>
            <h2 class="h1">How do I get help with the theme I purchased?</h2>
            <p class="fs-4">Technical support for each theme is given directly by the creator of the theme. You can contact us <a class="link" href="https://htmlstream.com/contact-us" target="_blank">here</a></p>
          </li>
          <li>
            <h2 class="h1">Is Front Admin available on other web application platforms?</h2>
            <p class="fs-4">Since the theme is a static HTML template, we do not offer any tutorials or any other materials on how to integrate our templates with any CMS, Web Application framework, or any other similar technology. However, since our templates are static HTML/CSS and JS templates, then they should be compatible with any backend technology.</p>
          </li>
          <li>
            <h2 class="h1">How can I access a Figma or Sketch file?</h2>
            <p class="fs-4">Unfortunately, the design files are not available. We will consider the possibility of adding this option in the near future. However, we cannot provide any ETA regarding the release.</p>
          </li>
        </ul>
        <hr class="my-7">
        <div class="text-center">
          <h3>¿No encontraste la respuesta a tu pregunta?</h3>
          <p><a class="link" href="/">Envíanos un mensaje</a> y te responderemos lo antes posible.</p>
        </div>
      </div>
    </div>
  </main>

  <footer class="container-lg text-center py-10">
    <!-- Redes Sociales -->
    <ul class="list-inline mb-3">
      <li class="list-inline-item">
        <a class="btn btn-soft-secondary btn-sm btn-icon rounded-circle" href="https://www.facebook.com/share/1BzyjcWxV1/">
          <i class="bi-facebook"></i>
        </a>
      </li>

      <li class="list-inline-item">
        <a class="btn btn-soft-secondary btn-sm btn-icon rounded-circle" href="https://www.instagram.com/bodegasmultiphlex?igsh=MXZlcHBxdzU2M2tsMw==">
          <i class="bi-instagram"></i>
        </a>
      </li>
    </ul>
    <p class="mb-0">&copy; Bodegas Multiplex. 2025. Todos los derechos reservados.</p>
  </footer>
  
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/jquery-migrate/dist/jquery-migrate.min.js') }}"></script>
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/hs-header/dist/hs-header.min.js') }}"></script>
  <script src="{{ asset('vendor/hs-img-compare/hs-img-compare.js') }}"></script>
  <script src="{{ asset('vendor/hs-go-to/dist/hs-go-to.min.js') }}"></script>
  <script src="{{ asset('js/hs.core.js') }}"></script>

  <script>
    // Funcionalidad adicional para el navbar
    document.addEventListener('DOMContentLoaded', function() {
      // Activar el enlace activo según la página actual
      const currentPath = window.location.pathname;
      const navLinks = document.querySelectorAll('.nav-link');
      
      navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
          link.classList.add('active');
        }
      });
      
      // Simular funcionalidad del carrito (para demo)
      const cartBadge = document.querySelector('.badge');
      let cartCount = 3; // Contador inicial
      
      // Función para actualizar el carrito
      function updateCart(count) {
        cartCount = count;
        cartBadge.textContent = cartCount;
        
        // Animación cuando se actualiza
        cartBadge.style.animation = 'none';
        setTimeout(() => {
          cartBadge.style.animation = 'pulse 0.5s ease-in-out';
        }, 10);
      }
      
      // Ejemplo: agregar producto al carrito (para demo)
      window.addToCart = function() {
        updateCart(cartCount + 1);
        // Aquí iría la lógica real para agregar al carrito
        console.log('Producto agregado al carrito');
      }
      
      // Búsqueda funcional
      const searchForm = document.querySelector('form[role="search"]');
      const searchInput = searchForm.querySelector('input[type="search"]');
      
      searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const query = searchInput.value.trim();
        if (query) {
          // Aquí iría la lógica de búsqueda
          console.log('Buscando:', query);
          alert('Buscando: ' + query);
        }
      });
    });
  </script>

</body>
</html>