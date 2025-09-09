@extends('layouts.formulario')

@section('title', 'Iniciar Sesión')
@section('description', 'Ingrese su correo y contraseña para acceder')

@section('content')

    <!-- Formulario -->
    <div class="container py-5 py-sm-7">

        <!-- Logo -->
        <div class="d-flex justify-content-center mb-5">
            <a href="/" class="d-flex align-items-center text-decoration-none">
                <img class="zi-2 me-3" src="{{ asset('svg/logos/logo.png') }}" alt="Bodegas Multiphlex" style="width: 4rem; height: auto;">
                <div>
                    <span class="text-primary fw-bold fs-3">Bodegas</span><span class="text-warning fw-bold fs-3">Multiphlex</span>
                </div>
            </a>
        </div>

        <div class="mx-auto" style="max-width: 30rem;">

            <!-- Tarjeta de inicio de sesión -->
            <div class="card card-lg mb-5">
                <div class="card-body">
                    <form class="js-validate needs-validation" method="POST" action="{{ route('login') }}" novalidate>
                        @csrf
                        <div class="text-center">
                            <div class="mb-5">
                                <h1 class="display-5">Iniciar sesión</h1>
                                <p>¿No tienes una cuenta? <a class="link" href="{{ route('register') }}">Regístrate
                                        aquí</a></p>
                            </div>
                        </div>

                        <!-- Mensajes de error -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi-exclamation-triangle me-2"></i>
                                <strong>Error:</strong> Las credenciales proporcionadas no son correctas.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="form-label" for="signinSrEmail">Correo electrónico</label>
                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                name="email" id="signinSrEmail" tabindex="1" placeholder="email@address.com"
                                aria-label="email@address.com" value="{{ old('email') }}" required autocomplete="email"
                                autofocus>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @else
                                <span class="invalid-feedback">Por favor, ingrese una dirección de correo electrónico
                                    válida.</span>
                            @enderror
                        </div>

                        <!-- Contraseña -->
                        <div class="mb-4">
                            <label class="form-label w-100" for="signupSrPassword" tabindex="0">
                                <span class="d-flex justify-content-between align-items-center">
                                    <span>Contraseña</span>
                                    <!-- Olvidaste tu contraseña -->
                                    <a class="form-label-link mb-0" href="{{ route('password.request') }}">¿Olvidaste tu
                                        contraseña?</a>
                                </span>
                            </label>

                            <div class="input-group input-group-merge" data-hs-validation-validate-class>
                                <input type="password"
                                    class="js-toggle-password form-control form-control-lg @error('password') is-invalid @enderror"
                                    name="password" id="signupSrPassword" placeholder="Ingrese su contraseña"
                                    aria-label="Ingrese su contraseña" required autocomplete="current-password"
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

                        <!-- Recuérdame -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Recuérdame
                            </label>
                        </div>

                        <!-- Boton iniciar sesión -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Iniciar sesión</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection