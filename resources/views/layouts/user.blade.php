<!DOCTYPE html>
<html lang="es">
<head>
  <!-- Required Meta Tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Title -->
  <title>{{ $pageTitle ?? 'Mis Pedidos' }} | {{ config('app.name') }}</title>

  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

  <!-- CSS Implementing Plugins -->
  <link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap-icons/font/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/tom-select/dist/css/tom-select.bootstrap5.css') }}">

  <!-- CSS Front -->
  <link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/css/theme.min.css') }}">

  @yield('styles')
</head>

<body class="has-navbar-vertical-aside navbar-vertical-aside-show-xl   footer-offset">

  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/js/hs.theme-appearance.js') }}"></script>
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside-mini-cache.js') }}"></script>

  <!-- ========== HEADER ========== -->
  <header id="header" class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-container navbar-bordered bg-white">
    <div class="navbar-nav-wrap">
      <!-- Logo -->
      <a class="navbar-brand" href="{{ route('user.orders.index') }}" aria-label="{{ config('app.name') }}">
        <img class="navbar-brand-logo" src="{{ asset('front-dashboard-v2.1.1/dist/assets/svg/logos/logo.svg') }}" alt="Logo" data-hs-theme-appearance="default">
        <img class="navbar-brand-logo" src="{{ asset('front-dashboard-v2.1.1/dist/assets/svg/logos-light/logo.svg') }}" alt="Logo" data-hs-theme-appearance="dark">
        <img class="navbar-brand-logo-mini" src="{{ asset('front-dashboard-v2.1.1/dist/assets/svg/logos/logo-short.svg') }}" alt="Logo" data-hs-theme-appearance="default">
        <img class="navbar-brand-logo-mini" src="{{ asset('front-dashboard-v2.1.1/dist/assets/svg/logos-light/logo-short.svg') }}" alt="Logo" data-hs-theme-appearance="dark">
      </a>
      <!-- End Logo -->

      <div class="navbar-nav-wrap-content-start">
        <!-- Navbar Vertical Toggle -->
        <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
          <i class="bi-arrow-bar-left navbar-toggler-short-align" data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' data-bs-toggle="tooltip" data-bs-placement="right" title="Collapse"></i>
          <i class="bi-arrow-bar-right navbar-toggler-full-align" data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' data-bs-toggle="tooltip" data-bs-placement="right" title="Expand"></i>
        </button>
        <!-- End Navbar Vertical Toggle -->
      </div>

      <div class="navbar-nav-wrap-content-end">
        <!-- Navbar -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <!-- Account -->
            <div class="dropdown">
              <a class="navbar-dropdown-account-wrapper" href="javascript:;" id="accountNavbarDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" data-bs-dropdown-animation>
                <div class="avatar avatar-sm avatar-circle">
                  <img class="avatar-img" src="{{ asset('front-dashboard-v2.1.1/dist/assets/img/160x160/img6.jpg') }}" alt="Image Description">
                  <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                </div>
              </a>

              <div class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless navbar-dropdown-account" aria-labelledby="accountNavbarDropdown" style="width: 16rem;">
                <div class="dropdown-item-text">
                  <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm avatar-circle">
                      <img class="avatar-img" src="{{ asset('front-dashboard-v2.1.1/dist/assets/img/160x160/img6.jpg') }}" alt="Image Description">
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <h5 class="mb-0">{{ auth()->user()->name }}</h5>
                      <p class="card-text text-body">{{ auth()->user()->email }}</p>
                    </div>
                  </div>
                </div>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="{{ route('user.orders.index') }}">
                  <span class="dropdown-item-icon">
                    <i class="bi-receipt"></i>
                  </span>
                  Mis Pedidos
                </a>

                <div class="dropdown-divider"></div>

                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item">
                    <span class="dropdown-item-icon">
                      <i class="bi-box-arrow-right"></i>
                    </span>
                    Cerrar Sesión
                  </button>
                </form>
              </div>
            </div>
            <!-- End Account -->
          </li>
        </ul>
        <!-- End Navbar -->
      </div>
    </div>
  </header>

  <!-- ========== END HEADER ========== -->

  <!-- ========== ASIDE ========== -->
  <aside class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered bg-white">
    <div class="navbar-vertical-container">
      <div class="navbar-vertical-footer-offset">

        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('user.orders.index') }}" aria-label="{{ config('app.name') }}">
          <img class="navbar-brand-logo" src="{{ asset('front-dashboard-v2.1.1/dist/assets/svg/logos/logo.svg') }}" alt="Logo" data-hs-theme-appearance="default">
          <img class="navbar-brand-logo" src="{{ asset('front-dashboard-v2.1.1/dist/assets/svg/logos-light/logo.svg') }}" alt="Logo" data-hs-theme-appearance="dark">
          <img class="navbar-brand-logo-mini" src="{{ asset('front-dashboard-v2.1.1/dist/assets/svg/logos/logo-short.svg') }}" alt="Logo" data-hs-theme-appearance="default">
          <img class="navbar-brand-logo-mini" src="{{ asset('front-dashboard-v2.1.1/dist/assets/svg/logos-light/logo-short.svg') }}" alt="Logo" data-hs-theme-appearance="dark">
        </a>
        <!-- End Logo -->

        <!-- Navbar Vertical Content -->
        <div class="navbar-vertical-content">
          <div id="navbarVerticalMenu" class="nav nav-pills nav-vertical card-navbar-nav">

            <!-- Mis Pedidos -->
            <div class="nav-item">
              <a class="nav-link {{ request()->routeIs('user.orders.*') ? 'active' : '' }}" href="{{ route('user.orders.index') }}">
                <i class="bi-receipt nav-icon"></i>
                <span class="nav-link-title">Mis Pedidos</span>
              </a>
            </div>
            <!-- End Mis Pedidos -->

          </div>
        </div>
        <!-- End Navbar Vertical Content -->

      </div>
    </div>
  </aside>

  <!-- ========== END ASIDE ========== -->

  <!-- ========== MAIN CONTENT ========== -->
  <main id="content" role="main" class="main">
    @yield('content')
  </main>
  <!-- ========== END MAIN CONTENT ========== -->

  <!-- ========== FOOTER ========== -->
  <div class="footer">
    <div class="row justify-content-between align-items-center">
      <div class="col">
        <p class="fs-6 text-muted mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}. <span class="d-none d-sm-inline-block">Todos los derechos reservados.</span></p>
      </div>

      <div class="col-auto">
        <div class="d-flex justify-content-end">
          <!-- List -->
          <ul class="list-inline list-separator">
            <li class="list-inline-item">
              <a class="list-separator-link" href="#!">FAQ</a>
            </li>

            <li class="list-inline-item">
              <a class="list-separator-link" href="#!">Licencia</a>
            </li>

            <li class="list-inline-item">
              <!-- Keyboard Shortcuts Toggle -->
              <button class="btn btn-ghost-secondary btn btn-icon btn-ghost-secondary rounded-circle" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasKeyboardShortcuts" aria-controls="offcanvasKeyboardShortcuts">
                <i class="bi-command"></i>
              </button>
              <!-- End Keyboard Shortcuts Toggle -->
            </li>
          </ul>
          <!-- End List -->
        </div>
      </div>
    </div>
  </div>
  <!-- ========== END FOOTER ========== -->

  <!-- JS Implementing Plugins -->
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside.min.js') }}"></script>
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/hs-form-search/dist/hs-form-search.min.js') }}"></script>
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/tom-select/dist/js/tom-select.complete.min.js') }}"></script>

  <!-- JS Front -->
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/js/theme.min.js') }}"></script>

  <!-- JS Plugins Init. -->
  <script>
    (function() {
      window.onload = function () {
        // INITIALIZATION OF NAVBAR VERTICAL ASIDE
        new HSSideNav('.js-navbar-vertical-aside').init()

        // INITIALIZATION OF FORM SEARCH
        new HSFormSearch('.js-form-search')

        // INITIALIZATION OF BOOTSTRAP DROPDOWN
        HSBsDropdown.init()

        // INITIALIZATION OF SELECT
        HSCore.components.HSTomSelect.init('.js-select')
      }
    })()
  </script>

  @yield('scripts')
</body>
</html>