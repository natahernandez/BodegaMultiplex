<!-- ========== Encabezado Navbar ========== -->
<header id="header" class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-container navbar-bordered bg-white">
  <div class="navbar-nav-wrap">

    <!-- Logotipo -->
    <a class="navbar-brand" href="{{ route('home') }}" aria-label="Front">
      <img class="navbar-brand-logo" src="{{ asset('svg/logos/logo.svg') }}" alt="Logo" style="height: 40px;">
    </a>

    <div class="navbar-nav-wrap-content-start">
      <!-- Botón de menú lateral -->
      <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
        <i class="bi-arrow-bar-left navbar-toggler-short-align" data-bs-toggle="tooltip" title="Collapse"></i>
        <i class="bi-arrow-bar-right navbar-toggler-full-align" data-bs-toggle="tooltip" title="Expand"></i>
      </button>
    </div>

    <div class="navbar-nav-wrap-content-end">
      @include('includes.app.user-dropdown')
    </div>
  </div>
</header> 