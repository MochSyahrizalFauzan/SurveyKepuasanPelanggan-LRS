/**
 * PT. Len Railways Systems — Client Voice & GRC Portal
 * Script Interaktivitas Formulir Survei Progresif (2-Langkah) & LocalStorage
 *
 * Mengacu pada PRD: Integrated Client Voice & GRC Portal PT LRS
 * 1. Alur 2 Langkah (Progressive Disclosure): Pilih Kategori -> Isi Indikator Kategori Terpilih
 * 2. Penilaian Ganda (Dual-Metric): Kepentingan (1-5) & Kepuasan (1-5) tanpa kolom catatan tambahan
 * 3. Upload File (Dummy) & Kirim Survei ke localStorage (lrs_survey_data)
 * 4. Tampilan Sukses ("Terima Kasih") dengan tombol "Isi Kategori Lain" dan "Kembali ke Beranda"
 */

(function () {
    'use strict';

    // Metadata Kategori Sesuai PRD.md (3 Kategori, 21 Indikator Total)
    const CATEGORY_MAP = {
        'A': {
            code: 'A',
            name: 'Layanan & Dukungan',
            total: 5,
            desc: 'Evaluasi keramahan, komunikasi, pemahaman kebutuhan pelanggan, kualitas solusi, dan kejelasan teknis personel PT LRS.'
        },
        'B': {
            code: 'B',
            name: 'Pelaksanaan Proyek',
            total: 11,
            desc: 'Evaluasi ketepatan waktu, koordinasi tim, kompetensi teknis, kerapihan kerja, penanganan kendala, dan budaya K3L.'
        },
        'C': {
            code: 'C',
            name: 'Masa Pemeliharaan',
            total: 5,
            desc: 'Evaluasi keandalan fungsi produk, kemudahan operasi & pemeliharaan, respon after sales, dan durabilitas produk.'
        }
    };

    // State Formulir Survei
    let activeCategoryId = null;
    let currentAttachmentName = null;
    const surveiState = {};
    let toastTimer = null;

    /**
     * Inisialisasi State Pertanyaan dari DOM
     */
    function initSurveiState() {
        const cards = document.querySelectorAll('.q-card');

        cards.forEach(card => {
            const id = card.dataset.id;
            const category = card.dataset.category || '';
            const categoryCode = card.dataset.categoryCode || id.charAt(0);
            const text = card.dataset.text || '';

            if (!surveiState[id]) {
                surveiState[id] = {
                    imp: 0,
                    sat: 0,
                    category: category,
                    categoryCode: categoryCode,
                    text: text
                };
            }
        });

        // Update indikator status kategori yang sudah diisi di Step 1
        updateCategoryStatusBadges();
        updateHeaderCounters();
    }

    /**
     * Perbarui Tanda Status Selesai pada Kartu Kategori di Langkah 1
     */
    function updateCategoryStatusBadges() {
        try {
            const surveys = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');
            const completedCategories = new Set(surveys.map(s => s.category_id));

            ['A', 'B', 'C'].forEach(catCode => {
                const badge = document.getElementById(`cat-status-${catCode}`);
                if (badge) {
                    if (completedCategories.has(catCode)) {
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            });
        } catch (e) {
            console.error('Gagal membaca data survei dari localStorage:', e);
        }
    }

    /**
     * LANGKAH 1 -> LANGKAH 2: Pilih Kategori Survei
     */
    window.selectCategory = function (catCode) {
        if (!CATEGORY_MAP[catCode]) return;

        activeCategoryId = catCode;
        const catInfo = CATEGORY_MAP[catCode];

        // Sembunyikan Langkah 1 (Pilih Kategori) dan Tampilan Sukses
        const step1 = document.getElementById('survey-step-1-category');
        const step2 = document.getElementById('survey-step-2-form');
        const successContainer = document.getElementById('survey-success-container');

        if (step1) step1.classList.add('hidden');
        if (successContainer) successContainer.classList.add('hidden');
        if (step2) step2.classList.remove('hidden');

        // Perbarui Header & Badge Langkah 2
        const badgeEl = document.getElementById('active-category-badge');
        const titleEl = document.getElementById('active-category-title');
        const descEl = document.getElementById('active-category-desc');

        if (badgeEl) badgeEl.textContent = `Kategori ${catInfo.code}: ${catInfo.name}`;
        if (titleEl) titleEl.textContent = `Kategori ${catInfo.code}. ${catInfo.name}`;
        if (descEl) descEl.textContent = catInfo.desc;

        // Tampilkan HANYA grup indikator milik kategori yang dipilih
        document.querySelectorAll('.category-indicators-group').forEach(group => {
            group.classList.add('hidden');
        });

        const activeGroup = document.getElementById(`category-group-${catCode}`);
        if (activeGroup) {
            activeGroup.classList.remove('hidden');
        }

        // Hitung dan perbarui progress kategori aktif
        updateCategoryProgress();

        // Scroll halus ke atas formulir langkah 2
        if (step2) {
            step2.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    /**
     * LANGKAH 2 -> LANGKAH 1: Tombol "Ganti Kategori"
     */
    window.backToCategorySelection = function () {
        const step1 = document.getElementById('survey-step-1-category');
        const step2 = document.getElementById('survey-step-2-form');
        const successContainer = document.getElementById('survey-success-container');

        if (step2) step2.classList.add('hidden');
        if (successContainer) successContainer.classList.add('hidden');
        if (step1) step1.classList.remove('hidden');

        // Perbarui tanda centang/badge kategori yang sudah terisi
        updateCategoryStatusBadges();

        if (step1) {
            step1.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    /**
     * Handler Pemilihan Skor Dual-Metric (Kepentingan / Kepuasan)
     */
    window.setScore = function (id, type, val) {
        if (!surveiState[id]) return;

        surveiState[id][type] = val;

        // Update tampilan tombol di baris yang diklik
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

        // Hapus highlight error jika kedua nilai (Kepentingan & Kepuasan) sudah terisi
        if (surveiState[id].imp > 0 && surveiState[id].sat > 0) {
            const card = document.getElementById(`card-${id}`);
            const errHint = document.getElementById(`err-${id}`);
            if (card) card.classList.remove('error');
            if (errHint) errHint.classList.add('hidden');
        }

        updateCategoryProgress();
    };

    /**
     * Hitung & visualisasikan progress pengisian untuk kategori yang sedang aktif
     */
    function updateCategoryProgress() {
        if (!activeCategoryId) return;

        const activeCards = document.querySelectorAll(`#category-group-${activeCategoryId} .q-card`);
        const totalCards = activeCards.length;
        let fullyFilledCards = 0;

        activeCards.forEach(card => {
            const id = card.dataset.id;
            const state = surveiState[id];
            if (state && state.imp > 0 && state.sat > 0) {
                fullyFilledCards++;
            }
        });

        const percent = totalCards > 0 ? Math.round((fullyFilledCards / totalCards) * 100) : 0;

        const elBar = document.getElementById('active-category-progress-bar');
        const elCounter = document.getElementById('active-category-counter');

        if (elBar) elBar.style.width = `${percent}%`;
        if (elCounter) elCounter.textContent = `${fullyFilledCards} / ${totalCards} Terisi`;
    }

    /**
     * Handler Input File Lampiran (Dummy)
     */
    window.handleSurveyFileChange = function (event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const file = input.files[0];
            currentAttachmentName = file.name;

            const nameEl = document.getElementById('attached-filename');
            const sizeEl = document.getElementById('attached-filesize');
            const dropzone = document.getElementById('survey-upload-dropzone');
            const preview = document.getElementById('survey-file-preview');

            const fileSizeFormatted = file.size > 1024 * 1024
                ? (file.size / (1024 * 1024)).toFixed(1) + ' MB'
                : Math.round(file.size / 1024) + ' KB';

            if (nameEl) nameEl.textContent = file.name;
            if (sizeEl) sizeEl.textContent = `${fileSizeFormatted} · Siap dilampirkan`;

            if (dropzone) dropzone.classList.add('hidden');
            if (preview) {
                preview.classList.remove('hidden');
                preview.classList.add('flex');
            }
        }
    };

    /**
     * Handler Hapus File Lampiran
     */
    window.removeSurveyFile = function (event) {
        if (event) event.stopPropagation();

        const input = document.getElementById('survey-attachment-input');
        if (input) input.value = '';

        currentAttachmentName = null;

        const dropzone = document.getElementById('survey-upload-dropzone');
        const preview = document.getElementById('survey-file-preview');

        if (preview) {
            preview.classList.add('hidden');
            preview.classList.remove('flex');
        }
        if (dropzone) dropzone.classList.remove('hidden');
    };

    /**
     * Validasi Formulir Survei untuk Kategori yang Aktif
     */
    function validateActiveCategory() {
        if (!activeCategoryId) return false;

        const activeCards = document.querySelectorAll(`#category-group-${activeCategoryId} .q-card`);
        let isValid = true;
        let firstIncompleteCard = null;

        // Reset semua error pada kategori ini
        activeCards.forEach(c => {
            c.classList.remove('error');
            const err = c.querySelector('.error-hint');
            if (err) err.classList.add('hidden');
        });

        activeCards.forEach(card => {
            const id = card.dataset.id;
            const item = surveiState[id];

            // Wajib terisi keduanya: Kepentingan (1-5) dan Kepuasan (1-5)
            if (!item || item.imp === 0 || item.sat === 0) {
                card.classList.add('error');
                const err = document.getElementById(`err-${id}`);
                if (err) err.classList.remove('hidden');

                if (!firstIncompleteCard) {
                    firstIncompleteCard = card;
                }
                isValid = false;
            }
        });

        // Scroll halus ke indikator pertama yang belum lengkap
        if (firstIncompleteCard) {
            firstIncompleteCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        return isValid;
    }

    /**
     * Setup Event Listener Submit Form Survei
     */
    function setupSurveyForm() {
        const form = document.getElementById('form-survei');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (!activeCategoryId) {
                window.showToast('⚠️', 'Pilih Kategori', 'Silakan pilih kategori survei terlebih dahulu.', '#DC2626');
                window.backToCategorySelection();
                return;
            }

            if (!validateActiveCategory()) {
                window.showToast('⚠️', 'Formulir Belum Lengkap', 'Mohon lengkapi seluruh indikator (Kepentingan & Kepuasan).', '#DC2626');
                return;
            }

            // Ambil sesi aktif pengguna dari localStorage (Zero-Login session)
            let respondentId = 'PIC-001';
            let projectName = 'Proyek Dummy';

            try {
                const rawSession = localStorage.getItem('lrs_active_session');
                if (rawSession) {
                    try {
                        const parsed = JSON.parse(rawSession);
                        if (typeof parsed === 'object' && parsed !== null) {
                            respondentId = parsed.respondent_id || parsed.pic_id || parsed.contract_number || parsed.token || 'PIC-001';
                            projectName = parsed.project_name || parsed.contract_number || 'Proyek Dummy';
                        } else if (typeof parsed === 'string') {
                            respondentId = parsed || 'PIC-001';
                            projectName = parsed || 'Proyek Dummy';
                        }
                    } catch {
                        respondentId = rawSession || 'PIC-001';
                        projectName = rawSession || 'Proyek Dummy';
                    }
                }
            } catch (err) {
                console.error('Error membaca lrs_active_session:', err);
            }

            // Kumpulkan jawaban HANYA dari kategori yang dipilih
            const activeCards = document.querySelectorAll(`#category-group-${activeCategoryId} .q-card`);
            const answers = Array.from(activeCards).map(card => {
                const id = card.dataset.id;
                return {
                    question_id: id,
                    kepentingan: surveiState[id].imp,
                    kepuasan: surveiState[id].sat
                };
            });

            const catInfo = CATEGORY_MAP[activeCategoryId] || { name: `Kategori ${activeCategoryId}` };

            // Buat struktur payload sesuai spesifikasi PRD & Prompt
            const elSaran = document.getElementById('saran-umum');
            const refNo = `SRV-${new Date().toISOString().slice(0, 10).replace(/-/g, '')}-${String(Math.floor(1000 + Math.random() * 9000))}`;
            let personnelName = 'Ir. Dwi Suryanto, S.T.';
            try {
                const sess = JSON.parse(localStorage.getItem('lrs_active_session') || '{}');
                if (sess.accompanying_personnel) personnelName = sess.accompanying_personnel;
                else if (sess.personnel_name) personnelName = sess.personnel_name;
            } catch (e) {}

            const surveyPayload = {
                reference_no: refNo,
                respondent_id: respondentId,
                project_name: projectName,
                category_id: activeCategoryId,
                category_name: catInfo.name,
                accompanying_personnel: personnelName,
                personnel_wa_status: 'Terkirim',
                attachment_name: currentAttachmentName || null,
                general_notes: elSaran ? elSaran.value.trim() || null : null,
                submitted_at: new Date().toISOString(),
                is_read: false,
                answers: answers
            };

            // Simpan ke localStorage pada key 'lrs_survey_data' (append)
            try {
                const existing = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');
                existing.push(surveyPayload);
                localStorage.setItem('lrs_survey_data', JSON.stringify(existing));

                // Simulasi pencatatan log notifikasi ke 3 pihak jika diaktifkan
                try {
                    const settings = JSON.parse(localStorage.getItem('lrs_admin_settings') || '{}');
                    if (settings.enable_wa_simulation !== false) {
                        const logs = JSON.parse(localStorage.getItem('lrs_notification_logs') || '[]');
                        const nowFormatted = new Date().toISOString().slice(0, 16).replace('T', ' ');
                        logs.unshift(
                            {
                                id: 'notif-' + Date.now() + '-1',
                                recipient: `Pelanggan (${respondentId})`,
                                channel: 'WhatsApp',
                                type: 'Tanda Terima Survei',
                                timestamp: nowFormatted,
                                status: 'Terkirim',
                                reference_no: refNo
                            },
                            {
                                id: 'notif-' + Date.now() + '-2',
                                recipient: `Personel Pendamping (${personnelName})`,
                                channel: 'WhatsApp',
                                type: 'Notifikasi Konfirmasi Klien',
                                timestamp: nowFormatted,
                                status: 'Terkirim',
                                reference_no: refNo
                            },
                            {
                                id: 'notif-' + Date.now() + '-3',
                                recipient: 'Administrator Sistem LRS',
                                channel: 'Sistem Web & Email',
                                type: 'Rekap Survei Masuk',
                                timestamp: nowFormatted,
                                status: 'Terkirim',
                                reference_no: refNo
                            }
                        );
                        localStorage.setItem('lrs_notification_logs', JSON.stringify(logs));
                    }
                } catch (eLog) {
                    console.error('Error saat mencatat log notifikasi:', eLog);
                }

                updateHeaderCounters();

                // Tampilkan Tampilan Sukses ("Terima Kasih")
                showSurveySuccessView(surveyPayload);

                window.showToast('🎉', 'Survei Berhasil!', 'Data survei telah tersimpan di browser storage.', '#059669');
            } catch (err) {
                console.error('Gagal menyimpan ke localStorage:', err);
                window.showToast('❌', 'Terjadi Kesalahan', 'Gagal menyimpan data ke browser storage.', '#DC2626');
            }
        });
    }

    /**
     * Tampilkan Layar Sukses Pengisian Survei
     */
    function showSurveySuccessView(payload) {
        const step2 = document.getElementById('survey-step-2-form');
        const successContainer = document.getElementById('survey-success-container');

        if (step2) step2.classList.add('hidden');
        if (successContainer) successContainer.classList.remove('hidden');

        // Isi Data Ringkasan
        const elCatName = document.getElementById('success-category-name');
        const elCatSummary = document.getElementById('success-category-summary');
        const elRespId = document.getElementById('success-respondent-id');
        const elProjName = document.getElementById('success-project-name');
        const elAttach = document.getElementById('success-attachment-name');
        const elTime = document.getElementById('success-submitted-time');

        if (elCatName) elCatName.textContent = payload.category_name;
        if (elCatSummary) elCatSummary.textContent = `${payload.category_id}. ${payload.category_name} (${payload.answers.length} Indikator)`;
        if (elRespId) elRespId.textContent = payload.respondent_id;
        if (elProjName) elProjName.textContent = payload.project_name;
        if (elAttach) elAttach.textContent = payload.attachment_name || 'Tidak ada lampiran';
        if (elTime) {
            elTime.textContent = new Date(payload.submitted_at).toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit'
            }) + ' WIB';
        }

        // Scroll halus ke tampilan sukses
        if (successContainer) {
            successContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    /**
     * Aksi Tombol Sukses: "Isi Kategori Lain"
     * Mengembalikan pengguna ke Langkah 1 dengan kategori yang baru saja diisi ditandai sudah selesai.
     */
    window.actionFillOtherCategory = function () {
        const step1 = document.getElementById('survey-step-1-category');
        const step2 = document.getElementById('survey-step-2-form');
        const successContainer = document.getElementById('survey-success-container');

        if (successContainer) successContainer.classList.add('hidden');
        if (step2) step2.classList.add('hidden');
        if (step1) step1.classList.remove('hidden');

        // Reset file attachment untuk kategori berikutnya
        window.removeSurveyFile();

        // Reset textarea saran umum jika ada
        const elSaran = document.getElementById('saran-umum');
        if (elSaran) elSaran.value = '';

        // Update status badge kategori yang sudah terisi
        updateCategoryStatusBadges();

        if (step1) {
            step1.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    /**
     * Aksi Tombol Sukses: "Kembali ke Beranda"
     */
    window.actionBackToHome = function () {
        window.actionFillOtherCategory();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    /**
     * Perbarui Counter di Header
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
     * Rating Bintang di panel Masukan
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
            'In Progress': { cls: 'status-progress', badge: 'badge-progress', icon: '🟡', label: 'In Progress' },
            'Resolved':    { cls: 'status-resolved', badge: 'badge-resolved', icon: '🟢', label: 'Resolved' }
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
     * Navigasi Antar Tab (Isi Survei, Beri Masukan, Buat Keluhan)
     */
    window.switchTab = function (tabId, btn) {
        document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));

        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active');
            b.setAttribute('aria-selected', 'false');
        });

        const targetPanel = document.getElementById(`panel-${tabId}`);
        if (targetPanel) {
            targetPanel.classList.add('active');
        }

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

    /**
     * Periksa Parameter URL Query (?proyek=...&personel=...&kategori=...)
     */
    function checkUrlQueryParams() {
        try {
            const params = new URLSearchParams(window.location.search);
            const proyek = params.get('proyek');
            const personel = params.get('personel');
            const kategori = params.get('kategori');

            if (proyek || personel) {
                let session = {};
                try {
                    session = JSON.parse(localStorage.getItem('lrs_active_session') || '{}');
                } catch (e) {}

                if (proyek) {
                    session.projectName = proyek;
                    session.project_code = proyek;
                }
                if (personel) {
                    session.accompanying_personnel = personel;
                    session.personnel_name = personel;
                }
                localStorage.setItem('lrs_active_session', JSON.stringify(session));

                const projEl = document.getElementById('session-project-name');
                if (projEl) projEl.textContent = proyek;
            }

            if (kategori && CATEGORY_MAP[kategori]) {
                window.selectCategory(kategori);
            }
        } catch (e) {
            console.error('Error saat memeriksa parameter query URL:', e);
        }
    }

    // Jalankan inisialisasi saat DOM siap
    document.addEventListener('DOMContentLoaded', function () {
        initSurveiState();
        setupSurveyForm();
        setupFeedbackForm();
        setupComplaintForm();
        checkUrlQueryParams();
    });

})();
