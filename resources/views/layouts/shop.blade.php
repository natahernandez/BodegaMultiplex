<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Bodegas Multiplex')</title>
  <script>
    // Mostrar hora de Guatemala en el header si lo necesitas
    document.addEventListener('DOMContentLoaded', function(){
      const tzSpan = document.getElementById('gt-time');
      if (!tzSpan) return;
      function updateTime(){
        try{
          const now = new Date();
          const options = { timeZone: 'America/Guatemala', hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' };
          tzSpan.textContent = new Intl.DateTimeFormat('es-GT', options).format(now);
        }catch(e){}
      }
      updateTime(); setInterval(updateTime, 1000);
    });
  </script>

  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Estilos compartidos de la tienda -->
  <link rel="stylesheet" href="{{ asset('css/shop.css') }}">
  @stack('styles')
</head>

<body>
  <!-- Header público de la tienda -->
  <header class="shop-header">
    <div class="container py-3">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="mb-0 text-primary">
            <a class="navbar-brand p-0 m-0" href="/" style="display:inline-block;">
              Bodegas <span class="text-warning">Multiplex</span>
            </a>
          </h3>
          <small class="text-muted">Hora GT: <span id="gt-time"></span></small>
        </div>
        <div class="col-auto">
          <a href="{{ route('shop.cart') }}" class="btn btn-outline-primary position-relative">
            <i class="bi-cart3"></i>
            <span id="cartCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              {{ session('carrito') ? array_sum(array_column(session('carrito'), 'cantidad')) : 0 }}
            </span>
          </a>
        </div>
      </div>
    </div>
  </header>

  <!-- Contenido principal -->
  <main class="container py-4">
    @yield('content')
  </main>

  <!-- Scripts base -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Funciones compartidas de la tienda (CSRF, alerts, utilidades) -->
  <script src="{{ asset('js/shop.js') }}"></script>
  @stack('scripts')
</body>
</html>


