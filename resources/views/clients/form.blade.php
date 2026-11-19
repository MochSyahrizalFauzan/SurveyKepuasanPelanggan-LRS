@extends('layouts.app')

@section('title', 'Portal Survei Kepuasan Klien · PT. Len Railways Systems')

@section('content')
<div class="min-h-screen bg-transparent flex flex-col antialiased text-slate-800">

    {{-- =========================================================================
         1. TOP BAR UTAMA: BRAND, IDENTITAS & TOMBOL SWITCHER
         ========================================================================= --}}
    <div class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-40 shadow-xs">
        {{-- Garis Aksen Tri-Warna Khas Len --}}
        <div class="h-1 w-full bg-gradient-to-r from-[#003B73] via-sky-500 to-[#D32F2F]"></div>

        <div class="max-w-7xl mx-auto px-4 md:px-8 py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

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
                    <div class="text-[11px] font-semibold text-slate-400">
                        Survei Kepuasan, Masukan & Pelaporan Kendala
                    </div>
                </div>
            </div>

            {{-- 2 Tombol Switcher Sederhana (Pill Tabs) & Quick Links Sesuai Permintaan --}}
            <div class="flex items-center gap-2.5 self-start sm:self-auto">
                <div class="inline-flex p-1 rounded-2xl bg-slate-200/70 border border-slate-200" role="tablist">
                    {{-- Tab 1: Ringkasan Kepuasan --}}
                    <button type="button"
                            id="tab-btn-overview"
                            onclick="switchAdminSection('overview')"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all bg-[#003B73] text-white shadow-sm flex items-center gap-2">
                        <span>Admin Page</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         2. SUB-NAVIGASI TAB KLIEN (Isi Survei, Beri Masukan, Buat Keluhan)
         Tampil konsisten dan rapi di semua layar (Mobile, Tablet, Desktop)
         ========================================================================= --}}
    <!-- <div class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 shadow-[0_2px_8px_rgba(15,23,42,0.03)] sticky top-[65px] md:top-[69px] z-30">
        <div class="max-w-4xl mx-auto px-4 md:px-8 py-2.5">
            <nav class="flex items-center gap-2 overflow-x-auto scrollbar-none" role="tablist" aria-label="Navigasi Formulir Klien">
                <button type="button"
                        class="tab-btn active shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-100 [&.active]:bg-[#003B73] [&.active]:text-white [&.active]:shadow-sm"
                        data-tab="survei"
                        role="tab"
                        aria-selected="true"
                        onclick="switchTab('survei', this)">
                    <span>📋</span>
                    <span>Isi Survei</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-white/20 text-current font-extrabold tab-badge">
                        {{ $totalQuestions ?? 21 }}
                    </span>
                </button>

                <button type="button"
                        class="tab-btn shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-100 [&.active]:bg-[#003B73] [&.active]:text-white [&.active]:shadow-sm"
                        data-tab="masukan"
                        role="tab"
                        aria-selected="false"
                        onclick="switchTab('masukan', this)">
                    <span>💬</span>
                    <span>Beri Masukan</span>
                </button>

                <button type="button"
                        class="tab-btn shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-100 [&.active]:bg-[#003B73] [&.active]:text-white [&.active]:shadow-sm"
                        data-tab="keluhan"
                        role="tab"
                        aria-selected="false"
                        onclick="switchTab('keluhan', this)">
                    <span>🔔</span>
                    <span>Buat Keluhan</span>
                </button>
            </nav>
        </div>
    </div> -->

    {{-- =========================================================================
         3. AREA KONTEN UTAMA: max-w-4xl mx-auto
         Terkunci di tengah secara proporsional dengan whitespace yang seimbang
         ========================================================================= --}}
    <main class="flex-1 min-w-0 bg-transparent p-4 md:p-8 lg:p-10">

        <div class="max-w-4xl mx-auto space-y-6">

            {{-- Header Perusahaan dengan Hiasan Ilustrasi Pusat Kendali OCC PT LRS --}}
            @include('clients.portal.partials.header')

            {{-- Kontainer Panel Konten Interaktif --}}
            <div class="panels-container">
                {{-- Panel 1: Formulir Survei FRM-QOP-06-02 (21 Indikator Dual-Metric) --}}
                @include('clients.portal.panels.survey')

                {{-- Panel 2: Formulir Beri Masukan & Apresiasi --}}
                @include('clients.portal.panels.feedback')

                {{-- Panel 3: Formulir Buat Keluhan Kendala & Pelacakan Tiket --}}
                @include('clients.portal.panels.complaint')
            </div>

        </div>

    </main>

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
