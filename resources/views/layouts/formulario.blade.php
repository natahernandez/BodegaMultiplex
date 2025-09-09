<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>Bodega Multiplex - @yield('title')</title>

  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap-icons/font/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
  <link rel="preload" href="{{ asset('css/theme.css') }}" data-hs-appearance="default" as="style">
  

</head>

<body>

  <main id="content" role="main" class="main">
    <div class="position-fixed top-0 end-0 start-0 bg-img-start" style="height: 32rem; background-image: url('{{ asset('front-dashboard-v2.1.1/dist/assets/svg/components/card-6.svg') }}');">

    @yield('content')

    @include('includes.formulario.footer')

    
  </main>

  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/hs-toggle-password/dist/js/hs-toggle-password.js') }}"></script>
  <script src="{{ asset('js/hs.core.js') }}"></script>
  <script src="{{ asset('js/hs.bs-validation.js') }}"></script>

</body>
</html>
