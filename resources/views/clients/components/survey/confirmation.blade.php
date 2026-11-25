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

            {{-- Icon Sukses Checkmark Lembut & Indikator Skor (Animasi Dinamis & Interaktif) --}}
            <div id="hero-icon-container"
                 onclick="replayCheckmarkAnimation()"
                 onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();replayCheckmarkAnimation();}"
                 role="button"
                 tabindex="0"
                 class="checkmark-pop group/check relative w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-emerald-50 ring-8 ring-emerald-50/80 hover:ring-12 hover:ring-emerald-100 text-emerald-600 flex items-center justify-center shadow-md shadow-emerald-500/10 cursor-pointer select-none transition-all duration-300 hover:scale-108 active:scale-95 focus:outline-none focus-visible:ring-4 focus-visible:ring-emerald-400"
                 title="Klik untuk memutar ulang animasi centang"
                 aria-label="Status Survei Berhasil Terkirim - Klik untuk memutar ulang animasi">

                {{-- Gelombang Ambien Halus (Pulsing Halo) --}}
                <span class="absolute inset-0 rounded-full bg-emerald-400/25 animate-ping opacity-60 pointer-events-none" style="animation-duration: 2.8s;"></span>

                {{-- SVG Checkmark Dinamis dengan Efek Stroke Draw --}}
                <svg id="hero-icon-svg"
                     class="w-8 h-8 sm:w-10 sm:h-10 transition-transform duration-300 group-hover/check:scale-110 group-hover/check:rotate-6"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2.75"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     viewBox="0 0 24 24"
                     aria-hidden="true">
                    <path id="hero-checkmark-path"
                          class="checkmark-stroke"
                          d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>

            {{-- Pesan Terima Kasih & Hero Dinamis --}}
            <div class="space-y-2">
                <h3 id="txt-success-title" class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Terima Kasih!
                </h3>
                <p id="txt-success-msg" class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto leading-relaxed pt-1">
                    Penilaian Anda untuk kategori <strong id="success-category-name" class="text-slate-800 font-bold">Layanan & Dukungan</strong> telah berhasil disimpan dalam sistem kami.
                </p>
            </div>

            {{-- Tanda Terima Digital --}}
            <div>
                <x-survey.confirmation-receipt />
            </div>

            {{-- Pintasan ke Pusat Keluhan Klien (/keluhan) --}}
            <div class="pt-1 flex justify-center">
                <a href="{{ route('client.complaint') }}"
                   id="btn-goto-complaint"
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-white hover:bg-rose-50 text-slate-700 hover:text-rose-700 border border-slate-200 hover:border-rose-200 font-extrabold text-xs sm:text-sm shadow-2xs transition-all active:scale-95 focus:outline-none">
                    <span>🎫</span>
                    <span id="txt-btn-goto-complaint">Sampaikan Keluhan</span>
                </a>
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
                iconContainer.className = 'checkmark-pop group/check relative w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-emerald-50 ring-8 ring-emerald-50/80 hover:ring-12 hover:ring-emerald-100 text-emerald-600 flex items-center justify-center shadow-md shadow-emerald-500/10 cursor-pointer select-none transition-all duration-300 hover:scale-108 active:scale-95 focus:outline-none focus-visible:ring-4 focus-visible:ring-emerald-400';
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
                iconContainer.className = 'checkmark-pop group/check relative w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-blue-50 ring-8 ring-blue-50/80 hover:ring-12 hover:ring-blue-100 text-blue-600 flex items-center justify-center shadow-md shadow-blue-500/10 cursor-pointer select-none transition-all duration-300 hover:scale-108 active:scale-95 focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-400';
            }
        } else if (numScore >= 2.5) {
            // Tier 3: Neutral (3.0)
            if (badge) {
                badge.className = 'inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-sky-50 text-sky-800 border border-sky-200/90 shadow-2xs';
            }
            if (stars) stars.textContent = '⭐️⭐️⭐️';
            if (text) text.textContent = isEnglish ? `Score: ${numScore.toFixed(1)} / 5.0 · Neutral` : `Skor Kepuasan: ${numScore.toFixed(1)} / 5.0 · Cukup / Netral`;
            if (iconContainer) {
                iconContainer.className = 'checkmark-pop group/check relative w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-sky-50 ring-8 ring-sky-50/80 hover:ring-12 hover:ring-sky-100 text-sky-600 flex items-center justify-center shadow-md shadow-sky-500/10 cursor-pointer select-none transition-all duration-300 hover:scale-108 active:scale-95 focus:outline-none focus-visible:ring-4 focus-visible:ring-sky-400';
            }
        } else {
            // Tier 4: Needs Improvement (1.0 - 2.0)
            if (badge) {
                badge.className = 'inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-amber-50 text-amber-800 border border-amber-200/90 shadow-2xs';
            }
            if (stars) stars.textContent = '⭐️⭐️';
            if (text) text.textContent = isEnglish ? `Score: ${numScore.toFixed(1)} / 5.0 · Under Review` : `Skor Kepuasan: ${numScore.toFixed(1)} / 5.0 · Perlu Evaluasi`;
            if (iconContainer) {
                iconContainer.className = 'checkmark-pop group/check relative w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-amber-50 ring-8 ring-amber-50/80 hover:ring-12 hover:ring-amber-100 text-amber-600 flex items-center justify-center shadow-md shadow-amber-500/10 cursor-pointer select-none transition-all duration-300 hover:scale-108 active:scale-95 focus:outline-none focus-visible:ring-4 focus-visible:ring-amber-400';
            }
        }
    }

    /**
     * Interaktivitas: Putar Ulang Animasi Centang saat Diklik atau Saat Form Disubmit
     */
    window.replayCheckmarkAnimation = function() {
        const path = document.getElementById('hero-checkmark-path');
        const container = document.getElementById('hero-icon-container');

        if (path) {
            path.style.animation = 'none';
            void path.offsetWidth; // Force reflow
            path.style.animation = 'lrsCheckmarkDraw 0.65s cubic-bezier(0.65, 0, 0.45, 1) forwards';
        }

        if (container) {
            container.classList.remove('checkmark-pop');
            void container.offsetWidth;
            container.classList.add('checkmark-pop');
        }
    };

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

        // Putar Animasi Centang saat Muncul
        window.replayCheckmarkAnimation();

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

<style>
/* Animasi Stroke Draw & Spring Pop Orisinal untuk Icon Centang (Clean & Modern) */
@keyframes lrsCheckmarkDraw {
    0% {
        stroke-dashoffset: 32;
        opacity: 0.2;
    }
    40% {
        opacity: 1;
    }
    100% {
        stroke-dashoffset: 0;
        opacity: 1;
    }
}

@keyframes lrsCheckmarkPop {
    0% {
        transform: scale(0.4);
        opacity: 0;
    }
    60% {
        transform: scale(1.14);
        opacity: 1;
    }
    85% {
        transform: scale(0.96);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.checkmark-stroke {
    stroke-dasharray: 32;
    stroke-dashoffset: 0;
    animation: lrsCheckmarkDraw 0.65s cubic-bezier(0.65, 0, 0.45, 1) forwards;
}

.checkmark-pop {
    animation: lrsCheckmarkPop 0.55s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}
</style>
