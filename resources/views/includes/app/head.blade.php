<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title>{{ $pageTitle ?? $title ?? 'BodegaMultiplex' }} - Dashboard</title>

@if(isset($pageDescription))
<meta name="description" content="{{ $pageDescription }}">
@endif

<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap-icons/font/bootstrap-icons.css') }}">
<link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/jsvectormap/dist/css/jsvectormap.min.css') }}">
<link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/src/assets/css/theme.css') }}">

@yield('styles') 