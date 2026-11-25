<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Portal Mutu & Survei Kepuasan Klien — PT Len Railway Systems')">
    <title>@yield('title', 'PT Len Railway Systems')</title>

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
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen relative overflow-x-hidden selection:bg-[#003B73] selection:text-white flex flex-col">

    {{-- =========================================================================
         HIASAN BACKGROUND GLOBAL (Senada di Halaman Klien & Admin)
         - Siluet Ilustrasi Operation Control Center (OCC) PT Len Railway Systems
         - Ambient Glowing Orbs Khas LRS (Corporate Navy & High-Tech Sky Blue)
         - Engineering Dot Matrix Grid
         ========================================================================= --}}
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden select-none" aria-hidden="true">
        {{-- Pendaran Gradasi Warna Lembut (Ambient Orbs) --}}
        <div class="absolute -top-32 -right-32 w-[520px] h-[520px] rounded-full bg-blue-100/40 blur-3xl"></div>
        <div class="absolute top-1/3 -left-40 w-[460px] h-[460px] rounded-full bg-sky-100/40 blur-3xl"></div>
        <div class="absolute bottom-10 right-1/4 w-[580px] h-[580px] rounded-full bg-slate-200/30 blur-3xl"></div>

        {{-- Grid Titik Halus (Engineering Dot Matrix Texture) --}}
        <div class="absolute inset-0 opacity-[0.035]" style="background-image: radial-gradient(#003B73 1.2px, transparent 1.2px); background-size: 28px 28px;"></div>

        {{-- Siluet Gambar Pusat Kendali Operasi Rel (OCC) PT Len Railway Systems di Sudut Kanan Bawah --}}
        <div class="absolute -right-16 -bottom-10 w-full max-w-2xl h-[420px] opacity-[0.06] bg-no-repeat bg-right-bottom bg-contain"
             style="background-image: url('{{ asset('images/bg-railway-control.jpg') }}'); filter: grayscale(25%); mask-image: linear-gradient(to top left, rgba(0,0,0,1) 20%, transparent 80%); -webkit-mask-image: linear-gradient(to top left, rgba(0,0,0,1) 20%, transparent 80%);">
        </div>
    </div>

    @yield('content')

    {{-- Script Utama Prototipe jika tidak menggunakan Vite --}}
    @if (!file_exists(public_path('build/manifest.json')) && !file_exists(public_path('hot')))
        <script src="{{ asset('js/portal.js') }}"></script>
    @endif

    @stack('scripts')
</body>
</html>
