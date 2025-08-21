<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  @include('includes.app.head')
</head>

<body class="has-navbar-vertical-aside navbar-vertical-aside-show-xl footer-offset">
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside-mini-cache.js') }}"></script>

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
