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
    
    <!-- Collapse Dashboard -->
    <div class="nav-item">
      <a class="nav-link dropdown-toggle {{ request()->routeIs('home') ? 'active' : '' }}" href="#navbarVerticalMenuDashboards" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuDashboards" aria-expanded="{{ request()->routeIs('home') ? 'true' : 'false' }}">
        <i class="bi-house-door nav-icon"></i>
        <span class="nav-link-title">Dashboard</span>
      </a>

      <div id="navbarVerticalMenuDashboards" class="nav-collapse collapse {{ request()->routeIs('home') ? 'show' : '' }}">
        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
          <i class="bi-speedometer2 nav-icon"></i>
          Principal
        </a>
      </div>
    </div>
    <!-- End Collapse -->
    
    <span class="dropdown-header mt-4">Gestión Comercial</span>

    <!-- Productos -->
    <div class="nav-item">
      <a class="nav-link dropdown-toggle {{ request()->routeIs('productos.*') ? 'active' : '' }}" href="#navbarVerticalMenuProductos" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuProductos" aria-expanded="{{ request()->routeIs('productos.*') ? 'true' : 'false' }}">
        <i class="bi-box-seam nav-icon"></i>
        <span class="nav-link-title">Productos</span>
      </a>

      <div id="navbarVerticalMenuProductos" class="nav-collapse collapse {{ request()->routeIs('productos.*') ? 'show' : '' }}">
        <a class="nav-link {{ request()->routeIs('productos.index') ? 'active' : '' }}" href="{{ route('productos.index') }}">
          <i class="bi-list-ul nav-icon"></i>
          Lista de Productos
        </a>
        <a class="nav-link {{ request()->routeIs('productos.create') ? 'active' : '' }}" href="{{ route('productos.create') }}">
          <i class="bi-plus-circle nav-icon"></i>
          Agregar Producto
        </a>
        <a class="nav-link" href="#">
          <i class="bi-tags nav-icon"></i>
          Categorías
        </a>
        <a class="nav-link" href="#">
          <i class="bi-bar-chart nav-icon"></i>
          Reportes
        </a>
      </div>
    </div>

    <!-- Inventario -->
    <div class="nav-item">
      <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuInventario" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuInventario">
        <i class="bi-boxes nav-icon"></i>
        <span class="nav-link-title">Inventario</span>
      </a>

      <div id="navbarVerticalMenuInventario" class="nav-collapse collapse">
        <a class="nav-link" href="#">
          <i class="bi-archive nav-icon"></i>
          Stock Actual
        </a>
        <a class="nav-link" href="#">
          <i class="bi-arrow-down-circle nav-icon"></i>
          Entradas
        </a>
        <a class="nav-link" href="#">
          <i class="bi-arrow-up-circle nav-icon"></i>
          Salidas
        </a>
        <a class="nav-link" href="#">
          <i class="bi-exclamation-triangle nav-icon"></i>
          Stock Mínimo
        </a>
      </div>
    </div>

    <!-- Ventas -->
    <div class="nav-item">
      <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuVentas" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuVentas">
        <i class="bi-cart-check nav-icon"></i>
        <span class="nav-link-title">Ventas</span>
      </a>

      <div id="navbarVerticalMenuVentas" class="nav-collapse collapse">
        <a class="nav-link" href="#">
          <i class="bi-receipt nav-icon"></i>
          Nueva Venta
        </a>
        <a class="nav-link" href="#">
          <i class="bi-list-check nav-icon"></i>
          Historial de Ventas
        </a>
        <a class="nav-link" href="#">
          <i class="bi-clock-history nav-icon"></i>
          Ventas Pendientes
        </a>
        <a class="nav-link" href="#">
          <i class="bi-graph-up nav-icon"></i>
          Reportes de Ventas
        </a>
      </div>
    </div>

    <!-- Clientes -->
    <div class="nav-item">
      <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuClientes" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuClientes">
        <i class="bi-people nav-icon"></i>
        <span class="nav-link-title">Clientes</span>
      </a>

      <div id="navbarVerticalMenuClientes" class="nav-collapse collapse">
        <a class="nav-link" href="#">
          <i class="bi-person-lines-fill nav-icon"></i>
          Lista de Clientes
        </a>
        <a class="nav-link" href="#">
          <i class="bi-person-plus nav-icon"></i>
          Agregar Cliente
        </a>
        <a class="nav-link" href="#">
          <i class="bi-credit-card nav-icon"></i>
          Créditos
        </a>
        <a class="nav-link" href="#">
          <i class="bi-pie-chart nav-icon"></i>
          Análisis de Clientes
        </a>
      </div>
    </div>

    <span class="dropdown-header mt-4">Administración</span>

    <!-- Gestión de Usuarios -->
    <div class="nav-item">
      <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuUsuarios" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuUsuarios">
        <i class="bi-person-gear nav-icon"></i>
        <span class="nav-link-title">Usuarios</span>
      </a>

      <div id="navbarVerticalMenuUsuarios" class="nav-collapse collapse">
        <a class="nav-link" href="#">
          <i class="bi-people-fill nav-icon"></i>
          Lista de Usuarios
        </a>
        <a class="nav-link" href="#">
          <i class="bi-person-add nav-icon"></i>
          Agregar Usuario
        </a>
        <a class="nav-link" href="#">
          <i class="bi-shield-check nav-icon"></i>
          Roles y Permisos
        </a>
        <a class="nav-link" href="#">
          <i class="bi-activity nav-icon"></i>
          Actividad de Usuarios
        </a>
      </div>
    </div>

    <!-- Configuración -->
    <div class="nav-item">
      <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuConfig" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuConfig">
        <i class="bi-gear nav-icon"></i>
        <span class="nav-link-title">Configuración</span>
      </a>

      <div id="navbarVerticalMenuConfig" class="nav-collapse collapse">
        <a class="nav-link" href="#">
          <i class="bi-building nav-icon"></i>
          Datos de la Empresa
        </a>
        <a class="nav-link" href="#">
          <i class="bi-currency-dollar nav-icon"></i>
          Configuración Fiscal
        </a>
        <a class="nav-link" href="#">
          <i class="bi-printer nav-icon"></i>
          Configuración de Impresión
        </a>
        <a class="nav-link" href="#">
          <i class="bi-cloud-arrow-down nav-icon"></i>
          Respaldos
        </a>
      </div>
    </div>

    <span class="dropdown-header mt-4">Herramientas</span>

    <div class="nav-item">
      <a class="nav-link" href="#">
        <i class="bi-calculator nav-icon"></i>
        <span class="nav-link-title">Calculadora</span>
      </a>
    </div>

    <div class="nav-item">
      <a class="nav-link" href="#">
        <i class="bi-calendar-event nav-icon"></i>
        <span class="nav-link-title">Calendario</span>
      </a>
    </div>

    <div class="nav-item">
      <a class="nav-link" href="#">
        <i class="bi-file-earmark-text nav-icon"></i>
        <span class="nav-link-title">Reportes</span>
      </a>
    </div>

  </div>
</div>
<!-- End Content -->