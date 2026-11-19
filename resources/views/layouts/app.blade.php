<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Portal Survei Kepuasan Klien — PT. Len Railways Systems">
    <title>@yield('title', 'Portal Klien · PT. Len Railways Systems')</title>

    {{-- Asset Bundler: Vite jika manifest tersedia, Fallback ke Asset Statis & CDN untuk Prototipe --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
    @endif

    {{-- Font Premium: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen">

    @yield('content')

    {{-- Script Utama Prototipe jika tidak menggunakan Vite --}}
    @if (!file_exists(public_path('build/manifest.json')) && !file_exists(public_path('hot')))
        <script src="{{ asset('js/portal.js') }}"></script>
    @endif

    @stack('scripts')
</body>
</html>
