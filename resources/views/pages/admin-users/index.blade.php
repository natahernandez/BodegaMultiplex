@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center mb-3">
    <div class="col-sm">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-no-gutter">
          <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Administradores</li>
        </ol>
      </nav>
      <h1 class="page-header-title">Gestión de Administradores</h1>
      <p class="page-header-text">Administra los usuarios con acceso al dashboard</p>
    </div>
    <div class="col-sm-auto">
      <a class="btn btn-primary" href="{{ route('admin-users.create') }}">
        <i class="bi-plus me-1"></i> Nuevo Administrador
      </a>
    </div>
  </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
  <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
    <div class="card h-100">
      <div class="card-body">
        <h6 class="card-subtitle mb-2">Total Administradores</h6>
        <div class="row align-items-center gx-2">
          <div class="col">
            <span class="display-4 text-primary">{{ $stats['total_admins'] }}</span>
          </div>
          <div class="col-auto">
            <i class="bi-shield-check text-primary" style="font-size: 2rem;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
    <div class="card h-100">
      <div class="card-body">
        <h6 class="card-subtitle mb-2">Total Clientes</h6>
        <div class="row align-items-center gx-2">
          <div class="col">
            <span class="display-4 text-info">{{ $stats['total_clients'] }}</span>
          </div>
          <div class="col-auto">
            <i class="bi-people text-info" style="font-size: 2rem;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
    <div class="card h-100">
      <div class="card-body">
        <h6 class="card-subtitle mb-2">Total Usuarios</h6>
        <div class="row align-items-center gx-2">
          <div class="col">
            <span class="display-4 text-success">{{ $stats['total_users'] }}</span>
          </div>
          <div class="col-auto">
            <i class="bi-person text-success" style="font-size: 2rem;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
    <div class="card h-100">
      <div class="card-body">
        <h6 class="card-subtitle mb-2">Nuevos (30 días)</h6>
        <div class="row align-items-center gx-2">
          <div class="col">
            <span class="display-4 text-warning">{{ $stats['recent_admins'] }}</span>
          </div>
          <div class="col-auto">
            <i class="bi-person-plus text-warning" style="font-size: 2rem;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Alerts -->
@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
  <div class="d-flex">
    <div class="flex-shrink-0">
      <i class="bi-check-circle-fill"></i>
    </div>
    <div class="flex-grow-1 ms-3">
      {{ session('success') }}
    </div>
  </div>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible" role="alert">
  <div class="d-flex">
    <div class="flex-shrink-0">
      <i class="bi-exclamation-triangle-fill"></i>
    </div>
    <div class="flex-grow-1 ms-3">
      {{ session('error') }}
    </div>
  </div>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Card -->
<div class="card">
  <!-- Header -->
  <div class="card-header card-header-content-md-between">
    <div class="mb-2 mb-md-0">
      <form method="GET" class="d-inline">
        <div class="input-group input-group-merge navbar-input-group">
          <div class="input-group-prepend input-group-text">
            <i class="bi-search"></i>
          </div>
          <input name="search" type="search" class="form-control" placeholder="Buscar administradores..." aria-label="Buscar administradores" value="{{ request('search') }}">
        </div>
      </form>
    </div>

    <div class="d-grid d-sm-flex gap-2">
      <a class="btn btn-primary" href="{{ route('admin-users.create') }}">
        <i class="bi-plus me-1"></i> Nuevo Administrador
      </a>
    </div>
  </div>
  <!-- End Header -->

  <!-- Table -->
  <div class="table-responsive">
    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
      <thead class="thead-light">
        <tr>
          <th class="table-column-pe-0">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="datatableCheckAll">
              <label class="form-check-label" for="datatableCheckAll"></label>
            </div>
          </th>
          <th>Usuario</th>
          <th>Email</th>
          <th>Rol</th>
          <th>Fecha Registro</th>
          <th>Acciones</th>
        </tr>
      </thead>

      <tbody>
        @forelse($users as $user)
        <tr>
          <td class="table-column-pe-0">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="userCheck{{ $user->id }}" value="{{ $user->id }}">
              <label class="form-check-label" for="userCheck{{ $user->id }}"></label>
            </div>
          </td>
          <td>
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0">
                <div class="avatar avatar-xs avatar-soft-primary">
                  <span class="avatar-initials">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                </div>
              </div>
              <div class="flex-grow-1 ms-3">
                <h6 class="text-inherit mb-0">{{ $user->name }}</h6>
                @if($user->id === auth()->id())
                  <small class="text-muted">(Tú)</small>
                @endif
              </div>
            </div>
          </td>
          <td>{{ $user->email }}</td>
          <td>
            <span class="badge bg-soft-primary text-primary">
              <i class="bi-shield-check me-1"></i>{{ $user->getRoleName() }}
            </span>
          </td>
          <td>{{ $user->created_at->format('M d, Y, H:i') }}</td>
          <td>
            <div class="btn-group" role="group">
              <a class="btn btn-white btn-sm" href="{{ route('admin-users.show', $user) }}">
                <i class="bi-eye me-1"></i> Ver
              </a>
              
              @if($user->id !== auth()->id())
              <div class="btn-group">
                <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userActionsDropdown{{ $user->id }}" data-bs-toggle="dropdown" aria-expanded="false"></button>
                <div class="dropdown-menu dropdown-menu-end mt-1">
                  <a class="dropdown-item" href="{{ route('admin-users.edit', $user) }}">
                    <i class="bi-pencil dropdown-item-icon"></i> Editar
                  </a>
                  <div class="dropdown-divider"></div>
                  <button type="button" class="dropdown-item text-danger" onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                    <i class="bi-trash dropdown-item-icon"></i> Eliminar
                  </button>
                </div>
              </div>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center">
            <div class="py-4">
              <div class="mb-3">
                <i class="bi-shield-check text-body" style="font-size: 3rem;"></i>
              </div>
              <h4 class="text-body">No hay administradores</h4>
              <p class="text-body">No se encontraron administradores registrados.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  <!-- Footer -->
  <div class="card-footer">
    <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
      <div class="col-sm mb-2 mb-sm-0">
        <div class="d-flex justify-content-center justify-content-sm-start align-items-center">
          <span class="me-2">Mostrando:</span>
          <span class="text-secondary me-2">{{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} de</span>
          <span>{{ $users->total() }}</span>
        </div>
      </div>
      <div class="col-sm-auto">
        <div class="d-flex justify-content-center justify-content-sm-end">
          {{ $users->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
// Simple checkbox select all functionality
document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('datatableCheckAll');
    const checkboxes = document.querySelectorAll('input[type="checkbox"][id^="userCheck"]');
    
    if (checkAll) {
        checkAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
});

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