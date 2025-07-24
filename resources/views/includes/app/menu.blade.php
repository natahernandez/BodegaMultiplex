<!-- Logo -->
<a class="navbar-brand" href="{{ route('home') }}" aria-label="Front">
  <img class="navbar-brand-logo" src="{{ asset('front-dashboard-v2.1.1/src/assets/svg/logos/logo.svg') }}" alt="Logo" data-hs-theme-appearance="default">
  <img class="navbar-brand-logo" src="{{ asset('front-dashboard-v2.1.1/src/assets/svg/logos-light/logo.svg') }}" alt="Logo" data-hs-theme-appearance="dark">
  <img class="navbar-brand-logo-mini" src="{{ asset('front-dashboard-v2.1.1/src/assets/svg/logos/logo-short.svg') }}" alt="Logo" data-hs-theme-appearance="default">
  <img class="navbar-brand-logo-mini" src="{{ asset('front-dashboard-v2.1.1/src/assets/svg/logos-light/logo-short.svg') }}" alt="Logo" data-hs-theme-appearance="dark">
</a>
<!-- End Logo -->

<!-- Navbar Vertical Toggle -->
<button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
  <i class="bi-arrow-bar-left navbar-toggler-short-align"></i>
  <i class="bi-arrow-bar-right navbar-toggler-full-align"></i>
</button>
<!-- End Navbar Vertical Toggle -->

<!-- Content -->
<div class="navbar-vertical-content">
  <div id="navbarVerticalMenu" class="nav nav-pills nav-vertical card-navbar-nav">
    
    <!-- Productos -->
    <div class="nav-item">
      <a class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}" href="{{ route('productos.index') }}">
        <i class="bi-box-seam nav-icon"></i>
        <span class="nav-link-title">Productos</span>
      </a>
    </div>

    <!-- Órdenes -->
    <div class="nav-item">
      <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
        <i class="bi-receipt nav-icon"></i>
        <span class="nav-link-title">Órdenes</span>
      </a>
    </div>

  </div>
</div>
<!-- End Content -->