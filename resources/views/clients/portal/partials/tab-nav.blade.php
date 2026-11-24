{{-- =========================================================================
     NAVIGASI 3 MENU UTAMA LAYANAN KLIEN (RESPONSIF DESKTOP & MOBILE)
     1. Isi Survei Kepuasan (Fill Satisfaction Survey)
     2. Sampaikan Keluhan & Lacak Tiket (Submit a Complaint & Track Ticket)
     3. Masukan & Saran Umum (General Feedback)
     ========================================================================= --}}
<div class="w-full bg-white rounded-2xl border border-slate-200/90 p-2 sm:p-2.5 shadow-[0_2px_12px_rgba(15,23,42,0.04)]">
    <nav class="grid grid-cols-1 sm:grid-cols-3 gap-2" role="tablist" aria-label="Pilihan Layanan Klien">

        {{-- Tab 1: Isi Survei Kepuasan --}}
        <button type="button"
                id="tab-btn-survei"
                class="tab-btn active w-full px-3.5 py-3 rounded-xl flex items-center gap-3 transition-all text-left font-bold border border-transparent"
                role="tab"
                aria-selected="true"
                data-tab="survei"
                onclick="switchTab('survei', this)">
            <span class="w-10 h-10 rounded-xl bg-blue-50 text-[#003B73] flex items-center justify-center text-lg shrink-0 shadow-2xs border border-blue-100">
                📋
            </span>
            <div class="flex-1 min-w-0">
                <span class="tab-label-title block text-xs sm:text-sm font-black text-slate-800 leading-tight truncate">
                    Isi Survei Kepuasan
                </span>
                <span class="tab-label-desc block text-[11px] text-slate-400 font-semibold truncate mt-0.5">
                    Evaluasi 3 Kategori Layanan
                </span>
            </div>
        </button>

        {{-- Tab 2: Sampaikan Keluhan --}}
        <button type="button"
                id="tab-btn-keluhan"
                class="tab-btn w-full px-3.5 py-3 rounded-xl flex items-center gap-3 transition-all text-left font-bold border border-transparent"
                role="tab"
                aria-selected="false"
                data-tab="keluhan"
                onclick="switchTab('keluhan', this)">
            <div class="flex-1 min-w-0">
                <span class="tab-label-title block text-xs sm:text-sm font-black text-slate-800 leading-tight truncate">
                    Sampaikan Keluhan
                </span>
                <span class="tab-label-desc block text-[11px] text-slate-400 font-semibold truncate mt-0.5">
                    Pelaporan & Lacak Tiket
                </span>
            </div>
        </button>

        {{-- Tab 3: Masukan & Saran Umum --}}
        <button type="button"
                id="tab-btn-masukan"
                class="tab-btn w-full px-3.5 py-3 rounded-xl flex items-center gap-3 transition-all text-left font-bold border border-transparent"
                role="tab"
                aria-selected="false"
                data-tab="masukan"
                onclick="switchTab('masukan', this)">
            <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-lg shrink-0 shadow-2xs border border-amber-100">
                💬
            </span>
            <div class="flex-1 min-w-0">
                <span class="tab-label-title block text-xs sm:text-sm font-black text-slate-800 leading-tight truncate">
                    Masukan & Saran Umum
                </span>
                <span class="tab-label-desc block text-[11px] text-slate-400 font-semibold truncate mt-0.5">
                    Apresiasi & Evaluasi Strategis
                </span>
            </div>
        </button>
    </nav>
</div>

