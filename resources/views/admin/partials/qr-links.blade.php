{{-- =========================================================================
     SECTION 4: GENERATOR QR CODE & TAUTAN SURVEI
     ========================================================================= --}}
<div id="section-qr" class="admin-section space-y-6 hidden">

    {{-- 1. HEADER KONTROL --}}
    <div class="bg-white/95 backdrop-blur-md rounded-2xl border border-slate-200/90 p-5 md:p-6 shadow-[0_2px_12px_rgba(15,23,42,0.04)]">
        <div class="flex items-center gap-2">
            <h1 class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight">
                Generator QR Code & Tautan Survei
            </h1>
        </div>
        <p class="text-xs md:text-sm text-slate-500 mt-1 max-w-3xl leading-relaxed">
            Buat kode QR dan tautan formulir survei otomatis per proyek dan personel pendamping. Klien dapat langsung memindai QR code untuk mengisi kuesioner tanpa perlu login.
        </p>
    </div>

    {{-- 2. GRID 2 KOLOM: FORM GENERATOR (KIRI) & PRATINJAU KARTU CETAK (KANAN) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- KOLOM KIRI (7 Kolom): Formulir Parameter Tautan --}}
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/90 p-5 md:p-6 shadow-[0_2px_12px_rgba(15,23,42,0.04)] space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-extrabold text-slate-800">
                    Parameter Kuesioner
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">
                    Tentukan proyek tujuan dan personel pendamping yang bertugas.
                </p>
            </div>

            <div class="space-y-4 text-xs">
                {{-- 1. Pilih Proyek --}}
                <div>
                    <label for="qr-input-project" class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wide text-[11px]">
                        Proyek Pekerjaan LRS <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <select id="qr-input-project"
                                onchange="onQrParameterChange()"
                                class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 pr-8 font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all cursor-pointer">
                            {{-- Diisi secara dinamis dari lrs_projects_data --}}
                        </select>
                        <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs">▼</div>
                    </div>
                </div>

                {{-- 2. Pilih Personel Pendamping --}}
                <div>
                    <label for="qr-input-personnel" class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wide text-[11px]">
                        Personel Pendamping Lapangan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <select id="qr-input-personnel"
                                onchange="onQrParameterChange()"
                                class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 pr-8 font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all cursor-pointer">
                            {{-- Diisi secara dinamis dari lrs_personnel_data --}}
                        </select>
                        <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs">▼</div>
                    </div>
                </div>

                {{-- 3. Pilih Kategori Survei --}}
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wide text-[11px]">
                        Lingkup / Kategori Kuesioner
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-white cursor-pointer transition-all">
                            <input type="radio" name="qr_category" value="all" checked onchange="onQrParameterChange()" class="text-[#003B73] focus:ring-0">
                            <span class="font-bold text-slate-700 text-xs">Semua Kategori (Klien Bebas Pilih)</span>
                        </label>
                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-white cursor-pointer transition-all">
                            <input type="radio" name="qr_category" value="A" onchange="onQrParameterChange()" class="text-[#003B73] focus:ring-0">
                            <span class="font-bold text-slate-700 text-xs">Kategori A · Layanan & Dukungan</span>
                        </label>
                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-white cursor-pointer transition-all">
                            <input type="radio" name="qr_category" value="B" onchange="onQrParameterChange()" class="text-[#003B73] focus:ring-0">
                            <span class="font-bold text-slate-700 text-xs">Kategori B · Pelaksanaan Proyek</span>
                        </label>
                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-white cursor-pointer transition-all">
                            <input type="radio" name="qr_category" value="C" onchange="onQrParameterChange()" class="text-[#003B73] focus:ring-0">
                            <span class="font-bold text-slate-700 text-xs">Kategori C · Masa Pemeliharaan</span>
                        </label>
                    </div>
                </div>

                {{-- 4. Tampilan URL Hasil Generate --}}
                <div>
                    <label for="qr-output-url" class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wide text-[11px]">
                        Tautan Survei yang Dihasilkan
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="text"
                               id="qr-output-url"
                               readonly
                               class="flex-1 bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-700 select-all cursor-text focus:outline-none">
                        <button type="button"
                                onclick="copyQrSurveyUrl()"
                                title="Salin Tautan ke Clipboard"
                                class="px-3.5 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs transition-colors shrink-0 flex items-center gap-1.5 cursor-pointer">
                            <span>Salin</span>
                        </button>
                    </div>
                </div>

                {{-- Tombol Aksi Tautan --}}
                <div class="pt-2 flex flex-wrap items-center gap-2.5">
                    <button type="button"
                            onclick="saveQrToHistory()"
                            class="px-4 py-2.5 rounded-xl bg-[#003B73] hover:bg-[#002B54] text-white font-bold text-xs shadow-sm shadow-[#003B73]/20 hover:shadow-md transition-all flex items-center gap-2 cursor-pointer">
                        <span>Simpan ke Riwayat</span>
                    </button>
                    <button type="button"
                            onclick="downloadQrCardPng()"
                            class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm shadow-emerald-600/20 hover:shadow-md transition-all flex items-center gap-2 cursor-pointer">
                        <span>Unduh Kartu QR (PNG)</span>
                    </button>
                    <button type="button"
                            onclick="openQrSurveyPreview()"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                        <span>Buka Pratinjau</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN (5 Kolom): Pratinjau Kartu QR Siap Cetak --}}
        <div class="lg:col-span-5 flex flex-col items-center">
            {{-- Kartu Cetak Fisik --}}
            <div id="qr-print-card"
                 class="w-full max-w-[360px] bg-white rounded-3xl border border-slate-200/90 shadow-[0_8px_30px_rgba(15,23,42,0.08)] p-6 text-center space-y-4 relative overflow-hidden transition-transform duration-300">
                {{-- Aksen Triwarna Khas Len --}}
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#003B73] via-sky-500 to-[#D32F2F]"></div>

                {{-- Header Kartu --}}
                <div class="pt-2 flex flex-col items-center">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="Logo PT. Len Railways Systems"
                         class="h-8 w-auto object-contain mb-1.5">
                    <span class="text-xs font-black text-[#003B73] tracking-tight uppercase">
                        PT. Len Railways Systems
                    </span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                        Survei Kepuasan Pelanggan
                    </span>
                </div>

                {{-- Detail Proyek --}}
                <div class="bg-slate-50/90 rounded-2xl p-3 border border-slate-200/70">
                    <span id="card-preview-project" class="block text-xs font-extrabold text-slate-800 line-clamp-2">
                        Kereta Cepat Jakarta-Bandung
                    </span>
                    <span id="card-preview-contract" class="block text-[10px] font-mono text-slate-400 mt-0.5">
                        CTR-LRS-2025-001
                    </span>
                </div>

                {{-- Wadah QR Code Canvas --}}
                <div class="flex items-center justify-center p-3 bg-white rounded-2xl border border-slate-200 shadow-inner mx-auto w-fit">
                    <div id="qrcode-canvas-container" class="flex items-center justify-center">
                        {{-- QR Code di-render oleh QRCode.js --}}
                    </div>
                </div>

                {{-- Kategori & Personel Pendamping --}}
                <div class="space-y-1">
                    <span id="card-preview-category" class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-[#003B73] border border-blue-100">
                        Semua Kategori Layanan
                    </span>
                    <div class="text-[11px] text-slate-500">
                        Pendamping: <strong id="card-preview-personnel" class="text-slate-700">Ir. Dwi Suryanto</strong>
                    </div>
                </div>

                {{-- Instruksi Singkat Klien --}}
                <p class="text-[10px] text-slate-400 leading-tight pt-2 border-t border-slate-100">
                    Arahkan kamera smartphone ke QR Code ini untuk membuka formulir evaluasi kepuasan secara instan.
                </p>
            </div>

            <div class="mt-3 text-center">
                <span class="text-[11px] font-semibold text-slate-400">
                    💡 Tips: Klik "Unduh Kartu QR (PNG)" untuk mencetak kartu fisik atau dilampirkan pada laporan.
                </span>
            </div>
        </div>

    </div>

    {{-- 3. TABEL RIWAYAT TAUTAN & QR AKTIF --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-[0_2px_12px_rgba(15,23,42,0.04)] overflow-hidden space-y-3 p-5 md:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-sm font-extrabold text-slate-800">
                    Riwayat Tautan & QR Aktif
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">
                    Daftar tautan survei yang telah digenerate dan tersimpan di penyimpanan peramban.
                </p>
            </div>
            <span id="qr-history-count-badge" class="text-xs font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 self-start sm:self-auto">
                0 Tautan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-extrabold text-slate-600 uppercase tracking-wider border-b border-slate-200/80">
                        <th class="py-3 px-3">Proyek & Kode</th>
                        <th class="py-3 px-3">Personel Pendamping</th>
                        <th class="py-3 px-3">Lingkup Kategori</th>
                        <th class="py-3 px-3">Tanggal Dibuat</th>
                        <th class="py-3 px-3">Tautan URL</th>
                        <th class="py-3 px-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-qr-history-body" class="divide-y divide-slate-100 text-slate-700">
                    {{-- Diisi secara dinamis --}}
                </tbody>
            </table>
        </div>

        <div id="table-qr-empty" class="hidden py-8 text-center text-slate-400 text-xs">
            Belum ada riwayat QR Code tersimpan. Silakan generate tautan dan klik "Simpan ke Riwayat".
        </div>
    </div>

</div>
