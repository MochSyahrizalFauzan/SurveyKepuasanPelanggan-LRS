@extends('layouts.app')

@section('title', 'Portal Survei Kepuasan Klien · PT. Len Railways Systems')

@section('content')
<div class="min-h-screen bg-transparent flex flex-col antialiased text-slate-800 w-full">

    {{-- =========================================================================
         1. TOP BAR UTAMA: BRAND, IDENTITAS & TAUTAN ADMIN
         Sticky top-0 z-40 agar seluruh konten di bawahnya terdorong rapi tanpa overlap.
         ========================================================================= --}}
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/70 shadow-xs">
        {{-- Garis Aksen Tri-Warna Khas Len --}}
        <div class="h-1 w-full bg-gradient-to-r from-[#003B73] via-sky-500 to-[#D32F2F]"></div>

        <div class="max-w-7xl mx-auto px-4 py-3 md:px-6 md:py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-4">

            {{-- Brand & Identitas Client Portal --}}
            <div class="flex items-center gap-3">
                <div class="h-10 md:h-11 px-2.5 rounded-xl bg-white flex items-center justify-center shrink-0 border border-slate-200 shadow-xs">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="Logo PT. Len Railways Systems"
                         class="h-7 w-auto object-contain">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm md:text-base font-black text-[#003B73] tracking-tight">PT. Len Railways Systems</span>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-blue-50 text-[#003B73] border border-blue-100">Portal Klien</span>
                    </div>
                    <div class="text-[11px] font-semibold text-slate-400 hidden sm:block">
                        Survei Kepuasan, Masukan & Pelaporan Kendala
                    </div>
                </div>
            </div>

            {{-- Tautan Singkat ke Admin Dashboard: Kompak di Mobile via Grid 2 Kolom --}}
            <div class="w-full sm:w-auto">
                <div class="grid grid-cols-2 gap-2 mt-2.5 sm:mt-0 sm:flex sm:items-center p-1 rounded-2xl bg-slate-200/70 border border-slate-200" role="tablist">
                    <a href="{{ route('admin.dashboard') }}#overview"
                       class="text-xs px-3 py-1.5 w-full sm:w-auto justify-center rounded-xl font-bold transition-all bg-[#003B73] text-white shadow-sm flex items-center gap-1.5">
                        <span class="truncate">Admin Page</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- =========================================================================
         3. AREA KONTEN UTAMA: max-w-6xl mx-auto
         Jarak atas lega dengan padding responsif bebas geser horizontal
         ========================================================================= --}}
    <main class="flex-1 min-w-0 bg-transparent pt-5 pb-10 md:pt-8 md:pb-14 px-4 sm:px-6 lg:px-8">

        <div class="max-w-6xl mx-auto space-y-6 sm:space-y-8">

            {{-- Header Perusahaan dengan Hiasan Ilustrasi Pusat Kendali OCC PT LRS --}}
            @include('clients.portal.partials.header')

            {{-- Kontainer Panel Konten Interaktif --}}
            <div class="panels-container">
                {{-- Panel 1: Formulir Survei FRM-QOP-06-02 (2-Step Progressive Disclosure) --}}
                @include('clients.portal.panels.survey')

                {{-- Panel 2: Formulir Beri Masukan & Apresiasi --}}
                @include('clients.portal.panels.feedback')

                {{-- Panel 3: Formulir Buat Keluhan Kendala & Pelacakan Tiket --}}
                @include('clients.portal.panels.complaint')
            </div>

        </div>

    </main>

    {{-- Global Footer PT Len Railway Systems --}}
    <x-footer />

</div>

{{-- Modal Dialog Sukses & Toast Notification --}}
@include('clients.portal.partials.modals')

@endsection

@push('scripts')
    {{-- Script Utama Prototipe Klien --}}
    <script src="{{ asset('js/portal.js') }}"></script>

    {{-- Handler Navigasi Admin dari Halaman Klien --}}
    <script>
        function switchAdminSection(sectionKey) {
            window.location.href = "{{ route('admin.dashboard') }}" + (sectionKey === 'kanban' ? '#kanban' : '#overview');
        }
    </script>
@endpush
