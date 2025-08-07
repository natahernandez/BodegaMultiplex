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
          <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('admin-users.show', $adminUser) }}">{{ $adminUser->name }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">Editar</li>
        </ol>
      </nav>
      <h1 class="page-header-title">Editar Administrador</h1>
      <p class="page-header-text">Modifica la información del usuario administrador</p>
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
          <i class="bi-shield-check me-2"></i>Editar Información del Administrador
        </h4>
      </div>
      <!-- End Header -->

      <!-- Body -->
      <div class="card-body">
        <form method="POST" action="{{ route('admin-users.update', $adminUser) }}">
          @csrf
          @method('PUT')

          <!-- Form -->
          <div class="row mb-4">
            <label for="nameLabel" class="col-sm-3 col-form-label form-label">Nombre completo <span class="text-danger">*</span></label>

            <div class="col-sm-9">
              <div class="input-group input-group-merge">
                <div class="input-group-prepend input-group-text">
                  <i class="bi-person"></i>
                </div>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="nameLabel" placeholder="Ej: Juan Pérez" aria-label="Nombre completo" value="{{ old('name', $adminUser->name) }}" required>
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
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="emailLabel" placeholder="admin@empresa.com" aria-label="Email" value="{{ old('email', $adminUser->email) }}" required>
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
            <label for="passwordLabel" class="col-sm-3 col-form-label form-label">Nueva contraseña</label>

            <div class="col-sm-9">
              <div class="input-group input-group-merge">
                <div class="input-group-prepend input-group-text">
                  <i class="bi-key"></i>
                </div>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="passwordLabel" placeholder="••••••••" aria-label="Contraseña">
                <a class="input-group-append input-group-text" href="javascript:;" onclick="togglePassword('passwordLabel')">
                  <i id="passwordLabelIcon" class="bi-eye"></i>
                </a>
              </div>
              @error('password')
                <span class="invalid-feedback d-block">{{ $message }}</span>
              @enderror
              <small class="form-text">Déjalo en blanco para mantener la contraseña actual. Mínimo 8 caracteres si la cambias.</small>
            </div>
          </div>
          <!-- End Form -->

          <!-- Form -->
          <div class="row mb-4">
            <label for="passwordConfirmationLabel" class="col-sm-3 col-form-label form-label">Confirmar nueva contraseña</label>

            <div class="col-sm-9">
              <div class="input-group input-group-merge">
                <div class="input-group-prepend input-group-text">
                  <i class="bi-key"></i>
                </div>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="passwordConfirmationLabel" placeholder="••••••••" aria-label="Confirmar contraseña">
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

          <!-- User Info -->
          <div class="row mb-4">
            <div class="col-sm-3"></div>
            <div class="col-sm-9">
              <div class="alert alert-soft-info">
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <i class="bi-info-circle"></i>
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <h6 class="alert-heading">Información del usuario</h6>
                    <ul class="mb-0">
                      <li>Registrado: {{ $adminUser->created_at->format('d/m/Y H:i') }}</li>
                      <li>Última actualización: {{ $adminUser->updated_at->format('d/m/Y H:i') }}</li>
                      <li>Email verificado: {{ $adminUser->email_verified_at ? 'Sí (' . $adminUser->email_verified_at->format('d/m/Y') . ')' : 'No' }}</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

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
                  <a class="btn btn-white" href="{{ route('admin-users.show', $adminUser) }}">
                    <i class="bi-arrow-left me-1"></i> Cancelar
                  </a>
                  <button type="submit" class="btn btn-primary">
                    <i class="bi-check me-1"></i> Guardar Cambios
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