{{-- =========================================================================
     SECTION 2: DATA SURVEI (REKAPITULASI SELURUH SURVEI KLIEN)
     ========================================================================= --}}
<div id="section-surveys" class="admin-section space-y-6 hidden">

    {{-- 1. HEADER KONTROL FILTER & EKSPOR --}}
    <div class="bg-white/95 backdrop-blur-md rounded-2xl border border-slate-200/90 p-5 md:p-6 shadow-[0_2px_12px_rgba(15,23,42,0.04)] flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight">
                    Rekap Data Survei Kepuasan
                </h1>
            </div>
            <p class="text-xs md:text-sm text-slate-500 mt-1">
                Seluruh data survei kepuasan yang telah dikirimkan oleh klien, tersinkronisasi otomatis dengan penyimpanan browser.
            </p>
        </div>

        {{-- Filter & Tombol Ekspor CSV --}}
        <div class="flex flex-wrap items-center gap-2.5">
            {{-- Input Pencarian --}}
            <div class="relative w-full sm:w-60">
                <input type="text"
                       id="filter-surveys-search"
                       oninput="onSurveyFilterChange()"
                       placeholder="Cari referensi, proyek, responden..."
                       class="w-full bg-slate-50/90 border border-slate-200/90 rounded-xl pl-8 pr-3 py-2 text-xs font-semibold text-slate-700 placeholder-slate-400 shadow-2xs hover:shadow-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            {{-- Filter Kategori --}}
            <div class="relative">
                <select id="filter-surveys-category"
                        onchange="onSurveyFilterChange()"
                        class="appearance-none bg-slate-50/90 hover:bg-white border border-slate-200/90 rounded-xl px-3 py-2 pr-7 text-xs font-semibold text-slate-700 shadow-2xs hover:shadow-xs focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all cursor-pointer">
                    <option value="all">Semua Kategori</option>
                    <option value="A">Kategori A · Layanan & Dukungan</option>
                    <option value="B">Kategori B · Pelaksanaan Proyek</option>
                    <option value="C">Kategori C · Masa Pemeliharaan</option>
                </select>
                <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]">▼</div>
            </div>

            {{-- Tombol Ekspor CSV --}}
            <button type="button"
                    onclick="exportSurveysCsv()"
                    class="bg-[#003B73] hover:bg-[#002B54] text-white px-3.5 py-2 rounded-xl text-xs font-bold transition-all shadow-sm shadow-[#003B73]/20 hover:shadow-md hover:shadow-[#003B73]/30 flex items-center gap-1.5 active:scale-95 focus:outline-none cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Ekspor CSV</span>
            </button>
        </div>
    </div>

    {{-- 2. RINGKASAN COUNTER KATEGORI --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs">
            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wide">Total Survei</span>
            <span id="stat-surveys-total" class="text-xl font-black text-slate-800">0</span>
            <span class="block text-[10px] text-slate-400">Responden terdaftar</span>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs">
            <span class="block text-[11px] font-bold text-blue-500 uppercase tracking-wide">Layanan & Dukungan</span>
            <span id="stat-surveys-cat-a" class="text-xl font-black text-[#003B73]">0</span>
            <span class="block text-[10px] text-slate-400">5 Indikator dinilai</span>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs">
            <span class="block text-[11px] font-bold text-amber-500 uppercase tracking-wide">Pelaksanaan Proyek</span>
            <span id="stat-surveys-cat-b" class="text-xl font-black text-amber-600">0</span>
            <span class="block text-[10px] text-slate-400">11 Indikator & K3L</span>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs">
            <span class="block text-[11px] font-bold text-purple-500 uppercase tracking-wide">Masa Pemeliharaan</span>
            <span id="stat-surveys-cat-c" class="text-xl font-black text-purple-600">0</span>
            <span class="block text-[10px] text-slate-400">5 Indikator dinilai</span>
        </div>
    </div>

    {{-- 3. TABEL REKAPITULASI SURVEI --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-[0_2px_12px_rgba(15,23,42,0.04)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-[11px] font-extrabold text-slate-600 uppercase tracking-wider">
                        <th class="py-3.5 px-4">No. Referensi</th>
                        <th class="py-3.5 px-4">Tanggal & Jam</th>
                        <th class="py-3.5 px-4">Proyek & Klien</th>
                        <th class="py-3.5 px-4">Kategori Survei</th>
                        <th class="py-3.5 px-4">Personel Pendamping</th>
                        <th class="py-3.5 px-4">Skor Rata-rata</th>
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
            <span class="text-4xl block mb-2">🔍</span>
            <p class="text-sm font-bold text-slate-600">Tidak ada data survei yang cocok.</p>
            <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter kategori Anda.</p>
        </div>
    </div>

</div>
