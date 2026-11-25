@php
    $smk3Exists = file_exists(public_path('images/certifications/smk3.png'));
    $bsiExists = file_exists(public_path('images/certifications/bsi-certifications.png'));
@endphp

<footer class="w-full text-white bg-gradient-to-b from-[#1a336e] to-[#11234d] relative mt-auto border-t border-white/10" role="contentinfo">

    {{-- 1. Garis Aksen Tri-Warna Tipis Khas PT Len Railway Systems (Biru - Merah) --}}
    <div class="h-1 w-full bg-gradient-to-r from-[#003B73] via-sky-400 to-[#D32F2F]"></div>

    {{-- Kontainer Utama Footer --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-10">

        {{-- 2. Grid 3 Kolom Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10 mb-10">

            {{-- =========================================================================
                 KOLOM 1: OUR ADDRESS
                 ========================================================================= --}}
            <div class="space-y-4">
                <div>
                    <h3 class="text-base font-bold text-white tracking-wider uppercase flex items-center gap-2">
                        Our Address
                    </h3>
                    <div class="h-0.5 w-12 bg-amber-400 mt-2 mb-3 rounded-full"></div>
                </div>

                <div class="text-sm font-bold text-white tracking-tight">
                    PT Len Railway Systems
                </div>

                <address class="not-italic text-xs sm:text-sm text-slate-300 space-y-3">
                    {{-- Alamat --}}
                    <div class="flex items-start gap-3">
                        <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="leading-relaxed">
                            Grha Giri Suseno (Ged. T) Len Business Park, Jl. Soekarno Hatta No. 442, Bandung 40254 – Indonesia
                        </span>
                    </div>

                    {{-- Telepon --}}
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <a href="tel:+62225231685"
                           class="hover:text-amber-300 transition-colors duration-200">
                            +62-22-5231685
                        </a>
                    </div>

                    {{-- Email --}}
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:info@lrs.co.id"
                           class="hover:text-amber-300 transition-colors duration-200">
                            info@lrs.co.id
                        </a>
                    </div>
                </address>
            </div>

            {{-- =========================================================================
                 KOLOM 2: SOCIAL MEDIA
                 ========================================================================= --}}
            <div class="space-y-4">
                <div>
                    <h3 class="text-base font-bold text-white tracking-wider uppercase">
                        Social Media
                    </h3>
                    <div class="h-0.5 w-12 bg-amber-400 mt-2 mb-3 rounded-full"></div>
                </div>

                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    Terhubung dengan kanal resmi kami untuk informasi inovasi dan keandalan sistem perkeretaapian nasional.
                </p>

                <nav aria-label="Social Media Links">
                    <ul class="flex items-center gap-3 pt-1">
                        {{-- TikTok --}}
                        <li>
                            <a href="https://www.tiktok.com/@lenrailwaysystems"
                               target="_blank"
                               rel="noopener noreferrer"
                               aria-label="TikTok PT Len Railway Systems"
                               class="w-10 h-10 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center text-white/90 hover:text-[#1a336e] hover:bg-white hover:-translate-y-0.5 shadow-sm transition-all duration-300 ease-in-out">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.298-.002.595.042.88.13V9.4a6.33 6.33 0 0 0-1-.08A6.34 6.34 0 0 0 3 15.66a6.34 6.34 0 0 0 10.83 4.46 6.3 6.3 0 0 0 1.87-4.47V8.62a8.27 8.27 0 0 0 4.89 1.58V6.75a4.85 4.85 0 0 1-1-.06Z"/>
                                </svg>
                            </a>
                        </li>

                        {{-- Instagram --}}
                        <li>
                            <a href="https://www.instagram.com/lenrailwaysystems"
                               target="_blank"
                               rel="noopener noreferrer"
                               aria-label="Instagram PT Len Railway Systems"
                               class="w-10 h-10 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center text-white/90 hover:text-[#1a336e] hover:bg-white hover:-translate-y-0.5 shadow-sm transition-all duration-300 ease-in-out">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                        </li>

                        {{-- YouTube --}}
                        <li>
                            <a href="https://www.youtube.com/@lenrailwaysystems"
                               target="_blank"
                               rel="noopener noreferrer"
                               aria-label="YouTube PT Len Railway Systems"
                               class="w-10 h-10 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center text-white/90 hover:text-[#1a336e] hover:bg-white hover:-translate-y-0.5 shadow-sm transition-all duration-300 ease-in-out">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            {{-- =========================================================================
                 KOLOM 3: WHISTLEBLOWING SYSTEM
                 ========================================================================= --}}
            <div class="rounded-2xl bg-white/[0.06] border border-white/10 p-5 sm:p-6 backdrop-blur-sm shadow-sm transition-all duration-300 hover:border-white/20">
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                    <h3 class="text-base font-bold text-white tracking-wide">
                        Whistleblowing System
                    </h3>
                </div>

                <p class="text-xs text-slate-300 leading-relaxed mb-3">
                    Saluran pengaduan independen untuk melaporkan dugaan pelanggaran integritas, etika bisnis, dan tata kelola secara aman dan rahasia.
                </p>

                <div class="flex items-center gap-2 mb-4 text-xs sm:text-sm text-slate-200">
                    <span class="text-amber-400 font-bold">Email:</span>
                    <a href="mailto:wbs@lrs.co.id"
                       class="font-semibold text-white hover:text-amber-300 underline underline-offset-2 transition-colors duration-200">
                        wbs@lrs.co.id
                    </a>
                </div>

                <a href="mailto:wbs@lrs.co.id?subject=Laporan%20Whistleblowing%20System%20PT%20Len%20Railway%20Systems"
                   class="group inline-flex items-center justify-between w-full px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 via-amber-600 to-orange-600 hover:from-amber-400 hover:to-orange-500 text-white font-bold text-xs sm:text-sm shadow-md shadow-amber-900/20 hover:shadow-lg transition-all duration-300 ease-in-out">
                    <span id="footer-wbs-btn-text">Laporkan Pelanggaran (Report)</span>
                    <svg class="w-4 h-4 transition-transform duration-300 ease-in-out group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

        </div>

        {{-- 3. Baris Sertifikasi Perusahaan --}}
        <div class="border-t border-white/10 pt-6 pb-2">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs font-semibold text-slate-300 uppercase tracking-wider text-center sm:text-left">
                    Standar Mutu & Keselamatan Terakreditasi
                </div>

                <div class="flex flex-wrap items-center justify-center sm:justify-end gap-3 sm:gap-4">
                    {{-- Logo Sertifikasi SMK3 --}}
                    @if ($smk3Exists)
                        <img src="{{ asset('images/certifications/smk3.png') }}"
                             alt="Sertifikasi SMK3"
                             loading="lazy"
                             class="h-20 sm:h-20 w-auto object-contain filter brightness-100 hover:brightness-110 transition-all duration-300"
                             onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                    @endif
                    {{-- Logo Sertifikasi BSI --}}
                    @if ($bsiExists)
                        <img src="{{ asset('images/certifications/bsi-certifications.png') }}"
                             alt="Sertifikasi BSI"
                             loading="lazy"
                             class="h-20 sm:h-20 w-auto object-contain filter brightness-100 hover:brightness-110 transition-all duration-300"
                             onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- 4. Bottom Copyright Bar --}}
    <div class="bg-[#0c1a3a] border-t border-white/10 py-4 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
            <p class="text-center sm:text-left">
                Copyright &copy; {{ date('Y') }} PT Len Railway Systems. All Rights Reserved.
            </p>
            <nav aria-label="Footer Legal Links">
                <a href="#"
                   class="hover:text-white transition-colors duration-200">
                    Privacy Policy
                </a>
            </nav>
        </div>
    </div>

</footer>
