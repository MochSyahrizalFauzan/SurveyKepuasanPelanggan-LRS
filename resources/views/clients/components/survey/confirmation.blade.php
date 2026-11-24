{{-- =========================================================================
     KOMPONEN BLADE: MODULAR SURVEY CONFIRMATION ("TERIMA KASIH")
     PT Len Railway Systems — Enterprise B2B CSI Confirmation Screen
     Event-Driven: Mendengarkan custom event 'lrs:survey-submitted'
     ========================================================================= --}}
<div id="survey-success-container" class="hidden min-h-[calc(100dvh-5.5rem)] flex flex-col justify-center px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <div class="my-auto w-full max-w-4xl mx-auto bg-white rounded-3xl border border-slate-200/90 shadow-[0_8px_30px_rgba(15,23,42,0.06)] overflow-hidden transition-all duration-300 relative">

        {{-- Confetti Canvas Overlay (Khusus Skor 5) --}}
        <canvas id="confirmation-confetti-canvas" class="absolute inset-0 pointer-events-none z-30 w-full h-full hidden" aria-hidden="true"></canvas>

        {{-- Garis aksen LRS di tepi paling atas kartu --}}
        <div class="h-1.5 w-full bg-gradient-to-r from-[#1a336e] via-[#2563eb] to-[#dc2626]" aria-hidden="true"></div>

        <div class="p-5 sm:p-7 lg:p-9 text-center space-y-5 sm:space-y-6 relative z-10">

            {{-- Baris Status Atas: Audio Chime & Status Badge --}}
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <span id="txt-success-badge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-emerald-100/70 text-emerald-800 border border-emerald-200 shadow-2xs">
                    ✓ Survei Berhasil Terkirim
                </span>

                {{-- Komponen Audio Chime --}}
                <x-survey.confirmation-audio />
            </div>

            {{-- Icon Sukses Checkmark Lembut & Indikator Skor --}}
            <div id="hero-icon-container" class="w-14 h-14 sm:w-16 sm:h-16 mx-auto rounded-full bg-emerald-50 ring-8 ring-emerald-50/60 text-emerald-600 flex items-center justify-center shadow-xs transition-transform duration-500 hover:scale-105">
                <svg id="hero-icon-svg" class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>

            {{-- Pesan Terima Kasih & Hero Dinamis --}}
            <div class="space-y-2">
                <h3 id="txt-success-title" class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Terima Kasih!
                </h3>

                {{-- Hero Dinamis Berdasarkan Skor --}}
                <div id="hero-score-badge-wrap" class="pt-1">
                    <div id="hero-score-badge" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200/90 shadow-2xs">
                        <span id="hero-score-stars">⭐️⭐️⭐️⭐️⭐️</span>
                        <span id="hero-score-text">Skor Kepuasan: 5.0 / 5.0 · Luar Biasa</span>
                    </div>
                </div>

                <p id="txt-success-msg" class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto leading-relaxed pt-1">
                    Penilaian Anda untuk kategori <strong id="success-category-name" class="text-slate-800 font-bold">Layanan & Dukungan</strong> telah berhasil disimpan dalam sistem kami.
                </p>
            </div>

            {{-- Grid 2 Kolom Berdampingan di Desktop (lg:grid-cols-12) / 1 Kolom di Mobile & Tablet --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-5 items-stretch text-left">
                {{-- Kolom Kiri: Digital Confirmation Receipt --}}
                <div class="lg:col-span-6 flex flex-col">
                    <x-survey.confirmation-receipt />
                </div>

                {{-- Kolom Kanan: Kartu Informasi SMAP ISO 37001 & Ticker --}}
                <div class="lg:col-span-6 flex flex-col justify-between">
                    <x-survey.confirmation-smap-ticker />
                </div>
            </div>

            {{-- 3 Tombol Aksi Hierarki Visual (Primary, Secondary, Tertiary) --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-2.5 sm:gap-3 pt-2">
                {{-- Primary: Solid Corporate Navy (#1a336e) --}}
                <button type="button"
                        id="btn-fill-other-category"
                        onclick="actionFillOtherCategory()"
                        class="w-full sm:w-auto min-h-[42px] sm:min-h-[44px] px-5 py-2.5 sm:py-3 rounded-xl bg-[#1a336e] hover:bg-[#12244f] text-white font-extrabold text-xs sm:text-sm shadow-md shadow-[#1a336e]/20 flex items-center justify-center gap-2 transition-all active:scale-95 focus:outline-none cursor-pointer">
                    <span id="txt-btn-fill-other">Isi Kategori Lain</span>
                </button>

                {{-- Tertiary: Ghost / Slate Halus --}}
                <button type="button"
                        id="btn-back-to-home"
                        onclick="actionBackToHome()"
                        class="w-full sm:w-auto min-h-[42px] sm:min-h-[44px] px-5 py-2.5 sm:py-3 rounded-xl bg-slate-100 hover:bg-slate-200/80 text-slate-600 hover:text-slate-800 font-extrabold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 active:scale-95 focus:outline-none cursor-pointer">
                    <span class="text-sm">🏠</span>
                    <span id="txt-btn-back-home">Kembali ke Beranda</span>
                </button>
            </div>
        </div>
        
        {{-- Modern CSS Marquee Ticker (Pure CSS) --}}
        <x-survey.confirmation-marque />
    </div>
</div>

{{-- Controller Khusus Konfirmasi (Event-Driven & Confetti) --}}
<script>
(function() {
    /**
     * Engine Confetti Ringan Vanilla JS (Tanpa Dependensi Eksternal)
     * Menggunakan palet korporat PT Len Railway Systems
     */
    function launchConfettiScore5() {
        const canvas = document.getElementById('confirmation-confetti-canvas');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        if (!ctx) return;

        canvas.classList.remove('hidden');
        canvas.width = canvas.parentElement.offsetWidth || 800;
        canvas.height = canvas.parentElement.offsetHeight || 600;

        const colors = ['#1a336e', '#2563eb', '#38bdf8', '#dc2626', '#f59e0b', '#10b981'];
        const particles = [];
        const particleCount = 65;

        for (let i = 0; i < particleCount; i++) {
            particles.push({
                x: canvas.width / 2 + (Math.random() - 0.5) * 120,
                y: 60 + Math.random() * 40,
                size: Math.random() * 7 + 4,
                color: colors[Math.floor(Math.random() * colors.length)],
                vx: (Math.random() - 0.5) * 8,
                vy: (Math.random() * -6) - 2,
                rotation: Math.random() * 360,
                rotationSpeed: (Math.random() - 0.5) * 8,
                gravity: 0.18,
                opacity: 1
            });
        }

        let animationId;
        const startTime = Date.now();
        const duration = 2800; // 2.8 detik

        function animate() {
            const elapsed = Date.now() - startTime;
            if (elapsed > duration) {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                canvas.classList.add('hidden');
                cancelAnimationFrame(animationId);
                return;
            }

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            particles.forEach((p) => {
                p.x += p.vx;
                p.y += p.vy;
                p.vy += p.gravity;
                p.rotation += p.rotationSpeed;
                p.opacity = Math.max(0, 1 - (elapsed / duration));

                ctx.save();
                ctx.globalAlpha = p.opacity;
                ctx.translate(p.x, p.y);
                ctx.rotate((p.rotation * Math.PI) / 180);
                ctx.fillStyle = p.color;
                ctx.fillRect(-p.size / 2, -p.size / 2, p.size, p.size * 0.6);
                ctx.restore();
            });

            animationId = requestAnimationFrame(animate);
        }

        animate();
    }

    /**
     * Perbarui Tampilan Hero Dinamis Berdasarkan Skor Kepuasan
     */
    function updateHeroByScore(score) {
        const badge = document.getElementById('hero-score-badge');
        const stars = document.getElementById('hero-score-stars');
        const text = document.getElementById('hero-score-text');
        const iconContainer = document.getElementById('hero-icon-container');
        const isEnglish = (typeof currentLang !== 'undefined' && currentLang === 'en');

        const numScore = parseFloat(score) || 5;

        if (numScore >= 4.5) {
            // Tier 1: Outstanding (5.0)
            if (badge) {
                badge.className = 'inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200/90 shadow-2xs';
            }
            if (stars) stars.textContent = '⭐️⭐️⭐️⭐️⭐️';
            if (text) text.textContent = isEnglish ? `Score: ${numScore.toFixed(1)} / 5.0 · Outstanding!` : `Skor Kepuasan: ${numScore.toFixed(1)} / 5.0 · Luar Biasa!`;
            if (iconContainer) {
                iconContainer.className = 'w-14 h-14 sm:w-16 sm:h-16 mx-auto rounded-full bg-emerald-50 ring-8 ring-emerald-50/60 text-emerald-600 flex items-center justify-center shadow-xs transition-transform duration-500 hover:scale-105';
            }
            // Trigger Confetti khusus untuk skor maksimal!
            setTimeout(launchConfettiScore5, 300);
        } else if (numScore >= 3.5) {
            // Tier 2: Good (4.0)
            if (badge) {
                badge.className = 'inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-blue-50 text-blue-800 border border-blue-200/90 shadow-2xs';
            }
            if (stars) stars.textContent = '⭐️⭐️⭐️⭐️';
            if (text) text.textContent = isEnglish ? `Score: ${numScore.toFixed(1)} / 5.0 · Very Good` : `Skor Kepuasan: ${numScore.toFixed(1)} / 5.0 · Sangat Baik`;
            if (iconContainer) {
                iconContainer.className = 'w-14 h-14 sm:w-16 sm:h-16 mx-auto rounded-full bg-blue-50 ring-8 ring-blue-50/60 text-blue-600 flex items-center justify-center shadow-xs transition-transform duration-500 hover:scale-105';
            }
        } else if (numScore >= 2.5) {
            // Tier 3: Neutral (3.0)
            if (badge) {
                badge.className = 'inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-sky-50 text-sky-800 border border-sky-200/90 shadow-2xs';
            }
            if (stars) stars.textContent = '⭐️⭐️⭐️';
            if (text) text.textContent = isEnglish ? `Score: ${numScore.toFixed(1)} / 5.0 · Neutral` : `Skor Kepuasan: ${numScore.toFixed(1)} / 5.0 · Cukup / Netral`;
            if (iconContainer) {
                iconContainer.className = 'w-14 h-14 sm:w-16 sm:h-16 mx-auto rounded-full bg-sky-50 ring-8 ring-sky-50/60 text-sky-600 flex items-center justify-center shadow-xs transition-transform duration-500 hover:scale-105';
            }
        } else {
            // Tier 4: Needs Improvement (1.0 - 2.0)
            if (badge) {
                badge.className = 'inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-amber-50 text-amber-800 border border-amber-200/90 shadow-2xs';
            }
            if (stars) stars.textContent = '⭐️⭐️';
            if (text) text.textContent = isEnglish ? `Score: ${numScore.toFixed(1)} / 5.0 · Under Review` : `Skor Kepuasan: ${numScore.toFixed(1)} / 5.0 · Perlu Evaluasi`;
            if (iconContainer) {
                iconContainer.className = 'w-14 h-14 sm:w-16 sm:h-16 mx-auto rounded-full bg-amber-50 ring-8 ring-amber-50/60 text-amber-600 flex items-center justify-center shadow-xs transition-transform duration-500 hover:scale-105';
            }
        }
    }

    /**
     * Handler Utama: Event 'lrs:survey-submitted'
     */
    window.addEventListener('lrs:survey-submitted', function(event) {
        const detail = event.detail || {};
        const step1 = document.getElementById('survey-step-1-category');
        const step2 = document.getElementById('survey-step-2-form');
        const successContainer = document.getElementById('survey-success-container');

        // Alihkan tampilan ke langkah 3
        if (step1) step1.classList.add('hidden');
        if (step2) step2.classList.add('hidden');
        if (successContainer) successContainer.classList.remove('hidden');

        // Isi data referensi & payload
        const elRefNo = document.getElementById('success-reference-no');
        const elRespId = document.getElementById('success-respondent-id');
        const elProjName = document.getElementById('success-project-name');
        const elCatName = document.getElementById('success-category-name');
        const elCatSummary = document.getElementById('success-category-summary');
        const elAttach = document.getElementById('success-attachment-name');
        const elTime = document.getElementById('success-submitted-time');
        const txtCopy = document.getElementById('txt-copy-ref');

        if (elRefNo) elRefNo.textContent = detail.referenceNumber || '-';
        if (elRespId) elRespId.textContent = detail.respondentName || 'PIC';
        if (elProjName) elProjName.textContent = detail.organization || '-';
        if (elCatName) elCatName.textContent = detail.serviceCategory || 'Layanan & Dukungan';
        if (elCatSummary) elCatSummary.textContent = detail.serviceCategory || '-';

        if (elAttach) {
            const noAttach = (typeof currentLang !== 'undefined' && currentLang === 'en') ? 'No attachment' : 'Tidak ada lampiran';
            elAttach.textContent = detail.attachmentName || noAttach;
        }

        if (elTime) {
            const dateObj = detail.submittedAt ? new Date(detail.submittedAt) : new Date();
            const isEn = (typeof currentLang !== 'undefined' && currentLang === 'en');
            elTime.textContent = dateObj.toLocaleTimeString((isEn ? 'en-US' : 'id-ID'), {
                hour: '2-digit', minute: '2-digit'
            }) + (isEn ? ' (Local)' : ' WIB');
        }

        if (txtCopy) {
            txtCopy.textContent = (typeof currentLang !== 'undefined' && currentLang === 'en') ? 'Copy' : 'Salin';
            txtCopy.classList.remove('text-emerald-600');
        }

        // Perbarui Hero Dinamis Berdasarkan Skor
        updateHeroByScore(detail.score || 5);

        // Putar Corporate Chime
        if (window.lrsPlayConfirmationChime) {
            window.lrsPlayConfirmationChime(false);
        }

        // Gulir halus ke puncak layar
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
})();
</script>
