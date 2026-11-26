{{-- =========================================================================
     KOMPONEN BLADE: TACTILE FLAG LANGUAGE SWITCHER (ID | EN)
     Menampilkan toggle kapsul dengan kenop bendera 3D geser (Merah-Putih / Union Jack)
     ========================================================================= --}}
<div id="lang-switcher-pill"
     data-active-lang="id"
     onclick="setLanguage(this.dataset.activeLang === 'en' ? 'id' : 'en')"
     class="relative inline-flex items-center w-[78px] h-9 p-1 rounded-full bg-slate-100 border border-slate-200/90 shadow-[inset_0_2px_5px_rgba(15,23,42,0.12)] hover:border-slate-300 transition-colors select-none cursor-pointer shrink-0"
     role="group"
     aria-label="Language Selector">

    {{-- Sliding Circular 3D Flag Knob --}}
    <span id="lang-flag-knob"
          aria-hidden="true"
          class="pointer-events-none absolute top-1 left-1 w-7 h-7 rounded-full overflow-hidden shadow-[0_2px_6px_rgba(15,23,42,0.28),0_1px_2px_rgba(15,23,42,0.18)] ring-1 ring-slate-900/10 transition-transform duration-300 ease-out translate-x-10 z-10">
        {{-- Bendera Indonesia (Merah-Putih) --}}
        <svg id="lang-flag-id" class="w-full h-full block" viewBox="0 0 32 32" aria-hidden="true">
            <rect width="32" height="16" y="0" fill="#DC2626" />
            <rect width="32" height="16" y="16" fill="#FFFFFF" />
        </svg>

        {{-- Bendera Inggris (Union Jack) --}}
        <svg id="lang-flag-en" class="w-full h-full hidden" viewBox="0 0 60 60" aria-hidden="true">
            <rect width="60" height="60" fill="#012169" />
            <path d="M0,0 L60,60 M60,0 L0,60" stroke="#FFFFFF" stroke-width="12" />
            <path d="M0,0 L60,60 M60,0 L0,60" stroke="#C8102E" stroke-width="6" />
            <path d="M30,0 V60 M0,30 H60" stroke="#FFFFFF" stroke-width="18" />
            <path d="M30,0 V60 M0,30 H60" stroke="#C8102E" stroke-width="10" />
        </svg>

        {{-- Efek Kilap & Inner Rim 3D --}}
        <span class="absolute inset-0 rounded-full bg-gradient-to-br from-white/40 via-transparent to-black/20 shadow-[inset_0_1px_1.5px_rgba(255,255,255,0.65)] pointer-events-none"></span>
    </span>

    {{-- Sisi Kiri: Label ID (tampil saat Bahasa Indonesia aktif) --}}
    <button type="button"
            id="btn-lang-id"
            onclick="event.stopPropagation(); setLanguage(document.getElementById('lang-switcher-pill')?.dataset.activeLang === 'id' ? 'en' : 'id')"
            title="Ubah Bahasa (ID / EN)"
            aria-label="Bahasa Indonesia"
            aria-pressed="true"
            class="relative z-20 flex-1 h-full flex items-center justify-center pl-1 pr-2 text-xs font-extrabold tracking-wider text-slate-600 transition-opacity duration-200 cursor-pointer focus:outline-none">
        <span>ID</span>
    </button>

    {{-- Sisi Kanan: Label EN (tampil saat Bahasa Inggris aktif) --}}
    <button type="button"
            id="btn-lang-en"
            onclick="event.stopPropagation(); setLanguage(document.getElementById('lang-switcher-pill')?.dataset.activeLang === 'en' ? 'id' : 'en')"
            title="Switch Language (ID / EN)"
            aria-label="English"
            aria-pressed="false"
            class="relative z-20 flex-1 h-full flex items-center justify-center pl-2 pr-1 text-xs font-extrabold tracking-wider text-slate-600 opacity-0 transition-opacity duration-200 cursor-pointer focus:outline-none">
        <span>EN</span>
    </button>
</div>
