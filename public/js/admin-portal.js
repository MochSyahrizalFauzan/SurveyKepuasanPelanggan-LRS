/**
 * PT Len Railway Systems — Admin Portal & Pemantauan Kepuasan Layanan
 * Logika Interaktif Vanilla JS + Chart.js + QRCode.js + LocalStorage
 *
 * 6 Section Utama (SPA Hash Navigation):
 * 1. #ringkasan       : Overview CSI, Gauge Chart, Grafik Kepentingan dan Kepuasan, 3 Kartu Kategori, 4 Metrik, Area Prioritas Perbaikan
 * 2. #data-survei     : Rekapitulasi Data Survei Klien, Filter Proyek/Kategori/Pencarian, Rincian Modal, Ekspor CSV
 * 3. #keluhan         : Daftar Keluhan Klien, Switcher Tabel & Kanban, Inline Status Change, Tindak Lanjut Modal
 * 4. #qr-tautan       : Tautan Survei Umum (Non-Kontrak), Generator QR Code Dinamis & Tautan Survei, Kartu Cetak PNG, Riwayat QR
 * 5. #proyek-personel : Kelola Master Data Proyek & Personel Pendamping LRS (CRUD Lengkap)
 * 6. #pengaturan      : Target Mutu Kepuasan, Toggle Audio Chime & Simulasi WhatsApp, Log Notifikasi, Reset Demo
 */

