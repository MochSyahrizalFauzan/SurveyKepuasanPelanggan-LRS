{{-- Toast Notification --}}
<div id="toast" role="alert" aria-live="assertive">
    <div class="toast-icon" id="toast-icon">✅</div>
    <div>
        <div class="toast-title" id="toast-title">Berhasil</div>
        <div class="toast-desc" id="toast-desc">Data tersimpan di browser.</div>
    </div>
</div>

{{-- Success Overlay Modal (Survei) --}}
<div id="successOverlay" role="dialog" aria-modal="true" aria-label="Survei berhasil dikirim">
    <div class="success-card">
        <div class="success-emoji">🎉</div>
        <div class="success-title">Terima Kasih!</div>
        <p class="success-msg">
            Survei kepuasan Anda telah berhasil tercatat. Masukan Anda sangat berharga bagi evaluasi
            kinerja dan perbaikan berkelanjutan PT. Len Railways Systems.
        </p>

        <div style="background:#F1F5F9; border-radius:12px; padding:12px 14px; margin-bottom:20px; text-align:left;">
            <div style="font-size:11px; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">
                ID Responden
            </div>
            <div style="font-size:16px; font-weight:800; color:#0353A4;" id="success-id">-</div>
        </div>

        <button type="button" class="btn-success-close" onclick="closeSuccessOverlay()">
            Kembali ke Portal
        </button>
    </div>
</div>
