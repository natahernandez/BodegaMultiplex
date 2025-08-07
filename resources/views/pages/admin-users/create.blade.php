@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center mb-3">
    <div class="col-sm">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-no-gutter">
          <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('admin-users.index') }}">Administradores</a></li>
          <li class="breadcrumb-item active" aria-current="page">Nuevo Administrador</li>
        </ol>
      </nav>
      <h1 class="page-header-title">Crear Nuevo Administrador</h1>
      <p class="page-header-text">Registra un nuevo usuario con acceso completo al dashboard</p>
    </div>
  </div>
</div>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <!-- Card -->
    <div class="card">
      <!-- Header -->
      <div class="card-header">
        <h4 class="card-header-title">
          <i class="bi-shield-check me-2"></i>Información del Administrador
        </h4>
      </div>
      <!-- End Header -->

      <!-- Body -->
      <div class="card-body">
        <form method="POST" action="{{ route('admin-users.store') }}">
          @csrf

          <!-- Form -->
          <div class="row mb-4">
            <label for="nameLabel" class="col-sm-3 col-form-label form-label">Nombre completo <span class="text-danger">*</span></label>

            <div class="col-sm-9">
              <div class="input-group input-group-merge">
                <div class="input-group-prepend input-group-text">
                  <i class="bi-person"></i>
                </div>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="nameLabel" placeholder="Ej: Juan Pérez" aria-label="Nombre completo" value="{{ old('name') }}" required>
              </div>
              @error('name')
                <span class="invalid-feedback d-block">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <!-- End Form -->

          <!-- Form -->
          <div class="row mb-4">
            <label for="emailLabel" class="col-sm-3 col-form-label form-label">Email <span class="text-danger">*</span></label>

            <div class="col-sm-9">
              <div class="input-group input-group-merge">
                <div class="input-group-prepend input-group-text">
                  <i class="bi-at"></i>
                </div>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="emailLabel" placeholder="admin@empresa.com" aria-label="Email" value="{{ old('email') }}" required>
              </div>
              @error('email')
                <span class="invalid-feedback d-block">{{ $message }}</span>
              @enderror
              <small class="form-text">Este email se usará para iniciar sesión en el dashboard.</small>
            </div>
          </div>
          <!-- End Form -->

          <!-- Form -->
          <div class="row mb-4">
            <label for="passwordLabel" class="col-sm-3 col-form-label form-label">Contraseña <span class="text-danger">*</span></label>

            <div class="col-sm-9">
              <div class="input-group input-group-merge">
                <div class="input-group-prepend input-group-text">
                  <i class="bi-key"></i>
                </div>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="passwordLabel" placeholder="••••••••" aria-label="Contraseña" required>
                <a class="input-group-append input-group-text" href="javascript:;" onclick="togglePassword('passwordLabel')">
                  <i id="passwordLabelIcon" class="bi-eye"></i>
                </a>
              </div>
              @error('password')
                <span class="invalid-feedback d-block">{{ $message }}</span>
              @enderror
              <small class="form-text">Mínimo 8 caracteres. Se recomienda incluir mayúsculas, minúsculas y números.</small>
            </div>
          </div>
          <!-- End Form -->

          <!-- Form -->
          <div class="row mb-4">
            <label for="passwordConfirmationLabel" class="col-sm-3 col-form-label form-label">Confirmar contraseña <span class="text-danger">*</span></label>

            <div class="col-sm-9">
              <div class="input-group input-group-merge">
                <div class="input-group-prepend input-group-text">
                  <i class="bi-key"></i>
                </div>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="passwordConfirmationLabel" placeholder="••••••••" aria-label="Confirmar contraseña" required>
                <a class="input-group-append input-group-text" href="javascript:;" onclick="togglePassword('passwordConfirmationLabel')">
                  <i id="passwordConfirmationLabelIcon" class="bi-eye"></i>
                </a>
              </div>
              @error('password_confirmation')
                <span class="invalid-feedback d-block">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <!-- End Form -->

          <!-- Alert -->
          <div class="alert alert-soft-primary">
            <div class="d-flex">
              <div class="flex-shrink-0">
                <i class="bi-shield-check"></i>
              </div>
              <div class="flex-grow-1 ms-3">
                <h5 class="alert-heading">Permisos de Administrador</h5>
                <p class="mb-0">Este usuario tendrá acceso completo al dashboard administrativo, incluyendo:</p>
                <ul class="mb-0">
                  <li>Gestión de productos</li>
                  <li>Gestión de órdenes</li>
                  <li>Acceso a reportes y estadísticas</li>
                  <li>Gestión de otros administradores</li>
                </ul>
              </div>
            </div>
          </div>
          <!-- End Alert -->

          <!-- Sticky Block End Point -->
          <div id="stickyBlockEndPoint"></div>

          <!-- Sticky Block -->
          <div class="js-sticky-block card" data-hs-sticky-block-options='{
                 "parentSelector": "#stickyBlockEndPoint",
                 "targetSelector": "#header",
                 "breakpoint": "lg",
                 "startPoint": "#stickyBlockEndPoint",
                 "endPoint": "#stickyBlockEndPoint"
               }'>
            <div class="card-body">
              <div class="d-flex justify-content-end">
                <div class="d-flex gap-3">
                  <a class="btn btn-white" href="{{ route('admin-users.index') }}">
                    <i class="bi-arrow-left me-1"></i> Cancelar
                  </a>
                  <button type="submit" class="btn btn-primary">
                    <i class="bi-check me-1"></i> Crear Administrador
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- End Sticky Block -->
        </form>
      </div>
      <!-- End Body -->
    </div>
    <!-- End Card -->
  </div>
</div>

@endsection

@section('scripts')
<script>
// Toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + 'Icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi-eye';
    }
}
</script>
@endsection