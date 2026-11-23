@extends('layouts.app')

@section('title', 'Ringkasan Kepuasan & Layanan · PT. Len Railways Systems')

@push('styles')
<style>
    /* =========================================================================
       ADMIN SIDEBAR & FOCUS MODE TRANSITIONS
       Transisi halus perubahan lebar sidebar & margin area konten
       ========================================================================= */
    #admin-sidebar {
        transition: width 0.28s cubic-bezier(0.4, 0, 0.2, 1), transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #admin-main-wrapper {
        transition: margin-left 0.28s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Mode Fokus Konten (Sidebar Diciutkan / Collapsed) */
    #admin-sidebar.is-collapsed .sidebar-text-group,
    #admin-sidebar.is-collapsed .sidebar-nav-text,
    #admin-sidebar.is-collapsed #tab-complaint-badge,
    #admin-sidebar.is-collapsed .sidebar-bottom-full {
        display: none !important;
    }
    #admin-sidebar.is-collapsed .sidebar-bottom-icon {
        display: flex !important;
    }
    #admin-sidebar.is-collapsed .admin-nav-btn {
        justify-content: center !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }
    #admin-sidebar.is-collapsed #sidebar-collapse-icon {
        transform: rotate(180deg);
    }
    #admin-sidebar.is-collapsed .sidebar-header-box {
        justify-content: center !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-transparent text-slate-800 antialiased selection:bg-[#003B73] selection:text-white flex flex-col justify-between w-full relative">

    {{-- =========================================================================
         BACKDROP OVERLAY UNTUK LAYAR MOBILE & TABLET (< lg)
         ========================================================================= --}}
    <div id="sidebar-backdrop"
         onclick="closeAdminMobileSidebar()"
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-40 hidden transition-opacity duration-300"
         aria-hidden="true"></div>

    {{-- =========================================================================
         SIDEBAR KHUSUS ADMINISTRATOR (RESPONSIF & COLLAPSIBLE / FOCUS MODE)
         - Default Desktop: w-64 lg:w-72 (Mode Navigasi Lengkap)
         - Collapsed Desktop: w-20 (Mode Fokus Konten / Website)
         - Mobile: Off-canvas drawer (-translate-x-full lg:translate-x-0)
         ========================================================================= --}}
    <aside id="admin-sidebar"
           class="fixed inset-y-0 left-0 z-50 flex flex-col bg-white border-r border-slate-200/90 shadow-[4px_0_24px_-4px_rgba(15,23,42,0.06)] -translate-x-full lg:translate-x-0 w-64 lg:w-72">

        {{-- Garis Aksen Tri-Warna Khas PT. Len Railways Systems --}}
        <div class="h-1 bg-gradient-to-r from-[#003B73] via-sky-500 to-[#D32F2F] shrink-0"></div>

        {{-- Sidebar Header / Brand LRS --}}
        <div class="sidebar-header-box p-4 border-b border-slate-100 flex items-center justify-between gap-3 shrink-0">
            <div class="flex items-center gap-3 overflow-hidden min-w-0">
                <div class="h-10 w-10 px-1 rounded-xl bg-white flex items-center justify-center shrink-0 border border-slate-200 shadow-2xs">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="Logo PT. Len Railways Systems"
                         class="h-7 w-auto object-contain">
                </div>
                <div class="sidebar-text-group min-w-0 transition-opacity duration-200">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-sm font-black text-[#003B73] tracking-tight truncate">Len Railway Systems</span>
                    </div>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider bg-blue-50 text-[#003B73] border border-blue-100 shrink-0">ADMINISTRATOR</span>
                    </div>
                </div>
            </div>

            {{-- Tombol Toggle Focus Mode di Sidebar Header (Desktop) --}}
            <button type="button"
                    id="btn-toggle-sidebar-collapse"
                    onclick="toggleAdminSidebarCollapse()"
                    title="Beralih Mode Fokus (Sembunyikan/Buka Menu)"
                    aria-label="Toggle Sidebar"
                    class="hidden lg:flex p-1.5 rounded-lg text-slate-400 hover:text-[#003B73] hover:bg-slate-100 transition-colors shrink-0 cursor-pointer">
                <x-ri-arrow-left-double-line id="sidebar-collapse-icon" class="w-5 h-5 transition-transform duration-300" />
            </button>

            {{-- Tombol Close di Mobile Drawer (< lg) --}}
            <button type="button"
                    onclick="closeAdminMobileSidebar()"
                    title="Tutup Menu"
                    class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer">
                <x-ri-close-line class="w-5 h-5" />
            </button>
        </div>

        {{-- Navigasi Menu Utama Sidebar --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1.5" role="tablist">
            <div class="sidebar-text-group px-3 pb-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                Menu Utama
            </div>

            {{-- Tab 1: Ringkasan --}}
            <button type="button"
                    id="tab-btn-ringkasan"
                    data-tab-name="ringkasan"
                    onclick="switchAdminSection('ringkasan'); onSidebarNavClick();"
                    title="Ringkasan Kepuasan & Layanan"
                    class="admin-nav-btn w-full px-3 py-2.5 rounded-xl text-xs font-bold transition-all bg-[#003B73] text-white shadow-xs flex items-center gap-3 cursor-pointer group text-left">
                <x-ri-dashboard-3-line class="w-5 h-5 shrink-0" />
                <span class="sidebar-nav-text whitespace-nowrap overflow-hidden transition-all duration-200">Dashboard</span>
            </button>

            {{-- Tab 2: Data Survei --}}
            <button type="button"
                    id="tab-btn-data-survei"
                    data-tab-name="data-survei"
                    onclick="switchAdminSection('data-survei'); onSidebarNavClick();"
                    title="Rekap Data Survei Kepuasan"
                    class="admin-nav-btn w-full px-3 py-2.5 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 flex items-center gap-3 cursor-pointer group text-left">
                <x-ri-survey-line class="w-5 h-5 shrink-0" />
                <span class="sidebar-nav-text whitespace-nowrap overflow-hidden transition-all duration-200">Data Survei</span>
            </button>

            {{-- Tab 3: Keluhan --}}
            <button type="button"
                    id="tab-btn-keluhan"
                    data-tab-name="keluhan"
                    onclick="switchAdminSection('keluhan'); onSidebarNavClick();"
                    title="Daftar & Progres Keluhan"
                    class="admin-nav-btn w-full px-3 py-2.5 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 flex items-center justify-between gap-3 cursor-pointer group text-left">
                <div class="flex items-center gap-3 min-w-0">
                    <x-ri-customer-service-2-line class="w-5 h-5 shrink-0" />
                    <span class="sidebar-nav-text whitespace-nowrap overflow-hidden transition-all duration-200">Keluhan</span>
                </div>
                <span id="tab-complaint-badge" class="hidden px-2 py-0.5 text-[10px] font-black rounded-full bg-rose-500 text-white leading-none shadow-xs shrink-0">0</span>
            </button>

            {{-- Tab 4: QR & Tautan --}}
            <button type="button"
                    id="tab-btn-qr-tautan"
                    data-tab-name="qr-tautan"
                    onclick="switchAdminSection('qr-tautan'); onSidebarNavClick();"
                    title="Generator QR Code & Tautan Survei"
                    class="admin-nav-btn w-full px-3 py-2.5 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 flex items-center gap-3 cursor-pointer group text-left">
                <x-ri-qr-code-line class="w-5 h-5 shrink-0" />
                <span class="sidebar-nav-text whitespace-nowrap overflow-hidden transition-all duration-200">QR & Tautan</span>
            </button>

            {{-- Tab 5: Proyek & Personel --}}
            <button type="button"
                    id="tab-btn-proyek-personel"
                    data-tab-name="proyek-personel"
                    onclick="switchAdminSection('proyek-personel'); onSidebarNavClick();"
                    title="Kelola Proyek & Personel Pendamping"
                    class="admin-nav-btn w-full px-3 py-2.5 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 flex items-center gap-3 cursor-pointer group text-left">
                <x-ri-team-line class="w-5 h-5 shrink-0" />
                <span class="sidebar-nav-text whitespace-nowrap overflow-hidden transition-all duration-200">Proyek & Personel</span>
            </button>

            <div class="pt-3 pb-1 border-t border-slate-100 mt-2">
                <div class="sidebar-text-group px-3 pb-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                    Sistem & Preferensi
                </div>
            </div>

            {{-- Tab 6: Pengaturan --}}
            <button type="button"
                    id="tab-btn-pengaturan"
                    data-tab-name="pengaturan"
                    onclick="switchAdminSection('pengaturan'); onSidebarNavClick();"
                    title="Pengaturan & Target Perusahaan"
                    class="admin-nav-btn w-full px-3 py-2.5 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 flex items-center gap-3 cursor-pointer group text-left">
                <x-ri-settings-4-line class="w-5 h-5 shrink-0" />
                <span class="sidebar-nav-text whitespace-nowrap overflow-hidden transition-all duration-200">Pengaturan</span>
            </button>
        </nav>

        {{-- Footer Sidebar: Quick Status & Portal Klien Link --}}
        <div class="p-3 border-t border-slate-100 bg-slate-50/70 space-y-2 shrink-0">

            {{-- Link Portal Klien --}}
            <a href="{{ route('form') }}"
               target="_blank"
               rel="noopener"
               title="Buka Formulir Klien di Tab Baru"
               class="flex items-center gap-2.5 w-full p-2.5 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-200/80 hover:text-[#003B73] hover:border-blue-200 hover:bg-blue-50/50 transition-all shadow-2xs group">
                <x-ri-global-line class="w-4 h-4 shrink-0 text-[#003B73]" />
                <span class="sidebar-nav-text whitespace-nowrap overflow-hidden transition-all duration-200 truncate">Portal Klien</span>
                <x-ri-external-link-line class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#003B73] ml-auto shrink-0 sidebar-text-group transition-colors" />
            </a>
        </div>

        {{-- Mobile Drawer Compatibility Helper Container for Tests --}}
        <div id="admin-mobile-drawer" class="hidden" aria-hidden="true">
            <span id="mobile-complaint-badge" class="hidden">0</span>
        </div>
    </aside>

    {{-- =========================================================================
         AREA KONTEN UTAMA (WEBSITE & DASHBOARD DENGAN MARGIN DINAMIS)
         - Mengikuti status collapse sidebar (ml-0 di mobile, ml-64/lg:ml-72 expanded, ml-20 collapsed)
         ========================================================================= --}}
    <div id="admin-main-wrapper" class="flex-1 flex flex-col min-w-0 transition-all duration-300 lg:ml-72 min-h-screen">

        {{-- =========================================================================
             TOPBAR STICKY DENGAN FOKUS TOGGLE, TITLE & NOTIFIKASI
             ========================================================================= --}}
        <header id="admin-topbar" class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 py-3 sm:px-6 sm:py-3.5 shadow-2xs">
            <div class="flex items-center justify-between gap-3">
                {{-- Kiri: Sidebar Toggles & Breadcrumb/Page Title --}}
                <div class="flex items-center gap-3 min-w-0">
                    {{-- Mobile Hamburger (< lg) --}}
                    <button type="button"
                            id="btn-admin-mobile-menu"
                            onclick="toggleAdminMobileSidebar()"
                            title="Buka Menu Navigasi"
                            aria-label="Menu Navigasi"
                            class="lg:hidden p-2 rounded-xl border border-slate-200 bg-white text-slate-600 hover:text-[#003B73] transition-all flex items-center justify-center shadow-2xs cursor-pointer">
                        <x-ri-menu-line class="w-5 h-5" />
                    </button>

                    {{-- Desktop Sidebar Focus Toggle (lg+) --}}
                    <button type="button"
                            id="btn-admin-sidebar-toggle"
                            onclick="toggleAdminSidebarCollapse()"
                            title="Mode Fokus: Sembunyikan / Buka Sidebar"
                            aria-label="Toggle Fokus Sidebar"
                            class="hidden lg:flex p-2 rounded-xl border border-slate-200 bg-white text-slate-600 hover:text-[#003B73] hover:border-slate-300 transition-all items-center justify-center shadow-2xs cursor-pointer">
                        <x-ri-side-bar-line class="w-5 h-5" />
                    </button>

                    {{-- Dynamic Breadcrumb / Page Title --}}
                    <div class="min-w-0">
                        <h1 id="admin-page-title" class="text-sm sm:text-base font-black text-slate-900 tracking-tight truncate">
                            Ringkasan Kepuasan & Layanan
                        </h1>
                        <p id="admin-page-subtitle" class="text-[11px] font-medium text-slate-400 hidden sm:block truncate">
                            Pemantauan Indeks Kepuasan Klien & Tindak Lanjut Layanan
                        </p>
                    </div>
                </div>

                {{-- Kanan: Target Badge, Notifikasi Lonceng, Portal Klien --}}
                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    {{-- Target Kepuasan Badge --}}
                    {{-- Ikon Lonceng Notifikasi & Dropdown Antar-Tab --}}
                    <div class="relative" id="notification-bell-wrapper">
                        <button type="button"
                                id="btn-bell-notification"
                                onclick="toggleNotificationDropdown(event)"
                                title="Notifikasi Survei & Keluhan Masuk"
                                aria-label="Notifikasi Survei & Keluhan Masuk"
                                class="relative p-2 sm:p-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 hover:text-[#003B73] hover:border-slate-300 transition-all flex items-center justify-center shadow-2xs hover:shadow-xs focus:outline-none cursor-pointer">
                            <x-ri-notification-3-line class="w-4 h-4" />

                            {{-- Badge Notifikasi Unread dengan Animasi Ping --}}
                            <span id="bell-unread-badge"
                                  class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 flex items-center justify-center text-[10px] font-black rounded-full bg-rose-500 text-white leading-none shadow-xs border-2 border-white">
                                <span id="bell-unread-ping" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span id="bell-unread-count" class="relative z-10">1</span>
                            </span>
                        </button>

                        {{-- Dropdown Notifikasi --}}
                        <div id="notification-dropdown"
                             class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl border border-slate-200/90 shadow-[0_10px_35px_-5px_rgba(15,23,42,0.15)] z-50 overflow-hidden text-left divide-y divide-slate-100">
                            {{-- Header Dropdown --}}
                            <div class="p-3.5 px-4 bg-slate-50/80 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-black text-slate-800 tracking-tight">Notifikasi Terbaru</span>
                                    <span id="notif-unread-count-text" class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-[#003B73]">0 baru</span>
                                </div>
                                <button type="button"
                                        onclick="markAllNotificationsAsRead()"
                                        class="text-[11px] font-bold text-[#003B73] hover:text-blue-800 hover:underline transition-all cursor-pointer">
                                    Tandai semua dibaca
                                </button>
                            </div>

                            {{-- List Item Notifikasi --}}
                            <div id="notification-list-container" class="max-h-80 overflow-y-auto divide-y divide-slate-100"></div>

                            {{-- Footer Dropdown --}}
                            <div class="p-2.5 text-center bg-slate-50/50 text-[10px] font-medium text-slate-400">
                                Notifikasi survei masuk tersinkronisasi otomatis antar-tab
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- =========================================================================
             KONTAINER UTAMA SECTION SPA (OVERVIEW, DATA, KELUHAN, QR, PROYEK, PENGATURAN)
             ========================================================================= --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 w-full max-w-[1600px] mx-auto">
            {{-- Section 1: Ringkasan Kepuasan & Layanan --}}
            @include('admin.partials.overview')

            {{-- Section 2: Rekap Data Survei --}}
            @include('admin.partials.surveys')

            {{-- Section 3: Daftar & Progres Keluhan --}}
            @include('admin.partials.complaints')

            {{-- Section 4: Generator QR Code & Tautan Survei --}}
            @include('admin.partials.qr-links')

            {{-- Section 5: Kelola Proyek & Personel Pendamping LRS --}}
            @include('admin.partials.projects-personnel')

            {{-- Section 6: Pengaturan & Preferensi Sistem --}}
            @include('admin.partials.settings')
        </main>
    </div>

</div>

{{-- Modal Detail Keluhan, Pemeriksaan Masalah & Langkah Perbaikan --}}
@include('admin.partials.ticket-modal')

{{-- Modal Detail Rekap Survei Klien --}}
@include('admin.partials.survey-modal')

{{-- Modal Tambah / Edit Proyek --}}
@include('admin.partials.project-modal')

{{-- Modal Tambah / Edit Personel Pendamping --}}
@include('admin.partials.personnel-modal')

{{-- Kontainer Toast Popup Melayang Real-Time di Pojok Kanan Bawah --}}
<div id="admin-toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2.5 max-w-sm pointer-events-none w-full sm:w-auto px-4 sm:px-0"></div>

@endsection

@push('scripts')
    {{-- Chart.js CDN untuk Gauge & Grafik Perbandingan --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- QRCode.js CDN untuk Pembuatan QR Code Tautan Survei --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
 
    {{-- Script Interaktivitas Admin & LocalStorage --}}
    <script src="{{ asset('js/admin-portal.js') }}"></script>
@endpush
