<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $pageTitle ?? $title ?? 'BodegaMultiplex' }} - Dashboard</title>
@if(isset($pageDescription))
  <meta name="description" content="{{ $pageDescription }}">
@endif

<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

{{-- Vendors (elige una opción: local o CDN) --}}
{{-- Opción CDN (simple y rápido) --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="https://unpkg.com/jsvectormap@1.5.3/dist/css/jsvectormap.min.css">

{{-- Opción LOCAL (si trajiste los vendors en front-dashboard-v2.1.1/dist/...) --}}
{{-- <link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap-icons/font/bootstrap-icons.min.css') }}"> --}}
{{-- <link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/daterangepicker/daterangepicker.css') }}"> --}}
{{-- <link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/jsvectormap/dist/css/jsvectormap.min.css') }}"> --}}

{{-- CSS propios --}}
<link rel="stylesheet" href="{{ asset('css/theme.css') }}">

@yield('styles')
