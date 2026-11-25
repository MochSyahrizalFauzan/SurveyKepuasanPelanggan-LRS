@extends('layouts.app')

@section('title', 'Pusat Keluhan Klien · PT Len Railway Systems')
@section('meta_description', 'Pusat Keluhan Klien & Pelacakan Tiket Layanan — PT Len Railway Systems')

@section('content')
<div id="complaint-page-root" class="min-h-screen bg-transparent flex flex-col justify-between antialiased text-slate-800 w-full">

    {{-- =========================================================================
         1. STICKY TOPBAR HEADER (KONSISTEN DENGAN PORTAL KLIEN)
         ========================================================================= --}}
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/70 shadow-xs">
        {{-- Garis Aksen Tri-Warna Khas PT Len Railway Systems --}}
        <div class="h-1 w-full bg-gradient-to-r from-[#003B73] via-sky-500 to-[#D32F2F]"></div>

        <div class="max-w-7xl mx-auto px-4 py-3 md:px-6 md:py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-4">

            {{-- Kiri: Logo PT Len Railway Systems + Badge PUSAT KELUHAN KLIEN --}}
            <div class="flex items-center gap-3 min-w-0">
                <div class="h-10 md:h-11 px-2.5 rounded-xl bg-white flex items-center justify-center shrink-0 border border-slate-200 shadow-xs">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="Logo PT Len Railway Systems"
                         class="h-7 w-auto object-contain">
                </div>
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm md:text-base font-black text-[#003B73] tracking-tight">PT Len Railway Systems</span>
                        <span id="txt-complaint-header-badge" class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200">
                            PUSAT KELUHAN KLIEN
                        </span>
                    </div>
                    <div id="txt-complaint-header-subtitle" class="text-[11px] font-semibold text-slate-400 hidden sm:block truncate">
                        Pelaporan Kendala Layanan  Proyek 
                    </div>
                </div>
            </div>

            {{-- Kanan: Tautan Kembali ke Survei Kepuasan (/form) & Switcher Bahasa ID | EN --}}
            <div class="w-full sm:w-auto flex items-center justify-between sm:justify-end gap-2.5 shrink-0">
                <a href="{{ route('form') }}"
                   id="link-back-survey"
                   class="text-xs px-3.5 py-2 rounded-xl font-bold transition-all bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-[#003B73] border border-slate-200 hover:border-blue-200 flex items-center gap-1.5 shadow-2xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span id="txt-link-back-survey">Survei Kepuasan</span>
                </a>

                {{-- Switcher Bahasa ID | EN --}}
                <div class="inline-flex items-center p-0.5 rounded-xl bg-slate-100 border border-slate-200/80 shadow-2xs" role="group" aria-label="Language Selector">
                    <button type="button"
                            id="btn-lang-id"
                            onclick="setLanguage('id')"
                            class="px-2.5 py-1 rounded-lg text-xs font-black transition-all bg-[#003B73] text-white shadow-xs cursor-pointer">
                        ID
                    </button>
                    <button type="button"
                            id="btn-lang-en"
                            onclick="setLanguage('en')"
                            class="px-2.5 py-1 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 transition-all cursor-pointer">
                        EN
                    </button>
                </div>
            </div>

        </div>
    </header>

    {{-- =========================================================================
         2. AREA KONTEN UTAMA
         ========================================================================= --}}
    <main class="flex-1 min-w-0 bg-transparent pt-5 pb-12 md:pt-8 md:pb-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-6">

            {{-- Hero Banner Pusat Keluhan --}}
            <section id="complaint-hero-banner" class="bg-[#003B73] rounded-2xl p-5 sm:p-6 md:p-7 text-white shadow-xl shadow-[#003B73]/20 relative overflow-hidden border border-white/10">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#D32F2F] via-[#F59E0B] to-[#38BDF8]"></div>
                <div class="absolute -right-8 -bottom-6 md:right-0 md:bottom-0 w-[220px] sm:w-[320px] h-[160px] md:h-full opacity-25 bg-no-repeat bg-right-bottom bg-contain pointer-events-none mix-blend-luminosity"
                     style="background-image: url('{{ asset('images/bg-railway-control.jpg') }}'); mask-image: linear-gradient(to left, rgba(0,0,0,1) 25%, transparent 95%); -webkit-mask-image: linear-gradient(to left, rgba(0,0,0,1) 25%, transparent 95%);">
                </div>

                <div class="relative z-10 space-y-2 max-w-2xl">
                    <h1 id="txt-hero-title" class="text-xl sm:text-2xl md:text-3xl font-black tracking-tight text-white">
                        Pusat Keluhan &amp; Pelacakan Tiket
                    </h1>
                    <p id="txt-hero-subtitle" class="text-xs sm:text-sm text-blue-100/90 leading-relaxed">
                        Laporkan kendala teknis, pelaksanaan proyek, atau layanan pemeliharaan kapan saja. Setiap laporan diterbitkan nomor tiket resmi untuk dipantau progres penyelesaiannya oleh PT Len Railway Systems.
                    </p>
                </div>
            </section>

            {{-- Navigasi 2 Tab Utama (Touch-Friendly) --}}
            <nav id="complaint-tab-nav" class="bg-white rounded-2xl border border-slate-200/90 p-2 shadow-[0_2px_12px_rgba(15,23,42,0.04)] grid grid-cols-2 gap-2" role="tablist" aria-label="Navigasi Pusat Keluhan">
                <button type="button"
                        id="tab-btn-submit"
                        role="tab"
                        aria-selected="true"
                        aria-controls="panel-submit-complaint"
                        onclick="switchComplaintTab('submit')"
                        class="w-full min-h-[48px] px-4 py-3 rounded-xl font-extrabold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 bg-[#003B73] text-white shadow-sm cursor-pointer">
                 
                    <span id="txt-tab-submit">Buat Laporan Keluhan</span>
                </button>
                <button type="button"
                        id="tab-btn-track"
                        role="tab"
                        aria-selected="false"
                        aria-controls="panel-track-ticket"
                        onclick="switchComplaintTab('track')"
                        class="w-full min-h-[48px] px-4 py-3 rounded-xl font-bold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 cursor-pointer">
         
                    <span id="txt-tab-track">Lacak Status Tiket</span>
                </button>
            </nav>

            {{-- =================================================================
                 TAB 1: FORMULIR BUAT LAPORAN KELUHAN
                 ================================================================= --}}
            <section id="panel-submit-complaint" role="tabpanel" aria-labelledby="tab-btn-submit" class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 md:p-8 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.06)]">
                <form id="form-client-complaint" novalidate class="space-y-6">
                    {{-- Identitas Pelapor & Proyek --}}
                    <div class="space-y-4">
                        <div class="border-b border-slate-100 pb-2.5">
                            <h2 id="txt-section-identity" class="text-sm md:text-base font-extrabold text-slate-800">
                                Identitas Pelapor &amp; Proyek
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Nama Pelapor (Wajib) --}}
                            <div class="space-y-1.5">
                                <label id="lbl-c-reporter" for="c-reporter" class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                                    Nama Pelapor / PIC <span class="text-rose-500">*</span>
                                </label>
                                <input type="text"
                                       id="c-reporter"
                                       name="reporter_name"
                                       required
                                       placeholder="Contoh: Ir. Budi Santoso"
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/80 text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#003B73] focus:ring-2 focus:ring-blue-100 outline-none transition-all">
                                <p id="err-c-reporter" class="hidden text-xs font-bold text-rose-600 mt-1">Nama pelapor wajib diisi.</p>
                            </div>

                            {{-- Nama Proyek / Instansi (Wajib) --}}
                            <div class="space-y-1.5">
                                <label id="lbl-c-project" for="c-project" class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                                    Nama Proyek / Instansi <span class="text-rose-500">*</span>
                                </label>
                                <input type="text"
                                       id="c-project"
                                       name="project_name"
                                       required
                                       placeholder="Contoh: LRT Jakarta Fase 1B atau No. Kontrak"
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/80 text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#003B73] focus:ring-2 focus:ring-blue-100 outline-none transition-all">
                                <p id="err-c-project" class="hidden text-xs font-bold text-rose-600 mt-1">Nama proyek atau instansi wajib diisi.</p>
                            </div>
                        </div>

                        {{-- Kontak / Email (Opsional) --}}
                        <div class="space-y-1.5">
                            <label id="lbl-c-contact" for="c-contact" class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                                Kontak / Email Pelapor <span id="txt-c-contact-opt" class="text-slate-400 font-semibold normal-case">(Opsional)</span>
                            </label>
                            <input type="text"
                                   id="c-contact"
                                   name="reporter_contact"
                                   placeholder="Contoh: budi@instansi.co.id / 0812xxxx"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/80 text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#003B73] focus:ring-2 focus:ring-blue-100 outline-none transition-all">
                        </div>
                    </div>

                    {{-- Kategori & Urgensi --}}
                    <div class="space-y-4 pt-2 border-t border-slate-100">
                        {{-- Kategori Keluhan --}}
                        <div class="space-y-1.5">
                            <label id="lbl-c-category" for="c-category" class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                                Kategori Keluhan <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="c-category"
                                        name="category"
                                        required
                                        class="w-full appearance-none px-4 py-3 pr-10 rounded-xl border border-slate-200 bg-slate-50/80 text-xs sm:text-sm font-semibold text-slate-800 focus:bg-white focus:border-[#003B73] focus:ring-2 focus:ring-blue-100 outline-none transition-all cursor-pointer">
                                    <option value="" id="opt-c-cat-placeholder">— Pilih kategori keluhan —</option>
                                    <option value="Layanan" id="opt-c-cat-service">Layanan</option>
                                    <option value="Pelaksanaan Proyek" id="opt-c-cat-project">Pelaksanaan Proyek</option>
                                    <option value="Teknis & Produk" id="opt-c-cat-technical">Teknis &amp; Produk</option>
                                    <option value="Masa Pemeliharaan" id="opt-c-cat-maintenance">Masa Pemeliharaan</option>
                                    <option value="Lainnya" id="opt-c-cat-others">Lainnya</option>
                                </select>
                                <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">▼</span>
                            </div>
                            <p id="err-c-category" class="hidden text-xs font-bold text-rose-600 mt-1">Silakan pilih kategori keluhan.</p>
                        </div>

                        {{-- Tingkat Urgensi (4 Tombol Kartu Interaktif) --}}
                        <div class="space-y-2">
                            <span id="lbl-c-urgency" class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                                Tingkat Urgensi <span class="text-rose-500">*</span>
                            </span>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5" role="radiogroup" aria-labelledby="lbl-c-urgency">
                                {{-- Rendah (Emerald/Slate) --}}
                                <label class="relative flex items-center gap-2.5 p-3.5 rounded-xl border border-slate-200/90 bg-slate-50/80 hover:bg-white hover:border-emerald-300 shadow-2xs cursor-pointer transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/90 has-[:checked]:text-emerald-800 has-[:checked]:ring-2 has-[:checked]:ring-emerald-100">
                                    <input type="radio" name="urgency" value="Rendah" class="sr-only">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                                    <span id="txt-urg-low" class="text-xs font-extrabold">Rendah</span>
                                </label>

                                {{-- Sedang (Blue) --}}
                                <label class="relative flex items-center gap-2.5 p-3.5 rounded-xl border border-slate-200/90 bg-slate-50/80 hover:bg-white hover:border-blue-300 shadow-2xs cursor-pointer transition-all has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/90 has-[:checked]:text-[#003B73] has-[:checked]:ring-2 has-[:checked]:ring-blue-100">
                                    <input type="radio" name="urgency" value="Sedang" class="sr-only">
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600 shrink-0"></span>
                                    <span id="txt-urg-medium" class="text-xs font-extrabold">Sedang</span>
                                </label>

                                {{-- Tinggi (Amber) --}}
                                <label class="relative flex items-center gap-2.5 p-3.5 rounded-xl border border-slate-200/90 bg-slate-50/80 hover:bg-white hover:border-amber-300 shadow-2xs cursor-pointer transition-all has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/90 has-[:checked]:text-amber-800 has-[:checked]:ring-2 has-[:checked]:ring-amber-100">
                                    <input type="radio" name="urgency" value="Tinggi" class="sr-only">
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></span>
                                    <span id="txt-urg-high" class="text-xs font-extrabold">Tinggi</span>
                                </label>

                                {{-- Kritis (Rose) --}}
                                <label class="relative flex items-center gap-2.5 p-3.5 rounded-xl border border-slate-200/90 bg-slate-50/80 hover:bg-white hover:border-rose-300 shadow-2xs cursor-pointer transition-all has-[:checked]:border-rose-600 has-[:checked]:bg-rose-50/90 has-[:checked]:text-rose-800 has-[:checked]:ring-2 has-[:checked]:ring-rose-100">
                                    <input type="radio" name="urgency" value="Kritis" class="sr-only">
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-600 shrink-0"></span>
                                    <span id="txt-urg-critical" class="text-xs font-extrabold">Kritis</span>
                                </label>
                            </div>
                            <p id="err-c-urgency" class="hidden text-xs font-bold text-rose-600 mt-1">Silakan pilih tingkat urgensi penanganan.</p>
                        </div>
                    </div>

                    {{-- Judul & Uraian Kendala --}}
                    <div class="space-y-4 pt-2 border-t border-slate-100">
                        <div class="space-y-1.5">
                            <label id="lbl-c-title" for="c-title" class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                                Judul Singkat Kendala <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   id="c-title"
                                   name="title"
                                   required
                                   placeholder="Contoh: Fluktuasi pembacaan sensor wesel Stasiun X"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/80 text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#003B73] focus:ring-2 focus:ring-blue-100 outline-none transition-all">
                            <p id="err-c-title" class="hidden text-xs font-bold text-rose-600 mt-1">Judul singkat kendala wajib diisi.</p>
                        </div>

                        <div class="space-y-1.5">
                            <label id="lbl-c-description" for="c-description" class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                                Uraian Detail Kendala <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="c-description"
                                      name="description"
                                      rows="4"
                                      required
                                      placeholder="Jelaskan lokasi, kronologi kejadian, serta dampak terhadap operasional..."
                                      class="w-full min-h-[120px] px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/80 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#003B73] focus:ring-2 focus:ring-blue-100 outline-none transition-all resize-y leading-relaxed"></textarea>
                            <p id="err-c-description" class="hidden text-xs font-bold text-rose-600 mt-1">Uraian detail kendala wajib diisi.</p>
                        </div>

                        {{-- Unggah Bukti Pendukung (Dummy) --}}
                        <div class="space-y-1.5">
                            <label id="lbl-c-attachment" class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                                Unggah Bukti Pendukung <span id="txt-c-attach-opt" class="text-slate-400 font-semibold normal-case">(Opsional)</span>
                            </label>
                            <input type="file"
                                   id="c-attachment-input"
                                   class="hidden"
                                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                   onchange="handleStandaloneComplaintFile(event)">
                            <div id="c-upload-dropzone"
                                 onclick="document.getElementById('c-attachment-input').click()"
                                 class="border-2 border-dashed border-slate-200 rounded-xl p-5 text-center bg-slate-50/70 hover:bg-blue-50/40 hover:border-[#003B73] transition-all cursor-pointer group">
                                <div class="text-2xl mb-1">📎</div>
                                <div id="txt-c-upload-title" class="text-xs font-bold text-slate-700 group-hover:text-[#003B73]">
                                    Klik untuk melampirkan foto / dokumen bukti pendukung
                                </div>
                                <div id="txt-c-upload-hint" class="text-[11px] text-slate-400 mt-0.5">
                                    Format JPG, PNG, PDF, DOCX (Maks. 10 MB — nama file dicatat otomatis)
                                </div>
                            </div>

                            <div id="c-file-preview" class="hidden items-center justify-between p-3.5 rounded-xl bg-blue-50/70 border border-blue-200 text-xs">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="text-base shrink-0">📄</span>
                                    <span id="c-attached-filename" class="font-bold text-slate-800 truncate">file.pdf</span>
                                </div>
                                <button type="button"
                                        id="btn-remove-c-file"
                                        onclick="removeStandaloneComplaintFile(event)"
                                        class="text-rose-600 font-bold hover:underline shrink-0 cursor-pointer">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-2">
                        <button type="submit"
                                id="btn-submit-complaint"
                                class="w-full py-3.5 px-6 rounded-xl bg-[#003B73] hover:bg-[#002B54] text-white font-extrabold text-sm shadow-md shadow-[#003B73]/25 hover:shadow-lg transition-all active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer">
                            <span id="txt-btn-submit-complaint">Kirim Laporan Keluhan</span>
                        </button>
                    </div>
                </form>
            </section>

            {{-- =================================================================
                 LAYAR KONFIRMASI TIKET TERBIT (SAFE VERTICAL CENTERING)
                 ================================================================= --}}
            <section id="complaint-success-container" class="hidden min-h-[calc(100dvh-6rem)] flex flex-col justify-center py-2 sm:py-4" aria-live="polite">
                <div class="my-auto w-full bg-white rounded-3xl border border-slate-200/90 shadow-[0_8px_30px_rgba(15,23,42,0.06)] overflow-hidden">
                    <div class="h-1.5 w-full bg-gradient-to-r from-[#1a336e] via-[#2563eb] to-[#dc2626]" aria-hidden="true"></div>

                    <div class="p-5 sm:p-7 lg:p-8 space-y-6">
                        {{-- Header Sukses --}}
                        <div class="text-center space-y-2">
                            <div class="w-16 h-16 mx-auto rounded-full bg-emerald-50 ring-8 ring-emerald-50/80 text-emerald-600 flex items-center justify-center shadow-sm">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>
                            <h2 id="txt-confirm-title" class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                                Laporan Keluhan Berhasil Diterbitkan!
                            </h2>
                            <p id="txt-confirm-subtitle" class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto leading-relaxed">
                                Simpan nomor tiket di bawah ini untuk memantau perkembangan investigasi dan langkah perbaikan oleh tim PT Len Railway Systems.
                            </p>
                        </div>

                        {{-- Grid 2 Kolom Proporsional di Desktop, 1 Kolom di Mobile --}}
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
                            {{-- Kolom Kiri: Nomor Tiket Besar + Tombol Salin + Ringkasan --}}
                            <div class="lg:col-span-7 bg-slate-50/95 rounded-2xl border border-slate-200/90 p-5 flex flex-col justify-between space-y-4">
                                <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-wrap items-center justify-between gap-3 shadow-2xs">
                                    <div>
                                        <span id="txt-confirm-ticket-lbl" class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                            Nomor Tiket Resmi
                                        </span>
                                        <span id="confirm-ticket-id" class="text-2xl sm:text-3xl font-black text-[#003B73] tracking-wider select-all">
                                            #TKT-0000
                                        </span>
                                    </div>
                                    <button type="button"
                                            id="btn-copy-ticket"
                                            onclick="copyCreatedTicketId()"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-extrabold bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-[#003B73] border border-slate-200 transition-all active:scale-95 cursor-pointer">
    
                                        <span id="txt-btn-copy-ticket">Salin</span>
                                    </button>
                                </div>

                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                    <div class="bg-white p-3 rounded-xl border border-slate-200/70">
                                        <dt id="lbl-sum-reporter" class="text-[10px] font-bold uppercase text-slate-400">Pelapor</dt>
                                        <dd id="sum-reporter" class="font-bold text-slate-800 mt-0.5 truncate">-</dd>
                                    </div>
                                    <div class="bg-white p-3 rounded-xl border border-slate-200/70">
                                        <dt id="lbl-sum-project" class="text-[10px] font-bold uppercase text-slate-400">Proyek / Instansi</dt>
                                        <dd id="sum-project" class="font-bold text-slate-800 mt-0.5 truncate">-</dd>
                                    </div>
                                    <div class="bg-white p-3 rounded-xl border border-slate-200/70">
                                        <dt id="lbl-sum-category" class="text-[10px] font-bold uppercase text-slate-400">Kategori</dt>
                                        <dd id="sum-category" class="font-bold text-slate-800 mt-0.5 truncate">-</dd>
                                    </div>
                                    <div class="bg-white p-3 rounded-xl border border-slate-200/70 min-w-0">
                                        <dt id="lbl-sum-urgency" class="text-[10px] font-bold uppercase text-slate-400">Urgensi &amp; Waktu Lapor</dt>
                                        <dd id="sum-urgency-time" class="font-bold text-slate-800 mt-0.5 break-words leading-snug">-</dd>
                                    </div>
                                </dl>
                            </div>

                            {{-- Kolom Kanan: Kartu SMAP ISO 37001:2016 PT Len Railway Systems --}}
                            <div class="lg:col-span-5 rounded-2xl bg-gradient-to-br from-blue-50/90 via-white to-sky-50/70 border border-blue-200/90 p-5 flex flex-col justify-between space-y-4 shadow-2xs">
                                <div class="space-y-2.5">
                                    <div class="flex items-center gap-2 pb-2 border-b border-blue-100">
                                        <span class="w-8 h-8 rounded-lg bg-[#003B73] text-white flex items-center justify-center text-sm shrink-0">🛡️</span>
                                        <div>
                                            <h3 id="txt-smap-title" class="text-xs sm:text-sm font-extrabold text-[#1a336e] leading-snug">
                                                SMAP ISO 37001:2016 PT Len Railway Systems
                                            </h3>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Anti-Bribery Management System</span>
                                        </div>
                                    </div>
                                    <p id="txt-smap-desc" class="text-xs text-slate-600 leading-relaxed">
                                        PT Len Railway Systems berkomitmen menerapkan Prinsip 4 NOs (No Bribery, No Kickback, No Gift, No Luxurious Hospitality) dan menjamin seluruh penanganan keluhan berlangsung secara transparan, objektif, serta bebas benturan kepentingan.
                                    </p>
                                </div>

                                <a href="https://lrs.co.id/about-us/smap"
                                   id="link-smap-policy"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-xl bg-white hover:bg-blue-50 text-[#1a336e] font-bold text-xs border border-blue-200 shadow-2xs transition-all">
                                    <span id="txt-link-smap">Pelajari Kebijakan SMAP</span>
                                    <span aria-hidden="true">↗</span>
                                </a>
                            </div>
                        </div>

                        {{-- Tombol Aksi Konfirmasi --}}
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3 pt-2 border-t border-slate-100">
                            <button type="button"
                                    id="btn-track-created-ticket"
                                    onclick="trackCreatedTicket()"
                                    class="w-full sm:w-auto px-6 py-3 rounded-xl bg-[#003B73] hover:bg-[#002B54] text-white font-extrabold text-xs sm:text-sm shadow-sm flex items-center justify-center gap-2 transition-all active:scale-95 cursor-pointer">
                                <span id="txt-btn-track-created">Lacak Tiket Ini</span>
                            </button>
                            <button type="button"
                                    id="btn-create-another-complaint"
                                    onclick="resetToComplaintForm()"
                                    class="w-full sm:w-auto px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all active:scale-95 cursor-pointer">
                                <span>➕</span>
                                <span id="txt-btn-create-another">Buat Laporan Lain</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            {{-- =================================================================
                 TAB 2: LACAK STATUS TIKET
                 ================================================================= --}}
            <section id="panel-track-ticket" role="tabpanel" aria-labelledby="tab-btn-track" class="hidden space-y-6">

                {{-- Kotak Pencarian Tiket & Tiket Terbaru --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 md:p-8 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.06)] space-y-5">
                    <div class="space-y-1">
                        <h2 id="txt-track-title" class="text-base sm:text-lg font-extrabold text-slate-800">
                            Lacak Progres Penanganan Tiket
                        </h2>
                        <p id="txt-track-subtitle" class="text-xs sm:text-sm text-slate-500">
                            Masukkan nomor tiket keluhan Anda (contoh: #TKT-1234 atau TKT-1234) untuk melihat status terbaru.
                        </p>
                    </div>

                    <form onsubmit="handleTrackSearch(event)" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                        <input type="text"
                               id="track-ticket-input"
                               placeholder="Contoh: #TKT-1234"
                               class="flex-1 px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/80 text-xs sm:text-sm font-bold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#003B73] focus:ring-2 focus:ring-blue-100 outline-none transition-all uppercase">
                        <button type="submit"
                                id="btn-track-ticket"
                                class="w-full sm:w-auto px-6 py-3 rounded-xl bg-[#003B73] hover:bg-[#002B54] text-white font-extrabold text-xs sm:text-sm shadow-sm transition-all active:scale-95 cursor-pointer shrink-0">
                            <span id="txt-btn-track-search">Lacak</span>
                        </button>
                    </form>

                    {{-- Chip Daftar Singkat: Tiket Terbaru Anda (Maks 3 Tiket) --}}
                    <div id="recent-tickets-wrapper" class="pt-3 border-t border-slate-100 space-y-2">
                        <span id="txt-recent-tickets-label" class="block text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            Tiket Terbaru Anda
                        </span>
                        <div id="recent-tickets-container" class="flex flex-wrap gap-2">
                            {{-- Diisi secara dinamis oleh JS --}}
                        </div>
                    </div>
                </div>

                {{-- Kartu Hasil Pelacakan --}}
                <div id="track-result-container" class="hidden bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 md:p-8 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.06)] space-y-6" aria-live="polite">
                    {{-- Header Nomor Tiket & Lencana Urgensi --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-100">
                        <div class="min-w-0">
                            <span id="track-detail-ticket-lbl" class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Nomor Tiket</span>
                            <span id="track-detail-ticket-id" class="text-xl font-black text-[#003B73]">#TKT-0000</span>
                        </div>
                        <span id="track-detail-urgency-badge" class="px-3 py-1 rounded-full text-xs font-extrabold border bg-slate-100 text-slate-700 border-slate-200">
                            Urgensi: -
                        </span>
                    </div>

                    {{-- Timeline Responsif & Dinamis (Vertical Mobile <-> Horizontal Desktop) --}}
                    <div class="p-4 sm:p-6 rounded-2xl bg-slate-50/90 border border-slate-200/80">
                        <ol id="ticket-timeline-list" class="relative flex flex-col md:flex-row md:items-start gap-0" aria-label="Linimasa Status Tiket">
                            <li class="relative flex flex-row md:flex-col md:flex-1 gap-3.5 md:gap-3">
                                <div class="flex flex-col items-center md:flex-row md:w-full shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-[#1a336e] text-white ring-4 ring-blue-100 flex items-center justify-center text-xs font-black shrink-0">1</div>
                                    <div class="w-0.5 flex-1 min-h-6 my-1 md:w-auto md:h-0.5 md:flex-1 md:my-0 md:mx-2 bg-slate-200 rounded-full"></div>
                                </div>
                                <div class="flex-1 min-w-0 pb-6 md:pb-0 md:pr-5">
                                    <span class="text-xs sm:text-sm font-extrabold text-slate-900">Baru Masuk</span>
                                    <time class="block text-[11px] font-semibold text-slate-500 mt-0.5">-</time>
                                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">Laporan diterima sistem dan menunggu peninjauan tim PT Len Railway Systems.</p>
                                </div>
                            </li>
                            <li class="relative flex flex-row md:flex-col md:flex-1 gap-3.5 md:gap-3">
                                <div class="flex flex-col items-center md:flex-row md:w-full shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-white border-2 border-slate-200 text-slate-400 flex items-center justify-center text-xs font-black shrink-0">2</div>
                                    <div class="w-0.5 flex-1 min-h-6 my-1 md:w-auto md:h-0.5 md:flex-1 md:my-0 md:mx-2 bg-slate-200 rounded-full"></div>
                                </div>
                                <div class="flex-1 min-w-0 pb-6 md:pb-0 md:pr-5">
                                    <span class="text-xs sm:text-sm font-bold text-slate-400">Sedang Diproses</span>
                                    <time class="block text-[11px] font-medium text-slate-400 mt-0.5">Menunggu</time>
                                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">Tim teknis sedang menindaklanjuti kendala di lapangan.</p>
                                </div>
                            </li>
                            <li class="relative flex flex-row md:flex-col md:flex-1 gap-3.5 md:gap-3">
                                <div class="flex flex-col items-center md:flex-row md:w-full shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-white border-2 border-slate-200 text-slate-400 flex items-center justify-center text-xs font-black shrink-0">3</div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="text-xs sm:text-sm font-bold text-slate-400">Selesai</span>
                                    <time class="block text-[11px] font-medium text-slate-400 mt-0.5">Menunggu</time>
                                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">Kendala telah selesai ditangani dan tiket ditutup.</p>
                                </div>
                            </li>
                        </ol>
                    </div>

                    {{-- Judul & Uraian Kendala --}}
                    <div class="space-y-2">
                        <h3 id="track-detail-title" class="text-base sm:text-lg font-extrabold text-slate-900 break-words">-</h3>
                        <p id="track-detail-description" class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line bg-slate-50/70 p-4 rounded-xl border border-slate-200/70 break-words">-</p>
                    </div>

                    {{-- Detail Metadata --}}
                    <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70 min-w-0">
                            <dt id="track-meta-lbl-reporter" class="text-[10px] font-bold uppercase text-slate-400">Pelapor</dt>
                            <dd id="track-meta-val-reporter" class="font-bold text-slate-800 mt-0.5 break-words leading-snug">-</dd>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70 min-w-0">
                            <dt id="track-meta-lbl-project" class="text-[10px] font-bold uppercase text-slate-400">Proyek / Instansi</dt>
                            <dd id="track-meta-val-project" class="font-bold text-slate-800 mt-0.5 break-words leading-snug">-</dd>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70 min-w-0">
                            <dt id="track-meta-lbl-category" class="text-[10px] font-bold uppercase text-slate-400">Kategori</dt>
                            <dd id="track-meta-val-category" class="font-bold text-slate-800 mt-0.5 break-words leading-snug">-</dd>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70 min-w-0">
                            <dt id="track-meta-lbl-date" class="text-[10px] font-bold uppercase text-slate-400">Tanggal Lapor</dt>
                            <dd class="font-bold text-slate-800 mt-0.5 break-words leading-snug">
                                <time id="track-meta-val-date">-</time>
                            </dd>
                        </div>
                    </dl>

                    <div class="text-xs text-slate-500 flex flex-wrap items-center gap-2">
                        <span aria-hidden="true">📎</span>
                        <span id="track-meta-lbl-attachment" class="font-semibold">Lampiran:</span>
                        <span id="track-meta-val-attachment" class="text-slate-700 font-bold break-all">-</span>
                    </div>

                    {{-- Blok Tindak Lanjut Tim PT Len Railway Systems --}}
                    <div id="track-followup-container"></div>
                </div>

                {{-- State Tidak Ditemukan --}}
                <div id="track-not-found" class="hidden bg-rose-50/70 rounded-2xl border border-rose-200 p-6 text-center space-y-2" aria-live="polite">
                    <h3 id="txt-track-not-found-title" class="text-sm font-extrabold text-rose-800">
                        Nomor Tiket Tidak Ditemukan
                    </h3>
                    <p id="txt-track-not-found-desc" class="text-xs text-rose-700/90 max-w-md mx-auto leading-relaxed">
                        Pastikan penulisan nomor tiket sudah benar (contoh: #TKT-1234) atau periksa kembali daftar tiket terbaru Anda di atas.
                    </p>
                </div>

            </section>

        </div>
    </main>

    {{-- Global Footer PT Len Railway Systems --}}
    <x-footer />

</div>

{{-- Toast Notifikasi Ringan --}}
<div id="complaint-toast"
     role="alert"
     aria-live="assertive"
     class="fixed bottom-5 right-5 z-50 hidden max-w-sm bg-[#002244] text-white rounded-2xl p-4 shadow-xl border border-blue-900/80 border-l-4 border-l-sky-400 transition-all">
    <div class="flex items-start gap-3">
        <div class="min-w-0">
            <div id="complaint-toast-title" class="text-xs font-black">Info</div>
            <div id="complaint-toast-desc" class="text-[11px] text-slate-200 mt-0.5 leading-snug"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/client-complaint.js') }}"></script>
@endpush
