@extends('layouts.app')

@section('title', 'Verifikasi Akses Klien · PT. Len Railways Systems')

@section('content')
<div class="min-h-screen bg-transparent flex flex-col antialiased text-slate-800 w-full flex-1 justify-between">

    {{-- Top Bar Sederhana --}}
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200/70 sticky top-0 z-40 shadow-xs">
        <div class="h-1 w-full bg-gradient-to-r from-[#003B73] via-sky-500 to-[#D32F2F]"></div>
        <div class="max-w-7xl mx-auto px-4 py-3 md:px-6 md:py-3.5 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="h-10 px-2.5 rounded-xl bg-white flex items-center justify-center shrink-0 border border-slate-200 shadow-xs">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="Logo PT. Len Railways Systems"
                         class="h-7 w-auto object-contain">
                </div>
                <div>
                    <span class="text-sm md:text-base font-black text-[#003B73] tracking-tight">PT. Len Railways Systems</span>
                    <span class="ml-2 px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-blue-50 text-[#003B73] border border-blue-100">Verifikasi Akses</span>
                </div>
            </div>

            <a href="{{ route('admin.dashboard') }}"
               class="text-xs px-3 py-1.5 rounded-xl font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200 flex items-center gap-1.5">
                <span>🔐</span>
                <span class="hidden sm:inline">Admin Portal</span>
            </a>
        </div>
    </header>

    {{-- Konten Utama: Card Verifikasi Zero-Login --}}
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-10">
        <div class="w-full max-w-md bg-white rounded-3xl border border-slate-200/90 shadow-[0_10px_35px_-10px_rgba(0,59,115,0.15)] p-6 sm:p-8 space-y-6">

            <div class="text-center space-y-2">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-2xl shadow-xs">
                    🛡️
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">
                    Portal Layanan & Umpan Balik Klien
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Masukkan nomor kontrak, kode proyek, atau token akses untuk memulai pengisian survei kepuasan atau pelaporan kendala.
                </p>
            </div>

            {{-- Formulir Verifikasi Zero-Login --}}
            <form id="verify-form" onsubmit="handleVerification(event)" class="space-y-4">
                <div>
                    <label for="contract-input" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
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
                        <span>Nomor kontrak atau kode proyek wajib diisi!</span>
                    </p>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            id="btn-access-portal"
                            class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-[#003B73] to-blue-700 hover:from-[#002D59] hover:to-blue-800 text-white font-bold text-sm shadow-md shadow-[#003B73]/25 hover:shadow-lg transition-all active:scale-[0.99] flex items-center justify-center gap-2">
                        <span>Akses Portal Klien</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>

                <div class="text-[11px] text-slate-400 text-center leading-relaxed pt-2 border-t border-slate-100">
                    Akses Zero-Login aman disimulasikan melalui browser lokal Anda untuk kenyamanan evaluasi tanpa hambatan kata sandi.
                </div>
            </form>

        </div>
    </main>

    {{-- Global Footer PT Len Railway Systems --}}
    <x-footer />

</div>

<script>
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
