/**
 * PT. Len Railways Systems — Client Voice & GRC Portal
 * Script Interaktivitas & Simulasi LocalStorage (Fase Prototipe)
 */

(function () {
    'use strict';

    // State kuesioner survei
    // Format: { 'A1': { imp: 0, sat: 0, keterangan: '', text: '', category: '' }, ... }
    const surveiState = {};
    let totalInputsNeeded = 0;
    let toastTimer = null;

    /**
     * Inisialisasi State dari DOM yang di-render oleh Blade
     */
    function initSurveiState() {
        const cards = document.querySelectorAll('.q-card');
        totalInputsNeeded = cards.length * 2; // Setiap pertanyaan butuh 2 input (Kepentingan & Kepuasan)

        cards.forEach(card => {
            const id = card.dataset.id;
            const category = card.dataset.category || '';
            const text = card.dataset.text || '';

            surveiState[id] = {
                imp: 0,
                sat: 0,
                keterangan: '',
                category: category,
                text: text
            };
        });

        // Perbarui counter di header dari data localStorage sebelumnya
        updateHeaderCounters();
        updateProgress();
    }

    /**
     * Update counter ringkasan di header
     */
    function updateHeaderCounters() {
        try {
            const surveys = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');
            const complaints = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');

            const elSurvey = document.getElementById('hdr-survey-count');
            const elComplaint = document.getElementById('hdr-complaint-count');

            if (elSurvey) elSurvey.textContent = surveys.length;
            if (elComplaint) elComplaint.textContent = complaints.length;
        } catch (e) {
            console.error('Gagal membaca localStorage:', e);
        }
    }

    /**
     * Handler pemilihan skor Dual-Metric (Kepentingan / Kepuasan)
     */
    window.setScore = function (id, type, val) {
        if (!surveiState[id]) return;

        surveiState[id][type] = val;

        // Update tampilan tombol di baris terkait
        const container = document.getElementById(`${type}-${id}`);
        if (container) {
            const buttons = container.querySelectorAll('.score-btn');
            buttons.forEach(btn => {
                const btnVal = parseInt(btn.dataset.val, 10);
                btn.classList.remove('active-imp', 'active-sat');
                if (btnVal === val) {
                    btn.classList.add(type === 'imp' ? 'active-imp' : 'active-sat');
                }
            });
        }

        // Logika Conditional "Keterangan":
        // Muncul & wajib diisi HANYA jika Kepuasan <= 2 (Sangat Tidak Puas / Tidak Puas)
        if (type === 'sat') {
            const ketWrap = document.getElementById(`ket-wrap-${id}`);
            const ketInput = document.getElementById(`ket-${id}`);
            const card = document.getElementById(`card-${id}`);

            if (val <= 2) {
                if (ketWrap) ketWrap.classList.add('visible');
                if (card) card.classList.add('has-low-score');
                if (ketInput) {
                    setTimeout(() => ketInput.focus(), 150);
                }
            } else {
                if (ketWrap) ketWrap.classList.remove('visible');
                if (card) card.classList.remove('has-low-score');
                if (ketInput) ketInput.value = '';
                surveiState[id].keterangan = '';
            }
        }

        // Hapus highlight error jika indikator sudah terisi
        const card = document.getElementById(`card-${id}`);
        if (card) card.classList.remove('error');

        updateProgress();
    };

    /**
     * Update nilai keterangan saat user mengetik
     */
    window.updateKeterangan = function (id, value) {
        if (surveiState[id]) {
            surveiState[id].keterangan = value.trim();
        }
    };

    /**
     * Hitung & visualisasikan progress pengisian survei
     */
    function updateProgress() {
        let filled = 0;
        Object.values(surveiState).forEach(item => {
            if (item.imp > 0) filled++;
            if (item.sat > 0) filled++;
        });

        const percent = totalInputsNeeded > 0 ? Math.round((filled / totalInputsNeeded) * 100) : 0;

        const elBar = document.getElementById('progress-bar');
        const elText = document.getElementById('progress-text');
        const elHdrProgress = document.getElementById('hdr-progress');

        if (elBar) elBar.style.width = `${percent}%`;
        if (elText) elText.textContent = `${filled} / ${totalInputsNeeded} diisi`;
        if (elHdrProgress) elHdrProgress.textContent = `${percent}%`;
    }

    /**
     * Validasi kelengkapan form survei
     */
    function validateSurvei() {
        let isValid = true;
        let firstFailingId = null;

        // Reset semua error
        document.querySelectorAll('.q-card').forEach(c => c.classList.remove('error'));

        Object.entries(surveiState).forEach(([id, item]) => {
            let hasError = false;

            // 1. Kepentingan & Kepuasan harus terisi
            if (item.imp === 0 || item.sat === 0) {
                hasError = true;
            }

            // 2. Keterangan wajib diisi jika Kepuasan bernilai 1 atau 2
            if (item.sat > 0 && item.sat <= 2 && !item.keterangan) {
                hasError = true;
            }

            if (hasError) {
                const card = document.getElementById(`card-${id}`);
                if (card) card.classList.add('error');

                if (!firstFailingId) {
                    firstFailingId = id;
                }
                isValid = false;
            }
        });

        // Scroll halus ke kartu yang belum lengkap
        if (firstFailingId) {
            const firstCard = document.getElementById(`card-${firstFailingId}`);
            if (firstCard) {
                firstCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        return isValid;
    }

    /**
     * Inisialisasi event listener submit Form Survei
     */
    function setupSurveyForm() {
        const form = document.getElementById('form-survei');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (!validateSurvei()) {
                window.showToast('⚠️', 'Formulir Belum Lengkap', 'Mohon lengkapi semua penilaian dan keterangan wajib yang ditandai merah.', '#DC2626');
                return;
            }

            // Generate ID Responden
            const respondentId = 'PIC-' + Date.now().toString().slice(-6);

            // Kumpulkan jawaban menjadi array JSON terstruktur
            const answers = Object.entries(surveiState).map(([id, item]) => {
                const ans = {
                    question_id: id,
                    category: item.category,
                    pertanyaan: item.text,
                    kepentingan: item.imp,
                    kepuasan: item.sat
                };
                if (item.keterangan) {
                    ans.keterangan = item.keterangan;
                }
                return ans;
            });

            // Ambil saran umum jika ada
            const saranEl = document.getElementById('saran-umum');
            const saranUmum = saranEl ? saranEl.value.trim() : '';

            const surveyPayload = {
                respondent_id: respondentId,
                project_name: 'Proyek Prototipe LRS',
                submitted_at: new Date().toISOString(),
                saran_umum: saranUmum || null,
                answers: answers
            };

            // Simpan ke localStorage dengan key 'lrs_survey_data'
            try {
                const existing = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');
                existing.push(surveyPayload);
                localStorage.setItem('lrs_survey_data', JSON.stringify(existing));

                updateHeaderCounters();

                // Tampilkan Success Modal Overlay
                const successIdEl = document.getElementById('success-id');
                const overlay = document.getElementById('successOverlay');
                if (successIdEl) successIdEl.textContent = respondentId;
                if (overlay) overlay.classList.add('show');
            } catch (err) {
                console.error('Gagal menyimpan ke localStorage:', err);
                window.showToast('❌', 'Terjadi Kesalahan', 'Gagal menyimpan data ke browser storage.', '#DC2626');
            }
        });
    }

    /**
     * Tutup Success Overlay
     */
    window.closeSuccessOverlay = function () {
        const overlay = document.getElementById('successOverlay');
        if (overlay) overlay.classList.remove('show');
    };

    /**
     * Handler Rating Bintang di panel Masukan
     */
    window.setFeedbackStar = function (val) {
        const ratingInput = document.getElementById('feedback-rating');
        if (ratingInput) ratingInput.value = val;

        const starBtns = document.querySelectorAll('#star-row .star-btn');
        starBtns.forEach(btn => {
            const btnVal = parseInt(btn.dataset.val, 10);
            btn.classList.toggle('active', btnVal <= val);
        });
    };

    /**
     * Submit Form Beri Masukan
     */
    function setupFeedbackForm() {
        const form = document.getElementById('form-masukan');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const ratingInput = document.getElementById('feedback-rating');
            const rating = ratingInput ? parseInt(ratingInput.value, 10) : 0;
            const kategori = document.getElementById('f-kategori')?.value || '';
            const subjek = document.getElementById('f-subjek')?.value.trim() || '';
            const isi = document.getElementById('f-isi')?.value.trim() || '';

            if (rating === 0 || !kategori || !subjek || !isi) {
                window.showToast('⚠️', 'Data Belum Lengkap', 'Mohon lengkapi seluruh kolom formulir masukan.', '#DC2626');
                return;
            }

            const feedbackPayload = {
                id: 'FB-' + Date.now().toString().slice(-6),
                rating: rating,
                category: kategori,
                subject: subjek,
                content: isi,
                submitted_at: new Date().toISOString()
            };

            try {
                const existing = JSON.parse(localStorage.getItem('lrs_feedback_data') || '[]');
                existing.push(feedbackPayload);
                localStorage.setItem('lrs_feedback_data', JSON.stringify(existing));

                form.reset();
                window.setFeedbackStar(0);
                window.showToast('💬', 'Masukan Terkirim!', 'Terima kasih atas apresiasi dan masukan berharga Anda.', '#059669');
            } catch (err) {
                console.error(err);
            }
        });
    }

    /**
     * Submit Form Buat Keluhan
     */
    function setupComplaintForm() {
        const form = document.getElementById('form-keluhan');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const kategori = document.getElementById('k-kategori')?.value || '';
            const subjek = document.getElementById('k-subjek')?.value.trim() || '';
            const urgencyEl = document.querySelector('input[name="urgency"]:checked');
            const deskripsi = document.getElementById('k-deskripsi')?.value.trim() || '';

            if (!kategori || !subjek || !urgencyEl || !deskripsi) {
                window.showToast('⚠️', 'Data Belum Lengkap', 'Mohon lengkapi semua kolom keluhan sebelum mengirim.', '#DC2626');
                return;
            }

            // Buat ID Tiket acak format TKT-XXXX (4 digit)
            const ticketId = 'TKT-' + String(Math.floor(1000 + Math.random() * 9000));

            const complaintPayload = {
                ticket_id: ticketId,
                category: kategori,
                subject: subjek,
                urgency: urgencyEl.value,
                description: deskripsi,
                status: 'Open',
                created_at: new Date().toISOString()
            };

            try {
                const existing = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');
                existing.push(complaintPayload);
                localStorage.setItem('lrs_complaint_data', JSON.stringify(existing));

                updateHeaderCounters();

                // Tampilkan kartu hasil tiket
                const resultEl = document.getElementById('tiket-result');
                if (resultEl) {
                    resultEl.className = 'ticket-result status-open visible';
                    resultEl.innerHTML = `
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                            <span style="font-size:28px;">🎫</span>
                            <div>
                                <div style="font-size:11px;font-weight:600;color:#94A3B8;text-transform:uppercase;letter-spacing:0.5px;">ID Tiket Anda</div>
                                <div style="font-size:22px;font-weight:800;color:#003B73;letter-spacing:1px;">${ticketId}</div>
                            </div>
                        </div>
                        <p style="font-size:12.5px;color:#475569;line-height:1.6;">
                            Keluhan Anda berhasil dicatat dengan status <span class="status-badge badge-open">🔴 Open</span>.
                            Simpan nomor tiket ini untuk melacak tindak lanjut penyelesaian.
                        </p>
                    `;
                }

                form.reset();
                window.showToast('🎫', 'Keluhan Terkirim!', `ID Tiket Anda: ${ticketId}`, '#059669');
            } catch (err) {
                console.error(err);
            }
        });
    }

    /**
     * Pelacakan Tiket Keluhan
     */
    window.trackTicket = function () {
        const inputEl = document.getElementById('track-input');
        const resultEl = document.getElementById('track-result');
        if (!inputEl || !resultEl) return;

        const ticketCode = inputEl.value.trim().toUpperCase();

        if (!ticketCode) {
            window.showToast('⚠️', 'ID Tiket Kosong', 'Ketikkan ID tiket Anda terlebih dahulu (contoh: TKT-1234).', '#D97706');
            return;
        }

        const data = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');
        const tiket = data.find(t => t.ticket_id === ticketCode);

        if (!tiket) {
            resultEl.className = 'ticket-result status-open visible';
            resultEl.innerHTML = `
                <p style="font-size:13px;color:#991B1B;font-weight:600;">
                    ❌ ID Tiket <strong>${ticketCode}</strong> tidak ditemukan dalam catatan sistem.
                </p>
            `;
            return;
        }

        const statusMap = {
            'Open':        { cls: 'status-open',     badge: 'badge-open',     icon: '🔴', label: 'Open' },
            'In Progress': { cls: 'status-progress',  badge: 'badge-progress', icon: '🟡', label: 'In Progress' },
            'Resolved':    { cls: 'status-resolved',  badge: 'badge-resolved', icon: '🟢', label: 'Resolved' }
        };
        const st = statusMap[tiket.status] || statusMap['Open'];
        const tgl = new Date(tiket.created_at).toLocaleString('id-ID', {
            dateStyle: 'long', timeStyle: 'short'
        });

        resultEl.className = `ticket-result ${st.cls} visible`;
        resultEl.innerHTML = `
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;flex-wrap:wrap;gap:8px;">
                <div style="font-size:16px;font-weight:800;color:#003B73;">${tiket.ticket_id}</div>
                <span class="status-badge ${st.badge}">${st.icon} ${st.label}</span>
            </div>
            <div style="font-size:13.5px;font-weight:700;color:#0F172A;margin-bottom:4px;">${tiket.subject || '-'}</div>
            <div style="font-size:12.5px;color:#475569;margin-bottom:8px;line-height:1.5;">${tiket.description}</div>
            <div style="font-size:11px;color:#94A3B8;">📅 Dibuat: ${tgl} · 🏷️ ${tiket.category} · ⚡ ${tiket.urgency}</div>
        `;
    };

    /**
     * Perpindahan Navigasi Tab (Mendukung Desktop Sidebar & Mobile Top Nav)
     */
    window.switchTab = function (tabId, btn) {
        // Sembunyikan semua panel
        document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));

        // Hapus kelas active dari SEMUA tombol tab (desktop & mobile)
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active');
            b.setAttribute('aria-selected', 'false');
        });

        // Tampilkan panel yang dituju
        const targetPanel = document.getElementById(`panel-${tabId}`);
        if (targetPanel) {
            targetPanel.classList.add('active');
        }

        // Tandai aktif pada tombol terkait di kedua navigasi (desktop & mobile)
        document.querySelectorAll(`.tab-btn[data-tab="${tabId}"]`).forEach(b => {
            b.classList.add('active');
            b.setAttribute('aria-selected', 'true');
        });

        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    /**
     * Tampilkan Toast Notification
     */
    window.showToast = function (icon, title, desc, color) {
        const toast = document.getElementById('toast');
        const iconEl = document.getElementById('toast-icon');
        const titleEl = document.getElementById('toast-title');
        const descEl = document.getElementById('toast-desc');

        if (!toast || !iconEl || !titleEl || !descEl) return;

        iconEl.textContent = icon;
        titleEl.textContent = title;
        descEl.textContent = desc;
        toast.style.background = color || '#001D3D';

        toast.classList.add('show');

        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toast.classList.remove('show');
        }, 4000);
    };

    // Jalankan inisialisasi saat DOM siap
    document.addEventListener('DOMContentLoaded', function () {
        initSurveiState();
        setupSurveyForm();
        setupFeedbackForm();
        setupComplaintForm();
    });

})();