(function () {
    'use strict';

    // Global State & Chart Instances
    let csiChartInstance = null;
    let ipaChartInstance = null;
    let activeTicketId = null;
    let activeQrInstance = null;
    let generalQrInstance = null;

    const DEFAULT_TARGET_SCORE = 80.0;
    const TARGET_TOTAL_PIC = 12;
    const TARGET_TOTAL_PROYEK = 5;

    /**
     * Helper Escape HTML untuk Mencegah Serangan XSS pada data localStorage
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
     * Helper Sanitasi URL (Hanya izinkan http:, https:, atau relative path)
     */
    function sanitizeUrl(url) {
        if (!url) return '#';
        const trimmed = String(url).trim();
        if (/^(https?:\/\/|\/)/i.test(trimmed)) {
            return trimmed;
        }
        return '#';
    }

    /**
     * Konversi Skor Skala 1-5 ke Angka Persentase (0-100)
     */
    function toPercentNum(scoreOutOf5) {
        return (parseFloat(scoreOutOf5) / 5) * 100;
    }

    /**
     * Konversi Skor Skala 1-5 ke Format Persentase 1 Desimal (contoh: "82.4%")
     */
    function toPercentStr(scoreOutOf5) {
        return toPercentNum(scoreOutOf5).toFixed(1) + '%';
    }

    /**
     * Cek apakah item survei/keluhan termasuk Survei Umum (Non-Kontrak)
     */
    function isGeneralSurveyItem(item) {
        if (!item) return false;
        return item.survey_source === 'general' || item.project_code === 'GENERAL';
    }

    /**
     * Cek kesesuaian periode tanggal ISO terhadap filter periode
     */
    function matchesPeriodFilter(isoDate, periodFilter) {
        if (!periodFilter || periodFilter === 'all') return true;
        if (!isoDate) return periodFilter === '2026-YTD';
        const d = new Date(isoDate);
        if (Number.isNaN(d.getTime())) return true;

        const year = d.getUTCFullYear();
        const month = d.getUTCMonth() + 1;

        if (periodFilter === '2025') return year === 2025;
        if (periodFilter === '2026-YTD') return year === 2026;
        if (periodFilter === '2026-Q1') return year === 2026 && month >= 1 && month <= 3;
        if (periodFilter === '2026-Q2') return year === 2026 && month >= 4 && month <= 6;
        return true;
    }

    // Mapping Kategori Sesuai PRD.md
    const CATEGORY_NAMES = {
        'A': 'Layanan & Dukungan',
        'B': 'Pelaksanaan Proyek',
        'C': 'Masa Pemeliharaan'
    };

    // Mapping 21 Indikator Lengkap Sesuai PRD.md
    const INDICATOR_MAP = {
        'A1': { code: 'A1', cat: 'Layanan & Dukungan', title: 'Kecepatan Respon Tim Teknis' },
        'A2': { code: 'A2', cat: 'Layanan & Dukungan', title: 'Keramahan & Komunikasi Personel' },
        'A3': { code: 'A3', cat: 'Layanan & Dukungan', title: 'Kejelasan Solusi Teknis' },
        'A4': { code: 'A4', cat: 'Layanan & Dukungan', title: 'Ketersediaan Personel Saat Dibutuhkan' },
        'A5': { code: 'A5', cat: 'Layanan & Dukungan', title: 'Kemudahan Menghubungi Personel' },
        'B1': { code: 'B1', cat: 'Pelaksanaan Proyek', title: 'Ketepatan Waktu Pelaksanaan' },
        'B2': { code: 'B2', cat: 'Pelaksanaan Proyek', title: 'Kesesuaian Spesifikasi Teknis' },
        'B3': { code: 'B3', cat: 'Pelaksanaan Proyek', title: 'Kerapian Standar Pemasangan' },
        'B4': { code: 'B4', cat: 'Pelaksanaan Proyek', title: 'Kepatuhan Standar K3L Lokasi' },
        'B5': { code: 'B5', cat: 'Pelaksanaan Proyek', title: 'Kelengkapan Dokumen Pengujian' },
        'B6': { code: 'B6', cat: 'Pelaksanaan Proyek', title: 'Kemampuan Koordinasi Lapangan' },
        'B7': { code: 'B7', cat: 'Pelaksanaan Proyek', title: 'Kecepatan Penanganan Kendala Proyek' },
        'B8': { code: 'B8', cat: 'Pelaksanaan Proyek', title: 'Kualitas Uji Fungsi Commissioning' },
        'B9': { code: 'B9', cat: 'Pelaksanaan Proyek', title: 'Kebersihan & Penataan Limbah Kerja' },
        'B10': { code: 'B10', cat: 'Pelaksanaan Proyek', title: 'Disiplin Penggunaan APD' },
        'B11': { code: 'B11', cat: 'Pelaksanaan Proyek', title: 'Transparansi Laporan Progres' },
        'C1': { code: 'C1', cat: 'Masa Pemeliharaan', title: 'Kecepatan Respon Masa Garansi' },
        'C2': { code: 'C2', cat: 'Masa Pemeliharaan', title: 'Ketersediaan Suku Cadang' },
        'C3': { code: 'C3', cat: 'Masa Pemeliharaan', title: 'Ketepatan Analisis Perbaikan Gangguan' },
        'C4': { code: 'C4', cat: 'Masa Pemeliharaan', title: 'Pemeriksaan Berkala Fasilitas' },
        'C5': { code: 'C5', cat: 'Masa Pemeliharaan', title: 'Pelatihan & Alih Pengetahuan Teknisi' }
    };

    /**
     * =========================================================================
     * 1. SEED DEFAULT FALLBACK DATA (Jika LocalStorage Kosong)
     * =========================================================================
     */
    function seedFallbackData(forceReset = false) {
        // A. Pengaturan Admin
        if (forceReset || !localStorage.getItem('lrs_admin_settings')) {
            const defaultSettings = {
                target_score: DEFAULT_TARGET_SCORE,
                audio_notification_enabled: true,
                enable_sound: true,
                enable_wa_simulation: true
            };
            localStorage.setItem('lrs_admin_settings', JSON.stringify(defaultSettings));
        }

        // B. Data Proyek
        if (forceReset || !localStorage.getItem('lrs_projects_data')) {
            const defaultProjects = [
                {
                    id: 'proj-1',
                    code: 'CTR-LRS-2025-001',
                    name: 'Kereta Cepat Jakarta-Bandung',
                    client: 'PT Kereta Cepat Indonesia China (KCIC)',
                    target_pic: 12,
                    status: 'Aktif'
                },
                {
                    id: 'proj-2',
                    code: 'CTR-LRS-2025-014',
                    name: 'LRT Jakarta Fase 1B (Velodrome-Manggarai)',
                    client: 'PT Jakarta Propertindo (Jakpro)',
                    target_pic: 8,
                    status: 'Aktif'
                },
                {
                    id: 'proj-3',
                    code: 'CTR-LRS-2024-089',
                    name: 'Modernisasi Gardu Traksi Manggarai',
                    client: 'Balai Teknik Perkeretaapian Wilayah Jakarta-Banten',
                    target_pic: 6,
                    status: 'Aktif'
                },
                {
                    id: 'proj-4',
                    code: 'CTR-LRS-2024-052',
                    name: 'Sistem Persinyalan Elektrik Solo-Semarang',
                    client: 'Ditjen Perkeretaapian Kemenhub',
                    target_pic: 10,
                    status: 'Selesai'
                },
                {
                    id: 'proj-5',
                    code: 'CTR-LRS-2023-118',
                    name: 'Depo Lokomotif Cipinang',
                    client: 'PT Kereta Api Indonesia (Persero)',
                    target_pic: 5,
                    status: 'Selesai'
                }
            ];
            localStorage.setItem('lrs_projects_data', JSON.stringify(defaultProjects));
        }

        // C. Data Personel Pendamping LRS
        if (forceReset || !localStorage.getItem('lrs_personnel_data')) {
            const defaultPersonnel = [
                {
                    id: 'pers-1',
                    name: 'Ir. Dwi Suryanto, S.T.',
                    division: 'Divisi Rekayasa & Sistem Persinyalan',
                    whatsapp: '081234567801',
                    email: 'dwi.suryanto@lrs.co.id'
                },
                {
                    id: 'pers-2',
                    name: 'Annisa Rahmadani, M.T.',
                    division: 'Divisi Jaminan Kualitas & Layanan',
                    whatsapp: '081234567802',
                    email: 'annisa.r@lrs.co.id'
                },
                {
                    id: 'pers-3',
                    name: 'Fajar Nugraha, S.T.',
                    division: 'Divisi Pemeliharaan Fasilitas Operasi',
                    whatsapp: '081234567803',
                    email: 'fajar.nugraha@lrs.co.id'
                },
                {
                    id: 'pers-4',
                    name: 'Rian Kusuma, S.T.',
                    division: 'Divisi Manajemen Proyek & Konstruksi',
                    whatsapp: '081234567804',
                    email: 'rian.kusuma@lrs.co.id'
                }
            ];
            localStorage.setItem('lrs_personnel_data', JSON.stringify(defaultPersonnel));
        }

        // D. Data Survei Kepuasan
        const existingSurveys = localStorage.getItem('lrs_survey_data');
        if (forceReset || !existingSurveys || JSON.parse(existingSurveys).length === 0) {
            const seedSurveys = [
                {
                    reference_no: 'SRV-20260214-8041',
                    respondent_id: 'PIC-804192',
                    client_name: 'Ir. Bambang Trihatmojo',
                    client_role: 'Project Director',
                    project_name: 'Kereta Cepat Jakarta-Bandung',
                    category_id: 'B',
                    category_name: 'Pelaksanaan Proyek',
                    accompanying_personnel: 'Ir. Dwi Suryanto, S.T.',
                    personnel_wa_status: 'Terkirim',
                    submitted_at: '2026-02-14T09:30:00Z',
                    attachment_name: 'Dokumentasi_Uji_Coba_Halim.pdf',
                    general_notes: 'Koordinasi tim persinyalan sangat memuaskan, pertahankan respon cepat saat uji coba kecepatan tinggi.',
                    is_read: false,
                    answers: [
                        { question_id: 'B1', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B2', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'B3', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B4', category: 'Pelaksanaan Proyek', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'B5', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B6', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 3 },
                        { question_id: 'B7', category: 'Pelaksanaan Proyek', kepentingan: 4, kepuasan: 4 }
                    ]
                },
                {
                    reference_no: 'SRV-20260212-7731',
                    respondent_id: 'PIC-773105',
                    client_name: 'Drs. Hendro Wibowo',
                    client_role: 'Operations Lead',
                    project_name: 'LRT Jakarta Fase 1B (Velodrome-Manggarai)',
                    category_id: 'A',
                    category_name: 'Layanan & Dukungan',
                    accompanying_personnel: 'Annisa Rahmadani, M.T.',
                    personnel_wa_status: 'Terkirim',
                    submitted_at: '2026-02-12T14:15:00Z',
                    attachment_name: null,
                    general_notes: 'Penyampaian jadwal teknisi lapangan perlu lebih teratur agar tidak berbenturan dengan uji dinamis trainset.',
                    is_read: true,
                    answers: [
                        { question_id: 'A1', category: 'Layanan & Dukungan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'A2', category: 'Layanan & Dukungan', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'A3', category: 'Layanan & Dukungan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'A4', category: 'Layanan & Dukungan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'A5', category: 'Layanan & Dukungan', kepentingan: 4, kepuasan: 4 }
                    ]
                },
                {
                    reference_no: 'SRV-20260210-6512',
                    respondent_id: 'PIC-651299',
                    client_name: 'Ir. Ahmad Zarkasih',
                    client_role: 'Substation Manager',
                    project_name: 'Modernisasi Gardu Traksi Manggarai',
                    category_id: 'C',
                    category_name: 'Masa Pemeliharaan',
                    accompanying_personnel: 'Fajar Nugraha, S.T.',
                    personnel_wa_status: 'Terkirim',
                    submitted_at: '2026-02-10T11:00:00Z',
                    attachment_name: 'Temuan_Pentanahan_Kabel.jpg',
                    general_notes: 'Harap perhatikan penataan kabel grounding dekat kabinet kontrol.',
                    is_read: true,
                    answers: [
                        { question_id: 'C1', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'C2', category: 'Masa Pemeliharaan', kepentingan: 4, kepuasan: 3 },
                        { question_id: 'C3', category: 'Masa Pemeliharaan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'C4', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'C5', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 }
                    ]
                }
            ];
            localStorage.setItem('lrs_survey_data', JSON.stringify(seedSurveys));
        } else {
            // Normalisasi data lama
            try {
                const parsedSurveys = JSON.parse(existingSurveys);
                let isUpdated = false;
                parsedSurveys.forEach((item, idx) => {
                    if (item.is_read === undefined || item.is_read === null) {
                        item.is_read = true;
                        isUpdated = true;
                    }
                    if (!item.reference_no) {
                        item.reference_no = `SRV-202602${String(10 + idx).padStart(2, '0')}-${String(Math.floor(1000 + Math.random() * 9000))}`;
                        isUpdated = true;
                    }
                    if (!item.accompanying_personnel) {
                        item.accompanying_personnel = 'Ir. Dwi Suryanto, S.T.';
                        isUpdated = true;
                    }
                });
                if (isUpdated) {
                    localStorage.setItem('lrs_survey_data', JSON.stringify(parsedSurveys));
                }
            } catch (err) {
                console.error('Error menormalisasi data survei:', err);
            }
        }

        // E. Data Keluhan Klien
        const existingComplaints = localStorage.getItem('lrs_complaint_data');
        if (forceReset || !existingComplaints || JSON.parse(existingComplaints).length === 0) {
            const now = new Date();
            const seedComplaints = [
                {
                    ticket_id: 'TKT-2026-001',
                    client_name: 'Ir. Bambang Trihatmojo',
                    project_name: 'Kereta Cepat Jakarta-Bandung',
                    category: 'Teknis',
                    subject: 'Fluktuasi Tegangan Sensor Interlocking Stasiun Halim',
                    description: 'Terjadi delay pembacaan status wesel nomor 12 pada saat jam sibuk pagi hari. Perlu pemeriksaan modul I/O dan kalibrasi sensor.',
                    urgency: 'Kritis',
                    status: 'Open',
                    created_at: new Date(now.getTime() - 36 * 3600 * 1000).toISOString(),
                    sla_hours: 24,
                    pic: 'Tim Sinyal Halim',
                    rca: '',
                    capa: '',
                    audit_trail: [
                        { timestamp: new Date(now.getTime() - 36 * 3600 * 1000).toISOString(), status: 'Open', note: 'Tiket keluhan diterima dan tercatat di sistem.' }
                    ]
                },
                {
                    ticket_id: 'TKT-2026-002',
                    client_name: 'Drs. Hendro Wibowo',
                    project_name: 'LRT Jakarta Fase 1B (Velodrome-Manggarai)',
                    category: 'Layanan',
                    subject: 'Keterlambatan Pengiriman Berita Acara Uji Beban',
                    description: 'Dokumen berita acara pengujian beban gardu traksi Velodrome belum diterima oleh konsultan pengawas.',
                    urgency: 'Sedang',
                    status: 'In Progress',
                    created_at: new Date(now.getTime() - 14 * 3600 * 1000).toISOString(),
                    in_progress_at: new Date(now.getTime() - 6 * 3600 * 1000).toISOString(),
                    sla_hours: 24,
                    pic: 'Sekretariat Proyek LRS',
                    pic_name: 'Sekretariat Proyek LRS',
                    rca: 'Pemberkasan tanda tangan pihak ketiga tertunda karena dinas luar kota.',
                    capa: 'Menggunakan tanda tangan digital tersertifikasi untuk mempercepat validasi.',
                    audit_trail: [
                        { timestamp: new Date(now.getTime() - 14 * 3600 * 1000).toISOString(), status: 'Open', note: 'Tiket masuk ke antrean.' },
                        { timestamp: new Date(now.getTime() - 6 * 3600 * 1000).toISOString(), status: 'In Progress', note: 'Sedang proses verifikasi tanda tangan.' }
                    ]
                },
                {
                    ticket_id: 'TKT-2026-003',
                    client_name: 'Ir. Ahmad Zarkasih',
                    project_name: 'Modernisasi Gardu Traksi Manggarai',
                    category: 'K3L',
                    subject: 'Penataan Kabel Pentanahan Dekat Akses Jalan Teknisi',
                    description: 'Terdapat kabel grounding sementara yang melintang di koridor tanpa pelindung kabel, berpotensi bahaya tersandung.',
                    urgency: 'Tinggi',
                    status: 'Resolved',
                    created_at: new Date(now.getTime() - 20 * 3600 * 1000).toISOString(),
                    in_progress_at: new Date(now.getTime() - 12 * 3600 * 1000).toISOString(),
                    resolved_at: new Date(now.getTime() - 4 * 3600 * 1000).toISOString(),
                    sla_hours: 24,
                    pic: 'Koordinator K3L Lapangan',
                    pic_name: 'Koordinator K3L Lapangan',
                    rca: 'Kabel dipasang saat pengujian cepat malam hari dan belum dirapikan oleh teknisi.',
                    capa: 'Pemasangan pelindung karet lantai dan penataan ulang jalur kabel permanen.',
                    audit_trail: [
                        { timestamp: new Date(now.getTime() - 20 * 3600 * 1000).toISOString(), status: 'Open', note: 'Tiket tercatat dari temuan inspeksi bersama.' },
                        { timestamp: new Date(now.getTime() - 12 * 3600 * 1000).toISOString(), status: 'In Progress', note: 'Tim teknis menuju lokasi memasang pelindung.' },
                        { timestamp: new Date(now.getTime() - 4 * 3600 * 1000).toISOString(), status: 'Resolved', note: 'Jalur kabel rapi dan diuji aman.' }
                    ]
                }
            ];
            localStorage.setItem('lrs_complaint_data', JSON.stringify(seedComplaints));
        }

        // F. Riwayat QR Code
        if (forceReset || !localStorage.getItem('lrs_qr_history')) {
            const defaultQr = [
                {
                    id: 'qr-1',
                    title: 'Survei Berkala KCIC',
                    project_name: 'Kereta Cepat Jakarta-Bandung',
                    project_code: 'CTR-LRS-2025-001',
                    personnel_name: 'Ir. Dwi Suryanto, S.T.',
                    category_id: 'all',
                    category_name: 'Semua Lingkup',
                    url: `${window.location.origin}/?proyek=CTR-LRS-2025-001&personel=Ir.+Dwi+Suryanto%2C+S.T.&kategori=all`,
                    created_at: '2026-02-14T10:00:00Z'
                },
                {
                    id: 'qr-2',
                    title: 'Evaluasi LRT Jakarta Fase 1B',
                    project_name: 'LRT Jakarta Fase 1B (Velodrome-Manggarai)',
                    project_code: 'CTR-LRS-2025-014',
                    personnel_name: 'Annisa Rahmadani, M.T.',
                    category_id: 'B',
                    category_name: 'Pelaksanaan Proyek',
                    url: `${window.location.origin}/?proyek=CTR-LRS-2025-014&personel=Annisa+Rahmadani%2C+M.T.&kategori=B`,
                    created_at: '2026-02-12T15:30:00Z'
                }
            ];
            localStorage.setItem('lrs_qr_history', JSON.stringify(defaultQr));
        }

        // G. Riwayat Log Notifikasi
        if (forceReset || !localStorage.getItem('lrs_notification_logs')) {
            const defaultLogs = [
                {
                    id: 'notif-1',
                    recipient: 'Pelanggan (Ir. Bambang Trihatmojo)',
                    channel: 'WhatsApp',
                    type: 'Tanda Terima Survei',
                    timestamp: '2026-02-14 16:30',
                    status: 'Terkirim',
                    reference_no: 'SRV-20260214-8041'
                },
                {
                    id: 'notif-2',
                    recipient: 'Personel Pendamping (Ir. Dwi Suryanto)',
                    channel: 'WhatsApp',
                    type: 'Notifikasi Konfirmasi Klien',
                    timestamp: '2026-02-14 16:30',
                    status: 'Terkirim',
                    reference_no: 'SRV-20260214-8041'
                },
                {
                    id: 'notif-3',
                    recipient: 'Administrator Sistem LRS',
                    channel: 'Sistem Web & Email',
                    type: 'Rekap Survei Masuk',
                    timestamp: '2026-02-14 16:30',
                    status: 'Terkirim',
                    reference_no: 'SRV-20260214-8041'
                }
            ];
            localStorage.setItem('lrs_notification_logs', JSON.stringify(defaultLogs));
        }
    }

    /**
     * =========================================================================
     * 2. KONTROL AUDIO & SYNTHESIZER CHIME (WEB AUDIO API)
     * =========================================================================
     */
    let sharedAudioCtx = null;

    function getAudioContext() {
        if (!sharedAudioCtx) {
            const AudioContextClass = window.AudioContext || window.webkitAudioContext;
            if (AudioContextClass) {
                sharedAudioCtx = new AudioContextClass();
            }
        }
        return sharedAudioCtx;
    }

    // Tangani kebijakan autoplay: resume AudioContext saat interaksi pertama pengguna di portal admin
    function initAutoplayAudioUnlock() {
        const unlockAudio = () => {
            try {
                const ctx = getAudioContext();
                if (ctx && ctx.state === 'suspended') {
                    ctx.resume().catch(() => {});
                }
            } catch (_) {}
        };
        window.addEventListener('click', unlockAudio, { once: true, passive: true });
        window.addEventListener('keydown', unlockAudio, { once: true, passive: true });
    }

    /**
     * Membunyikan nada lonceng ganda (two-tone chime) yang lembut, profesional,
     * dan tidak mengagetkan murni berbasis Web Audio API (tanpa file eksternal).
     * Nada 1: G5 (~784 Hz / 783.99 Hz)
     * Nada 2: C6 (~1046.5 Hz / 1046.50 Hz)
     * Fade-out halus: exponentialRampToValueAtTime, durasi total ~0.40 detik, volume moderat ~0.18.
     *
     * @param {boolean} force - Jika true, paksa bunyikan (misal saat tombol 'Uji Suara' diklik)
     */
    window.playNotificationChime = function (force = false) {
        try {
            const settings = JSON.parse(localStorage.getItem('lrs_admin_settings') || '{}');
            const isAudioEnabled = (settings.audio_notification_enabled !== false) && (settings.enable_sound !== false);

            if (!force && !isAudioEnabled) {
                return;
            }

            const ctx = getAudioContext();
            if (!ctx) return;

            if (ctx.state === 'suspended') {
                ctx.resume().catch(() => {});
            }

            const now = ctx.currentTime;
            const totalDuration = 0.40; // durasi 0.35 - 0.45 detik

            // Oscillator (Sine wave untuk nada lembut korporat)
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();

            osc.type = 'sine';

            // Two-tone sequence: G5 (~784Hz) -> C6 (~1046.5Hz)
            osc.frequency.setValueAtTime(783.99, now);
            osc.frequency.setValueAtTime(1046.50, now + 0.12);

            // Envelope ADSR halus dengan fade-out eksponensial (volume moderat ~0.18)
            const maxVolume = 0.18;
            gain.gain.setValueAtTime(0.0001, now);
            gain.gain.exponentialRampToValueAtTime(maxVolume, now + 0.04);
            gain.gain.setValueAtTime(maxVolume * 0.9, now + 0.12);
            gain.gain.exponentialRampToValueAtTime(maxVolume * 0.95, now + 0.15);
            gain.gain.exponentialRampToValueAtTime(0.0001, now + totalDuration);

            osc.connect(gain);
            gain.connect(ctx.destination);

            osc.start(now);
            osc.stop(now + totalDuration + 0.05);

            // Cleanup node setelah selesai diputar
            setTimeout(() => {
                try {
                    osc.disconnect();
                    gain.disconnect();
                } catch (_) {}
            }, (totalDuration + 0.1) * 1000);
        } catch (err) {
            // Bungkus try/catch agar tidak pernah memunculkan uncaught error di console
            console.warn('Audio chime omitted or blocked by browser policy:', err);
        }
    };

    /**
     * Tombol Uji Suara: memicu bunyi chime langsung dan memberikan feedback toast
     */
    window.testNotificationChime = function () {
        const settings = JSON.parse(localStorage.getItem('lrs_admin_settings') || '{}');
        const isAudioEnabled = (settings.audio_notification_enabled !== false) && (settings.enable_sound !== false);

        window.playNotificationChime(true);

        if (typeof showAdminToast === 'function') {
            if (isAudioEnabled) {
                showAdminToast('Memutar nada lonceng notifikasi (Web Audio API)...');
            } else {
                showAdminToast('Uji nada diputar (Catatan: Efek suara sedang dinonaktifkan di toggle).');
            }
        }
    };

    /**
     * =========================================================================
     * 3. SPA ROUTER & SECTION SWITCHER (HASH BASED)
     * =========================================================================
     */
    const SECTION_ID_MAP = {
        'ringkasan': 'section-overview',
        'overview': 'section-overview',
        'data-survei': 'section-surveys',
        'surveys': 'section-surveys',
        'keluhan': 'section-complaints',
        'kanban': 'section-complaints',
        'complaints': 'section-complaints',
        'qr-tautan': 'section-qr',
        'qr': 'section-qr',
        'proyek-personel': 'section-projects-personnel',
        'projects': 'section-projects-personnel',
        'pengaturan': 'section-settings',
        'settings': 'section-settings'
    };

    window.switchAdminSection = function (sectionKey) {
        const targetSectionId = SECTION_ID_MAP[sectionKey] || 'section-overview';
        const canonicalKey = (function () {
            if (targetSectionId === 'section-overview') return 'ringkasan';
            if (targetSectionId === 'section-surveys') return 'data-survei';
            if (targetSectionId === 'section-complaints') return 'keluhan';
            if (targetSectionId === 'section-qr') return 'qr-tautan';
            if (targetSectionId === 'section-projects-personnel') return 'proyek-personel';
            if (targetSectionId === 'section-settings') return 'pengaturan';
            return 'ringkasan';
        })();

        // Sembunyikan semua section
        document.querySelectorAll('.admin-section').forEach(sec => {
            sec.classList.add('hidden');
        });

        // Tampilkan section aktif
        const activeSec = document.getElementById(targetSectionId);
        if (activeSec) {
            activeSec.classList.remove('hidden');
        }

        // Perbarui style tombol navigasi sidebar
        const sidebar = document.getElementById('admin-sidebar');
        const isCollapsed = sidebar && sidebar.classList.contains('is-collapsed');
        document.querySelectorAll('.admin-nav-btn').forEach(btn => {
            const tabName = btn.getAttribute('data-tab-name');
            if (tabName === canonicalKey) {
                btn.className = `admin-nav-btn w-full px-3 py-2.5 rounded-xl text-xs font-bold transition-all bg-[#003B73] text-white shadow-xs flex items-center ${isCollapsed ? 'justify-center' : 'gap-3'} cursor-pointer group text-left`;
            } else {
                btn.className = `admin-nav-btn w-full px-3 py-2.5 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 flex items-center ${isCollapsed ? 'justify-center' : 'gap-3'} cursor-pointer group text-left`;
            }
        });

        // Perbarui Dynamic Breadcrumb / Page Title di Topbar
        const SECTION_TITLES = {
            'ringkasan': { title: 'Ringkasan Kepuasan & Layanan', subtitle: 'Pemantauan Indeks Kepuasan Klien & Tindak Lanjut Layanan' },
            'data-survei': { title: 'Rekap Data Survei Kepuasan', subtitle: 'Daftar Responden, Evaluasi Indikator & Rincian Pengisian' },
            'keluhan': { title: 'Daftar & Progres Keluhan', subtitle: 'Pencatatan Tiket, Eskalasi Masalah & Rencana Tindak Lanjut' },
            'qr-tautan': { title: 'Generator QR Code & Tautan Survei', subtitle: 'Buat Tautan Langsung & QR Code Per Proyek dan Personel' },
            'proyek-personel': { title: 'Proyek & Personel Pendamping', subtitle: 'Master Data Kontrak Proyek & Personel Pendamping LRS' },
            'pengaturan': { title: 'Pengaturan & Preferensi Sistem', subtitle: 'Target Mutu Perusahaan, Nada Notifikasi & Simulasi' }
        };
        const titleInfo = SECTION_TITLES[canonicalKey] || SECTION_TITLES['ringkasan'];
        const elTitle = document.getElementById('admin-page-title');
        const elSubtitle = document.getElementById('admin-page-subtitle');
        if (elTitle) elTitle.textContent = titleInfo.title;
        if (elSubtitle) elSubtitle.textContent = titleInfo.subtitle;

        // Update URL Hash tanpa trigger reload
        if (window.location.hash !== `#${canonicalKey}`) {
            history.replaceState(null, '', `#${canonicalKey}`);
        }

        // Panggil render handler spesifik per section
        if (targetSectionId === 'section-overview') {
            populateOverviewProjectFilter();
            renderOverviewData();
        } else if (targetSectionId === 'section-surveys') {
            populateSurveysProjectFilter();
            renderSurveysTable();
        } else if (targetSectionId === 'section-complaints') {
            renderComplaintsView();
            updateComplaintTabBadge();
        } else if (targetSectionId === 'section-qr') {
            initGeneralSurveyLinkCard();
            populateQrDropdowns();
            onQrParameterChange();
            renderQrHistoryTable();
        } else if (targetSectionId === 'section-projects-personnel') {
            renderProjectsTable();
            renderPersonnelTable();
        } else if (targetSectionId === 'section-settings') {
            loadAdminSettings();
            renderNotificationLogs();
        }
    };

    /**
     * =========================================================================
     * SIDEBAR & FOCUS MODE HANDLERS (COLLAPSIBLE SIDEBAR)
     * =========================================================================
     */
    window.toggleAdminSidebarCollapse = function () {
        const sidebar = document.getElementById('admin-sidebar');
        const mainWrapper = document.getElementById('admin-main-wrapper');
        if (!sidebar) return;

        const isCurrentlyCollapsed = sidebar.classList.contains('is-collapsed');
        if (isCurrentlyCollapsed) {
            // Expand (Focus on Sidebar Navigation)
            sidebar.classList.remove('is-collapsed');
            sidebar.classList.remove('lg:w-20');
            sidebar.classList.add('lg:w-72');
            if (mainWrapper) {
                mainWrapper.classList.remove('lg:ml-20');
                mainWrapper.classList.add('lg:ml-72');
            }
            localStorage.setItem('lrs_admin_sidebar_collapsed', 'false');
            updateSidebarNavStyling(false);
            if (typeof showAdminToast === 'function') {
                showAdminToast('Sidebar diperluas: Mode Navigasi');
            }
        } else {
            // Collapse (Focus on Website / Content)
            sidebar.classList.add('is-collapsed');
            sidebar.classList.remove('lg:w-72');
            sidebar.classList.add('lg:w-20');
            if (mainWrapper) {
                mainWrapper.classList.remove('lg:ml-72');
                mainWrapper.classList.add('lg:ml-20');
            }
            localStorage.setItem('lrs_admin_sidebar_collapsed', 'true');
            updateSidebarNavStyling(true);
            if (typeof showAdminToast === 'function') {
                showAdminToast('Sidebar diciutkan: Mode Fokus Konten');
            }
        }
    };

    window.toggleAdminMobileSidebar = function () {
        const sidebar = document.getElementById('admin-sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const drawer = document.getElementById('admin-mobile-drawer');
        if (drawer) {
            drawer.classList.toggle('hidden');
        }
        if (!sidebar) return;

        const isClosed = sidebar.classList.contains('-translate-x-full');
        if (isClosed) {
            sidebar.classList.remove('-translate-x-full');
            if (backdrop) backdrop.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            sidebar.classList.add('-translate-x-full');
            if (backdrop) backdrop.classList.add('hidden');
            document.body.style.overflow = '';
        }
    };
    window.toggleAdminMobileDrawer = window.toggleAdminMobileSidebar;

    window.closeAdminMobileSidebar = function () {
        const sidebar = document.getElementById('admin-sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        if (sidebar) sidebar.classList.add('-translate-x-full');
        if (backdrop) backdrop.classList.add('hidden');
        document.body.style.overflow = '';
    };

    window.onSidebarNavClick = function () {
        if (window.innerWidth < 1024) {
            closeAdminMobileSidebar();
        }
    };

    function updateSidebarNavStyling(isCollapsed) {
        document.querySelectorAll('.admin-nav-btn').forEach(btn => {
            if (isCollapsed) {
                btn.classList.add('justify-center');
                btn.classList.remove('gap-3');
            } else {
                btn.classList.remove('justify-center');
                btn.classList.add('gap-3');
            }
        });
        const focusLabel = document.querySelector('.sidebar-focus-label');
        if (focusLabel) {
            focusLabel.textContent = isCollapsed ? 'Perluas Menu' : 'Fokus: Website & Konten';
        }
    }

    function initSidebarState() {
        const savedCollapsed = localStorage.getItem('lrs_admin_sidebar_collapsed') === 'true';
        const sidebar = document.getElementById('admin-sidebar');
        const mainWrapper = document.getElementById('admin-main-wrapper');
        if (sidebar && savedCollapsed && window.innerWidth >= 1024) {
            sidebar.classList.add('is-collapsed', 'lg:w-20');
            sidebar.classList.remove('lg:w-72');
            if (mainWrapper) {
                mainWrapper.classList.add('lg:ml-20');
                mainWrapper.classList.remove('lg:ml-72');
            }
            updateSidebarNavStyling(true);
        }
    }

    function handleHashChange() {
        const hash = window.location.hash.replace('#', '') || 'ringkasan';
        window.switchAdminSection(hash);
    }

    /**
     * =========================================================================
     * 4. SECTION 1: RINGKASAN KEPUASAN & LAYANAN (OVERVIEW)
     * =========================================================================
     */
    function populateOverviewProjectFilter() {
        const select = document.getElementById('filter-overview-project');
        if (!select) return;

        const currentVal = select.value || 'all';
        const projects = JSON.parse(localStorage.getItem('lrs_projects_data') || '[]');

        let html = '<option value="all">Semua Proyek</option>';
        html += '<option value="GENERAL">Survei Umum (Non-Kontrak)</option>';
        projects.forEach(p => {
            html += `<option value="${escapeHtml(p.name)}">${escapeHtml(p.name)}</option>`;
        });
        select.innerHTML = html;
        if (Array.from(select.options).some(opt => opt.value === currentVal)) {
            select.value = currentVal;
        }
    }

    function populateSurveysProjectFilter() {
        const select = document.getElementById('filter-surveys-project');
        if (!select) return;

        const currentVal = select.value || 'all';
        const projects = JSON.parse(localStorage.getItem('lrs_projects_data') || '[]');

        let html = '<option value="all">Semua Proyek</option>';
        html += '<option value="GENERAL">Survei Umum (Non-Kontrak)</option>';
        projects.forEach(p => {
            html += `<option value="${escapeHtml(p.name)}">${escapeHtml(p.name)}</option>`;
        });
        select.innerHTML = html;
        if (Array.from(select.options).some(opt => opt.value === currentVal)) {
            select.value = currentVal;
        }
    }

    window.onFilterChange = function () {
        renderOverviewData();
    };

    function renderOverviewData() {
        const surveys = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');
        const complaints = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');
        const settings = JSON.parse(localStorage.getItem('lrs_admin_settings') || '{}');
        const targetMutuPercent = parseFloat(settings.target_score) || DEFAULT_TARGET_SCORE;

        const selectedProject = document.getElementById('filter-overview-project')?.value || 'all';
        const selectedPeriod = document.getElementById('filter-overview-period')?.value || 'all';

        // Filter Survei berdasarkan Proyek dan Periode
        const filteredSurveys = surveys.filter(s => {
            const matchPeriod = matchesPeriodFilter(s.submitted_at, selectedPeriod);
            if (!matchPeriod) return false;
            if (selectedProject === 'all') return true;
            if (selectedProject === 'GENERAL') return isGeneralSurveyItem(s);
            return (s.project_name || '').toLowerCase() === selectedProject.toLowerCase();
        });

        let totalScorePossible = 0;
        let totalScoreActual = 0;
        let sumKepentingan = 0;
        let sumKepuasan = 0;
        let countAnswers = 0;

        // Metrik per Dimensi (3 Kategori Sesuai PRD.md)
        const dimStats = {
            'A': { title: 'Layanan & Dukungan', actual: 0, possible: 0, imp: 0, sat: 0, count: 0, projectsSet: new Set(), picSet: new Set() },
            'B': { title: 'Pelaksanaan Proyek', actual: 0, possible: 0, imp: 0, sat: 0, count: 0, projectsSet: new Set(), picSet: new Set() },
            'C': { title: 'Masa Pemeliharaan', actual: 0, possible: 0, imp: 0, sat: 0, count: 0, projectsSet: new Set(), picSet: new Set() }
        };

        const activeProjectsSet = new Set();
        const respondentsSet = new Set();
        const lowSatisfactionIndicators = [];

        filteredSurveys.forEach(survey => {
            if (survey.project_name) activeProjectsSet.add(survey.project_name);
            if (survey.respondent_id) respondentsSet.add(survey.respondent_id);

            if (survey.category_id && dimStats[survey.category_id]) {
                if (survey.project_name) dimStats[survey.category_id].projectsSet.add(survey.project_name);
                if (survey.respondent_id) dimStats[survey.category_id].picSet.add(survey.respondent_id);
            }

            (survey.answers || []).forEach(ans => {
                const qId = ans.question_id || '';
                let dimKey = qId.charAt(0);
                if (dimKey === 'D') dimKey = 'B';
                if (!dimStats[dimKey] && survey.category_id && dimStats[survey.category_id]) {
                    dimKey = survey.category_id;
                }

                const imp = parseInt(ans.kepentingan, 10) || 0;
                const sat = parseInt(ans.kepuasan, 10) || 0;

                if (sat > 0 && imp > 0) {
                    totalScoreActual += sat;
                    totalScorePossible += 5;
                    sumKepentingan += imp;
                    sumKepuasan += sat;
                    countAnswers++;

                    if (dimStats[dimKey]) {
                        dimStats[dimKey].actual += sat;
                        dimStats[dimKey].possible += 5;
                        dimStats[dimKey].imp += imp;
                        dimStats[dimKey].sat += sat;
                        dimStats[dimKey].count++;
                        if (survey.project_name) dimStats[dimKey].projectsSet.add(survey.project_name);
                        if (survey.respondent_id) dimStats[dimKey].picSet.add(survey.respondent_id);
                    }

                    if (sat <= 2) {
                        lowSatisfactionIndicators.push({
                            source: 'Survei Kepuasan',
                            project: survey.project_name || 'Proyek LRS',
                            id: qId,
                            text: ans.pertanyaan || `Indikator ${qId}`,
                            keterangan: 'Penilaian kepuasan klien rendah butuh solusi cepat',
                            score: sat
                        });
                    }
                }
            });
        });

        // Hitung Indeks Kepuasan & Kepentingan dalam Persentase (%) dengan 1 desimal
        let csiPercentage = totalScorePossible > 0
            ? (totalScoreActual / totalScorePossible) * 100
            : 81.20;

        csiPercentage = Math.round(csiPercentage * 10) / 10;

        const avgKepuasanPctNum = countAnswers > 0 ? ((sumKepuasan / (countAnswers * 5)) * 100) : 82.4;
        const avgKepentinganPctNum = countAnswers > 0 ? ((sumKepentingan / (countAnswers * 5)) * 100) : 78.0;
        const avgKepuasanPctStr = avgKepuasanPctNum.toFixed(1) + '%';
        const avgKepentinganPctStr = avgKepentinganPctNum.toFixed(1) + '%';

        const selisihPctNum = parseFloat(avgKepuasanPctNum.toFixed(1)) - parseFloat(avgKepentinganPctNum.toFixed(1));
        const selisihPctStr = (selisihPctNum >= 0 ? '+' : '') + selisihPctNum.toFixed(1) + '%';

        // Update Skor & Gauge (Konsisten 1 Desimal toFixed(1))
        const csiNumberEl = document.getElementById('csi-score-number');
        const gaugeCenterEl = document.getElementById('gauge-center-val');
        const badgeEl = document.getElementById('csi-status-badge');
        const interpEl = document.getElementById('csi-interpretation-text');
        const targetTextEl = document.getElementById('csi-target-text');

        const csiFormatted = csiPercentage.toFixed(1);
        if (csiNumberEl) csiNumberEl.textContent = `${csiFormatted}%`;
        if (gaugeCenterEl) gaugeCenterEl.textContent = `${csiFormatted}%`;
        if (targetTextEl) {
            targetTextEl.innerHTML = `/ Target Perusahaan: <span class="whitespace-nowrap">(≥ ${targetMutuPercent.toFixed(1)}%)</span>`;
        }
        const topbarTargetEl = document.getElementById('topbar-target-score');
        if (topbarTargetEl) topbarTargetEl.textContent = targetMutuPercent.toFixed(1);

        if (badgeEl) {
            if (csiPercentage >= targetMutuPercent) {
                badgeEl.className = 'px-3 py-1 rounded-full text-xs font-extrabold border bg-emerald-50 text-emerald-700 border-emerald-200 shadow-2xs';
                badgeEl.textContent = 'Memenuhi Target';
                if (interpEl) {
                    interpEl.innerHTML = `Kinerja layanan sudah mencapai target perusahaan <span class="whitespace-nowrap">(≥ ${targetMutuPercent.toFixed(1)}%)</span>.`;
                }
            } else {
                badgeEl.className = 'px-3 py-1 rounded-full text-xs font-extrabold border bg-amber-50 text-amber-700 border-amber-200 shadow-2xs';
                badgeEl.textContent = 'Di Bawah Target';
                if (interpEl) {
                    interpEl.innerHTML = `Skor saat ini (${csiFormatted}%) masih di bawah target <span class="whitespace-nowrap">(≥ ${targetMutuPercent.toFixed(1)}%)</span>. Fokus perbaikan pada area yang nilainya rendah.`;
                }
            }
        }

        // Update 4 Kotak Ringkasan Sejajar (Kepuasan sebagai metrik utama dalam %)
        const elAvgImp = document.getElementById('avg-kepentingan-val');
        const elAvgSat = document.getElementById('avg-kepuasan-val');
        const elGapVal = document.getElementById('gap-index-val');
        const elGapLabel = document.getElementById('gap-label-text');
        const elRatioVal = document.getElementById('participation-ratio-val');
        const elRatioSub = document.getElementById('participation-subtext');

        if (elAvgSat) elAvgSat.textContent = avgKepuasanPctStr;
        if (elAvgImp) elAvgImp.textContent = avgKepentinganPctStr;

        if (elGapVal) {
            elGapVal.textContent = selisihPctStr;
            if (selisihPctNum >= 0) {
                elGapVal.className = 'text-base md:text-lg font-black text-emerald-600';
                if (elGapLabel) {
                    elGapLabel.className = 'block text-[10px] text-emerald-600 font-bold';
                    elGapLabel.textContent = 'Melampaui Kepentingan';
                }
            } else {
                elGapVal.className = 'text-base md:text-lg font-black text-rose-600';
                if (elGapLabel) {
                    elGapLabel.className = 'block text-[10px] text-rose-600 font-bold';
                    elGapLabel.textContent = 'Di Bawah Kepentingan';
                }
            }
        }

        // Partisipasi Responden
        const picCount = respondentsSet.size || (filteredSurveys.length > 0 ? filteredSurveys.length : 0);
        const activeProjectsCount = activeProjectsSet.size || (selectedProject !== 'all' ? (filteredSurveys.length > 0 ? 1 : 0) : 0);
        const targetPics = selectedProject === 'all' ? TARGET_TOTAL_PIC : Math.max(picCount, 3);
        const targetProjects = TARGET_TOTAL_PROYEK;
        const partPct = targetPics > 0 ? Math.round((picCount / targetPics) * 100) : 0;

        if (elRatioVal) elRatioVal.textContent = `${partPct}%`;
        if (elRatioSub) {
            if (selectedProject === 'all') {
                elRatioSub.textContent = `${picCount} dari ${targetPics} PIC • ${activeProjectsCount}/${targetProjects} Proyek Aktif`;
            } else if (selectedProject === 'GENERAL') {
                elRatioSub.textContent = `${picCount} PIC • Survei Umum (Non-Kontrak)`;
            } else {
                elRatioSub.textContent = `${picCount} PIC • Proyek: ${selectedProject}`;
            }
        }

        // Update 3 Kartu Kategori (Kepuasan utama % & Kepentingan pendamping %)
        const fallbackImpPct = { A: 93.0, B: 92.0, C: 89.0 };
        ['A', 'B', 'C'].forEach(dim => {
            const st = dimStats[dim];
            const pct = st.possible > 0
                ? (st.actual / st.possible) * 100
                : (dim === 'A' ? 88.2 : dim === 'B' ? 84.6 : 83.5);
            const impPct = st.count > 0
                ? ((st.imp / (st.count * 5)) * 100)
                : fallbackImpPct[dim];
            const fixedPct = pct.toFixed(1);
            const fixedImpPct = impPct.toFixed(1);

            const scoreEl = document.getElementById(`dim-score-${dim}`);
            const impEl = document.getElementById(`dim-imp-${dim}`);
            const barEl = document.getElementById(`dim-bar-${dim}`);
            const ratioEl = document.getElementById(`dim-ratio-${dim}`);
            const badgeDimEl = document.getElementById(`dim-badge-${dim}`);

            if (scoreEl) scoreEl.textContent = `${fixedPct}%`;
            if (impEl) impEl.textContent = `Kepentingan: ${fixedImpPct}%`;
            if (barEl) barEl.style.width = `${fixedPct}%`;

            const catProjectsCount = st.projectsSet.size;
            const catPicCount = st.picSet.size;
            if (ratioEl) ratioEl.textContent = `${catProjectsCount}/${targetProjects} Proyek • ${catPicCount}/${targetPics} PIC`;

            if (badgeDimEl) {
                const numPct = parseFloat(fixedPct);
                if (numPct >= 85.0) {
                    badgeDimEl.className = 'text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-md border text-emerald-700 bg-emerald-50 border-emerald-200 shadow-2xs';
                    badgeDimEl.textContent = 'Sangat Memuaskan';
                } else if (numPct >= 75.0) {
                    badgeDimEl.className = 'text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-md border text-blue-700 bg-blue-50 border-blue-200 shadow-2xs';
                    badgeDimEl.textContent = 'Memuaskan';
                } else if (numPct >= 65.0) {
                    badgeDimEl.className = 'text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-md border text-amber-700 bg-amber-50 border-amber-200 shadow-2xs';
                    badgeDimEl.textContent = 'Perlu Perhatian';
                } else {
                    badgeDimEl.className = 'text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-md border text-rose-700 bg-rose-50 border-rose-200 shadow-2xs';
                    badgeDimEl.textContent = 'Perlu Perbaikan';
                }
            }
        });

        // Render Area Prioritas Perbaikan (3 Indikator Terendah)
        renderPriorityIndicators(filteredSurveys);

        // Update Charts
        renderCsiGaugeChart(csiPercentage);
        renderIpaComboChart(dimStats);
        renderPemantauanDanTindakLanjut(complaints, lowSatisfactionIndicators, selectedProject);
    }

    function renderPriorityIndicators(surveys) {
        const container = document.getElementById('priority-indicators-container');
        if (!container) return;

        const scores = {};
        surveys.forEach(survey => {
            (survey.answers || []).forEach(ans => {
                const qId = ans.question_id;
                if (!qId) return;
                const sat = parseFloat(ans.kepuasan) || 0;
                if (sat > 0) {
                    if (!scores[qId]) scores[qId] = { sum: 0, count: 0 };
                    scores[qId].sum += sat;
                    scores[qId].count++;
                }
            });
        });

        const list = [];
        Object.keys(scores).forEach(qId => {
            const avg = scores[qId].sum / scores[qId].count;
            const meta = INDICATOR_MAP[qId] || { code: qId, cat: 'Layanan', title: `Indikator ${qId}` };
            list.push({ ...meta, avg: avg, count: scores[qId].count });
        });

        // Fallback realistis jika belum ada data
        if (list.length === 0) {
            list.push(
                { code: 'B6', cat: 'Pelaksanaan Proyek', title: 'Kemampuan Koordinasi Lapangan', avg: 3.4, count: 6 },
                { code: 'C2', cat: 'Masa Pemeliharaan', title: 'Ketersediaan Suku Cadang', avg: 3.6, count: 6 },
                { code: 'B7', cat: 'Pelaksanaan Proyek', title: 'Kecepatan Penanganan Kendala Proyek', avg: 3.7, count: 6 }
            );
        }

        // Urutkan nilai terendah pertama (ascending)
        list.sort((a, b) => a.avg - b.avg);
        const top3 = list.slice(0, 3);

        container.innerHTML = top3.map((item, idx) => {
            const pctStr = toPercentStr(item.avg);
            return `
                <div class="p-4 rounded-xl border border-amber-200/80 bg-amber-50/40 hover:bg-amber-50/70 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200">
                                <span>#${idx + 1} Prioritas</span>
                                <span>•</span>
                                <span>${escapeHtml(item.code)}</span>
                            </span>
                            <span class="text-xs font-black text-amber-700 bg-white px-2 py-0.5 rounded-md border border-amber-200 shadow-2xs">
                                ${pctStr}
                            </span>
                        </div>
                        <h4 class="text-xs font-bold text-slate-800 leading-snug mb-1">
                            ${escapeHtml(item.title)}
                        </h4>
                        <p class="text-[11px] text-slate-500 font-medium">
                            Kategori: <span class="font-semibold text-slate-700">${escapeHtml(item.cat)}</span>
                        </p>
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-amber-200/60 flex items-center justify-between text-[10px] font-semibold text-amber-800">
                        <span>Rekomendasi: Tinjau &amp; Evaluasi PIC Lapangan</span>
                        <span>⚠️</span>
                    </div>
                </div>
            `;
        }).join('');
    }

    function renderCsiGaugeChart(csiScore) {
        const canvas = document.getElementById('csiGaugeChart');
        if (!canvas || typeof Chart === 'undefined') return;

        if (csiChartInstance) {
            csiChartInstance.destroy();
        }

        const score = Math.min(Math.max(csiScore, 0), 100);
        const remainder = 100 - score;

        let scoreColor = '#10B981';
        if (score < 65) scoreColor = '#EF4444';
        else if (score < 80) scoreColor = '#F59E0B';

        const ctx = canvas.getContext('2d');
        csiChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [
                    {
                        data: [score, remainder],
                        backgroundColor: [scoreColor, '#F1F5F9'],
                        borderWidth: 0,
                        circumference: 180,
                        rotation: -90,
                        cutout: '76%'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 700 },
                plugins: {
                    tooltip: { enabled: false },
                    legend: { display: false }
                }
            }
        });
    }

    function renderIpaComboChart(dimStats) {
        const canvas = document.getElementById('ipaComboChart');
        if (!canvas || typeof Chart === 'undefined') return;

        if (ipaChartInstance) {
            ipaChartInstance.destroy();
        }

        const labels = ['A. Layanan & Dukungan', 'B. Pelaksanaan Proyek', 'C. Masa Pemeliharaan'];
        const impData = [
            dimStats.A.count > 0 ? parseFloat(((dimStats.A.imp / (dimStats.A.count * 5)) * 100).toFixed(1)) : 93.0,
            dimStats.B.count > 0 ? parseFloat(((dimStats.B.imp / (dimStats.B.count * 5)) * 100).toFixed(1)) : 92.0,
            dimStats.C.count > 0 ? parseFloat(((dimStats.C.imp / (dimStats.C.count * 5)) * 100).toFixed(1)) : 89.0
        ];
        const satData = [
            dimStats.A.count > 0 ? parseFloat(((dimStats.A.sat / (dimStats.A.count * 5)) * 100).toFixed(1)) : 88.2,
            dimStats.B.count > 0 ? parseFloat(((dimStats.B.sat / (dimStats.B.count * 5)) * 100).toFixed(1)) : 84.6,
            dimStats.C.count > 0 ? parseFloat(((dimStats.C.sat / (dimStats.C.count * 5)) * 100).toFixed(1)) : 83.5
        ];

        const ctx = canvas.getContext('2d');
        ipaChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Kepuasan',
                        data: satData,
                        type: 'bar',
                        backgroundColor: '#003B73',
                        hoverBackgroundColor: '#002B54',
                        borderRadius: 8,
                        borderSkipped: false,
                        barThickness: 36,
                        order: 2,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Kepentingan',
                        data: impData,
                        type: 'line',
                        borderColor: '#F59E0B',
                        backgroundColor: '#F59E0B',
                        borderWidth: 3,
                        pointBackgroundColor: '#F59E0B',
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        tension: 0.2,
                        order: 1,
                        yAxisID: 'y'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 600 },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        padding: 10,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 11 },
                        callbacks: {
                            label: function (ctx) {
                                return ` ${ctx.dataset.label}: ${parseFloat(ctx.raw).toFixed(1)}%`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 100,
                        ticks: {
                            stepSize: 20,
                            font: { size: 10, weight: 'bold' },
                            color: '#64748B',
                            callback: function (value) {
                                return value + '%';
                            }
                        },
                        grid: { color: '#F1F5F9' }
                    },
                    x: {
                        ticks: { font: { size: 11, weight: '600' }, color: '#475569' },
                        grid: { display: false }
                    }
                }
            }
        });

        // Kesimpulan otomatis (Kepentingan dan Kepuasan dalam %)
        const summaryTextEl = document.getElementById('chart-main-conclusion') || document.getElementById('ipa-summary-text');
        if (summaryTextEl) {
            const lowestSat = Math.min(...satData.map(Number));
            const lowestIdx = satData.findIndex(v => Number(v) === lowestSat);
            const lowestCat = labels[lowestIdx] || 'B. Pelaksanaan Proyek';
            const lowestImp = impData[lowestIdx] || 0;
            const gapVal = (lowestSat - lowestImp).toFixed(1);
            const gapText = parseFloat(gapVal) >= 0 ? `+${gapVal}%` : `${gapVal}%`;
            summaryTextEl.textContent = `Fokus utama pada "${lowestCat}" dengan kepuasan ${lowestSat.toFixed(1)}% dibanding kepentingan ${lowestImp.toFixed(1)}% (selisih ${gapText}).`;
        }
    }

    function renderPemantauanDanTindakLanjut(complaints, lowIndicators, selectedProject) {
        const filteredComplaints = complaints.filter(c => {
            if (selectedProject === 'all') return true;
            if (selectedProject === 'GENERAL') return isGeneralSurveyItem(c);
            return (c.project_name || '').toLowerCase() === selectedProject.toLowerCase();
        });

        const totalComplaints = filteredComplaints.length;
        const resolvedComplaints = filteredComplaints.filter(c => (c.status || '').toLowerCase() === 'resolved');
        const onTimeResolved = resolvedComplaints.filter(c => {
            if (!c.created_at || !c.resolved_at) return true;
            const hours = (new Date(c.resolved_at) - new Date(c.created_at)) / (1000 * 3600);
            return hours <= (c.sla_hours || 24);
        });

        const slaPct = totalComplaints > 0 ? Math.round((onTimeResolved.length / totalComplaints) * 100) : 100;
        const elSlaPct = document.getElementById('sla-adherence-pct');
        const elSlaBar = document.getElementById('sla-adherence-bar');
        const elSlaDetail = document.getElementById('sla-detail-text');
        const elStatusBadge = document.getElementById('status-layanan-badge');

        if (elSlaPct) elSlaPct.textContent = `${slaPct}% Tepat Waktu`;
        if (elSlaBar) elSlaBar.style.width = `${slaPct}%`;
        if (elSlaDetail) elSlaDetail.textContent = `${onTimeResolved.length} dari ${totalComplaints} keluhan selesai sesuai target`;
        if (elStatusBadge) {
            if (slaPct >= 80) {
                elStatusBadge.className = 'font-bold text-emerald-600';
                elStatusBadge.textContent = '● Status Layanan: Baik';
            } else if (slaPct >= 50) {
                elStatusBadge.className = 'font-bold text-amber-600';
                elStatusBadge.textContent = '● Status Layanan: Perlu Perhatian';
            } else {
                elStatusBadge.className = 'font-bold text-rose-600';
                elStatusBadge.textContent = '● Status Layanan: Kritis';
            }
        }

        const openComplaints = filteredComplaints.filter(c => (c.status || '').toLowerCase() === 'open');
        const inProgressComplaints = filteredComplaints.filter(c => (c.status || '').toLowerCase() === 'in progress');

        const actionItems = [];
        openComplaints.forEach(c => {
            actionItems.push({
                type: 'Keluhan Baru',
                badgeClass: 'bg-rose-50 text-rose-700 border-rose-200',
                title: c.title || c.subject || c.description,
                subtitle: `${c.project_name || 'Proyek LRS'} • ${c.ticket_id}`,
                actionText: 'Tindak Lanjut',
                actionTicketId: c.ticket_id
            });
        });

        inProgressComplaints.forEach(c => {
            actionItems.push({
                type: 'Dalam Pengerjaan',
                badgeClass: 'bg-amber-50 text-amber-700 border-amber-200',
                title: c.title || c.subject || c.description,
                subtitle: `${c.project_name || 'Proyek LRS'} • PIC: ${c.pic || 'Tim Terkait'}`,
                actionText: 'Lihat Progress',
                actionTicketId: c.ticket_id
            });
        });

        const elOpenCapa = document.getElementById('open-capa-count');
        if (elOpenCapa) elOpenCapa.textContent = `${actionItems.length} Masalah`;

        const container = document.getElementById('capa-alerts-container') || document.getElementById('prioritas-tindak-lanjut-list');
        if (!container) return;

        if (actionItems.length === 0) {
            container.innerHTML = `
                <div class="p-6 text-center text-slate-400">
                    <p class="text-xs font-semibold">Semua keluhan telah diselesaikan atau belum ada tiket masuk.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = actionItems.slice(0, 4).map(item => `
            <div class="p-3.5 rounded-xl border border-slate-200/80 bg-slate-50/70 hover:bg-white hover:border-slate-300 transition-all flex items-start justify-between gap-3 shadow-2xs">
                <div class="min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider border ${item.badgeClass}">
                            ${escapeHtml(item.type)}
                        </span>
                        <span class="text-[11px] font-semibold text-slate-500 truncate">${escapeHtml(item.subtitle)}</span>
                    </div>
                    <h4 class="text-xs font-bold text-slate-800 line-clamp-1">${escapeHtml(item.title)}</h4>
                </div>
                <button type="button"
                        onclick="openTicketModal('${escapeHtml(item.actionTicketId)}')"
                        class="px-2.5 py-1 rounded-lg text-[11px] font-bold text-[#003B73] bg-blue-50 hover:bg-blue-100 transition-colors shrink-0 cursor-pointer">
                    ${escapeHtml(item.actionText)}
                </button>
            </div>
        `).join('');
    }

    window.exportOverviewCsv = function () {
        const surveys = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');
        if (surveys.length === 0) {
            showAdminToast('Tidak ada data survei untuk diekspor.');
            return;
        }

        let csv = '\uFEFF';
        csv += 'PT Len Railway Systems - Laporan Kepentingan dan Kepuasan Pelanggan\n';
        csv += 'No,Nomor Referensi,Tanggal,Klien,Proyek,Sumber Survei,Kategori,Personel Pendamping,Tingkat Kepuasan (%),Tingkat Kepentingan (%),Selisih Kepentingan & Kepuasan (%),Catatan\n';

        surveys.forEach((s, idx) => {
            const dateStr = s.submitted_at ? new Date(s.submitted_at).toLocaleDateString('id-ID') : '-';
            const cleanNotes = (s.general_notes || '').replace(/"/g, '""');
            let sumImp = 0, sumSat = 0, cnt = 0;
            (s.answers || []).forEach(a => {
                if (a.kepentingan > 0 && a.kepuasan > 0) {
                    sumImp += parseFloat(a.kepentingan);
                    sumSat += parseFloat(a.kepuasan);
                    cnt++;
                }
            });
            const satPct = cnt > 0 ? ((sumSat / (cnt * 5)) * 100).toFixed(1) : '80.0';
            const impPct = cnt > 0 ? ((sumImp / (cnt * 5)) * 100).toFixed(1) : '80.0';
            const gapPctNum = parseFloat(satPct) - parseFloat(impPct);
            const gapPct = (gapPctNum >= 0 ? '+' : '') + gapPctNum.toFixed(1) + '%';
            const sourceLabel = isGeneralSurveyItem(s) ? 'Survei Umum' : 'Proyek Kontrak';

            csv += `${idx + 1},"${s.reference_no || '-'}","${dateStr}","${s.client_name || s.respondent_id || '-'}","${s.project_name || '-'}","${sourceLabel}","${s.category_name || s.category_id || '-'}","${s.accompanying_personnel || '-'}","${satPct}%","${impPct}%","${gapPct}","${cleanNotes}"\n`;
        });

        downloadCsvFile(csv, 'laporan-kepentingan-dan-kepuasan-lrs.csv');
        showAdminToast('Laporan CSV PT Len Railway Systems berhasil diunduh.');
    };
    window.exportIsoReport = window.exportOverviewCsv;

    /**
     * =========================================================================
     * 5. SECTION 2: DATA SURVEI (REKAPITULASI & DETAIL MODAL)
     * =========================================================================
     */
    window.onSurveyFilterChange = function () {
        renderSurveysTable();
    };

    function renderSurveysTable() {
        const tableBody = document.getElementById('table-surveys-body') || document.getElementById('surveys-table-body');
        const emptyState = document.getElementById('table-surveys-empty') || document.getElementById('surveys-empty-state');
        if (!tableBody) return;

        const surveys = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');
        const searchInput = ((document.getElementById('filter-surveys-search') || document.getElementById('filter-survey-search'))?.value || '').toLowerCase().trim();
        const categoryFilter = (document.getElementById('filter-surveys-category') || document.getElementById('filter-survey-category'))?.value || 'all';
        const projectFilter = document.getElementById('filter-surveys-project')?.value || 'all';

        const filtered = surveys.filter(s => {
            const matchCat = categoryFilter === 'all' || s.category_id === categoryFilter;
            let matchProj = true;
            if (projectFilter === 'GENERAL') {
                matchProj = isGeneralSurveyItem(s);
            } else if (projectFilter !== 'all') {
                matchProj = (s.project_name || '').toLowerCase() === projectFilter.toLowerCase();
            }
            const searchTarget = `${s.reference_no || ''} ${s.client_name || ''} ${s.respondent_id || ''} ${s.project_name || ''} ${s.accompanying_personnel || ''} ${isGeneralSurveyItem(s) ? 'survei umum general non-kontrak' : ''}`.toLowerCase();
            const matchSearch = !searchInput || searchTarget.includes(searchInput);
            return matchCat && matchProj && matchSearch;
        });

        // Update Counter Cards
        let totalSurveys = surveys.length;
        let countCatA = 0;
        let countCatB = 0;
        let countCatC = 0;

        surveys.forEach(s => {
            if (s.category_id === 'A') countCatA++;
            else if (s.category_id === 'B') countCatB++;
            else if (s.category_id === 'C') countCatC++;
        });

        const elTot = document.getElementById('stat-surveys-total') || document.getElementById('count-survey-total');
        const elCatA = document.getElementById('stat-surveys-cat-a') || document.getElementById('count-survey-very-sat');
        const elCatB = document.getElementById('stat-surveys-cat-b') || document.getElementById('count-survey-sat');
        const elCatC = document.getElementById('stat-surveys-cat-c') || document.getElementById('count-survey-warning');

        if (elTot) elTot.textContent = totalSurveys;
        if (elCatA) elCatA.textContent = countCatA;
        if (elCatB) elCatB.textContent = countCatB;
        if (elCatC) elCatC.textContent = countCatC;

        if (filtered.length === 0) {
            tableBody.innerHTML = '';
            if (emptyState) emptyState.classList.remove('hidden');
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');

        let html = '';
        filtered.forEach((s, idx) => {
            const origIdx = surveys.indexOf(s);
            const refNo = s.reference_no || `SRV-${String(idx + 1).padStart(4, '0')}`;
            const dateStr = s.submitted_at
                ? new Date(s.submitted_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
                : '-';
            const catName = s.category_name || CATEGORY_NAMES[s.category_id] || 'Pelaksanaan Proyek';
            const isGeneral = isGeneralSurveyItem(s);

            let sumSat = 0;
            let sumImp = 0;
            let countAns = 0;
            (s.answers || []).forEach(a => {
                if (a.kepuasan > 0) {
                    sumSat += parseFloat(a.kepuasan);
                    sumImp += parseFloat(a.kepentingan || 0);
                    countAns++;
                }
            });
            const scorePct = countAns > 0 ? ((sumSat / (countAns * 5)) * 100).toFixed(1) : '80.0';
            const impPct = countAns > 0 && sumImp > 0 ? ((sumImp / (countAns * 5)) * 100).toFixed(1) : '80.0';

            const generalBadge = isGeneral
                ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-sky-50 text-sky-700 border border-sky-200 ml-1.5">Survei Umum</span>`
                : '';

            html += `
                <tr class="hover:bg-slate-50/80 transition-colors border-b border-slate-100 text-xs">
                    <td class="px-4 py-3.5 font-black text-[#003B73] whitespace-nowrap">
                        ${escapeHtml(refNo)}
                    </td>
                    <td class="px-4 py-3.5 text-slate-500 whitespace-nowrap">
                        ${escapeHtml(dateStr)}
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="font-bold text-slate-800 flex flex-wrap items-center gap-1">
                            <span>${escapeHtml(s.project_name || (isGeneral ? 'Survei Umum (Non-Kontrak)' : '-'))}</span>
                            ${generalBadge}
                        </div>
                        <div class="text-[11px] text-slate-500">${escapeHtml(s.client_name || s.respondent_id || 'PIC Responden')}</div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-blue-50 text-[#003B73] border border-blue-100">
                            ${escapeHtml(catName)}
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-slate-700 font-semibold">
                        ${escapeHtml(s.accompanying_personnel || (isGeneral ? 'Layanan Umum LRS' : 'Ir. Dwi Suryanto, S.T.'))}
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex flex-col items-start gap-0.5">
                            <span class="inline-flex items-center gap-1 font-black text-xs px-2.5 py-0.5 rounded-md ${parseFloat(scorePct) >= 80 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'}">
                                Kepuasan: ${scorePct}%
                            </span>
                            <span class="text-[10px] text-slate-500 font-semibold pl-0.5">
                                Kepentingan: ${impPct}%
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5 text-center whitespace-nowrap">
                        <button type="button"
                                onclick="openSurveyDetailModal(${origIdx})"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold text-[#003B73] bg-blue-50 hover:bg-blue-100 border border-blue-200/60 shadow-2xs transition-colors cursor-pointer">
                            Rincian
                        </button>
                    </td>
                </tr>
            `;
        });

        tableBody.innerHTML = html;
    }

    window.openSurveyDetailModal = function (origIdx) {
        const surveys = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');
        const survey = surveys[origIdx];
        if (!survey) return;

        const modal = document.getElementById('surveyDetailModal');
        if (!modal) return;

        const isGeneral = isGeneralSurveyItem(survey);

        // Header & Metadata
        const elRef = document.getElementById('modal-survey-ref') || document.getElementById('detail-survey-ref');
        const elCatBadge = document.getElementById('modal-survey-category-badge');
        const elDate = document.getElementById('modal-survey-datetime') || document.getElementById('detail-survey-date');
        const elClientName = document.getElementById('modal-survey-client-name') || document.getElementById('detail-survey-client');
        const elClientRole = document.getElementById('modal-survey-client-role');
        const elProject = document.getElementById('modal-survey-project-name') || document.getElementById('detail-survey-project');
        const elPersonnel = document.getElementById('modal-survey-personnel-name') || document.getElementById('detail-survey-personnel');
        const elNotifStatus = document.getElementById('modal-survey-notif-status');
        const elNotes = document.getElementById('modal-survey-notes') || document.getElementById('detail-survey-notes');
        const elAttachWrap = document.getElementById('modal-survey-attachment-wrap');
        const elAttachName = document.getElementById('modal-survey-attachment-name') || document.getElementById('detail-survey-attachment');
        const elIndTable = document.getElementById('modal-survey-answers-body') || document.getElementById('detail-survey-indicators');

        const catName = survey.category_name || CATEGORY_NAMES[survey.category_id] || 'Pelaksanaan Proyek';

        if (elRef) elRef.textContent = survey.reference_no || 'SRV-LRS';
        if (elCatBadge) elCatBadge.textContent = isGeneral ? `${catName} · Survei Umum` : catName;
        if (elDate) {
            elDate.textContent = survey.submitted_at
                ? new Date(survey.submitted_at).toLocaleString('id-ID')
                : '-';
        }
        if (elClientName) elClientName.textContent = survey.client_name || survey.respondent_id || 'PIC Klien';
        if (elClientRole) elClientRole.textContent = survey.client_role ? `${survey.client_role} • ${survey.project_name || ''}` : 'Responden Lapangan';
        if (elProject) elProject.textContent = (survey.project_name || '-') + (isGeneral ? ' (Survei Umum)' : '');
        if (elPersonnel) elPersonnel.textContent = `Personel Pendamping: ${survey.accompanying_personnel || (isGeneral ? 'Layanan Umum LRS' : 'Ir. Dwi Suryanto, S.T.')}`;

        if (elNotifStatus) {
            elNotifStatus.textContent = `Simulasi notifikasi otomatis diteruskan ke ${survey.accompanying_personnel || 'Personel Pendamping'} via WhatsApp.`;
        }

        if (elNotes) elNotes.textContent = survey.general_notes ? `"${survey.general_notes}"` : 'Tidak ada catatan khusus yang disampaikan.';

        if (elAttachWrap) {
            if (survey.attachment_name) {
                elAttachWrap.classList.remove('hidden');
                if (elAttachName) elAttachName.textContent = survey.attachment_name;
            } else {
                elAttachWrap.classList.add('hidden');
            }
        }

        // Tabel Indikator (Kepuasan %, Kepentingan %, dan Selisih Kepentingan & Kepuasan %)
        if (elIndTable) {
            let indHtml = '';
            (survey.answers || []).forEach(a => {
                const meta = INDICATOR_MAP[a.question_id] || { code: a.question_id, title: `Indikator ${a.question_id}` };
                const imp = parseFloat(a.kepentingan) || 0;
                const sat = parseFloat(a.kepuasan) || 0;
                const satPctNum = toPercentNum(sat);
                const impPctNum = toPercentNum(imp);
                const gapPctNum = satPctNum - impPctNum;
                const gapStr = (gapPctNum >= 0 ? '+' : '') + gapPctNum.toFixed(1) + '%';
                const gapColor = gapPctNum >= 0 ? 'text-emerald-600' : 'text-rose-600';

                indHtml += `
                    <tr class="border-b border-slate-100 text-xs hover:bg-slate-50/60 transition-colors">
                        <td class="py-2.5 px-3 font-bold text-[#003B73]">${escapeHtml(meta.code)}</td>
                        <td class="py-2.5 px-3 text-slate-700">
                            <div>${escapeHtml(meta.title)}</div>
                            ${a.reason ? `<div class="text-[11px] text-rose-600 mt-0.5 italic">Catatan: ${escapeHtml(a.reason)}</div>` : ''}
                        </td>
                        <td class="py-2.5 px-3 text-center font-black text-[#003B73]">${satPctNum.toFixed(1)}%</td>
                        <td class="py-2.5 px-3 text-center font-bold text-slate-600">${impPctNum.toFixed(1)}%</td>
                        <td class="py-2.5 px-3 text-center font-black ${gapColor}">${gapStr}</td>
                    </tr>
                `;
            });
            elIndTable.innerHTML = indHtml || '<tr><td colspan="5" class="p-4 text-center text-slate-400">Tidak ada rincian jawaban.</td></tr>';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    window.closeSurveyDetailModal = function () {
        const modal = document.getElementById('surveyDetailModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    };

    window.exportSurveysCsv = function () {
        const surveys = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');
        if (surveys.length === 0) {
            showAdminToast('Belum ada data survei untuk diekspor.');
            return;
        }

        let csv = '\uFEFF';
        csv += 'PT Len Railway Systems - Rekap Data Survei Kepentingan dan Kepuasan\n';
        csv += 'Nomor Referensi,Tanggal,Klien,Role,Proyek,Sumber Survei,Kategori,Personel Pendamping,Tingkat Kepuasan (%),Tingkat Kepentingan (%),Selisih Kepentingan & Kepuasan (%),Catatan,Lampiran\n';

        surveys.forEach(s => {
            const dateStr = s.submitted_at ? new Date(s.submitted_at).toLocaleString('id-ID') : '-';
            const cleanNotes = (s.general_notes || '').replace(/"/g, '""');
            let sumImp = 0, sumSat = 0, cnt = 0;
            (s.answers || []).forEach(a => {
                if (a.kepentingan > 0 && a.kepuasan > 0) {
                    sumImp += parseFloat(a.kepentingan);
                    sumSat += parseFloat(a.kepuasan);
                    cnt++;
                }
            });
            const satPct = cnt > 0 ? ((sumSat / (cnt * 5)) * 100).toFixed(1) : '80.0';
            const impPct = cnt > 0 ? ((sumImp / (cnt * 5)) * 100).toFixed(1) : '80.0';
            const gapNum = parseFloat(satPct) - parseFloat(impPct);
            const gapPct = (gapNum >= 0 ? '+' : '') + gapNum.toFixed(1) + '%';
            const sourceLabel = isGeneralSurveyItem(s) ? 'Survei Umum' : 'Proyek Kontrak';

            csv += `"${s.reference_no || '-'}","${dateStr}","${s.client_name || s.respondent_id || '-'}","${s.client_role || '-'}","${s.project_name || '-'}","${sourceLabel}","${s.category_name || s.category_id || '-'}","${s.accompanying_personnel || '-'}","${satPct}%","${impPct}%","${gapPct}","${cleanNotes}","${s.attachment_name || '-'}"\n`;
        });

        downloadCsvFile(csv, 'rekap-survei-kepentingan-dan-kepuasan-lrs.csv');
        showAdminToast('Data survei berhasil diekspor ke CSV.');
    };

    /**
     * =========================================================================
     * 6. SECTION 3: KELUHAN (TABEL & KANBAN INTERAKTIF)
     * =========================================================================
     */
    let currentComplaintFilterStatus = 'all';

    window.switchComplaintView = function (viewType) {
        const tableContainer = document.getElementById('container-complaint-table');
        const kanbanContainer = document.getElementById('container-complaint-kanban');
        const btnTable = document.getElementById('btn-view-complaint-table');
        const btnKanban = document.getElementById('btn-view-complaint-kanban');

        if (viewType === 'table') {
            if (tableContainer) tableContainer.classList.remove('hidden');
            if (kanbanContainer) kanbanContainer.classList.add('hidden');
            if (btnTable) btnTable.className = 'px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-[#003B73] text-white shadow-xs flex items-center gap-1.5 cursor-pointer';
            if (btnKanban) btnKanban.className = 'px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-200/60 flex items-center gap-1.5 cursor-pointer';
        } else {
            if (tableContainer) tableContainer.classList.add('hidden');
            if (kanbanContainer) kanbanContainer.classList.remove('hidden');
            if (btnKanban) btnKanban.className = 'px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-[#003B73] text-white shadow-xs flex items-center gap-1.5 cursor-pointer';
            if (btnTable) btnTable.className = 'px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-200/60 flex items-center gap-1.5 cursor-pointer';
        }
    };

    window.filterComplaintsByStatus = function (status) {
        currentComplaintFilterStatus = status;
        const mapIds = {
            'all': 'filter-status-all',
            'Open': 'filter-status-open',
            'In Progress': 'filter-status-progress',
            'Resolved': 'filter-status-resolved'
        };
        Object.keys(mapIds).forEach(stKey => {
            const btn = document.getElementById(mapIds[stKey]);
            if (btn) {
                if (stKey === status) {
                    btn.className = 'px-3.5 py-1.5 rounded-xl text-xs font-extrabold border bg-[#003B73] text-white border-[#003B73] shadow-xs cursor-pointer transition-all';
                } else {
                    btn.className = 'px-3.5 py-1.5 rounded-xl text-xs font-extrabold border bg-slate-50 text-slate-700 border-slate-200 hover:border-slate-300 shadow-2xs cursor-pointer transition-all';
                }
            }
        });
        renderComplaintsView();
    };

    window.onComplaintFilterChange = function () {
        renderComplaintsView();
    };
    window.onKanbanFilterChange = window.onComplaintFilterChange;

    function renderComplaintsView() {
        renderComplaintsTable();
        renderKanbanBoard();
        updateSlaPills();
    }

    function renderComplaintsTable() {
        const tbody = document.getElementById('table-complaints-body') || document.getElementById('complaints-table-body');
        const emptyState = document.getElementById('table-complaints-empty');
        if (!tbody) return;

        const complaints = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');
        const searchInput = ((document.getElementById('filter-kanban-search') || document.getElementById('filter-complaint-search'))?.value || '').toLowerCase().trim();
        const categoryFilter = (document.getElementById('filter-kanban-category') || document.getElementById('filter-complaint-category'))?.value || 'all';

        // Update counter filter buttons
        const countAllEl = document.getElementById('count-complaints-all');
        const countOpenEl = document.getElementById('count-complaints-open');
        const countProgressEl = document.getElementById('count-complaints-progress');
        const countResolvedEl = document.getElementById('count-complaints-resolved');
        if (countAllEl) countAllEl.textContent = complaints.length;
        if (countOpenEl) countOpenEl.textContent = complaints.filter(c => (c.status || '').toLowerCase() === 'open').length;
        if (countProgressEl) countProgressEl.textContent = complaints.filter(c => (c.status || '').toLowerCase() === 'in progress').length;
        if (countResolvedEl) countResolvedEl.textContent = complaints.filter(c => (c.status || '').toLowerCase() === 'resolved').length;

        const filtered = complaints.filter(c => {
            const matchStatus = currentComplaintFilterStatus === 'all' || (c.status || '').toLowerCase() === currentComplaintFilterStatus.toLowerCase();
            const matchCat = categoryFilter === 'all' || (c.category || '').toLowerCase().includes(categoryFilter.toLowerCase());
            const textTarget = `${c.ticket_id || ''} ${c.reporter_name || c.client_name || ''} ${c.project_name || ''} ${c.title || c.subject || ''} ${c.description || ''}`.toLowerCase();
            const matchSearch = !searchInput || textTarget.includes(searchInput);
            return matchStatus && matchCat && matchSearch;
        });

        if (filtered.length === 0) {
            tbody.innerHTML = '';
            if (emptyState) emptyState.classList.remove('hidden');
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');

        let html = '';
        filtered.forEach(c => {
            const dateStr = c.created_at ? new Date(c.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';
            const slaText = getSlaBadgeHtml(c);
            const safeId = escapeHtml(c.ticket_id);
            const reporterLabel = c.reporter_name || c.client_name || 'Klien';
            const titleLabel = c.title || c.subject || c.description;

            html += `
                <tr class="hover:bg-slate-50/80 transition-colors border-b border-slate-100 text-xs">
                    <td class="px-4 py-3.5 font-black text-[#003B73] whitespace-nowrap">
                        ${safeId}
                    </td>
                    <td class="px-4 py-3.5 whitespace-nowrap">
                        <div class="text-slate-600 font-semibold">${escapeHtml(dateStr)}</div>
                        <div class="mt-0.5">${slaText}</div>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="font-bold text-slate-800">${escapeHtml(c.project_name || '-')}</div>
                        <div class="text-[11px] text-slate-500">${escapeHtml(reporterLabel)}</div>
                    </td>
                    <td class="px-4 py-3.5 max-w-xs">
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200 mb-1">
                            ${escapeHtml(c.category || 'Teknis')}
                        </span>
                        <div class="font-bold text-slate-800 truncate">${escapeHtml(titleLabel)}</div>
                    </td>
                    <td class="px-4 py-3.5">
                        <select data-ticket-id="${safeId}"
                                onchange="changeComplaintStatus(this.dataset.ticketId, this.value)"
                                class="bg-white border border-slate-200 rounded-lg px-2.5 py-1 text-xs font-bold ${getStatusColorClass(c.status)} focus:outline-none focus:ring-2 focus:ring-blue-100 cursor-pointer shadow-2xs">
                            <option value="Open" ${c.status === 'Open' ? 'selected' : ''}>Masuk (Open)</option>
                            <option value="In Progress" ${c.status === 'In Progress' ? 'selected' : ''}>Dalam Proses</option>
                            <option value="Resolved" ${c.status === 'Resolved' ? 'selected' : ''}>Selesai</option>
                        </select>
                    </td>
                    <td class="px-4 py-3.5 text-slate-700 font-semibold">
                        ${escapeHtml(c.pic || 'Belum Ditugaskan')}
                    </td>
                    <td class="px-4 py-3.5 text-center whitespace-nowrap">
                        <button type="button"
                                data-ticket-id="${safeId}"
                                onclick="openTicketModal(this.dataset.ticketId)"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold text-[#003B73] bg-blue-50 hover:bg-blue-100 border border-blue-200/60 shadow-2xs transition-colors cursor-pointer">
                            Tindak Lanjut
                        </button>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    function getStatusColorClass(status) {
        if (status === 'Open') return 'text-rose-700 bg-rose-50 border-rose-200';
        if (status === 'In Progress') return 'text-amber-700 bg-amber-50 border-amber-200';
        if (status === 'Resolved') return 'text-emerald-700 bg-emerald-50 border-emerald-200';
        return 'text-slate-700 bg-slate-50 border-slate-200';
    }

    function getSlaBadgeHtml(complaint) {
        if (complaint.status === 'Resolved') {
            return `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">✓ Selesai</span>`;
        }

        const now = new Date();
        const created = new Date(complaint.created_at || now);
        const hoursPassed = (now - created) / (1000 * 3600);
        const slaHours = complaint.sla_hours || 24;

        if (hoursPassed > slaHours) {
            return `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200 animate-pulse">Melewati SLA</span>`;
        } else if (hoursPassed > slaHours * 0.7) {
            return `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-black bg-amber-50 text-amber-700 border border-amber-200">Mendekati Batas</span>`;
        }
        return `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">Tepat Waktu</span>`;
    }

    window.changeComplaintStatus = function (ticketId, newStatus) {
        try {
            const complaints = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');
            const complaint = complaints.find(c => c.ticket_id === ticketId);
            if (!complaint) return;

            const nowIso = new Date().toISOString();
            complaint.status = newStatus;
            if (newStatus === 'In Progress' && !complaint.in_progress_at) {
                complaint.in_progress_at = nowIso;
            }
            if (newStatus === 'Resolved' && !complaint.resolved_at) {
                complaint.resolved_at = nowIso;
            }
            if (!complaint.audit_trail) complaint.audit_trail = [];
            complaint.audit_trail.push({
                timestamp: nowIso,
                status: newStatus,
                note: `Status tiket diubah menjadi "${newStatus}" melalui dashboard admin.`
            });
            if (Array.isArray(complaint.timeline_notes)) {
                complaint.timeline_notes.push({
                    timestamp: nowIso,
                    status: newStatus,
                    pic_name: complaint.pic_name || complaint.pic || null,
                    note: `Status tiket diubah menjadi "${newStatus}" melalui dashboard admin.`
                });
            }

            localStorage.setItem('lrs_complaint_data', JSON.stringify(complaints));
            updateComplaintTabBadge();
            renderComplaintsView();
            renderOverviewData();
            showAdminToast(`Status tiket ${ticketId} berhasil diperbarui.`);
        } catch (e) {
            console.error('Error saat mengubah status keluhan:', e);
        }
    };

    function updateSlaPills() {
        const complaints = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');
        let overdue = 0;
        let warning = 0;
        let ontrack = 0;

        const now = new Date();
        complaints.forEach(c => {
            if (c.status === 'Resolved') {
                ontrack++;
            } else {
                const hours = (now - new Date(c.created_at || now)) / (1000 * 3600);
                const sla = c.sla_hours || 24;
                if (hours > sla) overdue++;
                else if (hours > sla * 0.7) warning++;
                else ontrack++;
            }
        });

        const pillOverdue = document.getElementById('sla-pill-overdue');
        const pillWarning = document.getElementById('sla-pill-warning');
        const pillOntrack = document.getElementById('sla-pill-ontrack');

        if (pillOverdue) pillOverdue.textContent = `${overdue} Melewati Batas`;
        if (pillWarning) pillWarning.textContent = `${warning} Mendekati Batas`;
        if (pillOntrack) pillOntrack.textContent = `${ontrack} Tepat Waktu`;
    }

    function renderKanbanBoard() {
        const complaints = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');

        const colOpen = document.getElementById('cards-col-open');
        const colProgress = document.getElementById('cards-col-progress');
        const colResolved = document.getElementById('cards-col-resolved');

        const countOpen = document.getElementById('count-col-open');
        const countProgress = document.getElementById('count-col-progress');
        const countResolved = document.getElementById('count-col-resolved');

        if (!colOpen || !colProgress || !colResolved) return;

        let cardsOpen = '';
        let cardsProgress = '';
        let cardsResolved = '';

        let nOpen = 0, nProgress = 0, nResolved = 0;

        complaints.forEach(c => {
            const st = (c.status || '').toLowerCase();
            const cardHtml = createKanbanCardHtml(c);

            if (st === 'open') {
                cardsOpen += cardHtml;
                nOpen++;
            } else if (st === 'in progress') {
                cardsProgress += cardHtml;
                nProgress++;
            } else if (st === 'resolved') {
                cardsResolved += cardHtml;
                nResolved++;
            }
        });

        colOpen.innerHTML = cardsOpen || '<div class="p-4 text-center text-slate-400 text-xs">Kosong</div>';
        colProgress.innerHTML = cardsProgress || '<div class="p-4 text-center text-slate-400 text-xs">Kosong</div>';
        colResolved.innerHTML = cardsResolved || '<div class="p-4 text-center text-slate-400 text-xs">Kosong</div>';

        if (countOpen) countOpen.textContent = nOpen;
        if (countProgress) countProgress.textContent = nProgress;
        if (countResolved) countResolved.textContent = nResolved;
    }

    function createKanbanCardHtml(c) {
        const safeId = escapeHtml(c.ticket_id);
        return `
            <div class="bg-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs hover:shadow-xs hover:border-slate-300 transition-all cursor-grab"
                 draggable="true"
                 data-ticket-id="${safeId}"
                 ondragstart="handleDragStart(event, this.dataset.ticketId)">
                <div class="flex items-center justify-between gap-2 mb-1.5">
                    <span class="text-[10px] font-black text-[#003B73]">${safeId}</span>
                    <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-slate-100 text-slate-700">
                        ${escapeHtml(c.category || 'Teknis')}
                    </span>
                </div>
                <h4 class="text-xs font-bold text-slate-800 line-clamp-2 mb-1">${escapeHtml(c.title || c.subject || c.description)}</h4>
                <div class="text-[10px] text-slate-500 font-medium truncate mb-2">${escapeHtml(c.project_name || '-')}</div>
                <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[10px]">
                    <span class="font-semibold text-slate-600 truncate">PIC: ${escapeHtml(c.pic || 'Belum Ditugaskan')}</span>
                    <button type="button"
                            data-ticket-id="${safeId}"
                            onclick="openTicketModal(this.dataset.ticketId)"
                            class="font-bold text-[#003B73] hover:underline cursor-pointer">
                        Detail →
                    </button>
                </div>
            </div>
        `;
    }

    window.handleDragStart = function (e, ticketId) {
        e.dataTransfer.setData('text/plain', ticketId);
    };

    window.handleDragOver = function (e, status) {
        e.preventDefault();
        const col = e.currentTarget;
        if (col) col.classList.add('bg-blue-50/50');
    };

    window.handleDragLeave = function (e, status) {
        const col = e.currentTarget;
        if (col) col.classList.remove('bg-blue-50/50');
    };

    window.handleDrop = function (e, targetStatus) {
        e.preventDefault();
        const col = e.currentTarget;
        if (col) col.classList.remove('bg-blue-50/50');

        const ticketId = e.dataTransfer.getData('text/plain');
        if (ticketId) {
            window.changeComplaintStatus(ticketId, targetStatus);
        }
    };

    // Modal Tindak Lanjut Keluhan
    window.openTicketModal = function (ticketId) {
        const complaints = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');
        const complaint = complaints.find(c => c.ticket_id === ticketId);
        if (!complaint) return;

        activeTicketId = ticketId;
        const modal = document.getElementById('ticketModal');
        if (!modal) return;

        const elId = document.getElementById('modal-ticket-id') || document.getElementById('ticket-modal-id');
        const elStatusBadge = document.getElementById('modal-status-badge');
        const elPriorityBadge = document.getElementById('modal-priority-badge');
        const elTitle = document.getElementById('modal-subject') || document.getElementById('ticket-modal-title');
        const elProject = document.getElementById('modal-project-name') || document.getElementById('ticket-modal-project');
        const elClient = document.getElementById('ticket-modal-client');
        const elDesc = document.getElementById('modal-description') || document.getElementById('ticket-modal-desc');
        const elCreatedAt = document.getElementById('modal-created-at');
        const elCategory = document.getElementById('modal-category');
        const elSlaText = document.getElementById('modal-sla-text');
        const elPic = document.getElementById('modal-pic-input') || document.getElementById('ticket-form-pic');
        const elRca = document.getElementById('modal-rca-input') || document.getElementById('ticket-form-rca');
        const elCapa = document.getElementById('modal-capa-input') || document.getElementById('ticket-form-capa');
        const elStatus = document.getElementById('modal-status-select') || document.getElementById('ticket-form-status');
        const elTimeline = document.getElementById('modal-audit-trail') || document.getElementById('ticket-audit-timeline');

        if (elId) elId.textContent = complaint.ticket_id;
        if (elStatusBadge) elStatusBadge.textContent = complaint.status || 'Open';
        if (elPriorityBadge) elPriorityBadge.textContent = complaint.urgency || 'Sedang';
        if (elTitle) elTitle.textContent = complaint.title || complaint.subject || complaint.description;
        if (elProject) elProject.textContent = `${complaint.project_name || '-'} · ${complaint.reporter_name || complaint.client_name || 'Klien'}`;
        if (elClient) elClient.textContent = complaint.reporter_name || complaint.client_name || 'Klien';
        if (elDesc) elDesc.textContent = complaint.description || '-';
        if (elCreatedAt) elCreatedAt.textContent = complaint.created_at ? new Date(complaint.created_at).toLocaleString('id-ID') : '-';
        if (elCategory) elCategory.textContent = complaint.category || 'Teknis';
        if (elSlaText) elSlaText.textContent = `${complaint.sla_hours || 24} Jam`;

        if (elPic) elPic.value = complaint.pic_name || complaint.pic || '';
        if (elRca) elRca.value = complaint.root_cause || complaint.rca || '';
        if (elCapa) elCapa.value = complaint.action_taken || complaint.capa || '';
        if (elStatus) elStatus.value = complaint.status || 'Open';

        if (elTimeline) {
            const auditTrail = complaint.audit_trail || [];
            elTimeline.innerHTML = auditTrail.map(a => `
                <div class="relative pl-6 pb-4 border-l-2 border-slate-200 last:border-transparent last:pb-0">
                    <span class="absolute -left-[5px] top-0.5 w-2 h-2 rounded-full bg-[#003B73]"></span>
                    <div class="flex items-center justify-between text-[10px] text-slate-400 font-semibold mb-0.5">
                        <span>${escapeHtml(new Date(a.timestamp).toLocaleString('id-ID'))}</span>
                        <span class="font-bold text-slate-700">${escapeHtml(a.status)}</span>
                    </div>
                    <p class="text-[11px] text-slate-600">${escapeHtml(a.note)}</p>
                </div>
            `).join('') || '<p class="text-xs text-slate-400">Belum ada riwayat aktivitas.</p>';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    window.closeTicketModal = function () {
        const modal = document.getElementById('ticketModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        activeTicketId = null;
    };

    window.saveTicketDetails = function (e) {
        if (e) e.preventDefault();
        if (!activeTicketId) return;

        try {
            const complaints = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');
            const complaint = complaints.find(c => c.ticket_id === activeTicketId);
            if (!complaint) return;

            const pic = (document.getElementById('modal-pic-input') || document.getElementById('ticket-form-pic'))?.value.trim() || '';
            const rca = (document.getElementById('modal-rca-input') || document.getElementById('ticket-form-rca'))?.value.trim() || '';
            const capa = (document.getElementById('modal-capa-input') || document.getElementById('ticket-form-capa'))?.value.trim() || '';
            const status = (document.getElementById('modal-status-select') || document.getElementById('ticket-form-status'))?.value || 'Open';

            const nowIso = new Date().toISOString();
            complaint.pic = pic;
            complaint.pic_name = pic || null;
            complaint.rca = rca;
            complaint.root_cause = rca || null;
            complaint.capa = capa;
            complaint.action_taken = capa || null;
            complaint.status = status;

            if (status === 'In Progress' && !complaint.in_progress_at) {
                complaint.in_progress_at = nowIso;
            }
            if (status === 'Resolved' && !complaint.resolved_at) {
                complaint.resolved_at = nowIso;
            }

            const noteText = `Catatan tindak lanjut diperbarui: PIC ${pic || '-'}, status ${status}.`;
            if (!complaint.audit_trail) complaint.audit_trail = [];
            complaint.audit_trail.push({
                timestamp: nowIso,
                status: status,
                note: noteText
            });
            if (!Array.isArray(complaint.timeline_notes)) {
                complaint.timeline_notes = [];
            }
            complaint.timeline_notes.push({
                timestamp: nowIso,
                status: status,
                pic_name: pic || null,
                note: capa || rca || noteText
            });

            localStorage.setItem('lrs_complaint_data', JSON.stringify(complaints));
            const savedId = activeTicketId;
            closeTicketModal();
            updateComplaintTabBadge();
            renderComplaintsView();
            renderOverviewData();
            showAdminToast(`Tindak lanjut tiket ${savedId} berhasil disimpan.`);
        } catch (err) {
            console.error('Error saat menyimpan detail tiket:', err);
        }
    };
    window.saveTicketAnalysis = window.saveTicketDetails;

    /**
     * =========================================================================
     * 7. SECTION 4: TAUTAN SURVEI UMUM & GENERATOR QR CODE
     * =========================================================================
     */
    function getGeneralSurveyUrl() {
        return window.location.origin + '/form?mode=general';
    }

    function initGeneralSurveyLinkCard() {
        const url = getGeneralSurveyUrl();
        const urlInput = document.getElementById('general-survey-url');
        const openLink = document.getElementById('btn-open-general-url');
        if (urlInput) urlInput.value = url;
        if (openLink) openLink.setAttribute('href', url);
    }

    window.copyGeneralSurveyUrl = function () {
        const url = getGeneralSurveyUrl();
        const txtBtn = document.getElementById('txt-copy-general-url');
        const showCopied = () => {
            if (txtBtn) {
                txtBtn.textContent = 'Tersalin';
                setTimeout(() => {
                    txtBtn.textContent = 'Salin Tautan Umum';
                }, 2000);
            }
            showAdminToast('Tautan Survei Umum berhasil disalin ke clipboard!');
        };

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(showCopied).catch(() => {
                fallbackCopyText(url, showCopied);
            });
        } else {
            fallbackCopyText(url, showCopied);
        }
    };

    function fallbackCopyText(text, cb) {
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
        } catch (err) {}
        document.body.removeChild(el);
    }

    window.toggleGeneralQrPanel = function () {
        const panel = document.getElementById('general-qr-panel');
        const canvas = document.getElementById('general-qr-canvas');
        const txtBtn = document.getElementById('txt-toggle-general-qr');
        if (!panel) return;

        const isHidden = panel.classList.contains('hidden');
        if (isHidden) {
            panel.classList.remove('hidden');
            if (txtBtn) txtBtn.textContent = 'Sembunyikan QR Umum';
            if (canvas) {
                canvas.innerHTML = '';
                if (typeof QRCode !== 'undefined') {
                    generalQrInstance = new QRCode(canvas, {
                        text: getGeneralSurveyUrl(),
                        width: 150,
                        height: 150,
                        colorDark: '#002B54',
                        colorLight: '#FFFFFF',
                        correctLevel: QRCode.CorrectLevel.M
                    });
                } else {
                    canvas.innerHTML = '<div class="p-3 text-center text-xs text-slate-400">QRCode.js belum dimuat</div>';
                }
            }
        } else {
            panel.classList.add('hidden');
            if (txtBtn) txtBtn.textContent = 'Tampilkan QR Umum';
        }
    };

    function populateQrDropdowns() {
        const projSelect = document.getElementById('qr-input-project') || document.getElementById('qr-select-project');
        const persSelect = document.getElementById('qr-input-personnel') || document.getElementById('qr-select-personnel');

        if (projSelect) {
            const projects = JSON.parse(localStorage.getItem('lrs_projects_data') || '[]');
            let pOpts = '';
            projects.forEach(p => {
                pOpts += `<option value="${escapeHtml(p.code)}" data-name="${escapeHtml(p.name)}">${escapeHtml(p.code)} — ${escapeHtml(p.name)}</option>`;
            });
            projSelect.innerHTML = pOpts;
        }

        if (persSelect) {
            const personnel = JSON.parse(localStorage.getItem('lrs_personnel_data') || '[]');
            let persOpts = '<option value="">-- Tanpa Personel Khusus --</option>';
            personnel.forEach(pe => {
                persOpts += `<option value="${escapeHtml(pe.name)}">${escapeHtml(pe.name)} (${escapeHtml(pe.division)})</option>`;
            });
            persSelect.innerHTML = persOpts;
        }
    }

    window.onQrParameterChange = function () {
        const projSelect = document.getElementById('qr-input-project') || document.getElementById('qr-select-project');
        const persSelect = document.getElementById('qr-input-personnel') || document.getElementById('qr-select-personnel');
        const urlInput = document.getElementById('qr-output-url') || document.getElementById('qr-generated-url');

        let catVal = 'all';
        const checkedRadio = document.querySelector('input[name="qr_category"]:checked');
        if (checkedRadio) {
            catVal = checkedRadio.value;
        } else {
            const catSelect = document.getElementById('qr-select-category');
            if (catSelect) catVal = catSelect.value || 'all';
        }

        const projCode = projSelect?.value || 'CTR-LRS-2025-001';
        const projName = projSelect?.selectedOptions[0]?.getAttribute('data-name') || 'Kereta Cepat Jakarta-Bandung';
        const persName = persSelect?.value || '';

        // URL Survei dengan Query Parameter
        let targetUrl = `${window.location.origin}/?proyek=${encodeURIComponent(projCode)}`;
        if (persName) {
            targetUrl += `&personel=${encodeURIComponent(persName)}`;
        }
        if (catVal && catVal !== 'all') {
            targetUrl += `&kategori=${encodeURIComponent(catVal)}`;
        }

        if (urlInput) urlInput.value = targetUrl;

        // Update Info Kartu Cetak Preview
        const cardProj = document.getElementById('card-preview-project') || document.getElementById('qr-card-project-name');
        const cardContract = document.getElementById('card-preview-contract');
        const cardPers = document.getElementById('card-preview-personnel') || document.getElementById('qr-card-personnel-name');
        const cardCat = document.getElementById('card-preview-category') || document.getElementById('qr-card-category-name');

        if (cardProj) cardProj.textContent = projName;
        if (cardContract) cardContract.textContent = projCode;
        if (cardPers) cardPers.textContent = persName || 'Personel Layanan LRS';
        if (cardCat) cardCat.textContent = catVal === 'all' ? 'Semua Kategori Layanan' : (CATEGORY_NAMES[catVal] || catVal);

        // Render QR Code di Container
        generateQrCodeElement(targetUrl);
    };

    function generateQrCodeElement(url) {
        const container = document.getElementById('qrcode-canvas-container') || document.getElementById('qr-preview-container');
        if (!container) return;

        container.innerHTML = '';
        if (typeof QRCode !== 'undefined') {
            activeQrInstance = new QRCode(container, {
                text: url,
                width: 170,
                height: 170,
                colorDark: '#002B54',
                colorLight: '#FFFFFF',
                correctLevel: QRCode.CorrectLevel.M
            });
        } else {
            container.innerHTML = `<div class="p-4 text-center text-xs text-slate-400">QRCode.js belum dimuat</div>`;
        }
    }

    window.openQrSurveyPreview = function () {
        const urlInput = document.getElementById('qr-output-url') || document.getElementById('qr-generated-url');
        if (urlInput && urlInput.value) {
            window.open(urlInput.value, '_blank');
        }
    };

    window.copyQrSurveyUrl = function () {
        const urlInput = document.getElementById('qr-output-url') || document.getElementById('qr-generated-url');
        if (!urlInput) return;

        navigator.clipboard.writeText(urlInput.value).then(() => {
            showAdminToast('Tautan survei berhasil disalin ke clipboard!');
        }).catch(err => {
            console.error('Gagal menyalin tautan:', err);
        });
    };

    window.saveQrToHistory = function () {
        const projSelect = document.getElementById('qr-input-project') || document.getElementById('qr-select-project');
        const persSelect = document.getElementById('qr-input-personnel') || document.getElementById('qr-select-personnel');
        const urlInput = document.getElementById('qr-output-url') || document.getElementById('qr-generated-url');

        let catVal = 'all';
        const checkedRadio = document.querySelector('input[name="qr_category"]:checked');
        if (checkedRadio) catVal = checkedRadio.value;

        const projCode = projSelect?.value || 'CTR-LRS';
        const projName = projSelect?.selectedOptions[0]?.getAttribute('data-name') || 'Proyek LRS';
        const persName = persSelect?.value || 'Personel LRS';
        const catName = catVal === 'all' ? 'Semua Lingkup' : (CATEGORY_NAMES[catVal] || catVal);
        const url = urlInput?.value || window.location.origin;

        const qrTitle = prompt('Beri judul/keterangan singkat untuk QR ini:', `Survei ${projName}`);
        if (!qrTitle) return;

        const history = JSON.parse(localStorage.getItem('lrs_qr_history') || '[]');
        history.unshift({
            id: 'qr-' + Date.now(),
            title: qrTitle,
            project_name: projName,
            project_code: projCode,
            personnel_name: persName,
            category_id: catVal,
            category_name: catName,
            url: url,
            created_at: new Date().toISOString()
        });

        localStorage.setItem('lrs_qr_history', JSON.stringify(history));
        renderQrHistoryTable();
        showAdminToast('Tautan & QR Code berhasil disimpan ke riwayat.');
    };

    window.downloadQrCardPng = function () {
        const container = document.getElementById('qrcode-canvas-container') || document.getElementById('qr-preview-container');
        if (!container) return;

        const qrCanvas = container.querySelector('canvas');
        const qrImg = container.querySelector('img');

        if (!qrCanvas && !qrImg) {
            showAdminToast('QR Code belum selesai digenerate.');
            return;
        }

        const canvas = document.createElement('canvas');
        canvas.width = 600;
        canvas.height = 780;
        const ctx = canvas.getContext('2d');

        ctx.fillStyle = '#FFFFFF';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        ctx.fillStyle = '#003B73';
        ctx.fillRect(0, 0, canvas.width, 110);

        ctx.fillStyle = '#38BDF8';
        ctx.fillRect(0, 110, canvas.width / 2, 6);
        ctx.fillStyle = '#D32F2F';
        ctx.fillRect(canvas.width / 2, 110, canvas.width / 2, 6);

        ctx.fillStyle = '#FFFFFF';
        ctx.font = 'bold 24px Arial, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('PT Len Railway Systems', canvas.width / 2, 50);

        ctx.font = '14px Arial, sans-serif';
        ctx.fillText('Survei Kepuasan Pelanggan & Layanan Teknis', canvas.width / 2, 80);

        ctx.strokeStyle = '#E2E8F0';
        ctx.lineWidth = 2;
        ctx.strokeRect(175, 160, 250, 250);

        const drawQrOnCanvas = function (imageSource) {
            ctx.drawImage(imageSource, 190, 175, 220, 220);

            const projName = document.getElementById('card-preview-project')?.textContent || 'Proyek LRS';
            const persName = document.getElementById('card-preview-personnel')?.textContent || 'Personel LRS';
            const catName = document.getElementById('card-preview-category')?.textContent || 'Semua Lingkup';

            ctx.fillStyle = '#0F172A';
            ctx.font = 'bold 20px Arial, sans-serif';
            ctx.fillText(projName, canvas.width / 2, 460);

            ctx.fillStyle = '#475569';
            ctx.font = '14px Arial, sans-serif';
            ctx.fillText(`Personel Pendamping: ${persName}`, canvas.width / 2, 495);
            ctx.fillText(`Lingkup: ${catName}`, canvas.width / 2, 525);

            ctx.fillStyle = '#F8FAFC';
            ctx.fillRect(40, 570, 520, 90);
            ctx.strokeStyle = '#CBD5E1';
            ctx.strokeRect(40, 570, 520, 90);

            ctx.fillStyle = '#1E293B';
            ctx.font = 'bold 15px Arial, sans-serif';
            ctx.fillText('Pindai QR Code Menggunakan Kamera Smartphone Anda', canvas.width / 2, 608);

            ctx.fillStyle = '#64748B';
            ctx.font = '12px Arial, sans-serif';
            ctx.fillText('Pengisian membutuhkan waktu 2-3 menit. Partisipasi Anda sangat berharga bagi kami.', canvas.width / 2, 634);

            ctx.fillStyle = '#94A3B8';
            ctx.font = '11px Arial, sans-serif';
            ctx.fillText('PT Len Railway Systems — Divisi Jaminan Mutu & Layanan Klien', canvas.width / 2, 730);

            const link = document.createElement('a');
            link.download = `kartu-qr-survei-${Date.now()}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
            showAdminToast('Kartu QR Code (PNG) siap cetak berhasil diunduh.');
        };

        if (qrCanvas) {
            drawQrOnCanvas(qrCanvas);
        } else if (qrImg) {
            if (qrImg.complete) {
                drawQrOnCanvas(qrImg);
            } else {
                qrImg.onload = () => drawQrOnCanvas(qrImg);
            }
        }
    };

    function renderQrHistoryTable() {
        const tbody = document.getElementById('table-qr-history-body') || document.getElementById('qr-history-table-body');
        const emptyState = document.getElementById('table-qr-empty');
        const countBadge = document.getElementById('qr-history-count-badge');
        if (!tbody) return;

        const history = JSON.parse(localStorage.getItem('lrs_qr_history') || '[]');
        if (countBadge) countBadge.textContent = `${history.length} Tautan`;

        if (history.length === 0) {
            tbody.innerHTML = '';
            if (emptyState) emptyState.classList.remove('hidden');
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');

        let html = '';
        history.forEach(item => {
            const dateStr = item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID') : '-';
            const safeUrl = escapeHtml(sanitizeUrl(item.url));
            const safeId = escapeHtml(item.id);
            html += `
                <tr class="hover:bg-slate-50/80 transition-colors border-b border-slate-100 text-xs">
                    <td class="px-4 py-3 font-bold text-slate-800">
                        <div>${escapeHtml(item.project_name || '-')}</div>
                        <div class="text-[10px] font-mono text-slate-400 font-normal">${escapeHtml(item.project_code || '')}</div>
                    </td>
                    <td class="px-4 py-3 text-slate-700">
                        ${escapeHtml(item.personnel_name || '-')}
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-blue-50 text-[#003B73]">
                            ${escapeHtml(item.category_name || 'Semua')}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-slate-400 whitespace-nowrap">
                        ${escapeHtml(dateStr)}
                    </td>
                    <td class="px-4 py-3 font-mono text-[10px] text-slate-500 max-w-[140px] truncate">
                        ${escapeHtml(item.url)}
                    </td>
                    <td class="px-4 py-3 text-center whitespace-nowrap">
                        <button type="button"
                                data-url="${safeUrl}"
                                onclick="copySpecificUrl(this.dataset.url)"
                                class="text-blue-700 hover:underline font-bold mr-2.5 cursor-pointer">
                            Salin
                        </button>
                        <a href="${safeUrl}" target="_blank" rel="noopener noreferrer" class="text-slate-600 hover:underline font-semibold mr-2.5">
                            Buka
                        </a>
                        <button type="button"
                                data-id="${safeId}"
                                onclick="deleteQrHistory(this.dataset.id)"
                                class="text-rose-600 hover:underline font-bold cursor-pointer">
                            Hapus
                        </button>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    window.copySpecificUrl = function (url) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(() => {
                showAdminToast('Tautan berhasil disalin!');
            }).catch(() => fallbackCopyText(url, () => showAdminToast('Tautan berhasil disalin!')));
        } else {
            fallbackCopyText(url, () => showAdminToast('Tautan berhasil disalin!'));
        }
    };

    window.deleteQrHistory = function (id) {
        if (!confirm('Hapus riwayat tautan & QR ini?')) return;
        const history = JSON.parse(localStorage.getItem('lrs_qr_history') || '[]');
        const updated = history.filter(h => h.id !== id);
        localStorage.setItem('lrs_qr_history', JSON.stringify(updated));
        renderQrHistoryTable();
        showAdminToast('Riwayat QR Code berhasil dihapus.');
    };

    /**
     * =========================================================================
     * 8. SECTION 5: KELOLA PROYEK & PERSONEL PENDAMPING LRS (CRUD)
     * =========================================================================
     */
    window.switchProyekPersonelSubtab = function (subtab) {
        const paneProjects = document.getElementById('subtab-content-projects') || document.getElementById('subtab-pane-projects');
        const panePersonnel = document.getElementById('subtab-content-personnel') || document.getElementById('subtab-pane-personnel');
        const btnProjects = document.getElementById('subtab-btn-projects') || document.getElementById('btn-subtab-projects');
        const btnPersonnel = document.getElementById('subtab-btn-personnel') || document.getElementById('btn-subtab-personnel');

        if (subtab === 'projects') {
            if (paneProjects) paneProjects.classList.remove('hidden');
            if (panePersonnel) panePersonnel.classList.add('hidden');
            if (btnProjects) btnProjects.className = 'px-4 py-2 rounded-xl text-xs font-bold transition-all bg-[#003B73] text-white shadow-sm flex items-center gap-1.5 cursor-pointer';
            if (btnPersonnel) btnPersonnel.className = 'px-4 py-2 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-200/60 flex items-center gap-1.5 cursor-pointer';
            renderProjectsTable();
        } else {
            if (paneProjects) paneProjects.classList.add('hidden');
            if (panePersonnel) panePersonnel.classList.remove('hidden');
            if (btnPersonnel) btnPersonnel.className = 'px-4 py-2 rounded-xl text-xs font-bold transition-all bg-[#003B73] text-white shadow-sm flex items-center gap-1.5 cursor-pointer';
            if (btnProjects) btnProjects.className = 'px-4 py-2 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-200/60 flex items-center gap-1.5 cursor-pointer';
            renderPersonnelTable();
        }
    };

    // A. Proyek CRUD
    function renderProjectsTable() {
        const tbody = document.getElementById('table-projects-body') || document.getElementById('projects-table-body');
        const emptyState = document.getElementById('table-projects-empty');
        const countBadge = document.getElementById('count-subtab-projects');
        if (!tbody) return;

        const projects = JSON.parse(localStorage.getItem('lrs_projects_data') || '[]');
        const surveys = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');
        if (countBadge) countBadge.textContent = projects.length;

        if (projects.length === 0) {
            tbody.innerHTML = '';
            if (emptyState) emptyState.classList.remove('hidden');
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');

        let html = '';
        projects.forEach(p => {
            const isAktif = p.status === 'Aktif';
            const relatedCount = surveys.filter(s => (s.project_name || '').toLowerCase() === (p.name || '').toLowerCase() || (s.project_code || '') === p.code).length;
            const safeProjId = escapeHtml(p.id);
            html += `
                <tr class="hover:bg-slate-50/80 transition-colors border-b border-slate-100 text-xs">
                    <td class="px-4 py-3.5 font-black text-[#003B73] whitespace-nowrap">
                        ${escapeHtml(p.code)}
                    </td>
                    <td class="px-4 py-3.5 font-bold text-slate-800">
                        ${escapeHtml(p.name)}
                    </td>
                    <td class="px-4 py-3.5 text-slate-600">
                        ${escapeHtml(p.client)}
                    </td>
                    <td class="px-4 py-3.5 text-center font-bold text-slate-700">
                        ${escapeHtml(p.target_pic || 10)} PIC
                    </td>
                    <td class="px-4 py-3.5 text-center font-bold text-[#003B73]">
                        ${relatedCount} Survei
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black ${isAktif ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200'}">
                            ${escapeHtml(p.status || 'Aktif')}
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-center whitespace-nowrap">
                        <button type="button"
                                data-id="${safeProjId}"
                                onclick="openProjectModal(this.dataset.id)"
                                class="text-blue-700 hover:underline font-bold mr-3 cursor-pointer">
                            Edit
                        </button>
                        <button type="button"
                                data-id="${safeProjId}"
                                onclick="deleteProject(this.dataset.id)"
                                class="text-rose-600 hover:underline font-bold cursor-pointer">
                            Hapus
                        </button>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    window.openProjectModal = function (id) {
        const modal = document.getElementById('projectModal');
        if (!modal) return;

        const elId = document.getElementById('project-form-id');
        const elCode = document.getElementById('project-form-contract') || document.getElementById('project-form-code');
        const elName = document.getElementById('project-form-name');
        const elClient = document.getElementById('project-form-institution') || document.getElementById('project-form-client');
        const elTarget = document.getElementById('project-form-target');
        const elStatus = document.getElementById('project-form-status');
        const elTitle = document.getElementById('modal-project-title');

        if (id && id !== 'add') {
            const projects = JSON.parse(localStorage.getItem('lrs_projects_data') || '[]');
            const proj = projects.find(p => p.id === id);
            if (proj) {
                if (elId) elId.value = proj.id;
                if (elCode) elCode.value = proj.code;
                if (elName) elName.value = proj.name;
                if (elClient) elClient.value = proj.client;
                if (elTarget) elTarget.value = proj.target_pic || 10;
                if (elStatus) elStatus.value = proj.status || 'Aktif';
                if (elTitle) elTitle.textContent = 'Edit Data Proyek';
            }
        } else {
            if (elId) elId.value = '';
            if (elCode) elCode.value = `CTR-LRS-2026-${String(Math.floor(100 + Math.random() * 900))}`;
            if (elName) elName.value = '';
            if (elClient) elClient.value = '';
            if (elTarget) elTarget.value = 10;
            if (elStatus) elStatus.value = 'Aktif';
            if (elTitle) elTitle.textContent = 'Tambah Proyek Baru';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    window.closeProjectModal = function () {
        const modal = document.getElementById('projectModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    };

    window.saveProjectModal = function (e) {
        if (e) e.preventDefault();
        try {
            const id = document.getElementById('project-form-id')?.value;
            const code = (document.getElementById('project-form-contract') || document.getElementById('project-form-code'))?.value.trim();
            const name = document.getElementById('project-form-name')?.value.trim();
            const client = (document.getElementById('project-form-institution') || document.getElementById('project-form-client'))?.value.trim();
            const targetPic = parseInt(document.getElementById('project-form-target')?.value, 10) || 10;
            const status = document.getElementById('project-form-status')?.value || 'Aktif';

            const projects = JSON.parse(localStorage.getItem('lrs_projects_data') || '[]');

            if (id) {
                const idx = projects.findIndex(p => p.id === id);
                if (idx !== -1) {
                    projects[idx] = { ...projects[idx], code, name, client, target_pic: targetPic, status };
                }
            } else {
                projects.push({
                    id: 'proj-' + Date.now(),
                    code,
                    name,
                    client,
                    target_pic: targetPic,
                    status
                });
            }

            localStorage.setItem('lrs_projects_data', JSON.stringify(projects));
            closeProjectModal();
            renderProjectsTable();
            populateOverviewProjectFilter();
            populateSurveysProjectFilter();
            populateQrDropdowns();
            showAdminToast(`Data proyek "${name}" berhasil disimpan.`);
        } catch (err) {
            console.error('Error saat menyimpan proyek:', err);
        }
    };

    window.deleteProject = function (id) {
        if (!confirm('Hapus data proyek ini?')) return;
        const projects = JSON.parse(localStorage.getItem('lrs_projects_data') || '[]');
        const updated = projects.filter(p => p.id !== id);
        localStorage.setItem('lrs_projects_data', JSON.stringify(updated));
        renderProjectsTable();
        populateOverviewProjectFilter();
        populateSurveysProjectFilter();
        populateQrDropdowns();
        showAdminToast('Data proyek berhasil dihapus.');
    };

    // B. Personel CRUD
    function renderPersonnelTable() {
        const tbody = document.getElementById('table-personnel-body') || document.getElementById('personnel-table-body');
        const emptyState = document.getElementById('table-personnel-empty');
        const countBadge = document.getElementById('count-subtab-personnel');
        if (!tbody) return;

        const personnel = JSON.parse(localStorage.getItem('lrs_personnel_data') || '[]');
        const surveys = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');
        if (countBadge) countBadge.textContent = personnel.length;

        if (personnel.length === 0) {
            tbody.innerHTML = '';
            if (emptyState) emptyState.classList.remove('hidden');
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');

        let html = '';
        personnel.forEach(pe => {
            const relatedCount = surveys.filter(s => (s.accompanying_personnel || '').toLowerCase() === (pe.name || '').toLowerCase()).length;
            const safePersId = escapeHtml(pe.id);
            html += `
                <tr class="hover:bg-slate-50/80 transition-colors border-b border-slate-100 text-xs">
                    <td class="px-4 py-3.5 font-bold text-slate-800">
                        ${escapeHtml(pe.name)}
                    </td>
                    <td class="px-4 py-3.5 text-slate-600">
                        ${escapeHtml(pe.division)}
                    </td>
                    <td class="px-4 py-3.5 text-[#003B73] font-semibold">
                        ${escapeHtml(pe.whatsapp)}
                    </td>
                    <td class="px-4 py-3.5 text-slate-500">
                        ${escapeHtml(pe.email)}
                    </td>
                    <td class="px-4 py-3.5 text-center font-bold text-[#003B73]">
                        ${relatedCount} Survei
                    </td>
                    <td class="px-4 py-3.5 text-center whitespace-nowrap">
                        <button type="button"
                                data-id="${safePersId}"
                                onclick="openPersonnelModal(this.dataset.id)"
                                class="text-blue-700 hover:underline font-bold mr-3 cursor-pointer">
                            Edit
                        </button>
                        <button type="button"
                                data-id="${safePersId}"
                                onclick="deletePersonnel(this.dataset.id)"
                                class="text-rose-600 hover:underline font-bold cursor-pointer">
                            Hapus
                        </button>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    window.openPersonnelModal = function (id) {
        const modal = document.getElementById('personnelModal');
        if (!modal) return;

        const elId = document.getElementById('personnel-form-id');
        const elName = document.getElementById('personnel-form-name');
        const elDivision = document.getElementById('personnel-form-division');
        const elWa = document.getElementById('personnel-form-whatsapp');
        const elEmail = document.getElementById('personnel-form-email');
        const elTitle = document.getElementById('modal-personnel-title');

        if (id && id !== 'add') {
            const personnel = JSON.parse(localStorage.getItem('lrs_personnel_data') || '[]');
            const pers = personnel.find(p => p.id === id);
            if (pers) {
                if (elId) elId.value = pers.id;
                if (elName) elName.value = pers.name;
                if (elDivision) elDivision.value = pers.division;
                if (elWa) elWa.value = pers.whatsapp;
                if (elEmail) elEmail.value = pers.email;
                if (elTitle) elTitle.textContent = 'Edit Data Personel';
            }
        } else {
            if (elId) elId.value = '';
            if (elName) elName.value = '';
            if (elDivision) elDivision.value = '';
            if (elWa) elWa.value = '';
            if (elEmail) elEmail.value = '';
            if (elTitle) elTitle.textContent = 'Tambah Personel Baru';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    window.closePersonnelModal = function () {
        const modal = document.getElementById('personnelModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    };

    window.savePersonnelModal = function (e) {
        if (e) e.preventDefault();
        try {
            const id = document.getElementById('personnel-form-id')?.value;
            const name = document.getElementById('personnel-form-name')?.value.trim();
            const division = document.getElementById('personnel-form-division')?.value.trim();
            const whatsapp = document.getElementById('personnel-form-whatsapp')?.value.trim();
            const email = document.getElementById('personnel-form-email')?.value.trim();

            const personnel = JSON.parse(localStorage.getItem('lrs_personnel_data') || '[]');

            if (id) {
                const idx = personnel.findIndex(p => p.id === id);
                if (idx !== -1) {
                    personnel[idx] = { ...personnel[idx], name, division, whatsapp, email };
                }
            } else {
                personnel.push({
                    id: 'pers-' + Date.now(),
                    name,
                    division,
                    whatsapp,
                    email
                });
            }

            localStorage.setItem('lrs_personnel_data', JSON.stringify(personnel));
            closePersonnelModal();
            renderPersonnelTable();
            populateQrDropdowns();
            showAdminToast(`Personel "${name}" berhasil disimpan.`);
        } catch (err) {
            console.error('Error saat menyimpan personel:', err);
        }
    };

    window.deletePersonnel = function (id) {
        if (!confirm('Hapus personel ini?')) return;
        const personnel = JSON.parse(localStorage.getItem('lrs_personnel_data') || '[]');
        const updated = personnel.filter(p => p.id !== id);
        localStorage.setItem('lrs_personnel_data', JSON.stringify(updated));
        renderPersonnelTable();
        populateQrDropdowns();
        showAdminToast('Data personel berhasil dihapus.');
    };

    /**
     * =========================================================================
     * 9. SECTION 6: PENGATURAN & PREFERENSI SISTEM
     * =========================================================================
     */
    function loadAdminSettings() {
        const settings = JSON.parse(localStorage.getItem('lrs_admin_settings') || '{}');
        const targetInput = document.getElementById('setting-target-score');
        const soundToggle = document.getElementById('setting-toggle-sound');
        const waToggle = document.getElementById('setting-toggle-whatsapp') || document.getElementById('setting-toggle-wa');

        if (targetInput) targetInput.value = (parseFloat(settings.target_score) || DEFAULT_TARGET_SCORE).toFixed(1);
        const isAudioEnabled = (settings.audio_notification_enabled !== false) && (settings.enable_sound !== false);
        if (soundToggle) soundToggle.checked = isAudioEnabled;
        if (waToggle) waToggle.checked = settings.enable_wa_simulation !== false;
    }

    window.saveAdminTargetSetting = function (e) {
        if (e) e.preventDefault();
        try {
            const targetInput = document.getElementById('setting-target-score');
            const newTarget = parseFloat(targetInput?.value) || DEFAULT_TARGET_SCORE;

            const settings = JSON.parse(localStorage.getItem('lrs_admin_settings') || '{}');
            settings.target_score = newTarget;
            localStorage.setItem('lrs_admin_settings', JSON.stringify(settings));

            renderOverviewData();
            showAdminToast(`Target kepuasan perusahaan diperbarui ke ${newTarget.toFixed(1)}%.`);
        } catch (err) {
            console.error('Error saat menyimpan target skor:', err);
        }
    };

    window.saveNotificationToggles = function () {
        const soundToggle = document.getElementById('setting-toggle-sound');
        const waToggle = document.getElementById('setting-toggle-whatsapp') || document.getElementById('setting-toggle-wa');

        const settings = JSON.parse(localStorage.getItem('lrs_admin_settings') || '{}');
        const isAudioEnabled = soundToggle ? soundToggle.checked : true;
        settings.audio_notification_enabled = isAudioEnabled;
        settings.enable_sound = isAudioEnabled;
        settings.enable_wa_simulation = waToggle ? waToggle.checked : true;
        localStorage.setItem('lrs_admin_settings', JSON.stringify(settings));

        showAdminToast(isAudioEnabled ? 'Efek suara notifikasi diaktifkan.' : 'Efek suara notifikasi dinonaktifkan.');
    };

    window.toggleSoundSetting = function (isChecked) {
        const settings = JSON.parse(localStorage.getItem('lrs_admin_settings') || '{}');
        settings.audio_notification_enabled = isChecked;
        settings.enable_sound = isChecked;
        localStorage.setItem('lrs_admin_settings', JSON.stringify(settings));
        if (isChecked) {
            window.playNotificationChime(true);
        }
        showAdminToast(isChecked ? 'Efek suara notifikasi diaktifkan.' : 'Efek suara notifikasi dinonaktifkan.');
    };

    window.toggleWaSimulationSetting = function (isChecked) {
        const settings = JSON.parse(localStorage.getItem('lrs_admin_settings') || '{}');
        settings.enable_wa_simulation = isChecked;
        localStorage.setItem('lrs_admin_settings', JSON.stringify(settings));
        showAdminToast(isChecked ? 'Simulasi notifikasi WhatsApp diaktifkan.' : 'Simulasi notifikasi WhatsApp dinonaktifkan.');
    };

    function renderNotificationLogs() {
        const tbody = document.getElementById('table-notif-logs-body') || document.getElementById('notification-logs-table-body');
        const emptyState = document.getElementById('table-notif-logs-empty');
        if (!tbody) return;

        const logs = JSON.parse(localStorage.getItem('lrs_notification_logs') || '[]');
        if (logs.length === 0) {
            tbody.innerHTML = '';
            if (emptyState) emptyState.classList.remove('hidden');
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');

        let html = '';
        logs.forEach(log => {
            html += `
                <tr class="border-b border-slate-100 text-xs hover:bg-slate-50/70 transition-colors">
                    <td class="px-3 py-2.5 font-mono text-[11px] text-slate-500 whitespace-nowrap">${escapeHtml(log.timestamp)}</td>
                    <td class="px-3 py-2.5 font-semibold text-slate-800">${escapeHtml(log.recipient)}</td>
                    <td class="px-3 py-2.5">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold ${log.channel === 'WhatsApp' ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700'}">
                            ${escapeHtml(log.channel)}
                        </span>
                    </td>
                    <td class="px-3 py-2.5 font-mono text-[11px] text-[#003B73] font-bold">${escapeHtml(log.reference_no || '-')}</td>
                    <td class="px-3 py-2.5 text-slate-600">${escapeHtml(log.type)}</td>
                    <td class="px-3 py-2.5 text-center">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
                            ${escapeHtml(log.status)}
                        </span>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    window.resetToSeedDemoData = function () {
        if (!confirm('Apakah Anda yakin ingin mereset seluruh data contoh kembali ke awal? Semua perubahan tersimpan di peramban ini akan digantikan data default.')) {
            return;
        }

        seedFallbackData(true);
        renderOverviewData();
        renderSurveysTable();
        renderComplaintsView();
        renderProjectsTable();
        renderPersonnelTable();
        populateOverviewProjectFilter();
        populateSurveysProjectFilter();
        populateQrDropdowns();
        renderQrHistoryTable();
        loadAdminSettings();
        renderNotificationLogs();
        updateNotificationBell();
        updateComplaintTabBadge();

        showAdminToast('Seluruh data berhasil diatur ulang ke data contoh awal!');
    };

    /**
     * =========================================================================
     * 10. SISTEM NOTIFIKASI LONCENG & DROPDOWN ANTAR-TAB
     * =========================================================================
     */
    function updateNotificationBell() {
        const badgeEl = document.getElementById('bell-unread-badge');
        const listContainer = document.getElementById('notification-list-container');
        const countTextEl = document.getElementById('notif-unread-count-text');

        const surveys = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');

        const unreadCount = surveys.filter(s => s.is_read === false).length;

        if (badgeEl) {
            const countSpan = document.getElementById('bell-unread-count');
            const pingSpan = document.getElementById('bell-unread-ping');
            const countText = unreadCount > 99 ? '99+' : String(unreadCount);

            if (unreadCount > 0) {
                if (countSpan) {
                    countSpan.textContent = countText;
                } else {
                    badgeEl.textContent = countText;
                }
                badgeEl.classList.remove('hidden');
                badgeEl.classList.add('flex');
                if (pingSpan) pingSpan.classList.remove('hidden');
            } else {
                badgeEl.classList.add('hidden');
                badgeEl.classList.remove('flex');
                if (pingSpan) pingSpan.classList.add('hidden');
            }
        }

        if (countTextEl) {
            countTextEl.textContent = `${unreadCount} baru`;
        }

        if (!listContainer) return;

        if (surveys.length === 0) {
            listContainer.innerHTML = `
                <div class="p-6 text-center text-slate-400">
                    <p class="text-xs font-semibold">Belum ada survei baru masuk.</p>
                </div>
            `;
            return;
        }

        const reversed = [...surveys].map((s, idx) => ({ ...s, _origIdx: idx })).reverse();

        let html = '';
        reversed.slice(0, 8).forEach(item => {
            const isUnread = item.is_read === false;
            const catName = item.category_name || CATEGORY_NAMES[item.category_id] || 'Pelaksanaan Proyek';
            const respondentLabel = item.client_name || item.respondent_id || 'Responden Klien';
            const projectLabel = item.project_name || 'Proyek LRS';
            const relTime = getRelativeTime(item.submitted_at);

            html += `
                <div onclick="markSurveyNotificationAsRead(${item._origIdx})"
                     class="p-3.5 px-4 cursor-pointer transition-colors flex items-start gap-3 select-none ${isUnread ? 'bg-blue-50/70 hover:bg-blue-100/70' : 'bg-white hover:bg-slate-50'}">
                    <div class="mt-1 shrink-0 flex items-center justify-center">
                        ${isUnread 
                            ? '<span class="w-2.5 h-2.5 rounded-full bg-[#003B73] ring-4 ring-blue-100"></span>' 
                            : '<span class="w-2 h-2 rounded-full bg-slate-300"></span>'}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2 mb-0.5">
                            <span class="text-xs font-bold text-slate-800 truncate">${escapeHtml(catName)}</span>
                            <span class="text-[10px] font-semibold text-slate-400 shrink-0">${escapeHtml(relTime)}</span>
                        </div>
                        <p class="text-[11px] text-slate-600 truncate">
                            <span class="font-semibold text-slate-700">${escapeHtml(respondentLabel)}</span> • <span class="text-slate-500">${escapeHtml(projectLabel)}</span>
                        </p>
                    </div>
                </div>
            `;
        });

        listContainer.innerHTML = html;
    }

    function getRelativeTime(isoString) {
        if (!isoString) return 'Baru saja';
        const past = new Date(isoString).getTime();
        const now = Date.now();
        const diffSec = Math.floor((now - past) / 1000);

        if (diffSec < 60) return 'Baru saja';
        const diffMin = Math.floor(diffSec / 60);
        if (diffMin < 60) return `${diffMin} menit lalu`;
        const diffHour = Math.floor(diffMin / 60);
        if (diffHour < 24) return `${diffHour} jam lalu`;
        const diffDay = Math.floor(diffHour / 24);
        return `${diffDay} hari lalu`;
    }

    window.toggleNotificationDropdown = function (e) {
        if (e) e.stopPropagation();
        const dropdown = document.getElementById('notification-dropdown');
        if (!dropdown) return;

        if (dropdown.classList.contains('hidden')) {
            updateNotificationBell();
            dropdown.classList.remove('hidden');
        } else {
            dropdown.classList.add('hidden');
        }
    };

    window.markSurveyNotificationAsRead = function (origIdx) {
        try {
            const surveys = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');
            if (surveys[origIdx]) {
                surveys[origIdx].is_read = true;
                localStorage.setItem('lrs_survey_data', JSON.stringify(surveys));
                updateNotificationBell();
            }
        } catch (e) {
            console.error('Error saat menandai survei dibaca:', e);
        }
    };

    window.markAllNotificationsAsRead = function () {
        try {
            const surveys = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');
            surveys.forEach(s => {
                s.is_read = true;
            });
            localStorage.setItem('lrs_survey_data', JSON.stringify(surveys));
            updateNotificationBell();
            showAdminToast('Semua notifikasi survei telah ditandai dibaca.');
        } catch (e) {
            console.error('Error saat menandai semua survei dibaca:', e);
        }
    };

    // Tutup dropdown otomatis saat klik di luar
    document.addEventListener('click', function (e) {
        const wrapper = document.getElementById('notification-bell-wrapper');
        const dropdown = document.getElementById('notification-dropdown');
        if (wrapper && dropdown && !wrapper.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    /**
     * =========================================================================
     * 11. BADGE ANGKA TAB DAFTAR KELUHAN
     * =========================================================================
     */
    function updateComplaintTabBadge() {
        try {
            const complaints = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');
            const openCount = complaints.filter(c => (c.status || '').toLowerCase() === 'open').length;

            const badgeEl = document.getElementById('tab-complaint-badge');
            const mobileBadgeEl = document.getElementById('mobile-complaint-badge');

            [badgeEl, mobileBadgeEl].forEach(b => {
                if (b) {
                    if (openCount > 0) {
                        b.textContent = openCount > 99 ? '99+' : openCount;
                        b.classList.remove('hidden');
                    } else {
                        b.classList.add('hidden');
                    }
                }
            });
        } catch (e) {
            console.error('Error saat memperbarui badge tab keluhan:', e);
        }
    }

    /**
     * =========================================================================
     * 12. TOAST POPUP NOTIFIKASI
     * =========================================================================
     */
    function showAdminToast(message) {
        let container = document.getElementById('admin-toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = 'bg-[#002244] text-white border border-blue-900/80 shadow-[0_12px_32px_rgba(0,18,36,0.35)] rounded-2xl p-4 flex items-start gap-3 pointer-events-auto transform transition-all duration-300 ease-out translate-y-4 opacity-0 border-l-4 border-l-sky-500';
        toast.innerHTML = `
            <div class="w-8 h-8 rounded-xl bg-blue-900/70 text-sky-300 flex items-center justify-center shrink-0 font-bold text-sm shadow-xs border border-blue-700/60">
                🔔
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
                <span class="text-xs font-black tracking-tight text-white block mb-0.5">Pemberitahuan Sistem</span>
                <p class="text-[11px] text-slate-200 leading-snug break-words">${escapeHtml(message)}</p>
            </div>
            <button type="button" class="text-slate-400 hover:text-white transition-colors p-1 -mr-1 -mt-1 cursor-pointer" onclick="this.parentElement.remove()">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;

        container.appendChild(toast);
        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-4', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        });

        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-2', 'opacity-0');
            setTimeout(() => {
                if (toast.parentElement) toast.remove();
            }, 300);
        }, 4000);
    }

    function highlightRespondentCard() {
        const card = document.getElementById('card-partisipasi-responden');
        if (!card) return;
        card.classList.add('ring-4', 'ring-blue-400/60', 'scale-[1.02]', 'bg-blue-50/70');
        setTimeout(() => {
            card.classList.remove('ring-4', 'ring-blue-400/60', 'scale-[1.02]', 'bg-blue-50/70');
        }, 2000);
    }

    /**
     * =========================================================================
     * 13. STORAGE EVENT LISTENER (ANTAR-TAB REAL-TIME)
     * =========================================================================
     */
    function initStorageEventListener() {
        window.addEventListener('storage', function (e) {
            if (e.key === 'lrs_survey_data') {
                try {
                    const newSurveys = JSON.parse(e.newValue || '[]');
                    const oldSurveys = JSON.parse(e.oldValue || '[]');

                    if (newSurveys.length > oldSurveys.length) {
                        const latest = newSurveys[newSurveys.length - 1];
                        const catName = latest.category_name || CATEGORY_NAMES[latest.category_id] || 'Pelaksanaan Proyek';

                        showAdminToast(`Survei baru masuk • ${catName} (${latest.project_name || 'Proyek LRS'})`);
                        highlightRespondentCard();
                        playNotificationChime();
                    }

                    updateNotificationBell();
                    renderOverviewData();
                    renderSurveysTable();
                } catch (err) {
                    console.error('Error saat menangani event storage survei:', err);
                }
            }

            if (e.key === 'lrs_complaint_data') {
                try {
                    const newComplaints = JSON.parse(e.newValue || '[]');
                    const oldComplaints = JSON.parse(e.oldValue || '[]');

                    if (newComplaints.length > oldComplaints.length) {
                        const latest = newComplaints[newComplaints.length - 1];
                        showAdminToast(`Keluhan baru masuk • ${latest.category || 'Keluhan'} (${latest.ticket_id})`);
                        playNotificationChime();
                    }

                    updateComplaintTabBadge();
                    renderComplaintsView();
                    renderOverviewData();
                } catch (err) {
                    console.error('Error saat menangani event storage keluhan:', err);
                }
            }
        });
    }

    /**
     * =========================================================================
     * 14. UTILITAS DOWNLOAD FILE CSV
     * =========================================================================
     */
    function downloadCsvFile(csvContent, filename) {
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    /**
     * =========================================================================
     * 15. INISIALISASI SAAT DOM READY
     * =========================================================================
     */
    document.addEventListener('DOMContentLoaded', function () {
        seedFallbackData();
        initSidebarState();
        initAutoplayAudioUnlock();
        initGeneralSurveyLinkCard();
        populateOverviewProjectFilter();
        populateSurveysProjectFilter();
        populateQrDropdowns();
        renderOverviewData();
        renderComplaintsView();
        updateNotificationBell();
        updateComplaintTabBadge();
        initStorageEventListener();

        // Tangani navigasi awal via hash
        window.addEventListener('hashchange', handleHashChange);
        handleHashChange();
    });

})();
