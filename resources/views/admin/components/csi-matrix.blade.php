{{-- =========================================================================
     KOMPONEN ADMIN: MATRIKS PERHITUNGAN CSI (CUSTOMER SATISFACTION INDEX)
     PT Len Railway Systems — Sesuai Standar Indikator FRM-QOP-06-02
     ========================================================================= --}}
<div id="csi-matrix-root"
     data-categories='@json(config('survey.categories'))'
     class="space-y-5">

    {{-- A. RINGKASAN 4 KARTU KPI CSI --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3.5">
        {{-- Kartu 1: CSI Total (%) + Kategori Mutu Otomatis --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#003B73] to-[#002855] text-white p-4 sm:p-5 shadow-md shadow-[#003B73]/20 flex flex-col justify-between gap-2">
            <div class="flex items-start justify-between gap-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-blue-100">
                    CSI Total (%)
                </span>
                <span class="w-8 h-8 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </span>
            </div>
            <div class="flex flex-wrap items-baseline justify-between gap-2">
                <span id="csi-kpi-total-val" class="text-2xl sm:text-3xl font-black tracking-tight">0.00%</span>
                <span id="csi-kpi-quality-badge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-white/15 text-white border border-white/25">
                    Belum Ada Data
                </span>
            </div>
            <p class="text-[11px] text-blue-100/90 leading-snug">
                Rasio terbobot &Sigma;(I &times; S) / &Sigma;(I) seluruh fasa aktif
            </p>
        </div>

        {{-- Kartu 2: Rata-rata Indeks Kepuasan (S) vs Indeks Kepentingan (I) --}}
        <div class="rounded-2xl bg-white border border-slate-200/90 p-4 sm:p-5 shadow-2xs flex flex-col justify-between gap-2">
            <div class="flex items-start justify-between gap-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    Indeks Kepuasan (S) vs Kepentingan (I)
                </span>
                <span class="w-8 h-8 rounded-xl bg-sky-50 text-[#003B73] border border-sky-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                    </svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2.5 flex-wrap">
                <div>
                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Rata-rata S</span>
                    <span id="csi-kpi-avg-s" class="text-xl sm:text-2xl font-black text-emerald-700">0.00</span>
                </div>
                <span class="text-slate-300 font-bold text-sm">/</span>
                <div>
                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Rata-rata I</span>
                    <span id="csi-kpi-avg-i" class="text-xl sm:text-2xl font-black text-[#003B73]">0.00</span>
                </div>
            </div>
            <p id="csi-kpi-avg-diff" class="text-[11px] text-slate-500 font-medium">
                Skala indeks 0–100 sesuai bobot persepsi klien
            </p>
        </div>

        {{-- Kartu 3: GAP Terbesar (Prioritas Perbaikan) --}}
        <div class="rounded-2xl bg-white border border-slate-200/90 p-4 sm:p-5 shadow-2xs flex flex-col justify-between gap-2">
            <div class="flex items-start justify-between gap-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-rose-600">
                    GAP Terbesar (Prioritas Perbaikan)
                </span>
                <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </span>
            </div>
            <div class="space-y-1 min-w-0">
                <span id="csi-kpi-worst-gap-val" class="text-xl sm:text-2xl font-black text-rose-600 block">0.00</span>
                <p id="csi-kpi-worst-gap-label" class="text-xs font-bold text-slate-700 line-clamp-2 break-words leading-snug">
                    Belum ada indikator terukur
                </p>
            </div>
        </div>

        {{-- Kartu 4: Total Responden Terhitung --}}
        <div class="rounded-2xl bg-white border border-slate-200/90 p-4 sm:p-5 shadow-2xs flex flex-col justify-between gap-2">
            <div class="flex items-start justify-between gap-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    Total Responden Terhitung
                </span>
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-[#003B73] border border-blue-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </span>
            </div>
            <div>
                <span id="csi-kpi-respondents-count" class="text-2xl sm:text-3xl font-black text-slate-800">0</span>
                <span id="csi-kpi-respondents-sub" class="block text-[11px] text-slate-500 mt-0.5">
                    Responden sesuai filter proyek &amp; fasa aktif
                </span>
            </div>
        </div>
    </div>

    {{-- B. TOOLBAR KONTROL & FILTER DINAMIS --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-2xs flex flex-col lg:flex-row lg:items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2.5 flex-1">
            {{-- Filter Proyek --}}
            <div class="relative w-full sm:w-56">
                <label for="csi-filter-project" class="sr-only">Filter Proyek CSI</label>
                <select id="csi-filter-project"
                        onchange="onCsiFilterChange()"
                        class="w-full appearance-none bg-slate-50/90 hover:bg-white border border-slate-200/90 rounded-xl px-3.5 py-2.5 pr-8 text-xs font-semibold text-slate-700 shadow-2xs focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all cursor-pointer">
                    <option value="all">Semua Proyek</option>
                    <option value="GENERAL">Survei Umum (Non-Kontrak)</option>
                </select>
                <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>

            {{-- Filter Fasa / Kategori --}}
            <div class="relative w-full sm:w-56">
                <label for="csi-filter-category" class="sr-only">Filter Fasa / Kategori</label>
                <select id="csi-filter-category"
                        onchange="onCsiFilterChange()"
                        class="w-full appearance-none bg-slate-50/90 hover:bg-white border border-slate-200/90 rounded-xl px-3.5 py-2.5 pr-8 text-xs font-semibold text-slate-700 shadow-2xs focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all cursor-pointer">
                    <option value="all">Semua Fasa (21 Indikator)</option>
                    @foreach (config('survey.categories', []) as $category)
                        <option value="{{ $category['code'] }}">
                            Fasa {{ $category['code'] }} · {{ $category['name'] }}
                        </option>
                    @endforeach
                </select>
                <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>

            {{-- Pencarian Atribut / Indikator Real-Time --}}
            <div class="relative w-full sm:flex-1 sm:min-w-[220px] lg:max-w-sm">
                <label for="csi-filter-search" class="sr-only">Cari Atribut atau Kode Indikator</label>
                <input type="text"
                       id="csi-filter-search"
                       oninput="onCsiFilterChange()"
                       placeholder="Cari kode atau atribut indikator (mis. B8, K3L)..."
                       class="w-full bg-slate-50/90 border border-slate-200/90 rounded-xl pl-9 pr-3 py-2.5 text-xs font-semibold text-slate-700 placeholder-slate-400 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        {{-- Tombol Ekspor Matriks CSI (CSV) --}}
        <button type="button"
                id="btn-export-csi-csv"
                onclick="exportCsiMatrixCsv()"
                class="w-full lg:w-auto justify-center bg-[#003B73] hover:bg-[#002B54] text-white px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm shadow-[#003B73]/20 hover:shadow-md flex items-center gap-2 active:scale-95 focus:outline-none cursor-pointer shrink-0">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Ekspor Matriks CSI (CSV)</span>
        </button>
    </div>

    {{-- C. KONTAINER TABEL MATRIKS CSI + PAGINATION + EMPTY STATE --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-[0_2px_12px_rgba(15,23,42,0.04)] overflow-hidden">

        {{-- Empty State --}}
        <div id="csi-empty-state" class="hidden py-14 px-6 text-center">
            <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 id="csi-empty-title" class="text-sm font-extrabold text-slate-700">
                Belum ada data survei pada proyek ini
            </h3>
            <p id="csi-empty-desc" class="text-xs text-slate-500 mt-1 max-w-md mx-auto leading-relaxed">
                Matriks perhitungan CSI akan otomatis muncul setelah terdapat minimal 1 responden survei yang sesuai dengan filter proyek dan fasa aktif.
            </p>
        </div>

        {{-- Tabel Matriks Responsif dengan Sticky Columns --}}
        <div id="csi-matrix-table-wrapper" class="overflow-x-auto scroll-smooth">
            <table id="csi-matrix-table" class="w-full text-left border-collapse text-xs">
                <thead>
                    {{-- Baris 1 Header --}}
                    <tr class="bg-[#003B73] text-white text-[11px] font-extrabold uppercase tracking-wider border-b border-blue-900/50">
                        <th rowspan="2" scope="col" class="sticky left-0 z-20 bg-[#003B73] w-12 min-w-[3rem] py-3 px-3 text-center border-r border-white/15">
                            No
                        </th>
                        <th rowspan="2" scope="col" class="sticky left-12 z-20 bg-[#003B73] min-w-[210px] sm:min-w-[260px] py-3 px-3.5 border-r border-white/20 shadow-[2px_0_6px_-2px_rgba(15,23,42,0.2)]">
                            Atribut / Indikator
                        </th>
                        <th colspan="5" scope="colgroup" class="py-2 px-2 text-center border-b border-r border-white/15 bg-[#003B73]">
                            Kepentingan (1–5)
                        </th>
                        <th rowspan="2" scope="col" class="py-3 px-3 text-right border-r border-white/15 whitespace-nowrap">
                            Indeks (I)
                        </th>
                        <th colspan="5" scope="colgroup" class="py-2 px-2 text-center border-b border-r border-white/15 bg-[#003B73]">
                            Kepuasan (1–5)
                        </th>
                        <th rowspan="2" scope="col" class="py-3 px-3 text-right border-r border-white/15 whitespace-nowrap">
                            Indeks (S)
                        </th>
                        <th rowspan="2" scope="col" class="py-3 px-3 text-right border-r border-white/15 whitespace-nowrap">
                            GAP
                        </th>
                        <th rowspan="2" scope="col" class="py-3 px-3 text-right border-r border-white/15 whitespace-nowrap">
                            Impr &times; Satisf
                        </th>
                        <th rowspan="2" scope="col" class="py-3 px-3 text-center border-r border-white/15 whitespace-nowrap">
                            CSI Per Fasa
                        </th>
                        <th rowspan="2" scope="col" class="py-3 px-3.5 text-center whitespace-nowrap">
                            CSI Total
                        </th>
                    </tr>
                    {{-- Baris 2 Header Sub-Kolom 1-5 --}}
                    <tr class="bg-[#002f5c] text-blue-100 text-[10px] font-bold text-center border-b border-slate-300">
                        <th scope="col" class="py-1.5 px-2 min-w-[44px] border-r border-white/10">1</th>
                        <th scope="col" class="py-1.5 px-2 min-w-[44px] border-r border-white/10">2</th>
                        <th scope="col" class="py-1.5 px-2 min-w-[44px] border-r border-white/10">3</th>
                        <th scope="col" class="py-1.5 px-2 min-w-[44px] border-r border-white/10">4</th>
                        <th scope="col" class="py-1.5 px-2 min-w-[44px] border-r border-white/20">5</th>
                        <th scope="col" class="py-1.5 px-2 min-w-[44px] border-r border-white/10">1</th>
                        <th scope="col" class="py-1.5 px-2 min-w-[44px] border-r border-white/10">2</th>
                        <th scope="col" class="py-1.5 px-2 min-w-[44px] border-r border-white/10">3</th>
                        <th scope="col" class="py-1.5 px-2 min-w-[44px] border-r border-white/10">4</th>
                        <th scope="col" class="py-1.5 px-2 min-w-[44px] border-r border-white/20">5</th>
                    </tr>
                </thead>
                <tbody id="csi-matrix-body" class="divide-y divide-slate-100 text-slate-700">
                    {{-- Diisi aman (escaped) oleh csi-engine.js --}}
                </tbody>
                <tfoot id="csi-matrix-foot" class="bg-slate-50 border-t-2 border-slate-200 text-xs font-extrabold text-slate-800">
                    {{-- Ringkasan keseluruhan filter aktif --}}
                </tfoot>
            </table>
        </div>

        {{-- Footer Pagination Matriks CSI --}}
        <div id="csi-pagination-bar" class="px-4 sm:px-5 py-3.5 bg-slate-50/80 border-t border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
            <div class="flex flex-wrap items-center gap-3">
                <span id="csi-pagination-info" class="font-semibold text-slate-600">
                    Menampilkan 0–0 dari 0 indikator
                </span>
                <div class="flex items-center gap-1.5">
                    <label for="csi-per-page" class="text-slate-500 font-medium">Baris:</label>
                    <select id="csi-per-page"
                            onchange="onCsiPerPageChange()"
                            class="bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-100 cursor-pointer">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="all">Semua</option>
                    </select>
                </div>
            </div>

            <div id="csi-pagination-controls" class="flex items-center gap-1.5 flex-wrap">
                {{-- Tombol Prev, nomor halaman, dan Next oleh csi-engine.js --}}
            </div>
        </div>
    </div>
</div>
