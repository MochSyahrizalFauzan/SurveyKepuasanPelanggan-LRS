@extends('layouts.app')

@section('title', 'Verifikasi Akses Klien · PT Len Railway Systems')

@section('content')
<div class="min-h-screen bg-transparent flex flex-col antialiased text-slate-800 w-full flex-1 justify-between">

    {{-- Top Bar Sederhana --}}
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200/70 sticky top-0 z-40 shadow-xs">
        <div class="h-1 w-full bg-gradient-to-r from-[#003B73] via-sky-500 to-[#D32F2F]"></div>
        <div class="max-w-7xl mx-auto px-4 py-3 md:px-6 md:py-3.5 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="h-10 px-2.5 rounded-xl bg-white flex items-center justify-center shrink-0 border border-slate-200 shadow-xs">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="Logo PT Len Railway Systems"
                         class="h-7 w-auto object-contain">
                </div>
                <div>
                    <span class="text-sm md:text-base font-black text-[#003B73] tracking-tight">PT Len Railway Systems</span>
                    <span id="txt-verify-badge" class="ml-2 px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-blue-50 text-[#003B73] border border-blue-100">Verifikasi Akses</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- Switcher Bahasa ID | EN --}}
                <div class="inline-flex items-center p-0.5 rounded-xl bg-slate-100 border border-slate-200/80 shadow-2xs" role="group" aria-label="Language Selector">
                    <button type="button"
                            id="btn-lang-id"
                            onclick="setLanguage('id')"
                            class="px-2.5 py-1 rounded-lg text-xs font-black transition-all bg-[#003B73] text-white shadow-xs">
                        ID
                    </button>
                    <button type="button"
                            id="btn-lang-en"
                            onclick="setLanguage('en')"
                            class="px-2.5 py-1 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 transition-all">
                        EN
                    </button>
                </div>

                <a href="{{ route('admin.dashboard') }}"
                   class="text-xs px-3 py-1.5 rounded-xl font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200 flex items-center gap-1.5">
                    <span>🔐</span>
                    <span class="hidden sm:inline">Admin Portal</span>
                </a>
            </div>
        </div>
    </header>

    {{-- Konten Utama: Card Verifikasi Zero-Login --}}
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-10">
        <div class="w-full max-w-md bg-white rounded-3xl border border-slate-200/90 shadow-[0_10px_35px_-10px_rgba(0,59,115,0.15)] p-6 sm:p-8 space-y-6">

            <div class="text-center space-y-2">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-2xl shadow-xs">
                    🛡️
                </div>
                <h1 id="txt-verify-title" class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">
                    Portal Layanan & Umpan Balik Klien
                </h1>
                <p id="txt-verify-subtitle" class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Masukkan nomor kontrak, kode proyek, atau token akses untuk memulai pengisian survei kepuasan atau pelaporan kendala.
                </p>
            </div>

            {{-- Formulir Verifikasi Zero-Login --}}
            <form id="verify-form" onsubmit="handleVerification(event)" class="space-y-4">
                <div>
                    <label id="lbl-contract-input" for="contract-input" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nomor Kontrak / Kode Proyek
                    </label>
                    <input type="text"
                           id="contract-input"
                           name="contract_number"
                           placeholder="Contoh: LRS/PROJ/2026-001"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/80 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#003B73] focus:ring-2 focus:ring-blue-100 outline-none transition-all"
                           autocomplete="off">
                    <p id="verify-error" class="text-xs text-rose-600 font-bold mt-1.5 hidden flex items-center gap-1">
                        <span>⚠️</span>
                        <span id="txt-verify-error-msg">Nomor kontrak atau kode proyek wajib diisi!</span>
                    </p>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            id="btn-access-portal"
                            class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-[#003B73] to-blue-700 hover:from-[#002D59] hover:to-blue-800 text-white font-bold text-sm shadow-md shadow-[#003B73]/25 hover:shadow-lg transition-all active:scale-[0.99] flex items-center justify-center gap-2">
                        <span id="txt-btn-access">Akses Portal Klien</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>

                <div id="txt-verify-footer-note" class="text-[11px] text-slate-400 text-center leading-relaxed pt-2 border-t border-slate-100">
                    Akses Zero-Login aman disimulasikan melalui browser lokal Anda untuk kenyamanan evaluasi tanpa hambatan kata sandi.
                </div>
            </form>

        </div>
    </main>

    {{-- Global Footer PT Len Railway Systems --}}
    <x-footer />

</div>

<script>
const VERIFY_LANG = {
    id: {
        badge: "Verifikasi Akses",
        title: "Portal Layanan & Umpan Balik Klien",
        subtitle: "Masukkan nomor kontrak, kode proyek, atau token akses untuk memulai pengisian survei kepuasan atau pelaporan kendala.",
        label: "Nomor Kontrak / Kode Proyek",
        placeholder: "Contoh: LRS/PROJ/2026-001",
        error: "Nomor kontrak atau kode proyek wajib diisi!",
        btn: "Akses Portal Klien",
        note: "Akses Zero-Login aman disimulasikan melalui browser lokal Anda untuk kenyamanan evaluasi tanpa hambatan kata sandi.",
        pageTitle: "Verifikasi Akses Klien · PT Len Railway Systems"
    },
    en: {
        badge: "Access Verification",
        title: "Client Service & Feedback Portal",
        subtitle: "Enter contract number, project code, or access token to begin satisfaction survey or submit a complaint.",
        label: "Contract Number / Project Code",
        placeholder: "e.g. LRS/PROJ/2026-001",
        error: "Contract number or project code is required!",
        btn: "Access Client Portal",
        note: "Zero-Login access is securely simulated via your local browser for passwordless convenience.",
        pageTitle: "Client Access Verification · PT Len Railway Systems"
    }
};

function setLanguage(lang) {
    const activeLang = lang === 'en' ? 'en' : 'id';
    try {
        localStorage.setItem('lrs_client_lang', activeLang);
    } catch (e) {}

    const btnId = document.getElementById('btn-lang-id');
    const btnEn = document.getElementById('btn-lang-en');
    if (btnId && btnEn) {
        if (activeLang === 'en') {
            btnEn.className = "px-2.5 py-1 rounded-lg text-xs font-black transition-all bg-[#003B73] text-white shadow-xs";
            btnId.className = "px-2.5 py-1 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 transition-all";
        } else {
            btnId.className = "px-2.5 py-1 rounded-lg text-xs font-black transition-all bg-[#003B73] text-white shadow-xs";
            btnEn.className = "px-2.5 py-1 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 transition-all";
        }
    }

    const t = VERIFY_LANG[activeLang];
    document.title = t.pageTitle;
    const elBadge = document.getElementById('txt-verify-badge');
    const elTitle = document.getElementById('txt-verify-title');
    const elSubtitle = document.getElementById('txt-verify-subtitle');
    const elLabel = document.getElementById('lbl-contract-input');
    const elInput = document.getElementById('contract-input');
    const elError = document.getElementById('txt-verify-error-msg');
    const elBtn = document.getElementById('txt-btn-access');
    const elNote = document.getElementById('txt-verify-footer-note');

    if (elBadge) elBadge.textContent = t.badge;
    if (elTitle) elTitle.textContent = t.title;
    if (elSubtitle) elSubtitle.textContent = t.subtitle;
    if (elLabel) elLabel.textContent = t.label;
    if (elInput) elInput.placeholder = t.placeholder;
    if (elError) elError.textContent = t.error;
    if (elBtn) elBtn.textContent = t.btn;
    if (elNote) elNote.textContent = t.note;
}

document.addEventListener('DOMContentLoaded', function () {
    let savedLang = 'id';
    try {
        savedLang = localStorage.getItem('lrs_client_lang') || 'id';
    } catch (e) {}
    setLanguage(savedLang);
});

function handleVerification(event) {
    event.preventDefault();
    const input = document.getElementById('contract-input');
    const error = document.getElementById('verify-error');
    const val = input.value.trim();

    if (!val) {
        error.classList.remove('hidden');
        input.classList.add('border-rose-400', 'bg-rose-50/40');
        input.focus();
        return;
    }

    error.classList.add('hidden');
    input.classList.remove('border-rose-400', 'bg-rose-50/40');

    // Simpan sesi aktif ke localStorage sesuai PRD
    try {
        const sessionData = {
            respondent_id: 'PIC-' + Math.floor(100 + Math.random() * 900),
            contract_number: val,
            project_name: val,
            verified_at: new Date().toISOString()
        };
        localStorage.setItem('lrs_active_session', JSON.stringify(sessionData));
    } catch (e) {
        console.error('Error saving session:', e);
    }

    // Arahkan langsung ke portal klien
    window.location.href = "{{ route('form') }}";
}
</script>
@endsection

