








@extends('layouts.formulario')

@section('title', 'Iniciar Sesión')
@section('description', 'Ingrese su correo y contraseña para acceder')

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

        <!-- Tarjeta de inicio de sesión -->
        <div class="card card-lg mb-5">
          <div class="card-body">
            <!-- Formulario de inicio de sesión -->
            <form class="js-validate needs-validation" method="POST" action="{{ route('login') }}" novalidate>
            @csrf
            
              <div class="text-center">
                <div class="mb-5">
                  <h1 class="display-5">Iniciar sesión</h1>
                  <p>¿No tienes una cuenta? <a class="link" href="{{ route('register') }}">Regístrate aquí</a></p>
                </div>
              </div>

              <!-- Mensajes de error -->
              @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="bi-exclamation-triangle me-2"></i>
                  <strong>Error:</strong> Las credenciales proporcionadas no son correctas.
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif

              <!-- Formulario de inicio de sesión -->
              <div class="mb-4">
                <label class="form-label" for="signinSrEmail">Correo electrónico</label>
                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" id="signinSrEmail" tabindex="1" placeholder="email@address.com" aria-label="email@address.com" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @else
                    <span class="invalid-feedback">Por favor, ingrese una dirección de correo electrónico válida.</span>
                @enderror
              </div>
              <div class="mb-4">
                <label class="form-label w-100" for="signupSrPassword" tabindex="0">
                  <span class="d-flex justify-content-between align-items-center">
                    <span>Contraseña</span>
                    <a class="form-label-link mb-0" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                  </span>
                </label>

                <div class="input-group input-group-merge" data-hs-validation-validate-class>
                  <input type="password" class="js-toggle-password form-control form-control-lg @error('password') is-invalid @enderror" name="password" id="signupSrPassword" placeholder="Ingrese su contraseña" aria-label="Ingrese su contraseña" required autocomplete="current-password"
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

              <!-- Formulario de inicio de sesión -->
              <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                  Recuérdame
                </label>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Iniciar sesión</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    @endsection