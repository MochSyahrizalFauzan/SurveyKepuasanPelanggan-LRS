{{-- =========================================================================
     KOMPONEN BLADE: CORPORATE AUDIO CHIME & EQUALIZER VISUALIZER
     PT Len Railway Systems — Web Audio API Synthesizer (C5 -> G5)
     ========================================================================= --}}
<div id="confirmation-audio-widget" class="inline-flex items-center gap-2.5 px-3 py-1.5 rounded-full bg-slate-100/90 border border-slate-200/80 shadow-2xs text-xs text-slate-600 transition-all select-none">

    {{-- Equalizer 4-Bar Visualizer --}}
    <div class="flex items-end gap-1 h-4 w-5 px-0.5 justify-center" aria-hidden="true">
        <span id="eq-bar-1" class="eq-bar w-0.5 h-1.5 bg-slate-400 rounded-full transition-all duration-150"></span>
        <span id="eq-bar-2" class="eq-bar w-0.5 h-3 bg-slate-400 rounded-full transition-all duration-150"></span>
        <span id="eq-bar-3" class="eq-bar w-0.5 h-2 bg-slate-400 rounded-full transition-all duration-150"></span>
        <span id="eq-bar-4" class="eq-bar w-0.5 h-3.5 bg-slate-400 rounded-full transition-all duration-150"></span>
    </div>

    {{-- Label Audio Status --}}
    <span id="lbl-audio-status" class="text-[11px] font-bold text-slate-500">
        Chime Konfirmasi
    </span>

    {{-- Tombol Putar Ulang --}}
    <button type="button"
            id="btn-replay-chime"
            onclick="window.lrsPlayConfirmationChime(true)"
            class="p-1 rounded-full text-slate-500 hover:text-[#1a336e] hover:bg-white transition-all active:scale-90 focus:outline-none cursor-pointer"
            title="Putar Ulang Nada Konfirmasi"
            aria-label="Putar Ulang Nada Konfirmasi">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
        </svg>
    </button>

    {{-- Tombol Mute / Unmute --}}
    <button type="button"
            id="btn-mute-chime"
            onclick="window.lrsToggleChimeMute()"
            class="p-1 rounded-full text-slate-500 hover:text-slate-800 hover:bg-white transition-all active:scale-90 focus:outline-none cursor-pointer"
            title="Bisukan Audio"
            aria-label="Bisukan Audio">
        {{-- Icon Speaker Aktif --}}
        <svg id="icon-sound-on" class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.757 3.63 8.25 4.51 8.25H6.75z" />
        </svg>
        {{-- Icon Speaker Muted --}}
        <svg id="icon-sound-muted" class="w-3.5 h-3.5 text-slate-400 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 9.75L19.5 12m0 0l2.25 2.25M19.5 12l2.25-2.25M19.5 12l-2.25 2.25m-10.5-6l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.757 3.63 8.25 4.51 8.25H6.75z" />
        </svg>
    </button>

</div>

<script>
(function() {
    let audioCtx = null;
    let isMuted = localStorage.getItem('lrs_audio_muted') === 'true';

    function updateMuteUI() {
        const iconOn = document.getElementById('icon-sound-on');
        const iconMuted = document.getElementById('icon-sound-muted');
        const btnMute = document.getElementById('btn-mute-chime');
        if (iconOn && iconMuted) {
            if (isMuted) {
                iconOn.classList.add('hidden');
                iconMuted.classList.remove('hidden');
                if (btnMute) btnMute.title = 'Aktifkan Suara';
            } else {
                iconOn.classList.remove('hidden');
                iconMuted.classList.add('hidden');
                if (btnMute) btnMute.title = 'Bisukan Suara';
            }
        }
    }

    function animateVisualizer(active) {
        const bars = [
            document.getElementById('eq-bar-1'),
            document.getElementById('eq-bar-2'),
            document.getElementById('eq-bar-3'),
            document.getElementById('eq-bar-4')
        ];
        if (active) {
            bars.forEach((b, i) => {
                if (!b) return;
                b.classList.remove('bg-slate-400');
                b.classList.add('bg-blue-600');
                b.style.height = `${(i + 1) * 3 + 2}px`;
            });
        } else {
            bars.forEach((b, i) => {
                if (!b) return;
                b.classList.remove('bg-blue-600');
                b.classList.add('bg-slate-400');
                b.style.height = i % 2 === 0 ? '6px' : '12px';
            });
        }
    }

    window.lrsToggleChimeMute = function() {
        isMuted = !isMuted;
        localStorage.setItem('lrs_audio_muted', isMuted);
        updateMuteUI();
        if (!isMuted) {
            window.lrsPlayConfirmationChime(true);
        }
    };

    /**
     * Web Audio API Synthesizer: Two-Tone Chime Orisinal (C5 -> G5)
     * Tone 1: C5 (523.25 Hz)
     * Tone 2: G5 (783.99 Hz)
     */
    window.lrsPlayConfirmationChime = function(force = false) {
        if (isMuted && !force) return;

        try {
            const AudioContextClass = window.AudioContext || window.webkitAudioContext;
            if (!AudioContextClass) return;

            if (!audioCtx) {
                audioCtx = new AudioContextClass();
            }
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }

            const now = audioCtx.currentTime;
            animateVisualizer(true);

            // Nada 1: C5 (523.25 Hz)
            const osc1 = audioCtx.createOscillator();
            const gain1 = audioCtx.createGain();
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(523.25, now);
            gain1.gain.setValueAtTime(0.001, now);
            gain1.gain.exponentialRampToValueAtTime(0.22, now + 0.04);
            gain1.gain.exponentialRampToValueAtTime(0.0001, now + 0.55);
            osc1.connect(gain1);
            gain1.connect(audioCtx.destination);
            osc1.start(now);
            osc1.stop(now + 0.56);

            // Nada 2: G5 (783.99 Hz) Harmonik Sempurna
            const osc2 = audioCtx.createOscillator();
            const gain2 = audioCtx.createGain();
            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(783.99, now + 0.16);
            gain2.gain.setValueAtTime(0.0001, now + 0.16);
            gain2.gain.exponentialRampToValueAtTime(0.25, now + 0.20);
            gain2.gain.exponentialRampToValueAtTime(0.0001, now + 0.85);
            osc2.connect(gain2);
            gain2.connect(audioCtx.destination);
            osc2.start(now + 0.16);
            osc2.stop(now + 0.86);

            setTimeout(() => {
                animateVisualizer(false);
            }, 860);

        } catch (e) {
            console.debug('Web Audio API playback not allowed or unsupported:', e);
            animateVisualizer(false);
        }
    };

    document.addEventListener('DOMContentLoaded', updateMuteUI);
})();
</script>
