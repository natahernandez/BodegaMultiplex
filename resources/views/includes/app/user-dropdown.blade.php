<div class="dropdown">
  <a class="navbar-dropdown-account-wrapper" href="javascript:;" id="accountNavbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
    <div class="avatar avatar-sm avatar-circle">
      <img class="avatar-img" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Usuario') }}&background=007bff&color=ffffff&size=160" alt="{{ Auth::user()->name ?? 'Usuario' }}">
      <span class="avatar-status avatar-sm-status avatar-status-success"></span>
    </div>
  </a>

  <div class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-account" style="width: 16rem;">
    <div class="dropdown-item-text">
      <div class="d-flex align-items-center">
        <div class="avatar avatar-sm avatar-circle">
          <img class="avatar-img" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Usuario') }}&background=007bff&color=ffffff&size=160" alt="{{ Auth::user()->name ?? 'Usuario' }}">
        </div>
        <div class="flex-grow-1 ms-3">
          <h5 class="mb-0">{{ Auth::user()->name ?? 'Usuario' }}</h5>
          <p class="card-text text-body">{{ Auth::user()->email ?? 'user@example.com' }}</p>
        </div>
      </div>
    </div>
    <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
      <i class="bi-power"></i> Cerrar Sesión
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
  </div>
</div> 