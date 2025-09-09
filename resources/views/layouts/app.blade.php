<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  @include('includes.app.head')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  
  <!-- Menu Fix CSS - Máxima Prioridad en HEAD -->
  <link rel="stylesheet" href="{{ asset('css/menu-fix.css') }}">
</head>

<body class="has-navbar-vertical-aside footer-offset">
  <script>
    // SOLO AGREGAR CLASES BÁSICAS - EL CSS MANEJA TODO
    document.body.classList.add('has-navbar-vertical-aside');
  </script>
  
  <!-- Menu Fix JS - Solución Directa -->
  <script src="{{ asset('js/menu-fix.js') }}"></script>

  @include('includes.app.header')

  @include('includes.app.sidebar')

  <main id="content" role="main" class="main">

    <div class="content container-fluid">
      <!-- Alertas globales -->
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

      @if(session('warning'))
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
              <i class="bi-exclamation-triangle me-2"></i>{{ session('warning') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
      @endif

      @if(session('info'))
          <div class="alert alert-info alert-dismissible fade show" role="alert">
              <i class="bi-info-circle me-2"></i>{{ session('info') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
      @endif

      @if($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="bi-exclamation-triangle me-2"></i>
              <strong>Errores de validación:</strong>
              <ul class="mb-0 mt-2">
                  @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
      @endif
      
      @yield('content')
    </div>

    @include('includes.app.footer')
  </main>

  @include('includes.app.scripts')
</body>
</html>
