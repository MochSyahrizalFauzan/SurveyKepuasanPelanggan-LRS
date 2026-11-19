{{-- =========================================================================
     SECTION 2: DAFTAR & PROGRES KELUHAN
     ========================================================================= --}}
<div id="section-kanban" class="admin-section space-y-6 hidden">

    {{-- 1. HEADER KONTROL INLINE --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 md:p-6 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight">
                Daftar & Progres Keluhan
            </h1>
            <p class="text-xs md:text-sm text-slate-500 mt-0.5">
                Pantau dan kelola tindak lanjut setiap keluhan atau kendala dari klien
            </p>
        </div>

        {{-- Filter & Ringkasan Batas Waktu --}}
        <div class="flex flex-wrap items-center gap-3">
            {{-- Input Pencarian --}}
            <div class="relative w-full sm:w-56">
                <input type="text"
                       id="filter-kanban-search"
                       oninput="onKanbanFilterChange()"
                       placeholder="Cari ID tiket, proyek..."
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-3 py-2 text-xs font-semibold text-slate-700 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            {{-- Filter Kategori --}}
            <div class="relative">
                <select id="filter-kanban-category"
                        onchange="onKanbanFilterChange()"
                        class="appearance-none bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl px-3.5 py-2 pr-8 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all cursor-pointer">
                    <option value="all">Semua Kategori</option>
                    <option value="Teknis">Teknis & Operasional</option>
                    <option value="Layanan">Layanan & Koordinasi</option>
                    <option value="K3L">K3L & Keselamatan</option>
                    <option value="Produk">Kualitas Produk</option>
                </select>
                <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">▼</div>
            </div>

            {{-- Ringkasan Status Batas Waktu --}}
            <div class="flex items-center gap-1.5">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    <span id="sla-pill-overdue">0 Melewati Batas</span>
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span id="sla-pill-warning">0 Mendekati Batas</span>
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span id="sla-pill-ontrack">0 Tepat Waktu</span>
                </span>
            </div>
        </div>
    </div>

    {{-- 2. KANBAN BOARD 3 KOLOM --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom 1: Masuk --}}
        <div class="kanban-column bg-slate-100/70 rounded-2xl p-4 border border-slate-200/80 flex flex-col transition-colors min-h-[500px]"
             id="col-open"
             ondragover="handleDragOver(event, 'Open')"
             ondragleave="handleDragLeave(event, 'Open')"
             ondrop="handleDrop(event, 'Open')">

            {{-- Header Kolom --}}
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200/80">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">
                        Masuk
                    </h3>
                </div>
                <span id="count-col-open" class="text-xs font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 border border-rose-200">
                    0
                </span>
            </div>

            {{-- Kartu List --}}
            <div id="cards-col-open" class="kanban-cards-list space-y-3 flex-1 overflow-y-auto max-h-[700px] pr-1">
                {{-- Diisi secara dinamis oleh JS --}}
            </div>
        </div>

        {{-- Kolom 2: Dalam Proses --}}
        <div class="kanban-column bg-slate-100/70 rounded-2xl p-4 border border-slate-200/80 flex flex-col transition-colors min-h-[500px]"
             id="col-progress"
             ondragover="handleDragOver(event, 'In Progress')"
             ondragleave="handleDragLeave(event, 'In Progress')"
             ondrop="handleDrop(event, 'In Progress')">

            {{-- Header Kolom --}}
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200/80">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">
                        Dalam Proses
                    </h3>
                </div>
                <span id="count-col-progress" class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">
                    0
                </span>
            </div>

            {{-- Kartu List --}}
            <div id="cards-col-progress" class="kanban-cards-list space-y-3 flex-1 overflow-y-auto max-h-[700px] pr-1">
                {{-- Diisi secara dinamis oleh JS --}}
            </div>
        </div>

        {{-- Kolom 3: Selesai --}}
        <div class="kanban-column bg-slate-100/70 rounded-2xl p-4 border border-slate-200/80 flex flex-col transition-colors min-h-[500px]"
             id="col-resolved"
             ondragover="handleDragOver(event, 'Resolved')"
             ondragleave="handleDragLeave(event, 'Resolved')"
             ondrop="handleDrop(event, 'Resolved')">

            {{-- Header Kolom --}}
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200/80">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">
                        Selesai
                    </h3>
                </div>
                <span id="count-col-resolved" class="text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200">
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
