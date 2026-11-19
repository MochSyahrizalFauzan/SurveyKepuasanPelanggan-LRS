{{-- =========================================================================
     SECTION 1: RINGKASAN KEPUASAN & LAYANAN
     ========================================================================= --}}
<div id="section-overview" class="admin-section space-y-6">

    {{-- 1. HEADER KONTROL INLINE --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 md:p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight">
                Ringkasan Kepuasan & Layanan
            </h1>
            <p class="text-xs md:text-sm text-slate-500 mt-0.5">
                Pantauan kepuasan klien dan progres penanganan keluhan
            </p>
        </div>

        {{-- Kontrol Filter & Ekspor --}}
        <div class="flex flex-wrap items-center gap-2.5">
            {{-- Filter Proyek --}}
            <div class="relative">
                <select id="filter-overview-project"
                        onchange="onFilterChange()"
                        class="appearance-none bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl px-3.5 py-2.5 pr-8 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all cursor-pointer">
                    <option value="all">Semua Proyek</option>
                    <option value="LRT Jakarta Fase 1B">LRT Jakarta Fase 1B</option>
                    <option value="Kereta Cepat Jakarta-Bandung">Kereta Cepat Jakarta-Bandung (Whoosh)</option>
                    <option value="Gardu Traksi Manggarai">Gardu Traksi Manggarai</option>
                    <option value="MRT Jakarta CP106">MRT Jakarta CP106</option>
                    <option value="Elektrifikasi Solo-Yogyakarta">Elektrifikasi Solo-Yogyakarta</option>
                </select>
                <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">▼</div>
            </div>

            {{-- Filter Periode --}}
            <div class="relative">
                <select id="filter-overview-period"
                        onchange="onFilterChange()"
                        class="appearance-none bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl px-3.5 py-2.5 pr-8 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all cursor-pointer">
                    <option value="all">Semua Periode</option>
                    <option value="2026-Q1">Kuartal 1 (Q1 2026)</option>
                    <option value="2026-Q2">Kuartal 2 (Q2 2026)</option>
                    <option value="2026-YTD" selected>Tahun Berjalan 2026 (YTD)</option>
                    <option value="2025">Tahun 2025</option>
                </select>
                <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">▼</div>
            </div>

            {{-- Tombol Unduh Laporan --}}
            <button type="button"
                    onclick="exportIsoReport()"
                    class="bg-[#003B73] hover:bg-[#002B54] text-white px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2 active:scale-95 focus:outline-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Unduh Laporan</span>
            </button>
        </div>
    </div>

    {{-- 2. KARTU UTAMA: INDEKS KEPUASAN PELANGGAN (FULL-WIDTH CARD) --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 md:p-8 shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

            {{-- Kolom Kiri: Gauge Chart (Doughnut Setengah Lingkaran) --}}
            <div class="lg:col-span-5 flex flex-col items-center justify-center relative">
                <div class="w-full max-w-[280px] sm:max-w-[320px] aspect-[2/1] relative flex items-center justify-center">
                    <canvas id="csiGaugeChart" class="w-full h-full"></canvas>
                    {{-- Angka Tengah Gauge --}}
                    <div class="absolute bottom-1 text-center select-none pointer-events-none">
                        <span id="gauge-center-val" class="text-3xl md:text-4xl font-black text-[#003B73] tracking-tight">85.7%</span>
                        <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Skor Kepuasan</span>
                    </div>
                </div>
                {{-- Skala Penanda di Bawah Gauge --}}
                <div class="flex justify-between w-full max-w-[280px] sm:max-w-[320px] text-[10px] font-bold text-slate-400 px-2 mt-1">
                    <span class="text-rose-500">0%</span>
                    <span class="text-amber-500">50%</span>
                    <span class="text-blue-500">75%</span>
                    <span class="text-emerald-500">80%</span>
                    <span class="text-emerald-600">100%</span>
                </div>
            </div>

            {{-- Kolom Kanan: Skor, Badge Otomatis, & 4 Kotak Ringkasan --}}
            <div class="lg:col-span-7 space-y-4">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        Indeks Kepuasan Pelanggan
                    </span>
                    <span id="csi-status-badge" class="px-3 py-1 rounded-full text-xs font-extrabold border bg-emerald-50 text-emerald-700 border-emerald-200">
                        Memenuhi Target
                    </span>
                </div>

                <div class="flex items-baseline gap-3">
                    <span id="csi-score-number" class="text-4xl md:text-5xl font-black text-[#003B73] tracking-tight">
                        85.70%
                    </span>
                    <span class="text-xs text-slate-400 font-semibold">
                        / Target Perusahaan: ≥ 80.00%
                    </span>
                </div>

                <p id="csi-interpretation-text" class="text-xs md:text-sm text-slate-600 leading-relaxed">
                    Kinerja layanan sudah mencapai target perusahaan (≥ 80%).
                </p>

                {{-- 4 Kotak Ringkasan Sejajar --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 pt-4 border-t border-slate-100">
                    {{-- 1: Tingkat Harapan --}}
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100 text-center">
                        <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide">Tingkat Harapan</span>
                        <span id="avg-kepentingan-val" class="text-base md:text-lg font-black text-[#003B73]">4.62</span>
                        <span class="block text-[10px] text-slate-400 font-medium">dari skala 5.0</span>
                    </div>

                    {{-- 2: Tingkat Kepuasan --}}
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100 text-center">
                        <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide">Tingkat Kepuasan</span>
                        <span id="avg-kepuasan-val" class="text-base md:text-lg font-black text-amber-600">4.28</span>
                        <span class="block text-[10px] text-slate-400 font-medium">dari skala 5.0</span>
                    </div>

                    {{-- 3: Kesesuaian Harapan --}}
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100 text-center">
                        <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide">Kesesuaian Harapan</span>
                        <span id="gap-index-val" class="text-base md:text-lg font-black text-emerald-600">+0.08</span>
                        <span id="gap-label-text" class="block text-[10px] text-emerald-600 font-bold">(Melampaui Harapan)</span>
                    </div>

                    {{-- 4: Partisipasi Responden --}}
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100 text-center">
                        <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide">Partisipasi Responden</span>
                        <span id="participation-ratio-val" class="text-base md:text-lg font-black text-slate-800">6 dari 12 PIC (50%)</span>
                        <span id="participation-subtext" class="block text-[10px] text-slate-400 font-medium">dari 3 / 5 Proyek Aktif</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- 3. 4 KARTU DIMENSI (GRID 4 KOLOM) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Dimensi A: Layanan & Dukungan --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="w-9 h-9 rounded-xl bg-blue-50 text-[#003B73] font-bold flex items-center justify-center text-lg">
                        🤝
                    </span>
                    <span id="dim-badge-A" class="text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-md border text-emerald-700 bg-emerald-50 border-emerald-200">
                        Sangat Memuaskan
                    </span>
                </div>
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                    Layanan & Dukungan
                </h3>
                <div class="flex items-baseline gap-2 mt-1 mb-2">
                    <span id="dim-score-A" class="text-2xl md:text-3xl font-black text-[#003B73]">88.2%</span>
                </div>
                {{-- Mini Progress --}}
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden mb-3">
                    <div id="dim-bar-A" class="h-full bg-blue-600 rounded-full transition-all duration-500" style="width: 88.2%;"></div>
                </div>
            </div>
            {{-- Statistik Rasio Proyek & PIC --}}
            <div class="pt-2 border-t border-slate-100 text-[11px] text-slate-400 font-medium">
                <span id="dim-ratio-A" class="text-slate-500 font-semibold">3/5 Proyek • 6/12 PIC</span>
            </div>
        </div>

        {{-- Dimensi B: Pelaksanaan Proyek --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 font-bold flex items-center justify-center text-lg">
                        🏗️
                    </span>
                    <span id="dim-badge-B" class="text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-md border text-blue-700 bg-blue-50 border-blue-200">
                        Memuaskan
                    </span>
                </div>
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                    Pelaksanaan Proyek
                </h3>
                <div class="flex items-baseline gap-2 mt-1 mb-2">
                    <span id="dim-score-B" class="text-2xl md:text-3xl font-black text-[#003B73]">84.6%</span>
                </div>
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden mb-3">
                    <div id="dim-bar-B" class="h-full bg-amber-500 rounded-full transition-all duration-500" style="width: 84.6%;"></div>
                </div>
            </div>
            <div class="pt-2 border-t border-slate-100 text-[11px] text-slate-400 font-medium">
                <span id="dim-ratio-B" class="text-slate-500 font-semibold">3/5 Proyek • 6/12 PIC</span>
            </div>
        </div>

        {{-- Dimensi C: Masa Pemeliharaan --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="w-9 h-9 rounded-xl bg-purple-50 text-purple-700 font-bold flex items-center justify-center text-lg">
                        🔧
                    </span>
                    <span id="dim-badge-C" class="text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-md border text-blue-700 bg-blue-50 border-blue-200">
                        Memuaskan
                    </span>
                </div>
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                    Masa Pemeliharaan
                </h3>
                <div class="flex items-baseline gap-2 mt-1 mb-2">
                    <span id="dim-score-C" class="text-2xl md:text-3xl font-black text-[#003B73]">83.5%</span>
                </div>
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden mb-3">
                    <div id="dim-bar-C" class="h-full bg-purple-600 rounded-full transition-all duration-500" style="width: 83.5%;"></div>
                </div>
            </div>
            <div class="pt-2 border-t border-slate-100 text-[11px] text-slate-400 font-medium">
                <span id="dim-ratio-C" class="text-slate-500 font-semibold">3/5 Proyek • 6/12 PIC</span>
            </div>
        </div>

        {{-- Dimensi D: K3L --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 font-bold flex items-center justify-center text-lg">
                        🦺
                    </span>
                    <span id="dim-badge-D" class="text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-md border text-emerald-700 bg-emerald-50 border-emerald-200">
                        Sangat Memuaskan
                    </span>
                </div>
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                    K3L & Keselamatan
                </h3>
                <div class="flex items-baseline gap-2 mt-1 mb-2">
                    <span id="dim-score-D" class="text-2xl md:text-3xl font-black text-[#003B73]">90.5%</span>
                </div>
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden mb-3">
                    <div id="dim-bar-D" class="h-full bg-emerald-600 rounded-full transition-all duration-500" style="width: 90.5%;"></div>
                </div>
            </div>
            <div class="pt-2 border-t border-slate-100 text-[11px] text-slate-400 font-medium">
                <span id="dim-ratio-D" class="text-slate-500 font-semibold">3/5 Proyek • 6/12 PIC</span>
            </div>
        </div>

    </div>

    {{-- 4. ANALITIK: HARAPAN VS KENYATAAN & PEMANTAUAN (GRID 12 KOLOM) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Kiri (lg:col-span-7): Grafik Harapan vs. Kenyataan --}}
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 p-5 md:p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-800 tracking-tight">
                            Harapan vs. Kenyataan
                        </h2>
                        <p class="text-xs text-slate-500">
                            Membandingkan hal yang dianggap penting oleh klien (batang biru) dengan kepuasan yang dirasakan (garis kuning).
                        </p>
                    </div>
                    {{-- Legend --}}
                    <div class="flex items-center gap-3 text-xs font-semibold shrink-0">
                        <span class="inline-flex items-center gap-1.5 text-[#003B73]">
                            <span class="w-3 h-3 rounded-sm bg-[#003B73]"></span> Harapan
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-amber-600">
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span> Kepuasan
                        </span>
                    </div>
                </div>

                {{-- Container Grafik Chart.js --}}
                <div class="relative w-full h-[240px] md:h-[280px]">
                    <canvas id="ipaComboChart"></canvas>
                </div>
            </div>

            {{-- Kotak Kesimpulan Utama (Dihitung Otomatis oleh JavaScript) --}}
            <div class="mt-4 bg-slate-50 border-l-4 border-amber-500 p-3 rounded-r-xl">
                <div class="flex items-start gap-2.5">
                    <span class="text-base">💡</span>
                    <div>
                        <span class="text-xs font-bold text-slate-700 block mb-0.5">Kesimpulan Utama</span>
                        <p id="chart-main-conclusion" class="text-xs text-slate-600 leading-relaxed">
                            Memuat analisis perbandingan harapan dan kepuasan klien...
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kanan (lg:col-span-5): Pemantauan & Tindak Lanjut --}}
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 p-5 md:p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-800 tracking-tight">
                            Pemantauan & Tindak Lanjut
                        </h2>
                        <p class="text-xs text-slate-400">
                            Kecepatan penanganan keluhan dan daftar masalah prioritas
                        </p>
                    </div>
                    <span class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 font-bold text-sm">
                        ⚡
                    </span>
                </div>

                {{-- Bagian Ketepatan Waktu Penanganan --}}
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 mb-4">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wide">
                            Ketepatan Waktu Penanganan
                        </span>
                        <span id="sla-adherence-pct" class="text-sm font-extrabold text-emerald-600">
                            50% Tepat Waktu
                        </span>
                    </div>
                    <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden mb-2">
                        <div id="sla-adherence-bar" class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: 50%;"></div>
                    </div>
                    <div class="flex justify-between text-[11px] text-slate-500">
                        <span id="sla-detail-text">3 dari 6 keluhan selesai sesuai target</span>
                        <span class="text-slate-400">Target: ≥ 90%</span>
                    </div>
                </div>

                {{-- Bagian Daftar Masalah Prioritas --}}
                <div class="space-y-2.5">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wide">
                                Perlu Segera Ditindaklanjuti
                            </span>
                            <span class="block text-[10px] text-slate-400">
                                Keluhan mendesak atau penilaian tidak puas yang butuh solusi segera
                            </span>
                        </div>
                        <span id="open-capa-count" class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-red-100 text-red-700 border border-red-200 shrink-0">
                            0 Masalah
                        </span>
                    </div>

                    {{-- Daftar Dinamis Masalah --}}
                    <div id="capa-alerts-container" class="space-y-2 max-h-[170px] overflow-y-auto pr-1">
                        {{-- Diisi secara dinamis oleh JS --}}
                    </div>
                </div>
            </div>

            {{-- Footer Status Layanan Dinamis (Bukan Hardcoded) --}}
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                <span>Evaluasi Sistem & Mutu LRS</span>
                <span id="status-layanan-badge" class="font-bold text-emerald-600">
                    ● Status Layanan: Baik
                </span>
            </div>
        </div>

    </div>

</div>
