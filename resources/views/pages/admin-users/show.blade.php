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
          <li class="breadcrumb-item active" aria-current="page">{{ $adminUser->name }}</li>
        </ol>
      </nav>
      <h1 class="page-header-title">Detalles del Administrador</h1>
      <p class="page-header-text">Información completa del usuario administrador</p>
    </div>
    <div class="col-sm-auto">
      <div class="d-flex gap-2">
        @if($adminUser->id !== auth()->id())
        <a class="btn btn-primary" href="{{ route('admin-users.edit', $adminUser) }}">
          <i class="bi-pencil me-1"></i> Editar
        </a>
        @endif
        <a class="btn btn-white" href="{{ route('admin-users.index') }}">
          <i class="bi-arrow-left me-1"></i> Volver
        </a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-8">
    <!-- Profile Card -->
    <div class="card mb-4">
      <div class="card-header">
        <h4 class="card-title">
          <i class="bi-person me-2"></i>Información Personal
        </h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-sm-3">
            <div class="d-flex justify-content-center mb-3">
              <div class="avatar avatar-xxl avatar-soft-primary">
                <span class="avatar-initials">{{ strtoupper(substr($adminUser->name, 0, 2)) }}</span>
              </div>
            </div>
          </div>
          <div class="col-sm-9">
            <dl class="row">
              <dt class="col-sm-4">Nombre completo:</dt>
              <dd class="col-sm-8">{{ $adminUser->name }}</dd>

              <dt class="col-sm-4">Email:</dt>
              <dd class="col-sm-8">{{ $adminUser->email }}</dd>

              <dt class="col-sm-4">Rol:</dt>
              <dd class="col-sm-8">
                <span class="badge bg-soft-primary text-primary">
                  <i class="bi-shield-check me-1"></i>{{ $adminUser->getRoleName() }}
                </span>
              </dd>

              <dt class="col-sm-4">Estado:</dt>
              <dd class="col-sm-8">
                @if($adminUser->email_verified_at)
                <span class="badge bg-soft-success text-success">
                  <i class="bi-check-circle me-1"></i>Verificado
                </span>
                @else
                <span class="badge bg-soft-warning text-warning">
                  <i class="bi-clock me-1"></i>Pendiente de verificación
                </span>
                @endif
              </dd>

              <dt class="col-sm-4">Fecha de registro:</dt>
              <dd class="col-sm-8">{{ $adminUser->created_at->format('d/m/Y H:i') }}</dd>

              <dt class="col-sm-4">Última actualización:</dt>
              <dd class="col-sm-8">{{ $adminUser->updated_at->format('d/m/Y H:i') }}</dd>
            </dl>
          </div>
        </div>
      </div>
    </div>

    <!-- Access Log Card (placeholder for future implementation) -->
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">
          <i class="bi-clock-history me-2"></i>Actividad Reciente
        </h4>
      </div>
      <div class="card-body">
        <div class="text-center py-4">
          <i class="bi-activity text-muted" style="font-size: 3rem;"></i>
          <h5 class="text-muted mt-2">Registro de actividad</h5>
          <p class="text-muted">Esta funcionalidad estará disponible próximamente.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <!-- Stats Card -->
    <div class="card mb-4">
      <div class="card-header">
        <h4 class="card-title">
          <i class="bi-graph-up me-2"></i>Estadísticas
        </h4>
      </div>
      <div class="card-body">
        <div class="text-center">
          <div class="mb-3">
            <h2 class="display-4 text-primary">{{ $adminUser->created_at->diffInDays(now()) }}</h2>
            <p class="text-muted mb-0">días desde el registro</p>
          </div>
          
          <hr>
          
          <div class="row text-center">
            <div class="col-6">
              <div class="border-end">
                <h5 class="text-success">Activo</h5>
                <small class="text-muted">Estado actual</small>
              </div>
            </div>
            <div class="col-6">
              <h5 class="text-primary">Admin</h5>
              <small class="text-muted">Nivel de acceso</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Actions Card -->
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">
          <i class="bi-gear me-2"></i>Acciones
        </h4>
      </div>
      <div class="card-body">
        <div class="d-grid gap-2">
          @if($adminUser->id !== auth()->id())
          <a href="{{ route('admin-users.edit', $adminUser) }}" class="btn btn-primary">
            <i class="bi-pencil me-2"></i>Editar información
          </a>
          
          <button type="button" class="btn btn-danger" onclick="deleteUser({{ $adminUser->id }}, '{{ $adminUser->name }}')">
            <i class="bi-trash me-2"></i>Eliminar administrador
          </button>
          @else
          <div class="alert alert-soft-info">
            <div class="d-flex">
              <div class="flex-shrink-0">
                <i class="bi-info-circle"></i>
              </div>
              <div class="flex-grow-1 ms-3">
                <p class="mb-0">Esta es tu cuenta actual. Para editarla o eliminarla, contacta a otro administrador.</p>
              </div>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
// Delete user function
function deleteUser(userId, userName) {
    if (confirm(`¿Estás seguro de que quieres eliminar al administrador "${userName}"? Esta acción no se puede deshacer.`)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin-users/${userId}`;
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        const tokenField = document.createElement('input');
        tokenField.type = 'hidden';
        tokenField.name = '_token';
        tokenField.value = '{{ csrf_token() }}';
        
        form.appendChild(methodField);
        form.appendChild(tokenField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection