/**
 * PT Len Railway Systems — Client Voice & GRC Portal
 * Script Interaktivitas Formulir Survei Progresif (2-Langkah), Sistem Bilingual (ID/EN) & LocalStorage
 *
 * Mengacu pada PRD: Integrated Client Voice & GRC Portal PT Len Railway Systems (V4.0)
 * 1. Standardisasi Nama: PT Len Railway Systems
 * 2. Bilingual Instan: Indonesia & English tanpa reload halaman
 * 3. 3 Menu Utama Layanan (Survei, Keluhan & Lacak Tiket, Masukan)
 * 4. Dual-Metric: Kepentingan (1-5) & Kepuasan (1-5)
 * 5. Keterangan Wajib Kondisional jika Kepuasan < 4 (1, 2, atau 3)
 * 6. Tampilan Sukses ("Terima Kasih") Multi-Aksi & Komitmen SMAP ISO 37001 (link ke https://lrs.co.id/about-us/smap)
 */

(function () {
    'use strict';

    // Jalankan logika Portal Survei hanya pada halaman yang memiliki #panel-survei
    if (!document.getElementById('panel-survei')) return;

    // Cegah inisialisasi ganda jika aset dimuat via Vite dan static script tag
    if (window.__lrs_portal_initialized) return;
    window.__lrs_portal_initialized = true;

    const MIN_REASON_LENGTH = 10;

    // Metadata Kategori Sesuai PRD.md (3 Kategori, 21 Indikator Total) - Bilingual
    const CATEGORY_MAP = {
        'A': {
            code: 'A',
            name_id: 'Layanan & Dukungan',
            name_en: 'Services & Support',
            total: 5,
            desc_id: 'Evaluasi keramahan, komunikasi, pemahaman kebutuhan pelanggan, kualitas solusi, dan kejelasan teknis personel PT Len Railway Systems.',
            desc_en: 'Evaluation of friendliness, communication, understanding of customer needs, solution quality, and technical clarity of PT Len Railway Systems personnel.'
        },
        'B': {
            code: 'B',
            name_id: 'Pelaksanaan Proyek',
            name_en: 'Project Execution',
            total: 11,
            desc_id: 'Evaluasi ketepatan waktu, koordinasi tim, kompetensi teknis, kerapihan kerja, penanganan kendala, dan budaya K3L.',
            desc_en: 'Evaluation of timeliness, team coordination, technical competence, workmanship, issue handling, and HSE culture.'
        },
        'C': {
            code: 'C',
            name_id: 'Masa Pemeliharaan',
            name_en: 'Maintenance Period',
            total: 5,
            desc_id: 'Evaluasi keandalan fungsi produk, kemudahan operasi & pemeliharaan, respon after sales, dan durabilitas produk.',
            desc_en: 'Evaluation of product functional reliability, ease of operation & maintenance, after-sales responsiveness, and product durability.'
        }
    };

    // Kamus Terjemahan Lengkap (Bilingual Dictionary)
    const I18N = {
        id: {
            portalTitle: 'Portal Survei Kepuasan Klien · PT Len Railway Systems',
            portalBadge: 'Portal Klien',
            portalSubtitle: 'Survei Kepuasan, Masukan & Pelaporan Kendala',
            adminPage: 'Admin Page',

            // Tab Nav
            tabSurveiTitle: 'Isi Survei Kepuasan',
            tabSurveiDesc: 'Evaluasi 3 Kategori Layanan',
            tabKeluhanTitle: 'Sampaikan Keluhan',
            tabKeluhanDesc: 'Pelaporan & Lacak Tiket',
            tabMasukanTitle: 'Masukan & Saran Umum',
            tabMasukanDesc: 'Apresiasi & Evaluasi Strategis',

            // Header Banner
            headerTitle: 'Survei Kepuasan Pelanggan',
            headerSubtitle: 'Setiap evaluasi dan masukan Anda sangat berharga bagi peningkatan keandalan sistem perkeretaapian dan mutu layanan kami.',

            // Step 1
            step1Title: 'Pilih Kategori Survei Kepuasan',
            step1Subtitle: 'Silakan pilih kategori layanan yang ingin Anda evaluasi terlebih dahulu. Indikator pertanyaan penilaian ganda akan ditampilkan secara bertahap.',
            catActionText: 'Pilih Kategori Ini',
            privacyNote: 'Data evaluasi Anda dilindungi dan digunakan secara internal oleh PT Len Railway Systems untuk peningkatan keandalan produk dan layanan berkelanjutan.',

            // Step 2
            btnChangeCat: 'Ganti Kategori',
            step2Badge: 'Langkah 2:',
            progressLabel: 'Progress:',
            filledSuffix: 'Terisi',
            activeCatInstruction: 'Mohon berikan penilaian Kepentingan dan Kepuasan pada indikator di bawah ini:',

            // Indicators
            impLabel: 'Tingkat Kepentingan',
            impSublabel: '1: Sangat Tidak Penting → 5: Sangat Penting',
            satLabel: 'Tingkat Kepuasan',
            satSublabel: '1: Sangat Tidak Puas → 5: Sangat Puas',
            scaleLeft: 'Kurang Puas / Tidak Penting',
            scaleMid: 'Netral',
            scaleRight: 'Sangat Puas / Sangat Penting',

            // Reason Block
            reasonReq: '*Keterangan Wajib Diisi',
            reasonHint: '(nilai kepuasan rendah · minimal 10 karakter)',
            reasonPlaceholder: 'Mohon berikan penjelasan singkat mengenai kendala yang menyebabkan penilaian rendah ini (min. 10 karakter)...',
            reasonError: 'Penjelasan kendala wajib diisi minimal 10 karakter untuk nilai kepuasan rendah.',
            reasonMinSuffix: 'karakter',
            cardErrorHint: 'Mohon pilih penilaian Kepentingan dan Kepuasan (skala 1-5).',

            // General notes
            saranTitle: 'Saran / Inputan Umum',
            saranSubtitle: 'Sampaikan apresiasi, saran perbaikan, atau catatan strategis Anda kepada PT Len Railway Systems (opsional).',
            optionalPill: 'Opsional',
            saranPlaceholder: 'Tuliskan saran umum atau evaluasi bebas Anda kepada PT Len Railway Systems di sini...',

            // File upload
            uploadTitle: 'Upload File / Dokumen Pendukung (Dummy)',
            uploadSubtitle: 'Lampirkan dokumen, berita acara, atau foto pendukung evaluasi (opsional).',
            uploadDropzoneText: 'Klik di sini untuk memilih file lampiran',
            uploadDropzoneHint: 'Mendukung PDF, PNG, JPG, DOCX (Maks. 10 MB · Nama file akan dicatat)',
            btnRemoveFile: 'Hapus File',
            fileReadyText: 'Siap dilampirkan',

            // Submit
            btnSubmitSurvey: 'Kirim Survei',

            // Step 3 (Thank You)
            successBadge: '✓ Survei Berhasil Terkirim',
            successTitle: 'Terima Kasih!',
            successMsgPrefix: 'Penilaian Anda untuk kategori',
            successMsgSuffix: 'telah berhasil disimpan dalam sistem kami.',
            refLabel: 'Nomor Referensi:',
            copyRef: 'Salin',
            copiedRef: 'Tersalin',
            summaryTitle: 'Ringkasan Data Survei',
            lblRespId: 'ID RESPONDEN',
            lblProjName: 'PROYEK',
            lblCatSummary: 'KATEGORI',
            lblAttachName: 'FILE LAMPIRAN',
            noAttachment: 'Tidak ada lampiran',

            // SMAP Card
            smapTitle: 'Komitmen Sistem Manajemen Anti Penyuapan (SMAP) ISO 37001:2016',
            smapDesc: 'PT Len Railway Systems berkomitmen menerapkan prinsip 4 NOs (No Bribery, No Kickback, No Gift, No Luxurious Hospitality) serta menjamin seluruh proses evaluasi dan kerja sama bebas dari segala bentuk gratifikasi dan benturan kepentingan.',
            smapLinkText: 'Pelajari Kebijakan SMAP',

            // Success Actions
            btnFillOther: 'Isi Kategori Lain',
            btnGotoComplaint: 'Sampaikan Keluhan',
            btnBackHome: 'Kembali ke Beranda',

            // Complaint
            complaintBadge: 'Incident & Complaint Center',
            complaintTitle: 'Buat Keluhan & Laporan Kendala',
            complaintSubtitle: 'Setiap kendala yang Anda sampaikan akan langsung diterbitkan nomor tiket dan ditangani oleh unit terkait PT Len Railway Systems.',
            lblCompCategory: 'Kategori Kendala',
            lblCompSubject: 'Judul Kendala / Keluhan',
            lblCompUrgency: 'Tingkat Urgensi Penanganan',
            lblCompDesc: 'Deskripsi Rinci Kendala',
            lblCompUpload: 'Lampiran Berkas / Foto (Opsional)',
            compUploadText: 'Klik untuk melampirkan dokumen / gambar kendala',
            compUploadHint: 'Format JPG, PNG, PDF (Maks. 10 MB — nama file akan dicatat)',
            btnSubmitComplaint: 'Submit Keluhan & Terbitkan Tiket',
            trackHeader: '🔍 Pelacakan Status Tiket Keluhan',
            btnTrack: 'Lacak Tiket',
            trackPlaceholder: 'Contoh: #TKT-1234',

            // Feedback
            feedbackBadge: 'Voice of Customer',
            feedbackTitle: 'Sampaikan Masukan & Saran',
            feedbackSubtitle: 'Tanggapan, apresiasi, atau masukan strategis Anda menjadi bahan pertimbangan penting bagi peningkatan layanan berkelanjutan.',
            lblFeedbackRating: 'Penilaian Keseluruhan Layanan PT Len Railway Systems',
            lblFeedbackCategory: 'Kategori Masukan',
            lblFeedbackSubject: 'Subjek Masukan',
            lblFeedbackContent: 'Detail Masukan & Apresiasi',
            btnSubmitFeedback: 'Kirim Masukan Anda',

            // Footer
            footerWbsBtn: 'Laporkan Pelanggaran (Report)',

            // General Mode (?mode=general)
            generalModePill: 'Survei Umum (Non-Kontrak)',
            generalModeTitle: 'Identitas Proyek / Instansi (Opsional)',
            generalModeBadge: 'Akses Terbuka Tanpa Nomor Kontrak',
            generalModeDesc: 'Anda mengakses formulir melalui Tautan Survei Umum. Silakan isi nama proyek atau instansi Anda (opsional) agar evaluasi dapat diidentifikasi oleh tim PT Len Railway Systems.',
            lblGeneralProject: 'Nama Proyek / Pekerjaan (Opsional)',
            phGeneralProject: 'Contoh: Proyek Persinyalan Lintas Selatan',
            lblGeneralInstitution: 'Nama Instansi / Perusahaan (Opsional)',
            phGeneralInstitution: 'Contoh: PT Kereta Api Indonesia',
            lblGeneralRespondent: 'Nama Responden / PIC (Opsional)',
            phGeneralRespondent: 'Contoh: Budi Santoso'
        },
        en: {
            portalTitle: 'Client Satisfaction Survey Portal · PT Len Railway Systems',
            portalBadge: 'Client Portal',
            portalSubtitle: 'Satisfaction Survey, Feedback & Incident Reporting',
            adminPage: 'Admin Page',

            // Tab Nav
            tabSurveiTitle: 'Satisfaction Survey',
            tabSurveiDesc: 'Evaluate 3 Service Categories',
            tabKeluhanTitle: 'Submit a Complaint',
            tabKeluhanDesc: 'Reporting & Ticket Tracking',
            tabMasukanTitle: 'General Feedback',
            tabMasukanDesc: 'Appreciation & Strategic Ideas',

            // Header Banner
            headerTitle: 'Customer Satisfaction Survey',
            headerSubtitle: 'Every evaluation and feedback is invaluable for enhancing railway system reliability and our service quality.',

            // Step 1
            step1Title: 'Select Survey Category',
            step1Subtitle: 'Please select the service category you wish to evaluate first. Dual-metric questions will be displayed progressively.',
            catActionText: 'Select This Category',
            privacyNote: 'Your evaluation data is protected and used internally by PT Len Railway Systems for continuous product and service reliability enhancement.',

            // Step 2
            btnChangeCat: 'Change Category',
            step2Badge: 'Step 2:',
            progressLabel: 'Progress:',
            filledSuffix: 'Completed',
            activeCatInstruction: 'Please rate your Importance and Satisfaction for the following indicators:',

            // Indicators
            impLabel: 'Importance Level',
            impSublabel: '1: Very Unimportant → 5: Very Important',
            satLabel: 'Satisfaction Level',
            satSublabel: '1: Very Dissatisfied → 5: Very Satisfied',
            scaleLeft: 'Dissatisfied / Unimportant',
            scaleMid: 'Neutral',
            scaleRight: 'Very Satisfied / Very Important',

            // Reason Block
            reasonReq: '*Required Explanation',
            reasonHint: '(low satisfaction score · min. 10 characters)',
            reasonPlaceholder: 'Please provide a brief explanation of the issue causing this low rating (min. 10 characters)...',
            reasonError: 'Explanation must be at least 10 characters for a low satisfaction score.',
            reasonMinSuffix: 'chars',
            cardErrorHint: 'Please select both Importance and Satisfaction ratings (scale 1-5).',

            // General notes
            saranTitle: 'General Feedback / Suggestions',
            saranSubtitle: 'Provide your appreciation, constructive ideas, or strategic notes to PT Len Railway Systems (optional).',
            optionalPill: 'Optional',
            saranPlaceholder: 'Write your general feedback or overall evaluation to PT Len Railway Systems here...',

            // File upload
            uploadTitle: 'Upload Supporting Documents / Files (Dummy)',
            uploadSubtitle: 'Attach documents, minutes of meeting, or photos supporting your evaluation (optional).',
            uploadDropzoneText: 'Click here to select an attachment file',
            uploadDropzoneHint: 'Supports PDF, PNG, JPG, DOCX (Max. 10 MB · File name will be recorded)',
            btnRemoveFile: 'Remove File',
            fileReadyText: 'Ready to attach',

            // Submit
            btnSubmitSurvey: 'Submit Survey',

            // Step 3 (Thank You)
            successBadge: '✓ Survey Successfully Submitted',
            successTitle: 'Thank You!',
            successMsgPrefix: 'Your evaluation for category',
            successMsgSuffix: 'has been successfully recorded in our system.',
            refLabel: 'Reference Number:',
            copyRef: 'Copy',
            copiedRef: 'Copied',
            summaryTitle: 'Survey Summary',
            lblRespId: 'RESPONDENT ID',
            lblProjName: 'PROJECT',
            lblCatSummary: 'CATEGORY',
            lblAttachName: 'ATTACHED FILE',
            noAttachment: 'No attachment',

            // SMAP Card
            smapTitle: 'Anti-Bribery Management System (ABMS) ISO 37001:2016 Commitment',
            smapDesc: 'PT Len Railway Systems is committed to upholding the 4 NOs principles (No Bribery, No Kickback, No Gift, No Luxurious Hospitality) and ensuring all evaluation and business collaboration remain free from bribery and conflict of interest.',
            smapLinkText: 'Learn More About SMAP Policy',

            // Success Actions
            btnFillOther: 'Fill Another Category',
            btnGotoComplaint: 'Submit a Complaint',
            btnBackHome: 'Back to Home',

            // Complaint
            complaintBadge: 'Incident & Complaint Center',
            complaintTitle: 'Submit Complaint & Incident Report',
            complaintSubtitle: 'Every issue you report will be immediately assigned a ticket number and addressed by PT Len Railway Systems.',
            lblCompCategory: 'Issue Category',
            lblCompSubject: 'Complaint / Issue Subject',
            lblCompUrgency: 'Handling Urgency Level',
            lblCompDesc: 'Detailed Issue Description',
            lblCompUpload: 'Supporting Document / Photo (Optional)',
            compUploadText: 'Click to attach problem document or picture',
            compUploadHint: 'Format JPG, PNG, PDF (Max. 10 MB — file name will be recorded)',
            btnSubmitComplaint: 'Submit Complaint & Issue Ticket',
            trackHeader: '🔍 Complaint Ticket Status Tracking',
            btnTrack: 'Track Ticket',
            trackPlaceholder: 'e.g. #TKT-1234',

            // Feedback
            feedbackBadge: 'Voice of Customer',
            feedbackTitle: 'Submit Feedback & Suggestions',
            feedbackSubtitle: 'Your feedback, appreciation, or strategic advice is essential for our continuous service improvement.',
            lblFeedbackRating: 'Overall Service Rating of PT Len Railway Systems',
            lblFeedbackCategory: 'Feedback Category',
            lblFeedbackSubject: 'Feedback Subject',
            lblFeedbackContent: 'Detailed Feedback & Appreciation',
            btnSubmitFeedback: 'Submit Your Feedback',

            // Footer
            footerWbsBtn: 'Report a Violation (WBS)',

            // General Mode (?mode=general)
            generalModePill: 'General Survey (Non-Contract)',
            generalModeTitle: 'Project / Institution Identity (Optional)',
            generalModeBadge: 'Open Access Without Contract Number',
            generalModeDesc: 'You are accessing the form via the General Survey Link. Please enter your project or institution name (optional) so PT Len Railway Systems can identify your evaluation.',
            lblGeneralProject: 'Project / Work Name (Optional)',
            phGeneralProject: 'e.g. Southern Line Signalling Project',
            lblGeneralInstitution: 'Institution / Company Name (Optional)',
            phGeneralInstitution: 'e.g. PT Kereta Api Indonesia',
            lblGeneralRespondent: 'Respondent / PIC Name (Optional)',
            phGeneralRespondent: 'e.g. Budi Santoso'
        }
    };

    // State Formulir Survei
    let currentLang = 'id';
    let activeCategoryId = null;
    let currentAttachmentName = null;
    let currentComplaintAttachmentName = null;
    const surveiState = {};
    let toastTimer = null;

    /**
     * Helper Escape HTML untuk Mencegah Serangan XSS
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
                    reason: null,
                    category: category,
                    categoryCode: categoryCode,
                    text: text
                };
            }
        });

        updateCategoryStatusBadges();
        updateHeaderCounters();
    }

    /**
     * BILINGUAL: Set Bahasa Aktif (ID / EN) secara Instan Tanpa Reload
     */
    window.setLanguage = function (lang) {
        currentLang = (lang === 'en') ? 'en' : 'id';

        try {
            localStorage.setItem('lrs_client_lang', currentLang);
        } catch (e) {}

        // 1. Update State Toggle Switcher Bendera ID | EN
        const pill = document.getElementById('lang-switcher-pill');
        const knob = document.getElementById('lang-flag-knob');
        const flagId = document.getElementById('lang-flag-id');
        const flagEn = document.getElementById('lang-flag-en');
        const btnId = document.getElementById('btn-lang-id');
        const btnEn = document.getElementById('btn-lang-en');

        if (pill) {
            pill.dataset.activeLang = currentLang;
        }

        if (knob && flagId && flagEn) {
            if (currentLang === 'en') {
                knob.classList.remove('translate-x-10');
                knob.classList.add('translate-x-0');
                flagId.classList.add('hidden');
                flagEn.classList.remove('hidden');
            } else {
                knob.classList.remove('translate-x-0');
                knob.classList.add('translate-x-10');
                flagEn.classList.add('hidden');
                flagId.classList.remove('hidden');
            }
        }

        if (btnId && btnEn) {
            if (currentLang === 'en') {
                btnId.setAttribute('aria-pressed', 'false');
                btnEn.setAttribute('aria-pressed', 'true');
                btnId.classList.add('opacity-0');
                btnEn.classList.remove('opacity-0');
            } else {
                btnId.setAttribute('aria-pressed', 'true');
                btnEn.setAttribute('aria-pressed', 'false');
                btnId.classList.remove('opacity-0');
                btnEn.classList.add('opacity-0');
            }
        }

        const t = I18N[currentLang];

        // 2. Update Header, Brand & Title
        document.title = t.portalTitle;
        const elPortalBadge = document.getElementById('txt-portal-badge');
        const elPortalSubtitle = document.getElementById('txt-portal-subtitle');
        const elHeaderComplaint = document.getElementById('txt-btn-header-complaint');
        if (elPortalBadge) elPortalBadge.textContent = t.portalBadge;
        if (elPortalSubtitle) elPortalSubtitle.textContent = t.portalSubtitle;
        if (elHeaderComplaint) elHeaderComplaint.textContent = t.btnGotoComplaint;

        // 3. Update Nav Tab Labels
        const tabSurvei = document.getElementById('tab-btn-survei');
        if (tabSurvei) {
            const titleEl = tabSurvei.querySelector('.tab-label-title');
            const descEl = tabSurvei.querySelector('.tab-label-desc');
            if (titleEl) titleEl.textContent = t.tabSurveiTitle;
            if (descEl) descEl.textContent = t.tabSurveiDesc;
        }

        const tabKeluhan = document.getElementById('tab-btn-keluhan');
        if (tabKeluhan) {
            const titleEl = tabKeluhan.querySelector('.tab-label-title');
            const descEl = tabKeluhan.querySelector('.tab-label-desc');
            if (titleEl) titleEl.textContent = t.tabKeluhanTitle;
            if (descEl) descEl.textContent = t.tabKeluhanDesc;
        }

        const tabMasukan = document.getElementById('tab-btn-masukan');
        if (tabMasukan) {
            const titleEl = tabMasukan.querySelector('.tab-label-title');
            const descEl = tabMasukan.querySelector('.tab-label-desc');
            if (titleEl) titleEl.textContent = t.tabMasukanTitle;
            if (descEl) descEl.textContent = t.tabMasukanDesc;
        }

        // 4. Header Banner
        const hdrTitle = document.getElementById('client-header-title');
        const hdrSub = document.getElementById('client-header-subtitle');
        if (hdrTitle) hdrTitle.textContent = t.headerTitle;
        if (hdrSub) hdrSub.textContent = t.headerSubtitle;

        // 5. Langkah 1 (Pilih Kategori)
        const step1Title = document.getElementById('txt-step-1-title');
        const step1Sub = document.getElementById('txt-step-1-subtitle');
        const privNote = document.getElementById('txt-privacy-note');
        if (step1Title) step1Title.textContent = t.step1Title;
        if (step1Sub) step1Sub.textContent = t.step1Subtitle;
        if (privNote) privNote.textContent = t.privacyNote;

        // 3 Kartu Kategori
        document.querySelectorAll('.category-select-card').forEach(card => {
            const titleEl = card.querySelector('.cat-card-title');
            const descEl = card.querySelector('.cat-card-desc');
            const actionEl = card.querySelector('.cat-card-action-text');

            if (titleEl) {
                titleEl.textContent = (currentLang === 'en')
                    ? (titleEl.dataset.titleEn || titleEl.dataset.titleId)
                    : titleEl.dataset.titleId;
            }
            if (descEl) {
                descEl.textContent = (currentLang === 'en')
                    ? (descEl.dataset.descEn || descEl.dataset.descId)
                    : descEl.dataset.descId;
            }
            if (actionEl) actionEl.textContent = t.catActionText;
        });

        // 6. Langkah 2 (Header Aktif & Form Indikator)
        const btnChange = document.getElementById('txt-btn-change-cat');
        const step2Badge = document.getElementById('txt-step-2-badge');
        const progLabel = document.getElementById('txt-progress-label');
        if (btnChange) btnChange.textContent = t.btnChangeCat;
        if (step2Badge) step2Badge.textContent = t.step2Badge;
        if (progLabel) progLabel.textContent = t.progressLabel;

        if (activeCategoryId) {
            const cat = CATEGORY_MAP[activeCategoryId];
            const catName = (currentLang === 'en') ? cat.name_en : cat.name_id;
            const catDesc = (currentLang === 'en') ? cat.desc_en : cat.desc_id;
            const badgeEl = document.getElementById('active-category-badge');
            const titleEl = document.getElementById('active-category-title');
            const descEl = document.getElementById('active-category-desc');
            if (badgeEl) badgeEl.textContent = (currentLang === 'en') ? `Category ${cat.code}: ${catName}` : `Kategori ${cat.code}: ${catName}`;
            if (titleEl) titleEl.textContent = (currentLang === 'en') ? `Category ${cat.code}. ${catName}` : `Kategori ${cat.code}. ${catName}`;
            if (descEl) descEl.textContent = catDesc;
        }

        // Teks Setiap Kartu Indikator
        document.querySelectorAll('.q-card').forEach(card => {
            const id = card.dataset.id;
            const textEl = card.querySelector('.indicator-text');
            if (textEl) {
                textEl.textContent = (currentLang === 'en')
                    ? (card.dataset.textEn || card.dataset.textId)
                    : card.dataset.textId;
            }

            const impLabelText = card.querySelector('.label-imp-text');
            const impSublabel = card.querySelector('.sublabel-imp');
            const satLabelText = card.querySelector('.label-sat-text');
            const satSublabel = card.querySelector('.sublabel-sat');
            const scaleLeft = card.querySelector('.scale-label-left');
            const scaleMid = card.querySelector('.scale-label-mid');
            const scaleRight = card.querySelector('.scale-label-right');
            const reasonReq = card.querySelector('.reason-label-req');
            const reasonHint = card.querySelector('.reason-label-hint');
            const reasonTextarea = card.querySelector('.reason-textarea');
            const reasonErr = card.querySelector('.reason-err-text');
            const errorHint = card.querySelector('.error-hint-text');

            if (impLabelText) impLabelText.textContent = t.impLabel;
            if (impSublabel) impSublabel.textContent = t.impSublabel;
            if (satLabelText) satLabelText.textContent = t.satLabel;
            if (satSublabel) satSublabel.textContent = t.satSublabel;
            if (scaleLeft) scaleLeft.textContent = t.scaleLeft;
            if (scaleMid) scaleMid.textContent = t.scaleMid;
            if (scaleRight) scaleRight.textContent = t.scaleRight;
            if (reasonReq) reasonReq.textContent = t.reasonReq;
            if (reasonHint) reasonHint.textContent = t.reasonHint;
            if (reasonTextarea) reasonTextarea.placeholder = t.reasonPlaceholder;
            if (reasonErr) reasonErr.textContent = t.reasonError;
            if (errorHint) errorHint.textContent = t.cardErrorHint;
            if (card.dataset.id) {
                updateReasonCounter(card.dataset.id, reasonTextarea ? reasonTextarea.value : '');
            }
        });

        // Saran / Inputan Umum
        const saranTitle = document.getElementById('saran-umum-title');
        const saranSub = document.getElementById('saran-umum-subtitle');
        const saranPill = document.getElementById('saran-umum-pill');
        const saranTextarea = document.getElementById('saran-umum');
        if (saranTitle) saranTitle.textContent = t.saranTitle;
        if (saranSub) saranSub.textContent = t.saranSubtitle;
        if (saranPill) saranPill.textContent = t.optionalPill;
        if (saranTextarea) saranTextarea.placeholder = t.saranPlaceholder;

        // Upload File Dummy
        const upTitle = document.getElementById('upload-title');
        const upSub = document.getElementById('upload-subtitle');
        const upPill = document.getElementById('upload-pill');
        const upDropText = document.getElementById('upload-dropzone-text');
        const upDropHint = document.getElementById('upload-dropzone-hint');
        const btnRemove = document.getElementById('btn-remove-file');
        if (upTitle) upTitle.textContent = t.uploadTitle;
        if (upSub) upSub.textContent = t.uploadSubtitle;
        if (upPill) upPill.textContent = t.optionalPill;
        if (upDropText) upDropText.textContent = t.uploadDropzoneText;
        if (upDropHint) upDropHint.textContent = t.uploadDropzoneHint;
        if (btnRemove) btnRemove.textContent = t.btnRemoveFile;

        // Tombol Submit
        const btnSubSurvey = document.getElementById('txt-btn-submit-survey');
        if (btnSubSurvey) btnSubSurvey.textContent = t.btnSubmitSurvey;

        // Layar Sukses
        const succBadge = document.getElementById('txt-success-badge');
        const succTitle = document.getElementById('txt-success-title');
        const refLabel = document.getElementById('txt-ref-label');
        const txtCopyRef = document.getElementById('txt-copy-ref');
        const summTitle = document.getElementById('txt-summary-title');
        const lblResp = document.getElementById('lbl-resp-id');
        const lblProj = document.getElementById('lbl-proj-name');
        const lblCat = document.getElementById('lbl-cat-summary');
        const lblAttach = document.getElementById('lbl-attach-name');
        const btnFillOther = document.getElementById('txt-btn-fill-other');
        const btnGotoComp = document.getElementById('txt-btn-goto-complaint');
        const btnBackHome = document.getElementById('txt-btn-back-home');
        if (succBadge) succBadge.textContent = t.successBadge;
        if (succTitle) succTitle.textContent = t.successTitle;
        if (refLabel) refLabel.textContent = t.refLabel;
        if (txtCopyRef) txtCopyRef.textContent = t.copyRef;
        if (summTitle) summTitle.textContent = t.summaryTitle;
        if (lblResp) lblResp.textContent = t.lblRespId;
        if (lblProj) lblProj.textContent = t.lblProjName;
        if (lblCat) lblCat.textContent = t.lblCatSummary;
        if (lblAttach) lblAttach.textContent = t.lblAttachName;
        if (btnFillOther) btnFillOther.textContent = t.btnFillOther;
        if (btnGotoComp) btnGotoComp.textContent = t.btnGotoComplaint;
        if (btnBackHome) btnBackHome.textContent = t.btnBackHome;

        // SMAP Card
        const smapTitle = document.getElementById('smap-card-title');
        const smapDesc = document.getElementById('smap-card-desc');
        const smapLinkText = document.getElementById('smap-card-link-text');
        if (smapTitle) smapTitle.textContent = t.smapTitle;
        if (smapDesc) smapDesc.textContent = t.smapDesc;
        if (smapLinkText) smapLinkText.textContent = t.smapLinkText;

        // Panel Keluhan
        const compBadge = document.getElementById('txt-complaint-badge');
        const compTitle = document.getElementById('txt-complaint-title');
        const compSub = document.getElementById('txt-complaint-subtitle');
        const lblCompCategory = document.getElementById('lbl-complaint-category');
        const lblCompSubject = document.getElementById('lbl-complaint-subject');
        const lblCompUrgency = document.getElementById('lbl-complaint-urgency');
        const lblCompDesc = document.getElementById('lbl-complaint-desc');
        const lblCompUpload = document.getElementById('lbl-complaint-upload');
        const compUpText = document.getElementById('complaint-upload-text');
        const compUpHint = document.getElementById('complaint-upload-hint');
        const btnSubComp = document.getElementById('txt-btn-submit-complaint');
        const trackHdr = document.getElementById('txt-track-header');
        const btnTrack = document.getElementById('txt-btn-track');
        const trackInput = document.getElementById('track-input');

        if (compBadge) compBadge.textContent = t.complaintBadge;
        if (compTitle) compTitle.textContent = t.complaintTitle;
        if (compSub) compSub.textContent = t.complaintSubtitle;
        if (lblCompCategory) lblCompCategory.innerHTML = `${t.lblCompCategory}<span class="text-red-500 ml-1">*</span>`;
        if (lblCompSubject) lblCompSubject.innerHTML = `${t.lblCompSubject}<span class="text-red-500 ml-1">*</span>`;
        if (lblCompUrgency) lblCompUrgency.innerHTML = `${t.lblCompUrgency}<span class="text-red-500 ml-1">*</span>`;
        if (lblCompDesc) lblCompDesc.innerHTML = `${t.lblCompDesc}<span class="text-red-500 ml-1">*</span>`;
        if (lblCompUpload) lblCompUpload.textContent = t.lblCompUpload;
        if (compUpText) compUpText.textContent = t.compUploadText;
        if (compUpHint) compUpHint.textContent = t.compUploadHint;
        if (btnSubComp) btnSubComp.textContent = t.btnSubmitComplaint;
        if (trackHdr) trackHdr.textContent = t.trackHeader;
        if (btnTrack) btnTrack.textContent = t.btnTrack;
        if (trackInput) trackInput.placeholder = t.trackPlaceholder;

        // Panel Masukan
        const fbBadge = document.getElementById('txt-feedback-badge');
        const fbTitle = document.getElementById('txt-feedback-title');
        const fbSub = document.getElementById('txt-feedback-subtitle');
        const lblFbRating = document.getElementById('lbl-feedback-rating');
        const lblFbCat = document.getElementById('lbl-feedback-category');
        const lblFbSub = document.getElementById('lbl-feedback-subject');
        const lblFbCont = document.getElementById('lbl-feedback-content');
        const btnSubFb = document.getElementById('txt-btn-submit-feedback');

        if (fbBadge) fbBadge.textContent = t.feedbackBadge;
        if (fbTitle) fbTitle.textContent = t.feedbackTitle;
        if (fbSub) fbSub.textContent = t.feedbackSubtitle;
        if (lblFbRating) lblFbRating.innerHTML = `${t.lblFeedbackRating}<span class="text-red-500 ml-1">*</span>`;
        if (lblFbCat) lblFbCat.innerHTML = `${t.lblFeedbackCategory}<span class="text-red-500 ml-1">*</span>`;
        if (lblFbSub) lblFbSub.innerHTML = `${t.lblFeedbackSubject}<span class="text-red-500 ml-1">*</span>`;
        if (lblFbCont) lblFbCont.innerHTML = `${t.lblFeedbackContent}<span class="text-red-500 ml-1">*</span>`;
        if (btnSubFb) btnSubFb.textContent = t.btnSubmitFeedback;

        // Footer WBS Button
        const ftrWbs = document.getElementById('footer-wbs-btn-text');
        if (ftrWbs) ftrWbs.textContent = t.footerWbsBtn;

        // Mode Survei Umum (?mode=general)
        const genPill = document.getElementById('txt-general-mode-pill');
        const genTitle = document.getElementById('txt-general-mode-title');
        const genBadge = document.getElementById('txt-general-mode-badge');
        const genDesc = document.getElementById('txt-general-mode-desc');
        const lblGenProj = document.getElementById('lbl-general-project');
        const inpGenProj = document.getElementById('general-project-name');
        const lblGenInst = document.getElementById('lbl-general-institution');
        const inpGenInst = document.getElementById('general-client-institution');
        const lblGenResp = document.getElementById('lbl-general-respondent');
        const inpGenResp = document.getElementById('general-respondent-name');

        if (genPill) genPill.textContent = t.generalModePill;
        if (genTitle) genTitle.textContent = t.generalModeTitle;
        if (genBadge) genBadge.textContent = t.generalModeBadge;
        if (genDesc) genDesc.textContent = t.generalModeDesc;
        if (lblGenProj) lblGenProj.textContent = t.lblGeneralProject;
        if (inpGenProj) inpGenProj.placeholder = t.phGeneralProject;
        if (lblGenInst) lblGenInst.textContent = t.lblGeneralInstitution;
        if (inpGenInst) inpGenInst.placeholder = t.phGeneralInstitution;
        if (lblGenResp) lblGenResp.textContent = t.lblGeneralRespondent;
        if (inpGenResp) inpGenResp.placeholder = t.phGeneralRespondent;
    };

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
        const catName = (currentLang === 'en') ? catInfo.name_en : catInfo.name_id;
        const catDesc = (currentLang === 'en') ? catInfo.desc_en : catInfo.desc_id;

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

        if (badgeEl) badgeEl.textContent = (currentLang === 'en') ? `Category ${catInfo.code}: ${catName}` : `Kategori ${catInfo.code}: ${catName}`;
        if (titleEl) titleEl.textContent = (currentLang === 'en') ? `Category ${catInfo.code}. ${catName}` : `Kategori ${catInfo.code}. ${catName}`;
        if (descEl) descEl.textContent = catDesc;

        // Tampilkan HANYA grup indikator milik kategori yang dipilih
        document.querySelectorAll('.category-indicators-group').forEach(group => {
            group.classList.add('hidden');
        });

        const activeGroup = document.getElementById(`category-group-${catCode}`);
        if (activeGroup) {
            activeGroup.classList.remove('hidden');
        }

        updateCategoryProgress();

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

        const container = document.getElementById(`${type}-${id}`);
        if (container) {
            const buttons = container.querySelectorAll('.score-btn');
            buttons.forEach(btn => {
                const btnVal = parseInt(btn.dataset.val, 10);
                btn.classList.remove('active-imp', 'active-sat', 'active-sat-low', 'active-sat-high');
                if (btnVal === val) {
                    if (type === 'imp') {
                        btn.classList.add('active-imp');
                    } else {
                        // Kepuasan: 1, 2, 3 = Amber/Warm; 4, 5 = Emerald Green
                        if (val < 4) {
                            btn.classList.add('active-sat', 'active-sat-low');
                        } else {
                            btn.classList.add('active-sat', 'active-sat-high');
                        }
                    }
                }
            });
        }

        // KETERANGAN WAJIB KONDISIONAL (Jika Kepuasan < 4: 1, 2, 3)
        if (type === 'sat') {
            const reasonBlock = document.getElementById(`reason-block-${id}`);
            const reasonTextarea = document.getElementById(`reason-${id}`);
            const reasonErr = document.getElementById(`reason-err-${id}`);

            if (val < 4) {
                // Munculkan input alasan jika kepuasan bernilai rendah
                if (reasonBlock) {
                    reasonBlock.classList.remove('hidden');
                }
                const currentReasonVal = reasonTextarea ? reasonTextarea.value : (surveiState[id].reason || '');
                surveiState[id].reason = currentReasonVal.trim();
                updateReasonCounter(id, currentReasonVal);
            } else {
                // Sembunyikan jika kepuasan >= 4
                if (reasonBlock) {
                    reasonBlock.classList.add('hidden');
                }
                if (reasonErr) {
                    reasonErr.classList.add('hidden');
                }
                if (reasonTextarea) {
                    reasonTextarea.classList.remove('error');
                }
                surveiState[id].reason = null;
                updateReasonCounter(id, '');
            }
        }

        // Hapus highlight error jika indikator sudah terisi lengkap
        if (surveiState[id].imp > 0 && surveiState[id].sat > 0) {
            const card = document.getElementById(`card-${id}`);
            const errHint = document.getElementById(`err-${id}`);
            if (card) card.classList.remove('error');
            if (errHint) errHint.classList.add('hidden');
        }

        updateCategoryProgress();
    };

    /**
     * Perbarui indikator jumlah karakter pada input keterangan kendala (min. 10 karakter)
     */
    function updateReasonCounter(id, val) {
        const countEl = document.getElementById(`reason-count-${id}`);
        if (!countEl) return;

        const len = (val || '').trim().length;
        const suffix = I18N[currentLang]?.reasonMinSuffix || 'karakter';
        countEl.textContent = `${len} / min. ${MIN_REASON_LENGTH} ${suffix}`;

        countEl.classList.remove('text-slate-400', 'text-rose-500', 'text-emerald-600');
        if (len >= MIN_REASON_LENGTH) {
            countEl.classList.add('text-emerald-600');
        } else if (len > 0) {
            countEl.classList.add('text-rose-500');
        } else {
            countEl.classList.add('text-slate-400');
        }
    }

    /**
     * Handler Input Penjelasan Kendala (Keterangan Nilai < 4, Minimal 10 Karakter)
     */
    window.handleReasonInput = function (id, val) {
        if (!surveiState[id]) return;
        const trimmed = val.trim();
        surveiState[id].reason = trimmed;

        updateReasonCounter(id, val);

        const reasonTextarea = document.getElementById(`reason-${id}`);
        const reasonErr = document.getElementById(`reason-err-${id}`);

        if (trimmed.length >= MIN_REASON_LENGTH) {
            if (reasonTextarea) reasonTextarea.classList.remove('error');
            if (reasonErr) reasonErr.classList.add('hidden');
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
                // Jika sat < 4, cek apakah reason sudah diisi minimal 10 karakter
                if (state.sat < 4) {
                    if (state.reason && state.reason.trim().length >= MIN_REASON_LENGTH) {
                        fullyFilledCards++;
                    }
                } else {
                    fullyFilledCards++;
                }
            }
        });

        const percent = totalCards > 0 ? Math.round((fullyFilledCards / totalCards) * 100) : 0;
        const elBar = document.getElementById('active-category-progress-bar');
        const elCounter = document.getElementById('active-category-counter');

        const suffix = I18N[currentLang].filledSuffix;
        if (elBar) elBar.style.width = `${percent}%`;
        if (elCounter) elCounter.textContent = `${fullyFilledCards} / ${totalCards} ${suffix}`;
    }

    /**
     * Handler Input File Lampiran Survei (Dummy)
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
            if (sizeEl) sizeEl.textContent = `${fileSizeFormatted} · ${I18N[currentLang].fileReadyText}`;

            if (dropzone) dropzone.classList.add('hidden');
            if (preview) {
                preview.classList.remove('hidden');
                preview.classList.add('flex');
            }
        }
    };

    /**
     * Handler Hapus File Lampiran Survei
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
     * Validasi Formulir Survei untuk Kategori yang Aktif Termasuk Keterangan Wajib Nilai < 4
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

            const reasonTextarea = c.querySelector('.reason-textarea');
            const reasonErr = c.querySelector('.reason-err');
            if (reasonTextarea) reasonTextarea.classList.remove('error');
            if (reasonErr) reasonErr.classList.add('hidden');
        });

        activeCards.forEach(card => {
            const id = card.dataset.id;
            const item = surveiState[id];

            // 1. Cek Apakah Kepentingan (1-5) dan Kepuasan (1-5) sudah dipilih
            if (!item || item.imp === 0 || item.sat === 0) {
                card.classList.add('error');
                const err = document.getElementById(`err-${id}`);
                if (err) err.classList.remove('hidden');

                if (!firstIncompleteCard) {
                    firstIncompleteCard = card;
                }
                isValid = false;
                return;
            }

            // 2. Keterangan Wajib jika Kepuasan < 4 (1, 2, atau 3) — minimal 10 karakter
            if (item.sat < 4) {
                const reasonTextarea = document.getElementById(`reason-${id}`);
                const reasonVal = (reasonTextarea ? reasonTextarea.value : (item.reason || '')).trim();
                item.reason = reasonVal;

                if (reasonVal.length < MIN_REASON_LENGTH) {
                    card.classList.add('error');
                    const reasonBlock = document.getElementById(`reason-block-${id}`);
                    const reasonErr = document.getElementById(`reason-err-${id}`);

                    if (reasonBlock) reasonBlock.classList.remove('hidden');
                    if (reasonTextarea) {
                        reasonTextarea.classList.add('error');
                    }
                    if (reasonErr) reasonErr.classList.remove('hidden');

                    if (!firstIncompleteCard) {
                        firstIncompleteCard = card;
                        if (reasonTextarea) reasonTextarea.focus();
                    }
                    isValid = false;
                }
            }
        });

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
                window.showToast('⚠️', (currentLang === 'en' ? 'Select Category' : 'Pilih Kategori'), (currentLang === 'en' ? 'Please select a survey category first.' : 'Silakan pilih kategori survei terlebih dahulu.'), '#DC2626');
                window.backToCategorySelection();
                return;
            }

            if (!validateActiveCategory()) {
                window.showToast('⚠️', (currentLang === 'en' ? 'Incomplete Form' : 'Formulir Belum Lengkap'), (currentLang === 'en' ? `Please complete all indicators and provide at least ${MIN_REASON_LENGTH} characters for low score explanations.` : `Mohon lengkapi seluruh indikator serta keterangan wajib minimal ${MIN_REASON_LENGTH} karakter untuk nilai kepuasan rendah.`), '#DC2626');
                return;
            }

            // Ambil sesi aktif pengguna dari localStorage (Zero-Login session)
            const params = new URLSearchParams(window.location.search);
            const isGeneralQuery = params.get('mode') === 'general';
            let respondentId = 'PIC-001';
            let projectName = 'Proyek Dummy';
            let projectCode = '';
            let surveySource = isGeneralQuery ? 'general' : 'contract';
            let clientName = '';
            let clientRole = '';

            try {
                const rawSession = localStorage.getItem('lrs_active_session');
                if (rawSession) {
                    try {
                        const parsed = JSON.parse(rawSession);
                        if (typeof parsed === 'object' && parsed !== null) {
                            respondentId = parsed.respondent_id || parsed.pic_id || parsed.contract_number || parsed.token || 'PIC-001';
                            projectName = parsed.project_name || parsed.projectName || parsed.contract_number || 'Proyek Dummy';
                            projectCode = parsed.project_code || parsed.contract_number || '';
                            if (parsed.survey_source === 'general' || parsed.project_code === 'GENERAL') {
                                surveySource = 'general';
                            }
                            clientName = parsed.client_name || '';
                            clientRole = parsed.client_institution || parsed.client_role || '';
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

            // Override identitas mandiri jika mengakses dalam mode survei umum (?mode=general)
            if (surveySource === 'general') {
                projectCode = 'GENERAL';
                const genProjVal = document.getElementById('general-project-name')?.value.trim() || '';
                const genInstVal = document.getElementById('general-client-institution')?.value.trim() || '';
                const genRespVal = document.getElementById('general-respondent-name')?.value.trim() || '';

                if (genProjVal) {
                    projectName = genProjVal;
                } else if (genInstVal) {
                    projectName = `Survei Umum - ${genInstVal}`;
                } else {
                    projectName = 'Survei Umum (Non-Kontrak)';
                }

                if (genRespVal) clientName = genRespVal;
                if (genInstVal) clientRole = genInstVal;
                if (!respondentId || respondentId === 'PIC-001') {
                    respondentId = 'PIC-GEN-' + String(Math.floor(100 + Math.random() * 900));
                }
            }

            // Kumpulkan jawaban HANYA dari kategori yang dipilih, termasuk properti 'reason'
            const activeCards = document.querySelectorAll(`#category-group-${activeCategoryId} .q-card`);
            const answers = Array.from(activeCards).map(card => {
                const id = card.dataset.id;
                const st = surveiState[id];
                return {
                    question_id: id,
                    kepentingan: st.imp,
                    kepuasan: st.sat,
                    reason: (st.sat < 4 && st.reason) ? st.reason : null
                };
            });

            const catInfo = CATEGORY_MAP[activeCategoryId] || { name_id: `Kategori ${activeCategoryId}`, name_en: `Category ${activeCategoryId}` };
            const categoryName = (currentLang === 'en') ? catInfo.name_en : catInfo.name_id;

            const elSaran = document.getElementById('saran-umum');
            const refNo = `SRV-${new Date().toISOString().slice(0, 10).replace(/-/g, '')}-${String(Math.floor(1000 + Math.random() * 9000))}`;
            let personnelName = surveySource === 'general' ? 'Layanan Umum LRS' : 'Ir. Dwi Suryanto, S.T.';

            try {
                const sess = JSON.parse(localStorage.getItem('lrs_active_session') || '{}');
                if (sess.accompanying_personnel) personnelName = sess.accompanying_personnel;
                else if (sess.personnel_name) personnelName = sess.personnel_name;
            } catch (e) {}

            const surveyPayload = {
                reference_no: refNo,
                respondent_id: respondentId,
                client_name: clientName || respondentId,
                client_role: clientRole || (surveySource === 'general' ? 'Responden Umum' : 'Responden Lapangan'),
                survey_source: surveySource,
                project_code: projectCode || (surveySource === 'general' ? 'GENERAL' : ''),
                project_name: projectName,
                category_id: activeCategoryId,
                category_name: categoryName,
                language: currentLang,
                accompanying_personnel: personnelName,
                personnel_wa_status: 'Terkirim',
                attachment_name: currentAttachmentName || null,
                general_notes: elSaran ? elSaran.value.trim() || null : null,
                submitted_at: new Date().toISOString(),
                is_read: false,
                answers: answers
            };

            // Simpan ke localStorage pada key 'lrs_survey_data'
            try {
                const existing = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');
                existing.push(surveyPayload);
                localStorage.setItem('lrs_survey_data', JSON.stringify(existing));

                // Simulasi notifikasi
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

                // Hitung skor rata-rata kepuasan dari jawaban survei (skala 1-5)
                let totalScore = 0;
                let countScore = 0;
                if (Array.isArray(answers) && answers.length > 0) {
                    answers.forEach(a => {
                        if (typeof a.kepuasan === 'number' && !isNaN(a.kepuasan)) {
                            totalScore += a.kepuasan;
                            countScore++;
                        }
                    });
                }
                const averageScore = countScore > 0 ? Math.round((totalScore / countScore) * 10) / 10 : 5;

                // Event-Driven: Kirim CustomEvent 'lrs:survey-submitted'
                window.dispatchEvent(new CustomEvent('lrs:survey-submitted', {
                    detail: {
                        referenceNumber: refNo,
                        respondentName: respondentId,
                        organization: projectName,
                        serviceCategory: `${activeCategoryId}. ${categoryName}`,
                        score: averageScore,
                        submittedAt: surveyPayload.submitted_at,
                        attachmentName: surveyPayload.attachment_name
                    }
                }));

                showSurveySuccessView(surveyPayload);

                window.showToast('🎉', (currentLang === 'en' ? 'Survey Submitted!' : 'Survei Berhasil!'), (currentLang === 'en' ? 'Survey data successfully saved to local storage.' : 'Data survei telah tersimpan di browser storage.'), '#059669');
            } catch (err) {
                console.error('Gagal menyimpan ke localStorage:', err);
                window.showToast('❌', 'Error', 'Gagal menyimpan data ke browser storage.', '#DC2626');
            }
        });
    }

    /**
     * Tampilkan Layar Sukses Pengisian Survei
     */
    function showSurveySuccessView(payload) {
        if (!payload) return;

        // Pastikan CustomEvent 'lrs:survey-submitted' terkirim jika dipanggil langsung
        let totalScore = 0;
        let countScore = 0;
        if (Array.isArray(payload.answers) && payload.answers.length > 0) {
            payload.answers.forEach(a => {
                if (typeof a.kepuasan === 'number' && !isNaN(a.kepuasan)) {
                    totalScore += a.kepuasan;
                    countScore++;
                }
            });
        }
        const avgScore = countScore > 0 ? Math.round((totalScore / countScore) * 10) / 10 : (payload.score || 5);

        window.dispatchEvent(new CustomEvent('lrs:survey-submitted', {
            detail: {
                referenceNumber: payload.reference_no,
                respondentName: payload.respondent_id,
                organization: payload.project_name,
                serviceCategory: `${payload.category_id ? payload.category_id + '. ' : ''}${payload.category_name}`,
                score: avgScore,
                submittedAt: payload.submitted_at,
                attachmentName: payload.attachment_name
            }
        }));
        const step1 = document.getElementById('survey-step-1-category');
        const step2 = document.getElementById('survey-step-2-form');
        const successContainer = document.getElementById('survey-success-container');

        if (step1) step1.classList.add('hidden');
        if (step2) step2.classList.add('hidden');
        if (successContainer) successContainer.classList.remove('hidden');

        const elCatName = document.getElementById('success-category-name');
        const elCatSummary = document.getElementById('success-category-summary');
        const elRespId = document.getElementById('success-respondent-id');
        const elProjName = document.getElementById('success-project-name');
        const elAttach = document.getElementById('success-attachment-name');
        const elTime = document.getElementById('success-submitted-time');
        const elRefNo = document.getElementById('success-reference-no');
        const txtCopy = document.getElementById('txt-copy-ref');

        if (elCatName) elCatName.textContent = payload.category_name;
        if (elCatSummary) elCatSummary.textContent = `${payload.category_id}. ${payload.category_name} (${payload.answers.length} Indikator)`;
        if (elRespId) elRespId.textContent = payload.respondent_id;
        if (elProjName) elProjName.textContent = payload.project_name;
        if (elAttach) elAttach.textContent = payload.attachment_name || I18N[currentLang].noAttachment;
        if (elRefNo) elRefNo.textContent = payload.reference_no;

        if (txtCopy) {
            txtCopy.textContent = (currentLang === 'en') ? I18N.en.copyRef : I18N.id.copyRef;
            txtCopy.classList.remove('text-emerald-600');
        }

        if (elTime) {
            elTime.textContent = new Date(payload.submitted_at).toLocaleTimeString((currentLang === 'en' ? 'en-US' : 'id-ID'), {
                hour: '2-digit', minute: '2-digit'
            }) + (currentLang === 'en' ? ' (Local)' : ' WIB');
        }

        // Gulir halus ke posisi paling atas agar layar Terima Kasih tampak utuh dan tidak terpotong navbar
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    /**
     * Salin Nomor Referensi ke Clipboard dengan Umpan Balik Teks
     */
    window.copyReferenceNo = function () {
        const elRefNo = document.getElementById('success-reference-no');
        const refText = elRefNo ? elRefNo.textContent.trim() : '';
        if (!refText || refText === '-') return;

        const txtCopy = document.getElementById('txt-copy-ref');
        const defaultText = (currentLang === 'en') ? I18N.en.copyRef : I18N.id.copyRef;
        const feedbackText = (currentLang === 'en') ? I18N.en.copiedRef : I18N.id.copiedRef;

        const doFeedback = () => {
            if (txtCopy) {
                txtCopy.textContent = feedbackText;
                txtCopy.classList.add('text-emerald-600');
            }
            setTimeout(() => {
                if (txtCopy) {
                    txtCopy.textContent = (currentLang === 'en') ? I18N.en.copyRef : I18N.id.copyRef;
                    txtCopy.classList.remove('text-emerald-600');
                }
            }, 2000);
        };

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(refText).then(doFeedback).catch(() => {
                fallbackCopy(refText, doFeedback);
            });
        } else {
            fallbackCopy(refText, doFeedback);
        }
    };

    /**
     * Fallback penyalinan teks clipboard untuk browser versi lama
     */
    function fallbackCopy(text, cb) {
        const el = document.createElement('textarea');
        el.value = text;
        el.setAttribute('readonly', '');
        el.style.position = 'absolute';
        el.style.left = '-9999px';
        document.body.appendChild(el);
        el.select();
        try {
            document.execCommand('copy');
            if (cb) cb();
        } catch (e) {
            console.warn('Fallback copy failed', e);
        }
        document.body.removeChild(el);
    }

    /**
     * Aksi Tombol Sukses: "Isi Kategori Lain"
     */
    window.actionFillOtherCategory = function () {
        const step1 = document.getElementById('survey-step-1-category');
        const step2 = document.getElementById('survey-step-2-form');
        const successContainer = document.getElementById('survey-success-container');

        if (successContainer) successContainer.classList.add('hidden');
        if (step2) step2.classList.add('hidden');
        if (step1) step1.classList.remove('hidden');

        window.removeSurveyFile();

        const elSaran = document.getElementById('saran-umum');
        if (elSaran) elSaran.value = '';

        updateCategoryStatusBadges();

        if (step1) {
            step1.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    /**
     * Aksi Tombol Sukses: "Sampaikan Keluhan" (Pintasan Langsung ke Halaman /keluhan)
     */
    window.actionGotoComplaint = function () {
        window.location.href = '/keluhan';
    };

    /**
     * Aksi Tombol Sukses: "Kembali ke Beranda"
     */
    window.actionBackToHome = function () {
        window.actionFillOtherCategory();
        window.switchTab('survei');
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
                window.showToast('⚠️', (currentLang === 'en' ? 'Incomplete Data' : 'Data Belum Lengkap'), (currentLang === 'en' ? 'Please complete all fields before submitting feedback.' : 'Mohon lengkapi seluruh kolom formulir masukan.'), '#DC2626');
                return;
            }

            const feedbackPayload = {
                id: 'FB-' + Date.now().toString().slice(-6),
                rating: rating,
                category: kategori,
                subject: subjek,
                content: isi,
                language: currentLang,
                submitted_at: new Date().toISOString()
            };

            try {
                const existing = JSON.parse(localStorage.getItem('lrs_feedback_data') || '[]');
                existing.push(feedbackPayload);
                localStorage.setItem('lrs_feedback_data', JSON.stringify(existing));

                form.reset();
                window.setFeedbackStar(0);
                window.showToast('💬', (currentLang === 'en' ? 'Feedback Submitted!' : 'Masukan Terkirim!'), (currentLang === 'en' ? 'Thank you for your valuable appreciation and feedback.' : 'Terima kasih atas apresiasi dan masukan berharga Anda.'), '#059669');
            } catch (err) {
                console.error(err);
            }
        });
    }

    /**
     * File Attachment untuk Form Keluhan (Dummy)
     */
    window.handleComplaintFileChange = function (event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const file = input.files[0];
            currentComplaintAttachmentName = file.name;

            const nameEl = document.getElementById('complaint-attached-filename');
            const dropzone = document.getElementById('complaint-upload-dropzone');
            const preview = document.getElementById('complaint-file-preview');

            if (nameEl) nameEl.textContent = file.name;
            if (dropzone) dropzone.classList.add('hidden');
            if (preview) {
                preview.classList.remove('hidden');
                preview.classList.add('flex');
            }
        }
    };

    window.removeComplaintFile = function (event) {
        if (event) event.stopPropagation();

        const input = document.getElementById('complaint-attachment-input');
        if (input) input.value = '';

        currentComplaintAttachmentName = null;

        const dropzone = document.getElementById('complaint-upload-dropzone');
        const preview = document.getElementById('complaint-file-preview');

        if (preview) {
            preview.classList.add('hidden');
            preview.classList.remove('flex');
        }
        if (dropzone) dropzone.classList.remove('hidden');
    };

    /**
     * Submit Form Buat Keluhan (Format Otomatis #TKT-XXXX)
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
                window.showToast('⚠️', (currentLang === 'en' ? 'Incomplete Data' : 'Data Belum Lengkap'), (currentLang === 'en' ? 'Please complete all fields before submitting complaint.' : 'Mohon lengkapi semua kolom keluhan sebelum mengirim.'), '#DC2626');
                return;
            }

            // Terbitkan Nomor Tiket Otomatis format #TKT-XXXX
            const ticketId = '#TKT-' + String(Math.floor(1000 + Math.random() * 9000));
            const params = new URLSearchParams(window.location.search);
            let sess = {};
            try {
                sess = JSON.parse(localStorage.getItem('lrs_active_session') || '{}');
            } catch (errSess) {}

            const isGeneral = params.get('mode') === 'general' || sess.survey_source === 'general' || sess.project_code === 'GENERAL';
            const genProjVal = document.getElementById('general-project-name')?.value.trim() || '';
            const genInstVal = document.getElementById('general-client-institution')?.value.trim() || '';
            const genRespVal = document.getElementById('general-respondent-name')?.value.trim() || '';

            const complaintPayload = {
                ticket_id: ticketId,
                client_name: genRespVal || sess.client_name || sess.respondent_id || 'Klien Portal',
                project_name: genProjVal || sess.project_name || (genInstVal ? `Survei Umum - ${genInstVal}` : (isGeneral ? 'Survei Umum (Non-Kontrak)' : 'Proyek LRS')),
                project_code: isGeneral ? 'GENERAL' : (sess.project_code || sess.contract_number || ''),
                survey_source: isGeneral ? 'general' : 'contract',
                category: kategori,
                subject: subjek,
                urgency: urgencyEl.value,
                description: deskripsi,
                attachment_name: currentComplaintAttachmentName || null,
                status: 'Open',
                language: currentLang,
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
                                <div style="font-size:11px;font-weight:600;color:#94A3B8;text-transform:uppercase;letter-spacing:0.5px;">${currentLang === 'en' ? 'Your Ticket ID' : 'ID Tiket Anda'}</div>
                                <div style="font-size:22px;font-weight:800;color:#003B73;letter-spacing:1px;">${escapeHtml(ticketId)}</div>
                            </div>
                        </div>
                        <p style="font-size:12.5px;color:#475569;line-height:1.6;">
                            ${currentLang === 'en'
                                ? `Your complaint has been successfully registered with status <span class="status-badge badge-open">🔴 Open</span>. Please save this ticket number to track resolution progress.`
                                : `Keluhan Anda berhasil dicatat dengan status <span class="status-badge badge-open">🔴 Open</span>. Simpan nomor tiket ini untuk melacak tindak lanjut penyelesaian.`}
                        </p>
                    `;
                }

                form.reset();
                window.removeComplaintFile();
                window.showToast('🎫', (currentLang === 'en' ? 'Complaint Registered!' : 'Keluhan Terkirim!'), `${currentLang === 'en' ? 'Ticket ID:' : 'ID Tiket Anda:'} ${ticketId}`, '#059669');
            } catch (err) {
                console.error(err);
            }
        });
    }

    /**
     * Pelacakan Tiket Keluhan dengan Sanitasi XSS & Normalisasi Kode
     */
    window.trackTicket = function () {
        const inputEl = document.getElementById('track-input');
        const resultEl = document.getElementById('track-result');
        if (!inputEl || !resultEl) return;

        let rawInput = inputEl.value.trim().toUpperCase();

        if (!rawInput) {
            window.showToast('⚠️', (currentLang === 'en' ? 'Empty Ticket ID' : 'ID Tiket Kosong'), (currentLang === 'en' ? 'Please enter your ticket ID (e.g. #TKT-1234).' : 'Ketikkan ID tiket Anda terlebih dahulu (contoh: #TKT-1234).'), '#D97706');
            return;
        }

        // Normalisasi: jika user memasukkan "TKT-1234", cari juga "#TKT-1234"
        const data = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');
        const tiket = data.find(t => {
            const tId = String(t.ticket_id || '').toUpperCase();
            return tId === rawInput || tId === '#' + rawInput || ('#' + tId) === rawInput;
        });

        if (!tiket) {
            resultEl.className = 'ticket-result status-open visible';
            resultEl.innerHTML = `
                <p style="font-size:13px;color:#991B1B;font-weight:600;">
                    ❌ ${currentLang === 'en' ? 'Ticket ID' : 'ID Tiket'} <strong>${escapeHtml(rawInput)}</strong> ${currentLang === 'en' ? 'not found in system records.' : 'tidak ditemukan dalam catatan sistem.'}
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
        const tgl = new Date(tiket.created_at).toLocaleString((currentLang === 'en' ? 'en-US' : 'id-ID'), {
            dateStyle: 'long', timeStyle: 'short'
        });

        resultEl.className = `ticket-result ${st.cls} visible`;
        resultEl.innerHTML = `
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;flex-wrap:wrap;gap:8px;">
                <div style="font-size:16px;font-weight:800;color:#003B73;">${escapeHtml(tiket.ticket_id)}</div>
                <span class="status-badge ${st.badge}">${st.icon} ${st.label}</span>
            </div>
            <div style="font-size:13.5px;font-weight:700;color:#0F172A;margin-bottom:4px;">${escapeHtml(tiket.subject || '-')}</div>
            <div style="font-size:12.5px;color:#475569;margin-bottom:8px;line-height:1.5;">${escapeHtml(tiket.description)}</div>
            <div style="font-size:11px;color:#94A3B8;">📅 ${currentLang === 'en' ? 'Created:' : 'Dibuat:'} ${escapeHtml(tgl)} · 🏷️ ${escapeHtml(tiket.category)} · ⚡ ${escapeHtml(tiket.urgency)}</div>
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

        const activeBtns = document.querySelectorAll(`.tab-btn[data-tab="${tabId}"]`);
        activeBtns.forEach(b => {
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
     * Sinkronisasi input identitas mandiri pada Mode Survei Umum (?mode=general) ke localStorage
     */
    window.syncGeneralModeSession = function () {
        try {
            const session = JSON.parse(localStorage.getItem('lrs_active_session') || '{}');
            const projVal = document.getElementById('general-project-name')?.value.trim() || '';
            const instVal = document.getElementById('general-client-institution')?.value.trim() || '';
            const respVal = document.getElementById('general-respondent-name')?.value.trim() || '';

            session.survey_source = 'general';
            session.project_code = 'GENERAL';
            session.project_name = projVal || (instVal ? `Survei Umum - ${instVal}` : 'Survei Umum (Non-Kontrak)');
            session.client_institution = instVal;
            session.client_name = respVal;
            if (!session.respondent_id) {
                session.respondent_id = 'PIC-GEN-' + String(Math.floor(100 + Math.random() * 900));
            }
            if (!session.verified_at) {
                session.verified_at = new Date().toISOString();
            }

            localStorage.setItem('lrs_active_session', JSON.stringify(session));
        } catch (e) {
            console.error('Error sinkronisasi sesi umum:', e);
        }
    };

    /**
     * Periksa Parameter URL Query (?mode=general&proyek=...&personel=...&kategori=...&lang=...)
     */
    function checkUrlQueryParams() {
        try {
            const params = new URLSearchParams(window.location.search);
            const mode = params.get('mode');
            const proyek = params.get('proyek');
            const personel = params.get('personel');
            const kategori = params.get('kategori');
            const lang = params.get('lang');

            if (lang && (lang === 'en' || lang === 'id')) {
                window.setLanguage(lang);
            }

            if (mode === 'general') {
                const generalCard = document.getElementById('general-mode-identity-card');
                if (generalCard) generalCard.classList.remove('hidden');

                let session = {};
                try {
                    session = JSON.parse(localStorage.getItem('lrs_active_session') || '{}');
                } catch (e) {}

                const wasGeneral = session.survey_source === 'general' || session.project_code === 'GENERAL';
                session.survey_source = 'general';
                session.project_code = 'GENERAL';
                if (!session.respondent_id || !String(session.respondent_id).startsWith('PIC-GEN-')) {
                    session.respondent_id = 'PIC-GEN-' + String(Math.floor(100 + Math.random() * 900));
                }
                if (!session.project_name || !wasGeneral) {
                    session.project_name = 'Survei Umum (Non-Kontrak)';
                }
                session.verified_at = session.verified_at || new Date().toISOString();
                localStorage.setItem('lrs_active_session', JSON.stringify(session));

                const inpProj = document.getElementById('general-project-name');
                const inpInst = document.getElementById('general-client-institution');
                const inpResp = document.getElementById('general-respondent-name');
                if (inpProj && session.project_name && session.project_name !== 'Survei Umum (Non-Kontrak)') {
                    inpProj.value = session.project_name;
                }
                if (inpInst && session.client_institution) inpInst.value = session.client_institution;
                if (inpResp && session.client_name) inpResp.value = session.client_name;
            } else if (proyek || personel) {
                let session = {};
                try {
                    session = JSON.parse(localStorage.getItem('lrs_active_session') || '{}');
                } catch (e) {}

                if (proyek) {
                    session.survey_source = 'contract';
                    session.projectName = proyek;
                    session.project_name = proyek;
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

        // Inisialisasi bahasa dari localStorage jika ada
        let savedLang = 'id';
        try {
            savedLang = localStorage.getItem('lrs_client_lang') || 'id';
        } catch (e) {}
        window.setLanguage(savedLang);

        checkUrlQueryParams();
    });

})();
