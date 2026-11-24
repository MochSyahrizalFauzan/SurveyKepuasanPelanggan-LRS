{{-- =========================================================================
     KOMPONEN BLADE: DIGITAL CONFIRMATION RECEIPT
     PT Len Railway Systems — Bukti Konfirmasi Resmi & Detail Evaluasi
     ========================================================================= --}}
<div id="receipt-card-container"
     class="receipt-card bg-slate-50/95 rounded-2xl border border-slate-200/90 p-4 sm:p-5 flex flex-col justify-between text-xs space-y-4 shadow-2xs transition-transform duration-200 ease-out will-change-transform select-text">

    {{-- Header Receipt: Identitas & Waktu Submit --}}
    <div class="font-extrabold text-[#1a336e] uppercase tracking-wider text-[11px] pb-2.5 border-b border-slate-200/80 flex items-center justify-between">
        <span id="txt-summary-title" class="flex items-center gap-1.5">
            <span>🧾</span>
            <span>Tanda Terima Digital Resmi</span>
        </span>
        <span class="text-slate-400 font-semibold text-[11px]" id="success-submitted-time">-</span>
    </div>

    {{-- Pill Nomor Referensi dengan Tombol Salin --}}
    <div class="bg-white rounded-xl border border-slate-200/90 p-2.5 sm:p-3 flex flex-wrap items-center justify-between gap-2 shadow-2xs">
        <div class="min-w-0">
            <span id="txt-ref-label" class="text-[10px] sm:text-[11px] font-bold text-slate-400 block uppercase tracking-wider">
                Nomor Referensi:
            </span>
            <span id="success-reference-no" class="text-[#1a336e] font-black text-xs sm:text-sm tracking-wider select-all block truncate">
                -
            </span>
        </div>

        <button type="button"
                id="btn-copy-ref"
                onclick="copyReferenceNo()"
                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-extrabold bg-slate-50 hover:bg-slate-100 text-slate-700 hover:text-[#1a336e] border border-slate-200 shadow-2xs hover:shadow-xs transition-all active:scale-95 focus:outline-none cursor-pointer shrink-0"
                title="Salin Nomor Referensi"
                aria-label="Salin Nomor Referensi">
            <svg id="icon-copy-ref" class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
            </svg>
            <span id="txt-copy-ref">Salin</span>
        </button>
    </div>

    {{-- Detail Data Responden & Proyek (Semantic dl/dt/dd) --}}
    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-slate-600 flex-1">
        <div class="space-y-0.5 min-w-0 bg-white/70 p-2.5 rounded-xl border border-slate-200/60">
            <dt id="lbl-resp-id" class="text-slate-400 font-semibold block text-[10px] uppercase tracking-wider">ID RESPONDEN</dt>
            <dd id="success-respondent-id" class="font-bold text-slate-800 text-xs sm:text-sm truncate">-</dd>
        </div>
        <div class="space-y-0.5 min-w-0 bg-white/70 p-2.5 rounded-xl border border-slate-200/60">
            <dt id="lbl-proj-name" class="text-slate-400 font-semibold block text-[10px] uppercase tracking-wider">PROYEK</dt>
            <dd id="success-project-name" class="font-bold text-slate-800 text-xs sm:text-sm truncate">-</dd>
        </div>
        <div class="space-y-0.5 min-w-0 bg-white/70 p-2.5 rounded-xl border border-slate-200/60">
            <dt id="lbl-cat-summary" class="text-slate-400 font-semibold block text-[10px] uppercase tracking-wider">KATEGORI</dt>
            <dd id="success-category-summary" class="font-bold text-slate-800 text-xs sm:text-sm truncate">-</dd>
        </div>
        <div class="space-y-0.5 min-w-0 bg-white/70 p-2.5 rounded-xl border border-slate-200/60">
            <dt id="lbl-attach-name" class="text-slate-400 font-semibold block text-[10px] uppercase tracking-wider">FILE LAMPIRAN</dt>
            <dd id="success-attachment-name" class="font-bold text-slate-800 text-xs sm:text-sm truncate">-</dd>
        </div>
    </dl>

    {{-- Aksi Tambahan Receipt: Simpan PDF & Unduh JSON --}}
    <div class="pt-2 border-t border-slate-200/80 flex items-center justify-between gap-2">
        <button type="button"
                id="btn-print-receipt"
                onclick="window.print()"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold text-slate-600 hover:text-[#1a336e] bg-white hover:bg-slate-100 border border-slate-200 transition-all shadow-2xs active:scale-95 cursor-pointer">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.07-.367-2.18-.367-3.329 0-4.032 2.766-7.42 6.54-8.243.344-.075.698-.117 1.06-.117s.716.042 1.06.117c3.774.823 6.54 4.211 6.54 8.243 0 1.149-.127 2.259-.367 3.329m-14.466 0A9.02 9.02 0 0012 21a9.02 9.02 0 007.746-7.171m-15.492 0a9.046 9.046 0 010-3.329m15.492 3.329a9.046 9.046 0 000-3.329" />
            </svg>
            <span>Simpan PDF / Cetak</span>
        </button>

        <button type="button"
                id="btn-download-json"
                onclick="window.downloadSurveyReceiptJSON()"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold text-slate-600 hover:text-[#1a336e] bg-white hover:bg-slate-100 border border-slate-200 transition-all shadow-2xs active:scale-95 cursor-pointer">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            <span>Unduh JSON</span>
        </button>
    </div>

</div>

{{-- Subtle 3D Tilt Script (Desktop Only) --}}
<script>
(function() {
    const card = document.getElementById('receipt-card-container');
    if (!card) return;

    // Aktifkan hanya pada pointer fine (desktop)
    if (window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
        let isHovering = false;

        card.addEventListener('mouseenter', () => {
            isHovering = true;
            card.style.transition = 'transform 0.1s ease-out';
        });

        card.addEventListener('mousemove', (e) => {
            if (!isHovering) return;
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            // Efek subtle rotasi maks 4 derajat
            const rotateX = ((centerY - y) / centerY) * 4;
            const rotateY = ((x - centerX) / centerX) * 4;

            card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) scale3d(1.008, 1.008, 1.008)`;
        });

        card.addEventListener('mouseleave', () => {
            isHovering = false;
            card.style.transition = 'transform 0.4s ease-out';
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
        });
    }

    /**
     * Unduh Payload Tanda Terima dalam Format JSON
     */
    window.downloadSurveyReceiptJSON = function() {
        const refNo = document.getElementById('success-reference-no')?.textContent.trim() || 'LRS-SURVEY';
        const respId = document.getElementById('success-respondent-id')?.textContent.trim() || '';
        const proj = document.getElementById('success-project-name')?.textContent.trim() || '';
        const cat = document.getElementById('success-category-summary')?.textContent.trim() || '';
        const time = document.getElementById('success-submitted-time')?.textContent.trim() || new Date().toISOString();

        const receiptData = {
            company: 'PT Len Railway Systems',
            document: 'Survey Satisfaction Receipt (FRM-QOP-06-02)',
            reference_number: refNo,
            respondent_id: respId,
            project_name: proj,
            category: cat,
            submitted_at: time,
            integrity_system: 'SMAP ISO 37001:2016 Compliant'
        };

        const blob = new Blob([JSON.stringify(receiptData, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Receipt-${refNo}.json`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);

        if (window.showToast) {
            window.showToast('📥', 'Unduh Berhasil', `File JSON tanda terima ${refNo} telah diunduh.`);
        }
    };
})();
</script>
