{{-- resources/views/clients/components/survey/confirmation-marque.blade.php --}}
<div class="relative w-full overflow-hidden rounded-xl border-slate-200/100 bg-[#F8FAFC] hover:border-[#252D87]/30 hover:bg-slate-50 transition-colors duration-200 group/ticker no-print">
    {{-- Badge Statis di Kiri (Opsional, menjaga identitas SMAP tetap terbaca saat teks berjalan) --}}
    <div class="hidden sm:flex items-center gap-1.5 absolute inset-y-0 left-0 z-20 bg-gradient-to-r from-[#F8FAFC] via-[#F8FAFC] to-transparent pl-3.5 pr-6 pointer-events-none">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase bg-[#252D87] text-white shadow-sm">
            <svg class="w-3 h-3 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
            </svg>
            SMAP
        </span>
    </div>

    {{-- Link Utama + Area Marquee --}}
    <a href="https://lrs.co.id/about-us/smap"
       id="smap-card-link"
       target="_blank"
       rel="noopener noreferrer"
       title="Pelajari Komitmen SMAP ISO 37001:2016 PT Len Railway Systems"
       class="block w-full py-2.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#252D87]"
       style="mask-image: linear-gradient(to right, transparent, black 6%, black 94%, transparent); -webkit-mask-image: linear-gradient(to right, transparent, black 6%, black 94%, transparent);">

        <div class="flex items-center whitespace-nowrap lrs-marquee-track group-hover/ticker:[animation-play-state:paused]">
            {{-- Loop Segmen 1 --}}
            <div class="flex items-center gap-5 shrink-0 text-xs text-slate-600 pr-5">
                <span id="smap-card-title" class="font-bold text-[#252D87]">
                    Sistem Manajemen Anti Penyuapan (SMAP) ISO 37001
                </span>
                <span class="w-1.5 h-1.5 rounded-full bg-[#ED1C24] shrink-0"></span>
                <span class="font-semibold text-slate-800">PT Len Railway Systems</span>
                <span class="text-slate-300">|</span>
                <span id="smap-card-desc">
                    PT Len Railway Systems berkomitmen menerapkan <strong class="font-semibold text-[#252D87]">Prinsip 4 NOs (No Bribery, No Kickback, No Gift, No Luxurious Hospitality)</strong> serta menjamin seluruh proses evaluasi dan kerja sama bebas dari segala bentuk gratifikasi dan benturan kepentingan.
                </span>
                <span class="inline-flex items-center gap-1 font-semibold text-[#252D87] group-hover/ticker:text-[#ED1C24] transition-colors">
                    <span id="smap-card-link-text">Pelajari Kebijakan SMAP</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                    </svg>
                </span>
                <span class="text-slate-300">|</span>
                <span class="text-[#252D87] font-semibold">https://lrs.co.id</span>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 shrink-0"></span>
            </div>

            {{-- Loop Segmen 2 (Duplikasi identik untuk Seamless Infinite Loop) --}}
            <div class="flex items-center gap-5 shrink-0 text-xs text-slate-600 pr-5" aria-hidden="true">
                <span class="font-bold text-[#252D87]">
                    Sistem Manajemen Anti Penyuapan (SMAP) ISO 37001
                </span>
                <span class="w-1.5 h-1.5 rounded-full bg-[#ED1C24] shrink-0"></span>
                <span class="font-semibold text-slate-800">PT Len Railway Systems</span>
                <span class="text-slate-300">|</span>
                <span>
                    PT Len Railway Systems berkomitmen menerapkan <strong class="font-semibold text-[#252D87]">Prinsip 4 NOs (No Bribery, No Kickback, No Gift, No Luxurious Hospitality)</strong> serta menjamin seluruh proses evaluasi dan kerja sama bebas dari segala bentuk gratifikasi dan benturan kepentingan.
                </span>
                <span class="inline-flex items-center gap-1 font-semibold text-[#252D87] group-hover/ticker:text-[#ED1C24] transition-colors">
                    <span>Pelajari Kebijakan SMAP</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                    </svg>
                </span>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 shrink-0"></span>
            </div>
        </div>
    </a>
</div>

<style>
@keyframes lrsMarqueeScroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.lrs-marquee-track {
    display: inline-flex;
    width: max-content;
    animation: lrsMarqueeScroll 38s linear infinite;
    will-change: transform;
}
@media (prefers-reduced-motion: reduce) {
    .lrs-marquee-track {
        animation: none;
    }
}
</style>