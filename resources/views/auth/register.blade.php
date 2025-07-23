@extends('layouts.formulario')

@section('title', 'Registrarse')
@section('description', 'Crea tu cuenta para comenzar a comprar')

@section('content')

<style>
  /* Estilos para mensajes de error */
  .alert {
    border: none;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
  }
  
  .alert-danger {
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    color: #721c24;
  }
  
  .form-control.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
  }
  
  .form-control.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
  }
  
  .btn-close {
    opacity: 0.7;
  }
  
  .btn-close:hover {
    opacity: 1;
  }
</style>

<!-- Formulario -->
<div class="container py-5 py-sm-7">
      <a class="d-flex justify-content-center mb-5" href="/">
        <img class="zi-2" src="{{ asset('svg/logos/logo.svg') }}" alt="Image Description" style="width: 8rem;">
      </a>

      <div class="mx-auto" style="max-width: 30rem;">

        <!-- Tarjeta de registro -->
        <div class="card card-lg mb-5">
          <div class="card-body">
            <!-- Formulario de registro -->
            <form class="js-validate needs-validation" method="POST" action="{{ route('register') }}" novalidate>
            @csrf
            
              <div class="text-center">
                <div class="mb-5">
                  <h1 class="display-5">Crear cuenta</h1>
                  <p>¿Ya tienes una cuenta? <a class="link" href="{{ route('login') }}">Inicia sesión aquí</a></p>
                </div>
              </div>

              <!-- Mensajes de error -->
              @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="bi-exclamation-triangle me-2"></i>
                  <strong>Error:</strong> Por favor, corrige los errores en el formulario.
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif

              <!-- Nombre -->
              <div class="mb-4">
                <label class="form-label" for="name">Nombre completo</label>
                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" id="name" placeholder="Ingrese su nombre completo" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @else
                    <span class="invalid-feedback">Por favor, ingrese su nombre completo.</span>
                @enderror
              </div>

              <!-- Email -->
              <div class="mb-4">
                <label class="form-label" for="email">Correo electrónico</label>
                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" id="email" placeholder="email@address.com" value="{{ old('email') }}" required autocomplete="email">
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @else
                    <span class="invalid-feedback">Por favor, ingrese una dirección de correo electrónico válida.</span>
                @enderror
              </div>

              <!-- Contraseña -->
              <div class="mb-4">
                <label class="form-label" for="password">Contraseña</label>
                <div class="input-group input-group-merge" data-hs-validation-validate-class>
                  <input type="password" class="js-toggle-password form-control form-control-lg @error('password') is-invalid @enderror" name="password" id="password" placeholder="8+ caracteres requeridos" required minlength="8" autocomplete="new-password"
                         data-hs-toggle-password-options='{
                           "target": "#changePassTarget",
                           "defaultClass": "bi-eye-slash",
                           "showClass": "bi-eye",
                           "classChangeTarget": "#changePassIcon"
                         }'>
                  <a id="changePassTarget" class="input-group-append input-group-text" href="javascript:;">
                    <i id="changePassIcon" class="bi-eye"></i>
                  </a>
                </div>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @else
                    <span class="invalid-feedback">Por favor, ingrese una contraseña válida.</span>
                @enderror
              </div>

              <!-- Confirmar Contraseña -->
              <div class="mb-4">
                <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                <div class="input-group input-group-merge" data-hs-validation-validate-class>
                  <input type="password" class="js-toggle-password form-control form-control-lg" name="password_confirmation" id="password_confirmation" placeholder="Confirme su contraseña" required minlength="8" autocomplete="new-password"
                         data-hs-toggle-password-options='{
                           "target": "#changePassConfirmTarget",
                           "defaultClass": "bi-eye-slash",
                           "showClass": "bi-eye",
                           "classChangeTarget": "#changePassConfirmIcon"
                         }'>
                  <a id="changePassConfirmTarget" class="input-group-append input-group-text" href="javascript:;">
                    <i id="changePassConfirmIcon" class="bi-eye"></i>
                  </a>
                </div>
                <span class="invalid-feedback">Por favor, confirme su contraseña.</span>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Crear cuenta</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

@endsection