{{-- =========================================================================
     SECTION 2: DATA SURVEI (REKAP RESPONDEN & PERHITUNGAN CSI MODULAR)
     ========================================================================= --}}
<div id="section-surveys" class="admin-section space-y-6 hidden">

    {{-- 1. HEADER SECTION & SEGMENTED TAB SWITCHER --}}
    <div class="bg-white/95 backdrop-blur-md rounded-2xl border border-slate-200/90 p-5 md:p-6 shadow-[0_2px_12px_rgba(15,23,42,0.04)] flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight">
                    Rekap Data Survei Kepuasan
                </h1>
            </div>
            <p class="text-xs md:text-sm text-slate-500 mt-1">
                Seluruh data survei kepuasan yang telah dikirimkan oleh klien beserta matriks perhitungan CSI, tersinkronisasi otomatis dengan penyimpanan browser.
            </p>
        </div>

        {{-- Segmented Tab Switcher --}}
        <div role="tablist"
             aria-label="Navigasi Sub-Menu Data Survei"
             class="inline-flex items-center p-1 rounded-2xl bg-slate-100/90 border border-slate-200/80 shadow-2xs self-start md:self-auto shrink-0">
            <button type="button"
                    role="tab"
                    id="tab-btn-surveys-recap"
                    aria-selected="true"
                    aria-controls="panel-surveys-recap"
                    onclick="switchSurveysSubTab('recap')"
                    class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all duration-200 flex items-center gap-2 bg-[#003B73] text-white shadow-xs cursor-pointer">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Rekap Responden</span>
            </button>
            <button type="button"
                    role="tab"
                    id="tab-btn-surveys-csi"
                    aria-selected="false"
                    aria-controls="panel-surveys-csi"
                    onclick="switchSurveysSubTab('csi')"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-white/80 cursor-pointer">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <span>Perhitungan CSI</span>
            </button>
        </div>
    </div>

    {{-- =====================================================================
         TAB 1 PANEL: REKAP RESPONDEN (FILTER + KPI + TABEL + PAGINATION)
         ===================================================================== --}}
    <div id="panel-surveys-recap"
         role="tabpanel"
         aria-labelledby="tab-btn-surveys-recap"
         class="space-y-6 transition-opacity duration-200">

        {{-- Toolbar Filter & Ekspor CSV Tab 1 --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-2xs flex flex-wrap items-center justify-between gap-2.5">
            <div class="flex flex-wrap items-center gap-2.5 flex-1">
                {{-- Input Pencarian --}}
                <div class="relative w-full sm:w-64">
                    <label for="filter-surveys-search" class="sr-only">Cari referensi, proyek, responden</label>
                    <input type="text"
                           id="filter-surveys-search"
                           oninput="onSurveyFilterChange()"
                           placeholder="Cari referensi, proyek, responden..."
                           class="w-full bg-slate-50/90 border border-slate-200/90 rounded-xl pl-8 pr-3 py-2 text-xs font-semibold text-slate-700 placeholder-slate-400 shadow-2xs hover:shadow-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                {{-- Filter Proyek --}}
                <div class="relative w-full sm:w-auto">
                    <label for="filter-surveys-project" class="sr-only">Filter Proyek Rekap Responden</label>
                    <select id="filter-surveys-project"
                            onchange="onSurveyFilterChange()"
                            class="w-full sm:w-auto appearance-none bg-slate-50/90 hover:bg-white border border-slate-200/90 rounded-xl px-3 py-2 pr-8 text-xs font-semibold text-slate-700 shadow-2xs hover:shadow-xs focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all cursor-pointer">
                        <option value="all">Semua Proyek</option>
                        <option value="GENERAL">Survei Umum (Non-Kontrak)</option>
                    </select>
                    <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                {{-- Filter Kategori --}}
                <div class="relative w-full sm:w-auto">
                    <label for="filter-surveys-category" class="sr-only">Filter Kategori Rekap Responden</label>
                    <select id="filter-surveys-category"
                            onchange="onSurveyFilterChange()"
                            class="w-full sm:w-auto appearance-none bg-slate-50/90 hover:bg-white border border-slate-200/90 rounded-xl px-3 py-2 pr-8 text-xs font-semibold text-slate-700 shadow-2xs hover:shadow-xs focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all cursor-pointer">
                        <option value="all">Semua Kategori</option>
                        <option value="A">Kategori A · Layanan &amp; Dukungan</option>
                        <option value="B">Kategori B · Pelaksanaan Proyek</option>
                        <option value="C">Kategori C · Masa Pemeliharaan</option>
                    </select>
                    <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            {{-- Tombol Ekspor CSV --}}
            <button type="button"
                    onclick="exportSurveysCsv()"
                    class="w-full sm:w-auto justify-center bg-[#003B73] hover:bg-[#002B54] text-white px-3.5 py-2 rounded-xl text-xs font-bold transition-all shadow-sm shadow-[#003B73]/20 hover:shadow-md hover:shadow-[#003B73]/30 flex items-center gap-1.5 active:scale-95 focus:outline-none cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Ekspor CSV</span>
            </button>
        </div>

        {{-- Ringkasan Counter Kategori --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs">
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wide">Total Survei</span>
                <span id="stat-surveys-total" class="text-xl font-black text-slate-800">0</span>
                <span class="block text-[10px] text-slate-400">Responden terdaftar</span>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs">
                <span class="block text-[11px] font-bold text-blue-500 uppercase tracking-wide">Layanan &amp; Dukungan</span>
                <span id="stat-surveys-cat-a" class="text-xl font-black text-[#003B73]">0</span>
                <span class="block text-[10px] text-slate-400">5 Indikator dinilai</span>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs">
                <span class="block text-[11px] font-bold text-amber-500 uppercase tracking-wide">Pelaksanaan Proyek</span>
                <span id="stat-surveys-cat-b" class="text-xl font-black text-amber-600">0</span>
                <span class="block text-[10px] text-slate-400">11 Indikator &amp; K3L</span>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs">
                <span class="block text-[11px] font-bold text-purple-500 uppercase tracking-wide">Masa Pemeliharaan</span>
                <span id="stat-surveys-cat-c" class="text-xl font-black text-purple-600">0</span>
                <span class="block text-[10px] text-slate-400">5 Indikator dinilai</span>
            </div>
        </div>

        {{-- Tabel Rekapitulasi Survei + Pagination --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-[0_2px_12px_rgba(15,23,42,0.04)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/90 border-b border-slate-200/80 text-[11px] font-extrabold text-slate-600 uppercase tracking-wider">
                            <th class="py-3.5 px-4">No. Referensi</th>
                            <th class="py-3.5 px-4">Tanggal &amp; Jam</th>
                            <th class="py-3.5 px-4">Proyek &amp; Klien</th>
                            <th class="py-3.5 px-4">Kategori Survei</th>
                            <th class="py-3.5 px-4">Personel Pendamping</th>
                            <th class="py-3.5 px-4">Kepuasan &amp; Kepentingan</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-surveys-body" class="divide-y divide-slate-100 text-slate-700">
                        {{-- Diisi secara dinamis oleh JS --}}
                    </tbody>
                </table>
            </div>

            {{-- State Kosong --}}
            <div id="table-surveys-empty" class="hidden py-12 px-4 text-center text-slate-400">
                <div class="w-11 h-11 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-2.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-slate-600">Tidak ada data survei yang cocok.</p>
                <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter kategori Anda.</p>
            </div>

            {{-- Footer Pagination Rekap Responden (10 / 25 / 50) --}}
            <div id="surveys-recap-pagination-bar" class="px-4 sm:px-5 py-3.5 bg-slate-50/80 border-t border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <div class="flex flex-wrap items-center gap-3">
                    <span id="surveys-recap-pagination-info" class="font-semibold text-slate-600">
                        Menampilkan 0–0 dari 0 responden
                    </span>
                    <div class="flex items-center gap-1.5">
                        <label for="surveys-recap-per-page" class="text-slate-500 font-medium">Tampilkan:</label>
                        <select id="surveys-recap-per-page"
                                onchange="onSurveyRecapPerPageChange()"
                                class="bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-100 cursor-pointer">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
                <div id="surveys-recap-pagination-controls" class="flex items-center gap-1.5 flex-wrap"></div>
            </div>
        </div>
    </div>

    {{-- =====================================================================
         TAB 2 PANEL: PERHITUNGAN CSI (KOMPONEN BLADE MODULAR)
         ===================================================================== --}}
    <div id="panel-surveys-csi"
         role="tabpanel"
         aria-labelledby="tab-btn-surveys-csi"
         class="hidden opacity-0 transition-opacity duration-200">
        <x-admin::csi-matrix />
    </div>

    {{-- Modal Modular Edit Metadata Responden --}}
    <x-admin::surveys.edit-metadata-modal />

</div>
