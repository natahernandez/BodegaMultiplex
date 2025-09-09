<!-- ========== Encabezado Navbar ========== -->
<header id="header" class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-container navbar-bordered bg-white">
  <div class="navbar-nav-wrap">

    <div class="navbar-nav-wrap-content-start">
      <!-- Botón de menú lateral - Solo visible en móviles, ahora a la izquierda -->
      <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler d-lg-none">
        <i class="bi-list navbar-toggler-default" data-bs-toggle="tooltip" title="Abrir menú"></i>
        <i class="bi-x navbar-toggler-toggled" data-bs-toggle="tooltip" title="Cerrar menú"></i>
      </button>
    </div>

    <div class="navbar-nav-wrap-content-end">
      @include('includes.app.user-dropdown')
    </div>
  </div>
</header> 