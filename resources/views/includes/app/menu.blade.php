<!-- Logo -->
<a class="navbar-brand" href="{{ route('home') }}" aria-label="Front">
  <img class="navbar-brand-logo" src="{{ asset('svg/logos/logo.png') }}" alt="Bodegas Multiphlex" style="height: 60px; width: auto; object-fit: contain;">
  <img class="navbar-brand-logo-mini" src="{{ asset('svg/logos/logo.png') }}" alt="Bodegas Multiphlex" style="height: 50px; width: auto; object-fit: contain;">
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

    <!-- Ofertas -->
    <div class="nav-item">
      <a class="nav-link dropdown-toggle {{ request()->routeIs(['ofertas.*']) ? 'active' : '' }}" href="#navbarVerticalMenuOfertas" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuOfertas" aria-expanded="{{ request()->routeIs(['ofertas.*']) ? 'true' : 'false' }}" aria-controls="navbarVerticalMenuOfertas">
        <i class="bi-fire nav-icon"></i>
        <span class="nav-link-title">Ofertas</span>
      </a>

      <div id="navbarVerticalMenuOfertas" class="nav-collapse collapse {{ request()->routeIs(['ofertas.*']) ? 'show' : '' }}" data-bs-parent="#navbarVerticalMenu">
        <a class="nav-link {{ request()->routeIs('ofertas.index') ? 'active' : '' }}" href="{{ route('ofertas.index') }}">
          <i class="bi-percent nav-icon"></i>
          <span class="nav-link-title">Gestionar Ofertas</span>
        </a>
      </div>
    </div>

    <!-- Mantenimiento -->
    <div class="nav-item">
      <a class="nav-link dropdown-toggle {{ request()->routeIs(['brands.*', 'categories.*']) ? 'active' : '' }}" href="#navbarVerticalMenuMantenimiento" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuMantenimiento" aria-expanded="{{ request()->routeIs(['brands.*', 'categories.*']) ? 'true' : 'false' }}" aria-controls="navbarVerticalMenuMantenimiento">
        <i class="bi-gear nav-icon"></i>
        <span class="nav-link-title">Mantenimiento</span>
      </a>

      <div id="navbarVerticalMenuMantenimiento" class="nav-collapse collapse {{ request()->routeIs(['brands.*', 'categories.*']) ? 'show' : '' }}" data-bs-parent="#navbarVerticalMenu">
        <a class="nav-link {{ request()->routeIs('brands.*') ? 'active' : '' }}" href="{{ route('brands.index') }}">
          <i class="bi-tags nav-icon"></i>
          <span class="nav-link-title">Marcas</span>
        </a>
        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
          <i class="bi-grid nav-icon"></i>
          <span class="nav-link-title">Categorías</span>
        </a>
      </div>
    </div>

    <!-- Órdenes -->
    <div class="nav-item">
      <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
        <i class="bi-receipt nav-icon"></i>
        <span class="nav-link-title">Órdenes</span>
      </a>
    </div>

    <!-- Administradores -->
    <div class="nav-item">
      <a class="nav-link {{ request()->routeIs('admin-users.*') ? 'active' : '' }}" href="{{ route('admin-users.index') }}">
        <i class="bi-shield-check nav-icon"></i>
        <span class="nav-link-title">Administradores</span>
      </a>
    </div>

  </div>
</div>
<!-- End Content -->