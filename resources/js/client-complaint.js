/**
 * PT Len Railway Systems — Pusat Keluhan Klien (/keluhan)
 * Script Interaktif Standalone: Tab Laporan Keluhan, Konfirmasi Tiket, Pelacakan 3-Tahap & Bilingual (ID/EN)
 * Penyimpanan lokal: window.localStorage ('lrs_complaint_data', 'lrs_active_session', 'lrs_client_lang')
 */
(function () {
    'use strict';

    if (!document.getElementById('complaint-page-root')) return;
    if (window.__lrs_complaint_page_initialized) return;
    window.__lrs_complaint_page_initialized = true;

    let currentLang = 'id';
    let currentAttachmentName = null;
    let lastCreatedTicketId = null;
    let activeTrackedTicketId = null;
    let toastTimer = null;

    const I18N = {
        id: {
            pageTitle: 'Pusat Keluhan Klien · PT Len Railway Systems',
            headerBadge: 'PUSAT KELUHAN KLIEN',
            headerSubtitle: 'Pelaporan Kendala Layanan & Proyek',
            backToSurvey: 'Survei Kepuasan',
            heroPill: 'Layanan Tanggap Keluhan · Zero-Login',
            heroTitle: 'Pusat Keluhan',
            heroSubtitle: 'Laporkan kendala teknis, pelaksanaan proyek, atau layanan pemeliharaan kapan saja.',
            tabSubmit: 'Buat Laporan Keluhan',

            // Form Tab 1
            sessionPrefill: 'Identitas proyek & pelapor diisi otomatis dari sesi aktif Anda (tetap dapat diubah).',
            sectionIdentity: 'Identitas Pelapor & Proyek',
            lblReporter: 'Nama Pelapor / PIC',
            phReporter: 'Contoh: Ir. Budi Santoso',
            errReporter: 'Nama pelapor wajib diisi.',
            lblProject: 'Nama Proyek / Instansi',
            phProject: 'Contoh: LRT Jakarta Fase 1B atau No. Kontrak',
            errProject: 'Nama proyek atau instansi wajib diisi.',
            lblContact: 'Kontak / Email Pelapor',
            optLabel: '(Opsional)',
            phContact: 'Contoh: budi@instansi.co.id / 0812xxxx',
            lblCategory: 'Kategori Keluhan',
            catPlaceholder: '— Pilih kategori keluhan —',
            catService: 'Layanan',
            catProject: 'Pelaksanaan Proyek',
            catTechnical: 'Teknis & Produk',
            catMaintenance: 'Masa Pemeliharaan',
            catOthers: 'Lainnya',
            errCategory: 'Silakan pilih kategori keluhan.',
            lblUrgency: 'Tingkat Urgensi',
            urgLow: 'Rendah',
            urgMedium: 'Sedang',
            urgHigh: 'Tinggi',
            urgCritical: 'Kritis',
            errUrgency: 'Silakan pilih tingkat urgensi penanganan.',
            lblTitle: 'Judul Singkat Kendala',
            phTitle: 'Contoh: Fluktuasi pembacaan sensor wesel Stasiun X',
            errTitle: 'Judul singkat kendala wajib diisi.',
            lblDescription: 'Uraian Detail Kendala',
            phDescription: 'Jelaskan lokasi, kronologi kejadian, serta dampak terhadap operasional...',
            errDescription: 'Uraian detail kendala wajib diisi.',
            lblAttachment: 'Unggah Bukti Pendukung',
            uploadTitle: 'Klik untuk melampirkan foto / dokumen bukti pendukung',
            uploadHint: 'Format JPG, PNG, PDF, DOCX (Maks. 10 MB — nama file dicatat otomatis)',
            btnRemoveFile: 'Hapus',
            btnSubmit: 'Kirim Laporan Keluhan',

            // Konfirmasi
            confirmTitle: 'Laporan Keluhan Berhasil Diterbitkan!',
            confirmSubtitle: 'Simpan nomor tiket di bawah ini untuk memantau perkembangan investigasi dan langkah perbaikan oleh tim PT Len Railway Systems.',
            confirmTicketLbl: 'Nomor Tiket Resmi',
            btnCopy: 'Salin',
            btnCopied: 'Tersalin',
            sumReporter: 'Pelapor',
            sumProject: 'Proyek / Instansi',
            sumCategory: 'Kategori',
            sumUrgencyTime: 'Urgensi & Waktu Lapor',
            smapTitle: 'SMAP ISO 37001:2016 PT Len Railway Systems',
            smapDesc: 'PT Len Railway Systems berkomitmen menerapkan Prinsip 4 NOs (No Bribery, No Kickback, No Gift, No Luxurious Hospitality) dan menjamin seluruh penanganan keluhan berlangsung secara transparan, objektif, serta bebas benturan kepentingan.',
            linkSmap: 'Pelajari Kebijakan SMAP',
            btnTrackCreated: 'Lacak Tiket Ini',
            btnCreateAnother: 'Buat Laporan Lain',

            // Track Tab 2
            trackTitle: 'Lacak Progres Penanganan Tiket',
            trackSubtitle: 'Masukkan nomor tiket keluhan Anda (contoh: #TKT-1234 atau TKT-1234) untuk melihat status terbaru.',
            phTrackInput: 'Contoh: #TKT-1234',
            btnTrackSearch: 'Lacak',
            recentTicketsLabel: 'Tiket Terbaru Anda',
            noRecentTickets: 'Belum ada riwayat tiket yang dibuat di peramban ini.',
            notFoundTitle: 'Nomor Tiket Tidak Ditemukan',
            notFoundDesc: 'Pastikan penulisan nomor tiket sudah benar (contoh: #TKT-1234) atau periksa kembali daftar tiket terbaru Anda di atas.',

            // Stepper & Tracking Detail
            stepOpen: 'Baru Masuk (Open)',
            stepProgress: 'Sedang Diproses (In Progress)',
            stepResolved: 'Selesai (Resolved)',
            detailTicketLbl: 'Nomor Tiket',
            detailReporterLbl: 'Pelapor',
            detailProjectLbl: 'Proyek / Instansi',
            detailCategoryLbl: 'Kategori',
            detailUrgencyLbl: 'Urgensi',
            detailDateLbl: 'Tanggal Lapor',
            detailAttachmentLbl: 'Lampiran',
            noAttachment: 'Tidak ada lampiran',
            followupHeader: 'Tindak Lanjut Tim PT Len Railway Systems',
            rootCauseLbl: 'Akar Masalah / Pemeriksaan',
            actionTakenLbl: 'Langkah Perbaikan yang Dilakukan',
            pendingFollowup: 'Tim teknis PT Len Railway Systems sedang meninjau laporan Anda.',

            // Toasts
            toastValidationTitle: 'Formulir Belum Lengkap',
            toastValidationDesc: 'Mohon lengkapi seluruh kolom wajib yang ditandai merah.',
            toastSubmittedTitle: 'Tiket Diterbitkan!',
            toastSubmittedDesc: 'Laporan Anda tersimpan dengan nomor tiket',
            toastCopiedTitle: 'Disalin',
            toastCopiedDesc: 'Nomor tiket berhasil disalin ke clipboard.',
            toastEmptySearchTitle: 'Nomor Tiket Kosong',
            toastEmptySearchDesc: 'Masukkan nomor tiket yang ingin dilacak terlebih dahulu.'
        },
        en: {
            pageTitle: 'Client Complaint Center · PT Len Railway Systems',
            headerBadge: 'CLIENT COMPLAINT CENTER',
            headerSubtitle: 'Service & Project Issue Reporting and Ticket Status Tracking',
            backToSurvey: 'Satisfaction Survey',
            heroPill: 'Responsive Complaint Service · Zero-Login',
            heroTitle: 'Complaint Center & Ticket Tracking',
            heroSubtitle: 'Report technical, project execution, or maintenance issues anytime. Each report receives an official ticket number to monitor resolution progress by PT Len Railway Systems.',
            tabSubmit: 'Submit Complaint',
            tabTrack: 'Track Ticket Status',

            // Form Tab 1
            sessionPrefill: 'Project & reporter details auto-filled from your active session (editable).',
            sectionIdentity: 'Reporter & Project Identity',
            lblReporter: 'Reporter / PIC Name',
            phReporter: 'e.g. Ir. Budi Santoso',
            errReporter: 'Reporter name is required.',
            lblProject: 'Project / Institution Name',
            phProject: 'e.g. LRT Jakarta Phase 1B or Contract No.',
            errProject: 'Project or institution name is required.',
            lblContact: 'Reporter Contact / Email',
            optLabel: '(Optional)',
            phContact: 'e.g. budi@company.co.id / +62812xxxx',
            lblCategory: 'Complaint Category',
            catPlaceholder: '— Select complaint category —',
            catService: 'Services',
            catProject: 'Project Execution',
            catTechnical: 'Technical & Product',
            catMaintenance: 'Maintenance Period',
            catOthers: 'Others',
            errCategory: 'Please select a complaint category.',
            lblUrgency: 'Urgency Level',
            urgLow: 'Low',
            urgMedium: 'Medium',
            urgHigh: 'High',
            urgCritical: 'Critical',
            errUrgency: 'Please select handling urgency level.',
            lblTitle: 'Brief Issue Title',
            phTitle: 'e.g. Point machine sensor reading fluctuation at Station X',
            errTitle: 'Brief issue title is required.',
            lblDescription: 'Detailed Issue Description',
            phDescription: 'Describe the location, chronology, and operational impact...',
            errDescription: 'Detailed issue description is required.',
            lblAttachment: 'Upload Supporting Evidence',
            uploadTitle: 'Click to attach supporting photo or document',
            uploadHint: 'Supports JPG, PNG, PDF, DOCX (Max. 10 MB — file name recorded automatically)',
            btnRemoveFile: 'Remove',
            btnSubmit: 'Submit Complaint Report',

            // Konfirmasi
            confirmTitle: 'Complaint Ticket Successfully Issued!',
            confirmSubtitle: 'Save the ticket number below to monitor investigation progress and corrective actions by PT Len Railway Systems.',
            confirmTicketLbl: 'Official Ticket Number',
            btnCopy: 'Copy',
            btnCopied: 'Copied',
            sumReporter: 'Reporter',
            sumProject: 'Project / Institution',
            sumCategory: 'Category',
            sumUrgencyTime: 'Urgency & Reported At',
            smapTitle: 'ABMS ISO 37001:2016 PT Len Railway Systems',
            smapDesc: 'PT Len Railway Systems is committed to upholding the 4 NOs Principles (No Bribery, No Kickback, No Gift, No Luxurious Hospitality) and ensuring all complaint handling remains transparent, objective, and free from conflict of interest.',
            linkSmap: 'Learn More About SMAP Policy',
            btnTrackCreated: 'Track This Ticket',
            btnCreateAnother: 'Submit Another Report',

            // Track Tab 2
            trackTitle: 'Track Ticket Resolution Progress',
            trackSubtitle: 'Enter your complaint ticket number (e.g. #TKT-1234 or TKT-1234) to view the latest status.',
            phTrackInput: 'e.g. #TKT-1234',
            btnTrackSearch: 'Track',
            recentTicketsLabel: 'Your Recent Tickets',
            noRecentTickets: 'No tickets have been created in this browser yet.',
            notFoundTitle: 'Ticket Number Not Found',
            notFoundDesc: 'Please ensure the ticket number is entered correctly (e.g. #TKT-1234) or select from your recent tickets above.',

            // Stepper & Tracking Detail
            stepOpen: 'Received (Open)',
            stepProgress: 'In Progress',
            stepResolved: 'Resolved',
            detailTicketLbl: 'Ticket Number',
            detailReporterLbl: 'Reporter',
            detailProjectLbl: 'Project / Institution',
            detailCategoryLbl: 'Category',
            detailUrgencyLbl: 'Urgency',
            detailDateLbl: 'Reported Date',
            detailAttachmentLbl: 'Attachment',
            noAttachment: 'No attachment',
            followupHeader: 'PT Len Railway Systems Team Follow-Up',
            rootCauseLbl: 'Root Cause / Investigation',
            actionTakenLbl: 'Corrective Action Taken',
            pendingFollowup: 'PT Len Railway Systems technical team is reviewing your report.',

            // Toasts
            toastValidationTitle: 'Incomplete Form',
            toastValidationDesc: 'Please complete all required fields highlighted in red.',
            toastSubmittedTitle: 'Ticket Issued!',
            toastSubmittedDesc: 'Your report has been saved with ticket number',
            toastCopiedTitle: 'Copied',
            toastCopiedDesc: 'Ticket number copied to clipboard.',
            toastEmptySearchTitle: 'Empty Ticket Number',
            toastEmptySearchDesc: 'Please enter the ticket number to track.'
        }
    };

    /**
     * Helper Escape HTML untuk Mencegah XSS
     */
    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function normalizeTicketKey(val) {
        return String(val || '').trim().toUpperCase().replace(/^#/, '');
    }

    function formatDisplayTicket(ticketId) {
        const clean = normalizeTicketKey(ticketId);
        return clean ? '#' + clean : '-';
    }

    function getCategoryLabel(catVal) {
        const t = I18N[currentLang];
        const map = {
            'Layanan': t.catService,
            'Pelaksanaan Proyek': t.catProject,
            'Teknis & Produk': t.catTechnical,
            'Teknis': t.catTechnical,
            'Masa Pemeliharaan': t.catMaintenance,
            'Lainnya': t.catOthers
        };
        return map[catVal] || catVal || '-';
    }

    function getUrgencyMeta(urgVal) {
        const t = I18N[currentLang];
        const map = {
            'Rendah': { label: t.urgLow, cls: 'bg-emerald-50 text-emerald-700 border-emerald-200' },
            'Sedang': { label: t.urgMedium, cls: 'bg-blue-50 text-[#003B73] border-blue-200' },
            'Tinggi': { label: t.urgHigh, cls: 'bg-amber-50 text-amber-800 border-amber-200' },
            'Kritis': { label: t.urgCritical, cls: 'bg-rose-50 text-rose-700 border-rose-200' }
        };
        return map[urgVal] || { label: urgVal || '-', cls: 'bg-slate-100 text-slate-700 border-slate-200' };
    }

    /**
     * Toast Notifikasi
     */
    function showToast(icon, title, desc) {
        const toast = document.getElementById('complaint-toast');
        const elIcon = document.getElementById('complaint-toast-icon');
        const elTitle = document.getElementById('complaint-toast-title');
        const elDesc = document.getElementById('complaint-toast-desc');
        if (!toast || !elIcon || !elTitle || !elDesc) return;

        elIcon.textContent = icon;
        elTitle.textContent = title;
        elDesc.textContent = desc;
        toast.classList.remove('hidden');

        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toast.classList.add('hidden');
        }, 4000);
    }

    /**
     * Pengalih Bahasa Bilingual (ID | EN) Instan Tanpa Reload
     */
    window.setLanguage = function (lang) {
        currentLang = (lang === 'en') ? 'en' : 'id';
        try {
            localStorage.setItem('lrs_client_lang', currentLang);
        } catch (e) {}

        const btnId = document.getElementById('btn-lang-id');
        const btnEn = document.getElementById('btn-lang-en');
        if (btnId && btnEn) {
            if (currentLang === 'en') {
                btnEn.className = 'px-2.5 py-1 rounded-lg text-xs font-black transition-all bg-[#003B73] text-white shadow-xs cursor-pointer';
                btnId.className = 'px-2.5 py-1 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 transition-all cursor-pointer';
            } else {
                btnId.className = 'px-2.5 py-1 rounded-lg text-xs font-black transition-all bg-[#003B73] text-white shadow-xs cursor-pointer';
                btnEn.className = 'px-2.5 py-1 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 transition-all cursor-pointer';
            }
        }

        const t = I18N[currentLang];
        document.title = t.pageTitle;

        const setText = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.textContent = val;
        };
        const setHtmlReq = (id, labelText) => {
            const el = document.getElementById(id);
            if (el) el.innerHTML = `${escapeHtml(labelText)} <span class="text-rose-500">*</span>`;
        };
        const setPh = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.placeholder = val;
        };

        // Header & Hero & Tabs
        setText('txt-complaint-header-badge', t.headerBadge);
        setText('txt-complaint-header-subtitle', t.headerSubtitle);
        setText('txt-link-back-survey', t.backToSurvey);
        const heroPillSpan = document.querySelector('#txt-hero-pill span:last-child');
        if (heroPillSpan) heroPillSpan.textContent = t.heroPill;
        setText('txt-hero-title', t.heroTitle);
        setText('txt-hero-subtitle', t.heroSubtitle);
        setText('txt-tab-submit', t.tabSubmit);
        setText('txt-tab-track', t.tabTrack);

        // Form Tab 1
        setText('txt-session-prefill', t.sessionPrefill);
        setText('txt-section-identity', t.sectionIdentity);
        setHtmlReq('lbl-c-reporter', t.lblReporter);
        setPh('c-reporter', t.phReporter);
        setText('err-c-reporter', t.errReporter);

        setHtmlReq('lbl-c-project', t.lblProject);
        setPh('c-project', t.phProject);
        setText('err-c-project', t.errProject);

        const lblContact = document.getElementById('lbl-c-contact');
        if (lblContact) {
            lblContact.innerHTML = `${escapeHtml(t.lblContact)} <span id="txt-c-contact-opt" class="text-slate-400 font-semibold normal-case">${escapeHtml(t.optLabel)}</span>`;
        }
        setPh('c-contact', t.phContact);

        setHtmlReq('lbl-c-category', t.lblCategory);
        setText('opt-c-cat-placeholder', t.catPlaceholder);
        setText('opt-c-cat-service', t.catService);
        setText('opt-c-cat-project', t.catProject);
        setText('opt-c-cat-technical', t.catTechnical);
        setText('opt-c-cat-maintenance', t.catMaintenance);
        setText('opt-c-cat-others', t.catOthers);
        setText('err-c-category', t.errCategory);

        setHtmlReq('lbl-c-urgency', t.lblUrgency);
        setText('txt-urg-low', t.urgLow);
        setText('txt-urg-medium', t.urgMedium);
        setText('txt-urg-high', t.urgHigh);
        setText('txt-urg-critical', t.urgCritical);
        setText('err-c-urgency', t.errUrgency);

        setHtmlReq('lbl-c-title', t.lblTitle);
        setPh('c-title', t.phTitle);
        setText('err-c-title', t.errTitle);

        setHtmlReq('lbl-c-description', t.lblDescription);
        setPh('c-description', t.phDescription);
        setText('err-c-description', t.errDescription);

        const lblAttach = document.getElementById('lbl-c-attachment');
        if (lblAttach) {
            lblAttach.innerHTML = `${escapeHtml(t.lblAttachment)} <span id="txt-c-attach-opt" class="text-slate-400 font-semibold normal-case">${escapeHtml(t.optLabel)}</span>`;
        }
        setText('txt-c-upload-title', t.uploadTitle);
        setText('txt-c-upload-hint', t.uploadHint);
        setText('btn-remove-c-file', t.btnRemoveFile);
        setText('txt-btn-submit-complaint', t.btnSubmit);

        // Confirmation
        setText('txt-confirm-title', t.confirmTitle);
        setText('txt-confirm-subtitle', t.confirmSubtitle);
        setText('txt-confirm-ticket-lbl', t.confirmTicketLbl);
        setText('txt-btn-copy-ticket', t.btnCopy);
        setText('lbl-sum-reporter', t.sumReporter);
        setText('lbl-sum-project', t.sumProject);
        setText('lbl-sum-category', t.sumCategory);
        setText('lbl-sum-urgency', t.sumUrgencyTime);
        setText('txt-smap-title', t.smapTitle);
        setText('txt-smap-desc', t.smapDesc);
        setText('txt-link-smap', t.linkSmap);
        setText('txt-btn-track-created', t.btnTrackCreated);
        setText('txt-btn-create-another', t.btnCreateAnother);

        // Track Tab 2
        setText('txt-track-title', t.trackTitle);
        setText('txt-track-subtitle', t.trackSubtitle);
        setPh('track-ticket-input', t.phTrackInput);
        setText('txt-btn-track-search', t.btnTrackSearch);
        setText('txt-recent-tickets-label', t.recentTicketsLabel);
        setText('txt-track-not-found-title', t.notFoundTitle);
        setText('txt-track-not-found-desc', t.notFoundDesc);

        renderRecentTickets();
        if (activeTrackedTicketId) {
            renderTrackTicketResult(activeTrackedTicketId);
        }
        if (lastCreatedTicketId) {
            refreshConfirmationSummary(lastCreatedTicketId);
        }
    };

    /**
     * Navigasi 2 Tab Utama
     */
    window.switchComplaintTab = function (tab) {
        const panelSubmit = document.getElementById('panel-submit-complaint');
        const panelTrack = document.getElementById('panel-track-ticket');
        const successBox = document.getElementById('complaint-success-container');
        const btnSubmit = document.getElementById('tab-btn-submit');
        const btnTrack = document.getElementById('tab-btn-track');

        const activeClass = 'w-full min-h-[48px] px-4 py-3 rounded-xl font-extrabold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 bg-[#003B73] text-white shadow-sm cursor-pointer';
        const inactiveClass = 'w-full min-h-[48px] px-4 py-3 rounded-xl font-bold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 cursor-pointer';

        if (successBox) successBox.classList.add('hidden');

        if (tab === 'track') {
            if (panelSubmit) panelSubmit.classList.add('hidden');
            if (panelTrack) panelTrack.classList.remove('hidden');
            if (btnTrack) {
                btnTrack.className = activeClass;
                btnTrack.setAttribute('aria-selected', 'true');
            }
            if (btnSubmit) {
                btnSubmit.className = inactiveClass;
                btnSubmit.setAttribute('aria-selected', 'false');
            }
            renderRecentTickets();
        } else {
            if (panelTrack) panelTrack.classList.add('hidden');
            if (panelSubmit) panelSubmit.classList.remove('hidden');
            if (btnSubmit) {
                btnSubmit.className = activeClass;
                btnSubmit.setAttribute('aria-selected', 'true');
            }
            if (btnTrack) {
                btnTrack.className = inactiveClass;
                btnTrack.setAttribute('aria-selected', 'false');
            }
        }
    };

    /**
     * Auto-fill Identitas dari lrs_active_session jika tersedia
     */
    function prefillFromActiveSession() {
        try {
            const raw = localStorage.getItem('lrs_active_session');
            if (!raw) return;

            const session = JSON.parse(raw);
            if (!session || typeof session !== 'object') return;

            const projVal = session.project_name || session.projectName || session.contract_number || '';
            const repVal = session.client_name || session.respondent_id || '';

            const elProj = document.getElementById('c-project');
            const elRep = document.getElementById('c-reporter');
            const notice = document.getElementById('session-prefill-notice');

            let prefilled = false;
            if (elProj && projVal && !elProj.value) {
                elProj.value = projVal;
                prefilled = true;
            }
            if (elRep && repVal && !elRep.value) {
                elRep.value = repVal;
                prefilled = true;
            }
            if (prefilled && notice) {
                notice.classList.remove('hidden');
            }
        } catch (e) {
            console.error('Gagal membaca sesi aktif:', e);
        }
    }

    /**
     * Dummy File Attachment Handler
     */
    window.handleStandaloneComplaintFile = function (event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            currentAttachmentName = input.files[0].name;
            const nameEl = document.getElementById('c-attached-filename');
            const dropzone = document.getElementById('c-upload-dropzone');
            const preview = document.getElementById('c-file-preview');

            if (nameEl) nameEl.textContent = currentAttachmentName;
            if (dropzone) dropzone.classList.add('hidden');
            if (preview) {
                preview.classList.remove('hidden');
                preview.classList.add('flex');
            }
        }
    };

    window.removeStandaloneComplaintFile = function (event) {
        if (event) event.stopPropagation();
        const input = document.getElementById('c-attachment-input');
        if (input) input.value = '';
        currentAttachmentName = null;

        const dropzone = document.getElementById('c-upload-dropzone');
        const preview = document.getElementById('c-file-preview');
        if (preview) {
            preview.classList.add('hidden');
            preview.classList.remove('flex');
        }
        if (dropzone) dropzone.classList.remove('hidden');
    };

    /**
     * Validasi & Submit Formulir Keluhan
     */
    function setupComplaintForm() {
        const form = document.getElementById('form-client-complaint');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const elReporter = document.getElementById('c-reporter');
            const elProject = document.getElementById('c-project');
            const elContact = document.getElementById('c-contact');
            const elCategory = document.getElementById('c-category');
            const elUrgency = document.querySelector('input[name="urgency"]:checked');
            const elTitle = document.getElementById('c-title');
            const elDescription = document.getElementById('c-description');

            const reporterName = elReporter?.value.trim() || '';
            const projectName = elProject?.value.trim() || '';
            const contactVal = elContact?.value.trim() || '';
            const categoryVal = elCategory?.value.trim() || '';
            const urgencyVal = elUrgency?.value || '';
            const titleVal = elTitle?.value.trim() || '';
            const descVal = elDescription?.value.trim() || '';

            const toggleErr = (errId, inputEl, hasErr) => {
                const errEl = document.getElementById(errId);
                if (errEl) errEl.classList.toggle('hidden', !hasErr);
                if (inputEl) {
                    inputEl.classList.toggle('border-rose-400', hasErr);
                    inputEl.classList.toggle('bg-rose-50/40', hasErr);
                }
            };

            let valid = true;
            if (!reporterName) { toggleErr('err-c-reporter', elReporter, true); valid = false; } else { toggleErr('err-c-reporter', elReporter, false); }
            if (!projectName) { toggleErr('err-c-project', elProject, true); valid = false; } else { toggleErr('err-c-project', elProject, false); }
            if (!categoryVal) { toggleErr('err-c-category', elCategory, true); valid = false; } else { toggleErr('err-c-category', elCategory, false); }
            if (!urgencyVal) { toggleErr('err-c-urgency', null, true); valid = false; } else { toggleErr('err-c-urgency', null, false); }
            if (!titleVal) { toggleErr('err-c-title', elTitle, true); valid = false; } else { toggleErr('err-c-title', elTitle, false); }
            if (!descVal) { toggleErr('err-c-description', elDescription, true); valid = false; } else { toggleErr('err-c-description', elDescription, false); }

            const t = I18N[currentLang];
            if (!valid) {
                showToast('⚠️', t.toastValidationTitle, t.toastValidationDesc);
                return;
            }

            let sessionMeta = {};
            try {
                sessionMeta = JSON.parse(localStorage.getItem('lrs_active_session') || '{}');
            } catch (errSess) {}

            // Format ticket_id: TKT-XXXX (ditampilkan #TKT-XXXX pada UI)
            const ticketId = 'TKT-' + String(Math.floor(1000 + Math.random() * 9000));
            const createdIso = new Date().toISOString();

            const payload = {
                ticket_id: ticketId,
                reporter_name: reporterName,
                client_name: reporterName,
                reporter_contact: contactVal || null,
                project_name: projectName,
                project_code: sessionMeta.project_code || sessionMeta.contract_number || null,
                survey_source: sessionMeta.survey_source || 'complaint_portal',
                category: categoryVal,
                urgency: urgencyVal,
                title: titleVal,
                subject: titleVal,
                description: descVal,
                attachment_name: currentAttachmentName || null,
                status: 'Open',
                root_cause: null,
                action_taken: null,
                rca: '',
                capa: '',
                is_read: false,
                language: currentLang,
                sla_hours: urgencyVal === 'Kritis' ? 12 : (urgencyVal === 'Tinggi' ? 24 : 48),
                created_at: createdIso,
                audit_trail: [
                    {
                        timestamp: createdIso,
                        status: 'Open',
                        note: 'Laporan keluhan diterima melalui Pusat Keluhan Klien (/keluhan).'
                    }
                ]
            };

            try {
                const existing = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');
                existing.push(payload);
                localStorage.setItem('lrs_complaint_data', JSON.stringify(existing));

                lastCreatedTicketId = ticketId;
                showConfirmationScreen(payload);
                showToast('🎫', t.toastSubmittedTitle, `${t.toastSubmittedDesc} #${ticketId}`);
            } catch (err) {
                console.error('Gagal menyimpan keluhan ke localStorage:', err);
            }
        });
    }

    function showConfirmationScreen(payload) {
        const panelSubmit = document.getElementById('panel-submit-complaint');
        const panelTrack = document.getElementById('panel-track-ticket');
        const successBox = document.getElementById('complaint-success-container');

        if (panelSubmit) panelSubmit.classList.add('hidden');
        if (panelTrack) panelTrack.classList.add('hidden');
        if (successBox) successBox.classList.remove('hidden');

        refreshConfirmationSummary(payload.ticket_id, payload);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function refreshConfirmationSummary(ticketId, preloaded) {
        let payload = preloaded;
        if (!payload) {
            const list = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');
            payload = list.find(item => normalizeTicketKey(item.ticket_id) === normalizeTicketKey(ticketId));
        }
        if (!payload) return;

        const elTicketId = document.getElementById('confirm-ticket-id');
        const elReporter = document.getElementById('sum-reporter');
        const elProject = document.getElementById('sum-project');
        const elCategory = document.getElementById('sum-category');
        const elUrgTime = document.getElementById('sum-urgency-time');

        const urgMeta = getUrgencyMeta(payload.urgency);
        const timeStr = payload.created_at
            ? new Date(payload.created_at).toLocaleString(currentLang === 'en' ? 'en-US' : 'id-ID', {
                day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
            })
            : '-';

        if (elTicketId) elTicketId.textContent = formatDisplayTicket(payload.ticket_id);
        if (elReporter) elReporter.textContent = payload.reporter_name || payload.client_name || '-';
        if (elProject) elProject.textContent = payload.project_name || '-';
        if (elCategory) elCategory.textContent = getCategoryLabel(payload.category);
        if (elUrgTime) elUrgTime.textContent = `${urgMeta.label} · ${timeStr}`;
    }

    window.copyCreatedTicketId = function () {
        const elTicket = document.getElementById('confirm-ticket-id');
        const text = elTicket ? elTicket.textContent.trim() : '';
        if (!text || text === '-') return;

        const t = I18N[currentLang];
        const btnTxt = document.getElementById('txt-btn-copy-ticket');
        const onSuccess = () => {
            if (btnTxt) {
                btnTxt.textContent = t.btnCopied;
                setTimeout(() => {
                    btnTxt.textContent = I18N[currentLang].btnCopy;
                }, 2000);
            }
            showToast('📋', t.toastCopiedTitle, t.toastCopiedDesc);
        };

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(onSuccess).catch(() => fallbackCopy(text, onSuccess));
        } else {
            fallbackCopy(text, onSuccess);
        }
    };

    function fallbackCopy(text, cb) {
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.setAttribute('readonly', '');
        ta.style.position = 'absolute';
        ta.style.left = '-9999px';
        document.body.appendChild(ta);
        ta.select();
        try {
            document.execCommand('copy');
            if (cb) cb();
        } catch (e) {}
        document.body.removeChild(ta);
    }

    window.trackCreatedTicket = function () {
        if (!lastCreatedTicketId) return;
        window.switchComplaintTab('track');
        const input = document.getElementById('track-ticket-input');
        if (input) input.value = formatDisplayTicket(lastCreatedTicketId);
        renderTrackTicketResult(lastCreatedTicketId);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    window.resetToComplaintForm = function () {
        const form = document.getElementById('form-client-complaint');
        if (form) form.reset();
        window.removeStandaloneComplaintFile();
        lastCreatedTicketId = null;
        prefillFromActiveSession();
        window.switchComplaintTab('submit');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    /**
     * Tab 2: Render Daftar Singkat "Tiket Terbaru Anda" (Maks 3 Tiket)
     */
    function renderRecentTickets() {
        const container = document.getElementById('recent-tickets-container');
        if (!container) return;

        const t = I18N[currentLang];
        let list = [];
        try {
            list = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');
        } catch (e) {}

        const recent = [...list].reverse().slice(0, 3);
        if (recent.length === 0) {
            container.innerHTML = `<span class="text-xs text-slate-400 italic">${escapeHtml(t.noRecentTickets)}</span>`;
            return;
        }

        container.innerHTML = recent.map(item => {
            const dispId = formatDisplayTicket(item.ticket_id);
            const titleText = item.title || item.subject || item.category || '';
            const safeRawId = escapeHtml(item.ticket_id);
            return `
                <button type="button"
                        data-ticket-id="${safeRawId}"
                        onclick="selectRecentTicket(this.dataset.ticketId)"
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-[#003B73] border border-slate-200 hover:border-blue-200 text-xs font-bold transition-all cursor-pointer max-w-full">
                    <span class="font-black text-[#003B73]">${escapeHtml(dispId)}</span>
                    <span class="text-slate-400">·</span>
                    <span class="truncate max-w-[180px]">${escapeHtml(titleText)}</span>
                </button>
            `;
        }).join('');
    }

    window.selectRecentTicket = function (ticketId) {
        const input = document.getElementById('track-ticket-input');
        if (input) input.value = formatDisplayTicket(ticketId);
        renderTrackTicketResult(ticketId);
    };

    window.handleTrackSearch = function (event) {
        if (event) event.preventDefault();
        const input = document.getElementById('track-ticket-input');
        const query = input ? input.value.trim() : '';
        const t = I18N[currentLang];

        if (!query) {
            showToast('⚠️', t.toastEmptySearchTitle, t.toastEmptySearchDesc);
            return;
        }

        renderTrackTicketResult(query);
    };

    /**
     * Render Kartu Hasil Pelacakan dengan Stepper 3 Tahap & Tindak Lanjut Tim LRS
     */
    function renderTrackTicketResult(rawQuery) {
        const resultContainer = document.getElementById('track-result-container');
        const notFoundBox = document.getElementById('track-not-found');
        if (!resultContainer || !notFoundBox) return;

        const targetKey = normalizeTicketKey(rawQuery);
        let list = [];
        try {
            list = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');
        } catch (e) {}

        const ticket = list.find(item => normalizeTicketKey(item.ticket_id) === targetKey);
        if (!ticket) {
            activeTrackedTicketId = null;
            resultContainer.classList.add('hidden');
            notFoundBox.classList.remove('hidden');
            return;
        }

        activeTrackedTicketId = ticket.ticket_id;
        notFoundBox.classList.add('hidden');
        resultContainer.classList.remove('hidden');

        const t = I18N[currentLang];
        const statusNorm = String(ticket.status || 'Open').toLowerCase();
        const stepIdx = statusNorm === 'resolved' ? 3 : (statusNorm === 'in progress' ? 2 : 1);

        const stepItems = [
            { num: 1, label: t.stepOpen, active: stepIdx >= 1 },
            { num: 2, label: t.stepProgress, active: stepIdx >= 2 },
            { num: 3, label: t.stepResolved, active: stepIdx >= 3 }
        ];

        const urgMeta = getUrgencyMeta(ticket.urgency);
        const createdStr = ticket.created_at
            ? new Date(ticket.created_at).toLocaleString(currentLang === 'en' ? 'en-US' : 'id-ID', {
                day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
            })
            : '-';

        const rootCause = ticket.root_cause || ticket.rca || '';
        const actionTaken = ticket.action_taken || ticket.capa || '';
        const hasFollowup = Boolean(String(rootCause).trim() || String(actionTaken).trim());

        const stepperHtml = stepItems.map((st, idx) => {
            const circleCls = st.active
                ? (stepIdx === 3 && st.num === 3 ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-[#003B73] text-white border-[#003B73]')
                : 'bg-white text-slate-400 border-slate-300';
            const textCls = st.active ? 'text-slate-900 font-extrabold' : 'text-slate-400 font-semibold';
            const connector = idx < stepItems.length - 1
                ? `<div class="hidden sm:block flex-1 h-1 mx-2 rounded-full ${stepIdx > st.num ? 'bg-[#003B73]' : 'bg-slate-200'}"></div>`
                : '';

            return `
                <div class="flex items-center ${idx < stepItems.length - 1 ? 'sm:flex-1' : ''}">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-black shrink-0 ${circleCls}">
                            ${st.active && stepIdx > st.num ? '✓' : st.num}
                        </span>
                        <span class="text-xs leading-tight ${textCls}">${escapeHtml(st.label)}</span>
                    </div>
                    ${connector}
                </div>
            `;
        }).join('');

        const followupBlockHtml = hasFollowup
            ? `
                <div class="p-4 sm:p-5 rounded-2xl bg-blue-50/70 border border-blue-200/90 space-y-3">
                    <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-[#003B73]">
                        <span>🛠️</span>
                        <span>${escapeHtml(t.followupHeader)}</span>
                    </div>
                    ${rootCause ? `
                        <div>
                            <span class="block text-[11px] font-bold text-slate-500 uppercase">${escapeHtml(t.rootCauseLbl)}</span>
                            <p class="text-xs sm:text-sm text-slate-800 mt-0.5 leading-relaxed">${escapeHtml(rootCause)}</p>
                        </div>
                    ` : ''}
                    ${actionTaken ? `
                        <div>
                            <span class="block text-[11px] font-bold text-slate-500 uppercase">${escapeHtml(t.actionTakenLbl)}</span>
                            <p class="text-xs sm:text-sm text-slate-800 mt-0.5 leading-relaxed">${escapeHtml(actionTaken)}</p>
                        </div>
                    ` : ''}
                </div>
            `
            : `
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-xs text-slate-500 flex items-center gap-2.5">
                    <span>⏳</span>
                    <span>${escapeHtml(t.pendingFollowup)}</span>
                </div>
            `;

        resultContainer.innerHTML = `
            <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-100">
                <div>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">${escapeHtml(t.detailTicketLbl)}</span>
                    <span class="text-xl font-black text-[#003B73]">${escapeHtml(formatDisplayTicket(ticket.ticket_id))}</span>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-extrabold border ${urgMeta.cls}">
                    ${escapeHtml(t.detailUrgencyLbl)}: ${escapeHtml(urgMeta.label)}
                </span>
            </div>

            <!-- Stepper 3 Tahap Visual -->
            <div class="p-4 rounded-2xl bg-slate-50/90 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                ${stepperHtml}
            </div>

            <!-- Judul & Uraian -->
            <div class="space-y-2">
                <h3 class="text-base sm:text-lg font-extrabold text-slate-900">
                    ${escapeHtml(ticket.title || ticket.subject || '-')}
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line bg-slate-50/70 p-4 rounded-xl border border-slate-200/70">
                    ${escapeHtml(ticket.description || '-')}
                </p>
            </div>

            <!-- Detail Metadata -->
            <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70">
                    <dt class="text-[10px] font-bold uppercase text-slate-400">${escapeHtml(t.detailReporterLbl)}</dt>
                    <dd class="font-bold text-slate-800 mt-0.5 truncate">${escapeHtml(ticket.reporter_name || ticket.client_name || '-')}</dd>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70">
                    <dt class="text-[10px] font-bold uppercase text-slate-400">${escapeHtml(t.detailProjectLbl)}</dt>
                    <dd class="font-bold text-slate-800 mt-0.5 truncate">${escapeHtml(ticket.project_name || '-')}</dd>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70">
                    <dt class="text-[10px] font-bold uppercase text-slate-400">${escapeHtml(t.detailCategoryLbl)}</dt>
                    <dd class="font-bold text-slate-800 mt-0.5 truncate">${escapeHtml(getCategoryLabel(ticket.category))}</dd>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70">
                    <dt class="text-[10px] font-bold uppercase text-slate-400">${escapeHtml(t.detailDateLbl)}</dt>
                    <dd class="font-bold text-slate-800 mt-0.5 truncate">${escapeHtml(createdStr)}</dd>
                </div>
            </dl>

            <div class="text-xs text-slate-500 flex items-center gap-2">
                <span>📎</span>
                <span class="font-semibold">${escapeHtml(t.detailAttachmentLbl)}:</span>
                <span class="text-slate-700 font-bold">${escapeHtml(ticket.attachment_name || t.noAttachment)}</span>
            </div>

            ${followupBlockHtml}
        `;
    }

    document.addEventListener('DOMContentLoaded', function () {
        let savedLang = 'id';
        try {
            savedLang = localStorage.getItem('lrs_client_lang') || 'id';
        } catch (e) {}

        prefillFromActiveSession();
        setupComplaintForm();
        window.setLanguage(savedLang);

        const params = new URLSearchParams(window.location.search);
        const tabParam = params.get('tab');
        const ticketParam = params.get('tiket');
        if (tabParam === 'track' || ticketParam) {
            window.switchComplaintTab('track');
            if (ticketParam) {
                const input = document.getElementById('track-ticket-input');
                if (input) input.value = formatDisplayTicket(ticketParam);
                renderTrackTicketResult(ticketParam);
            }
        }
    });
})();
