<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  @include('includes.app.head')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="has-navbar-vertical-aside navbar-vertical-aside-show-xl footer-offset">
  <script>
    // FORCE LAYOUT CLASSES IMMEDIATELY
    document.body.classList.add('has-navbar-vertical-aside');
    if (window.innerWidth >= 1200) {
      document.body.classList.add('navbar-vertical-aside-show-xl');
    }
  </script>
  <script src="{{ asset('vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside-mini-cache.js') }}"></script>

  @include('includes.app.header')

  @include('includes.app.sidebar')

  <main id="content" role="main" class="main">

    <div class="content container-fluid">
      @yield('content')
    </div>

    @include('includes.app.footer')
  </main>

  @include('includes.app.scripts')
</body>
</html>
