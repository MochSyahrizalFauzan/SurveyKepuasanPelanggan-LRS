{{-- =========================================================================
     SECTION 3: DAFTAR & PROGRES KELUHAN (TABEL & KANBAN INTERAKTIF)
     ========================================================================= --}}
<div id="section-complaints" class="admin-section space-y-6 hidden">

    {{-- 1. HEADER KONTROL INLINE --}}
    <div class="bg-white/95 backdrop-blur-md rounded-2xl border border-slate-200/90 p-5 md:p-6 shadow-[0_2px_12px_rgba(15,23,42,0.04)] flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 font-bold flex items-center justify-center text-sm shadow-2xs border border-rose-100">
                    🎫
                </span>
                <h1 class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight">
                    Daftar & Progres Keluhan
                </h1>
            </div>
            <p class="text-xs md:text-sm text-slate-500 mt-1">
                Pantau dan kelola tindak lanjut setiap keluhan atau kendala dari klien, status penyelesaian, dan kepatuhan batas waktu (SLA).
            </p>
        </div>

        {{-- Switcher Tampilan (Tabel vs Kanban) & SLA Ringkasan --}}
        <div class="flex flex-wrap items-center gap-3">
            {{-- Pill Switcher Tampilan Tabel / Kanban --}}
            <div class="inline-flex p-1 rounded-xl bg-slate-100 border border-slate-200" role="tablist">
                <button type="button"
                        id="btn-view-complaint-table"
                        onclick="switchComplaintView('table')"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-[#003B73] text-white shadow-xs flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    <span>Tabel</span>
                </button>
                <button type="button"
                        id="btn-view-complaint-kanban"
                        onclick="switchComplaintView('kanban')"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-200/60 flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                    </svg>
                    <span>Papan Kanban</span>
                </button>
            </div>

            {{-- Ringkasan Status Batas Waktu SLA --}}
            <div class="flex items-center gap-1.5">
                <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    <span id="sla-pill-overdue">0 Melewati Batas</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span id="sla-pill-warning">0 Mendekati Batas</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span id="sla-pill-ontrack">0 Tepat Waktu</span>
                </span>
            </div>
        </div>
    </div>

    {{-- 2. BAR FILTER STATUS & PENCARIAN --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        {{-- Status Filter Buttons --}}
        <div class="flex flex-wrap items-center gap-2">
            <button type="button"
                    onclick="filterComplaintsByStatus('all')"
                    id="filter-status-all"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold border bg-[#003B73] text-white border-[#003B73] shadow-xs cursor-pointer transition-all">
                Semua (<span id="count-complaints-all">0</span>)
            </button>
            <button type="button"
                    onclick="filterComplaintsByStatus('Open')"
                    id="filter-status-open"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold border bg-slate-50 text-slate-700 border-slate-200 hover:border-slate-300 shadow-2xs cursor-pointer transition-all">
                Baru Masuk (<span id="count-complaints-open">0</span>)
            </button>
            <button type="button"
                    onclick="filterComplaintsByStatus('In Progress')"
                    id="filter-status-progress"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold border bg-slate-50 text-slate-700 border-slate-200 hover:border-slate-300 shadow-2xs cursor-pointer transition-all">
                Diproses (<span id="count-complaints-progress">0</span>)
            </button>
            <button type="button"
                    onclick="filterComplaintsByStatus('Resolved')"
                    id="filter-status-resolved"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold border bg-slate-50 text-slate-700 border-slate-200 hover:border-slate-300 shadow-2xs cursor-pointer transition-all">
                Selesai (<span id="count-complaints-resolved">0</span>)
            </button>
        </div>

        {{-- Input Pencarian & Dropdown Kategori --}}
        <div class="flex flex-wrap items-center gap-2">
            <div class="relative w-full sm:w-56">
                <input type="text"
                       id="filter-kanban-search"
                       oninput="onKanbanFilterChange()"
                       placeholder="Cari ID tiket, proyek..."
                       class="w-full bg-slate-50/90 border border-slate-200/90 rounded-xl pl-8 pr-3 py-1.5 text-xs font-semibold text-slate-700 placeholder-slate-400 shadow-2xs hover:shadow-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <div class="relative">
                <select id="filter-kanban-category"
                        onchange="onKanbanFilterChange()"
                        class="appearance-none bg-slate-50/90 hover:bg-white border border-slate-200/90 rounded-xl px-3 py-1.5 pr-7 text-xs font-semibold text-slate-700 shadow-2xs hover:shadow-xs focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all cursor-pointer">
                    <option value="all">Semua Kategori</option>
                    <option value="Teknis">Teknis & Operasional</option>
                    <option value="Layanan">Layanan & Koordinasi</option>
                    <option value="K3L">K3L & Keselamatan</option>
                    <option value="Produk">Kualitas Produk</option>
                </select>
                <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]">▼</div>
            </div>
        </div>
    </div>

    {{-- 3. TAMPILAN 1: TABEL KELUHAN (DEFAULT) DENGAN INLINE DROPDOWN STATUS --}}
    <div id="container-complaint-table" class="bg-white rounded-2xl border border-slate-200/90 shadow-[0_2px_12px_rgba(15,23,42,0.04)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-[11px] font-extrabold text-slate-600 uppercase tracking-wider">
                        <th class="py-3.5 px-4">ID Tiket</th>
                        <th class="py-3.5 px-4">Waktu & SLA</th>
                        <th class="py-3.5 px-4">Proyek & Pelapor</th>
                        <th class="py-3.5 px-4">Kategori & Keluhan</th>
                        <th class="py-3.5 px-4">Status Langsung</th>
                        <th class="py-3.5 px-4">PIC Penanganan</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-complaints-body" class="divide-y divide-slate-100 text-slate-700">
                    {{-- Diisi secara dinamis oleh JS --}}
                </tbody>
            </table>
        </div>

        <div id="table-complaints-empty" class="hidden py-12 px-4 text-center text-slate-400">
            <span class="text-4xl block mb-2">🎉</span>
            <p class="text-sm font-bold text-slate-600">Tidak ada tiket keluhan ditemukan.</p>
            <p class="text-xs text-slate-400 mt-1">Semua keluhan dalam filter ini telah tertangani dengan baik.</p>
        </div>
    </div>

    {{-- 4. TAMPILAN 2: PAPAN KANBAN (3 KOLOM DRAG & DROP) --}}
    <div id="container-complaint-kanban" class="grid grid-cols-1 lg:grid-cols-3 gap-6 hidden">

        {{-- Kolom 1: Masuk --}}
        <div class="kanban-column bg-slate-100/75 rounded-2xl p-4 border border-slate-200/80 shadow-[0_1px_3px_rgba(15,23,42,0.03)] flex flex-col transition-colors min-h-[500px]"
             id="col-open"
             ondragover="handleDragOver(event, 'Open')"
             ondragleave="handleDragLeave(event, 'Open')"
             ondrop="handleDrop(event, 'Open')">

            {{-- Header Kolom --}}
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200/80">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-rose-500 shadow-2xs"></span>
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">
                        Masuk (Open)
                    </h3>
                </div>
                <span id="count-col-open" class="text-xs font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 border border-rose-200 shadow-2xs">
                    0
                </span>
            </div>

            {{-- Kartu List --}}
            <div id="cards-col-open" class="kanban-cards-list space-y-3 flex-1 overflow-y-auto max-h-[700px] pr-1">
                {{-- Diisi secara dinamis oleh JS --}}
            </div>
        </div>

        {{-- Kolom 2: Dalam Proses --}}
        <div class="kanban-column bg-slate-100/75 rounded-2xl p-4 border border-slate-200/80 shadow-[0_1px_3px_rgba(15,23,42,0.03)] flex flex-col transition-colors min-h-[500px]"
             id="col-progress"
             ondragover="handleDragOver(event, 'In Progress')"
             ondragleave="handleDragLeave(event, 'In Progress')"
             ondrop="handleDrop(event, 'In Progress')">

            {{-- Header Kolom --}}
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200/80">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-500 shadow-2xs"></span>
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">
                        Dalam Proses
                    </h3>
                </div>
                <span id="count-col-progress" class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200 shadow-2xs">
                    0
                </span>
            </div>

            {{-- Kartu List --}}
            <div id="cards-col-progress" class="kanban-cards-list space-y-3 flex-1 overflow-y-auto max-h-[700px] pr-1">
                {{-- Diisi secara dinamis oleh JS --}}
            </div>
        </div>

        {{-- Kolom 3: Selesai --}}
        <div class="kanban-column bg-slate-100/75 rounded-2xl p-4 border border-slate-200/80 shadow-[0_1px_3px_rgba(15,23,42,0.03)] flex flex-col transition-colors min-h-[500px]"
             id="col-resolved"
             ondragover="handleDragOver(event, 'Resolved')"
             ondragleave="handleDragLeave(event, 'Resolved')"
             ondrop="handleDrop(event, 'Resolved')">

            {{-- Header Kolom --}}
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200/80">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-2xs"></span>
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">
                        Selesai
                    </h3>
                </div>
                <span id="count-col-resolved" class="text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200 shadow-2xs">
                    0
                </span>
            </div>

            {{-- Kartu List --}}
            <div id="cards-col-resolved" class="kanban-cards-list space-y-3 flex-1 overflow-y-auto max-h-[700px] pr-1">
                {{-- Diisi secara dinamis oleh JS --}}
            </div>
        </div>

    </div>

</div>
