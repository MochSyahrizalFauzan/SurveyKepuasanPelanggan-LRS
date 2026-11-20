@extends('layouts.app')

@section('title', 'Ringkasan Kepuasan & Layanan · PT. Len Railways Systems')

@section('content')
<div class="bg-transparent min-h-screen text-slate-800 antialiased selection:bg-[#003B73] selection:text-white flex flex-col justify-between w-full">

    {{-- Container Utama: max-w-7xl mx-auto p-4 md:p-8 --}}
    <main class="flex-1 max-w-7xl mx-auto p-4 md:p-8 space-y-6 w-full">

        {{-- =========================================================================
             TOP BAR: BRAND & 2 PILL TABS SWITCHER (Ringkasan & Daftar Keluhan)
             (FOKUS KONTEN: Tanpa Sidebar & Tanpa Navbar Luar)
             ========================================================================= --}}
        <div class="bg-white/95 backdrop-blur-md rounded-2xl border border-slate-200/80 p-4 md:px-6 md:py-3.5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative overflow-hidden">
            {{-- Garis Aksen Tri-Warna Khas Len --}}
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#003B73] via-sky-500 to-[#D32F2F]"></div>

            {{-- Brand & Identitas Admin --}}
            <div class="flex items-center gap-3">
                <div class="h-10 md:h-11 px-2.5 rounded-xl bg-white flex items-center justify-center shrink-0 border border-slate-200 shadow-xs">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="Logo PT. Len Railways Systems"
                         class="h-7 w-auto object-contain">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm md:text-base font-black text-[#003B73] tracking-tight">PT. Len Railways Systems</span>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-blue-50 text-[#003B73] border border-blue-100">Admin Layanan</span>
                    </div>
                    <div class="text-[11px] font-semibold text-slate-400">
                        Pemantauan Kepuasan Klien & Penanganan Masalah Layanan
                    </div>
                </div>
            </div>

            {{-- 2 Tombol Switcher Sederhana (Pill Tabs) & Quick Links --}}
            <div class="flex items-center gap-2.5 self-start sm:self-auto">
                <div class="inline-flex p-1 rounded-2xl bg-slate-200/70 border border-slate-200" role="tablist">
                    {{-- Tab 1: Ringkasan Kepuasan --}}
                    <button type="button"
                            id="tab-btn-overview"
                            onclick="switchAdminSection('overview')"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all bg-[#003B73] text-white shadow-sm flex items-center gap-2">
                        <span>Ringkasan Kepuasan</span>
                    </button>

                    {{-- Tab 2: Daftar Keluhan --}}
                    <button type="button"
                            id="tab-btn-kanban"
                            onclick="switchAdminSection('kanban')"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-100 flex items-center gap-2">
                        <span>Daftar Keluhan</span>
                    </button>
                </div>

                {{-- Tautan Singkat ke Portal Klien untuk Simulasi --}}
                <a href="{{ route('form') }}"
                   title="Buka Formulir Klien di Tab Baru"
                   class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-600 hover:text-[#003B73] hover:border-slate-300 transition-all flex items-center gap-1.5 shadow-2xs">
                    <span>🌐</span>
                    <span class="hidden md:inline">Portal Klien</span>
                </a>
            </div>

        </div>

        {{-- =========================================================================
             SECTION 1: RINGKASAN KEPUASAN & LAYANAN
             ========================================================================= --}}
        @include('admin.partials.overview')

        {{-- =========================================================================
             SECTION 2: DAFTAR & PROGRES KELUHAN (PAPAN KELUHAN)
             ========================================================================= --}}
        @include('admin.partials.kanban')

    </main>

    {{-- Global Footer PT Len Railway Systems --}}
    <x-footer />

</div>

{{-- Modal Detail Keluhan, Pemeriksaan Penyebab Masalah & Langkah Perbaikan --}}
@include('admin.partials.ticket-modal')

@endsection

@push('scripts')
    {{-- Chart.js CDN untuk Gauge & Grafik Perbandingan --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Script Interaktivitas Admin & LocalStorage --}}
    <script src="{{ asset('js/admin-portal.js') }}"></script>
@endpush
