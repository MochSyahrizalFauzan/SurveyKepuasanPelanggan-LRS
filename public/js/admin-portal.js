/**
 * PT. Len Railways Systems — Admin Portal & Pemantauan Kepuasan Layanan
 * Logika Interaktif Vanilla JS + Chart.js + LocalStorage
 */

(function () {
    'use strict';

    // State Global Admin
    let csiChartInstance = null;
    let ipaChartInstance = null;
    let activeTicketId = null;

    const TARGET_MUTU_PERCENT = 80.0;
    const TARGET_TOTAL_PIC = 12;
    const TARGET_TOTAL_PROYEK = 5;

    /**
     * 1. SEED DUMMY FALLBACK DATA (Jika LocalStorage Kosong)
     */
    function seedFallbackData() {
        // Cek Survei Klien
        const existingSurveys = localStorage.getItem('lrs_survey_data');
        if (!existingSurveys || JSON.parse(existingSurveys).length === 0) {
            const seedSurveys = [
                {
                    respondent_id: 'PIC-804192',
                    client_name: 'Ir. Bambang Trihatmojo',
                    client_role: 'Project Director',
                    project_name: 'Kereta Cepat Jakarta-Bandung',
                    submitted_at: '2026-02-14T09:30:00Z',
                    saran_umum: 'Koordinasi tim persinyalan sangat memuaskan, pertahankan respon cepat saat uji coba kecepatan tinggi.',
                    answers: [
                        { question_id: 'A1', category: 'Layanan & Dukungan', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'A2', category: 'Layanan & Dukungan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'A3', category: 'Layanan & Dukungan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'A4', category: 'Layanan & Dukungan', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'A5', category: 'Layanan & Dukungan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'B1', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B2', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'B3', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B4', category: 'Pelaksanaan Proyek', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'B5', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B6', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B7', category: 'Pelaksanaan Proyek', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'C1', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'C2', category: 'Masa Pemeliharaan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'C3', category: 'Masa Pemeliharaan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'C4', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'C5', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'D1', category: 'K3L', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'D2', category: 'K3L', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'D3', category: 'K3L', kepentingan: 4, kepuasan: 5 },
                        { question_id: 'D4', category: 'K3L', kepentingan: 5, kepuasan: 5 }
                    ]
                },
                {
                    respondent_id: 'PIC-773105',
                    client_name: 'Drs. Hendro Wibowo',
                    client_role: 'Operations Lead',
                    project_name: 'LRT Jakarta Fase 1B',
                    submitted_at: '2026-02-12T14:15:00Z',
                    saran_umum: 'Penyampaian jadwal teknisi lapangan perlu lebih teratur agar tidak berbenturan dengan uji dinamis trainset.',
                    answers: [
                        { question_id: 'A1', category: 'Layanan & Dukungan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'A2', category: 'Layanan & Dukungan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'A3', category: 'Layanan & Dukungan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'A4', category: 'Layanan & Dukungan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'A5', category: 'Layanan & Dukungan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'B1', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B2', category: 'Pelaksanaan Proyek', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'B3', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'B4', category: 'Pelaksanaan Proyek', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'B5', category: 'Pelaksanaan Proyek', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'B6', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 3 },
                        { question_id: 'B7', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'C1', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'C2', category: 'Masa Pemeliharaan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'C3', category: 'Masa Pemeliharaan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'C4', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'C5', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'D1', category: 'K3L', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'D2', category: 'K3L', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'D3', category: 'K3L', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'D4', category: 'K3L', kepentingan: 5, kepuasan: 4 }
                    ]
                },
                {
                    respondent_id: 'PIC-651299',
                    client_name: 'Ir. Ahmad Zarkasih',
                    client_role: 'Substation Manager',
                    project_name: 'Gardu Traksi Manggarai',
                    submitted_at: '2026-02-10T11:00:00Z',
                    saran_umum: 'Harap perhatikan penataan kabel grounding dekat kabinet kontrol.',
                    answers: [
                        { question_id: 'A1', category: 'Layanan & Dukungan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'A2', category: 'Layanan & Dukungan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'A3', category: 'Layanan & Dukungan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'A4', category: 'Layanan & Dukungan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'A5', category: 'Layanan & Dukungan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B1', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B2', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B3', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B4', category: 'Pelaksanaan Proyek', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'B5', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B6', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 3 },
                        { question_id: 'B7', category: 'Pelaksanaan Proyek', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'C1', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'C2', category: 'Masa Pemeliharaan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'C3', category: 'Masa Pemeliharaan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'C4', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 3 },
                        { question_id: 'C5', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'D1', category: 'K3L', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'D2', category: 'K3L', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'D3', category: 'K3L', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'D4', category: 'K3L', kepentingan: 5, kepuasan: 4 }
                    ]
                },
                {
                    respondent_id: 'PIC-551902',
                    client_name: 'Siti Rahmawati, S.T.',
                    client_role: 'Quality Assurance Lead',
                    project_name: 'Kereta Cepat Jakarta-Bandung',
                    submitted_at: '2026-02-08T15:20:00Z',
                    saran_umum: 'Kompetensi teknisi Len sangat baik, pelatihan operator berjalan tepat waktu.',
                    answers: [
                        { question_id: 'A1', category: 'Layanan & Dukungan', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'A2', category: 'Layanan & Dukungan', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'A3', category: 'Layanan & Dukungan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'A4', category: 'Layanan & Dukungan', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'A5', category: 'Layanan & Dukungan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B1', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B2', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'B3', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'B4', category: 'Pelaksanaan Proyek', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'B5', category: 'Pelaksanaan Proyek', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'B6', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B7', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'C1', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'C2', category: 'Masa Pemeliharaan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'C3', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'C4', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'C5', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'D1', category: 'K3L', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'D2', category: 'K3L', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'D3', category: 'K3L', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'D4', category: 'K3L', kepentingan: 5, kepuasan: 5 }
                    ]
                },
                {
                    respondent_id: 'PIC-441028',
                    client_name: 'Budi Santoso, M.T.',
                    client_role: 'Signalling Engineer',
                    project_name: 'LRT Jakarta Fase 1B',
                    submitted_at: '2026-02-05T10:45:00Z',
                    saran_umum: 'Respon keluhan teknis sudah bagus, pastikan ketersediaan modul cadangan di depo.',
                    answers: [
                        { question_id: 'A1', category: 'Layanan & Dukungan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'A2', category: 'Layanan & Dukungan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'A3', category: 'Layanan & Dukungan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'A4', category: 'Layanan & Dukungan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'A5', category: 'Layanan & Dukungan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'B1', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B2', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B3', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B4', category: 'Pelaksanaan Proyek', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'B5', category: 'Pelaksanaan Proyek', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'B6', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 3 },
                        { question_id: 'B7', category: 'Pelaksanaan Proyek', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'C1', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'C2', category: 'Masa Pemeliharaan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'C3', category: 'Masa Pemeliharaan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'C4', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 3 },
                        { question_id: 'C5', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'D1', category: 'K3L', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'D2', category: 'K3L', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'D3', category: 'K3L', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'D4', category: 'K3L', kepentingan: 5, kepuasan: 5 }
                    ]
                },
                {
                    respondent_id: 'PIC-332110',
                    client_name: 'Faisal Akbar, S.T.',
                    client_role: 'Site Inspector',
                    project_name: 'Gardu Traksi Manggarai',
                    submitted_at: '2026-02-02T16:10:00Z',
                    saran_umum: 'Secara umum memuaskan, dokumen hasil kalibrasi perlu diserahkan lebih cepat.',
                    answers: [
                        { question_id: 'A1', category: 'Layanan & Dukungan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'A2', category: 'Layanan & Dukungan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'A3', category: 'Layanan & Dukungan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'A4', category: 'Layanan & Dukungan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'A5', category: 'Layanan & Dukungan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'B1', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B2', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B3', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B4', category: 'Pelaksanaan Proyek', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'B5', category: 'Pelaksanaan Proyek', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'B6', category: 'Pelaksanaan Proyek', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'B7', category: 'Pelaksanaan Proyek', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'C1', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'C2', category: 'Masa Pemeliharaan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'C3', category: 'Masa Pemeliharaan', kepentingan: 4, kepuasan: 4 },
                        { question_id: 'C4', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'C5', category: 'Masa Pemeliharaan', kepentingan: 5, kepuasan: 4 },
                        { question_id: 'D1', category: 'K3L', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'D2', category: 'K3L', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'D3', category: 'K3L', kepentingan: 5, kepuasan: 5 },
                        { question_id: 'D4', category: 'K3L', kepentingan: 5, kepuasan: 4 }
                    ]
                }
            ];
            localStorage.setItem('lrs_survey_data', JSON.stringify(seedSurveys));
        }

        // Cek Keluhan Klien
        const existingComplaints = localStorage.getItem('lrs_complaint_data');
        if (!existingComplaints || JSON.parse(existingComplaints).length === 0) {
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
                    created_at: new Date(now.getTime() - 36 * 3600 * 1000).toISOString(), // 36 jam lalu (Melewati Batas)
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
                    project_name: 'LRT Jakarta Fase 1B',
                    category: 'Layanan',
                    subject: 'Keterlambatan Pengiriman Berita Acara Uji Beban',
                    description: 'Dokumen berita acara pengujian beban gardu traksi Velodrome belum diterima oleh konsultan pengawas.',
                    urgency: 'Sedang',
                    status: 'In Progress',
                    created_at: new Date(now.getTime() - 14 * 3600 * 1000).toISOString(), // 14 jam lalu (Mendekati Batas)
                    sla_hours: 24,
                    pic: 'Sekretariat Proyek LRS',
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
                    project_name: 'Gardu Traksi Manggarai',
                    category: 'K3L',
                    subject: 'Penataan Kabel Pentanahan Dekat Akses Jalan Teknisi',
                    description: 'Terdapat kabel grounding sementara yang melintang di koridor tanpa pelindung kabel (cable protector), berpotensi bahaya tersandung.',
                    urgency: 'Tinggi',
                    status: 'In Progress',
                    created_at: new Date(now.getTime() - 6 * 3600 * 1000).toISOString(), // 6 jam lalu (Tepat Waktu)
                    sla_hours: 12,
                    pic: 'Koordinator K3L Lapangan',
                    rca: 'Kabel dipasang saat pengujian cepat malam hari dan belum dirapikan oleh teknisi.',
                    capa: 'Pemasangan pelindung karet lantai dan penataan ulang jalur kabel permanen.',
                    audit_trail: [
                        { timestamp: new Date(now.getTime() - 6 * 3600 * 1000).toISOString(), status: 'Open', note: 'Tiket tercatat dari temuan inspeksi bersama.' },
                        { timestamp: new Date(now.getTime() - 2 * 3600 * 1000).toISOString(), status: 'In Progress', note: 'Tim teknis menuju lokasi untuk perapihan.' }
                    ]
                },
                {
                    ticket_id: 'TKT-2026-004',
                    client_name: 'Drs. Hendro Wibowo',
                    project_name: 'LRT Jakarta Fase 1B',
                    category: 'Produk',
                    subject: 'Layar Monitor CCTV Ruang Kontrol Mati Sesaat',
                    description: 'Monitor panel kendali jalur 2 sempat blank selama 3 menit sebelum restart otomatis.',
                    urgency: 'Sedang',
                    status: 'Resolved',
                    created_at: new Date(now.getTime() - 48 * 3600 * 1000).toISOString(),
                    resolved_at: new Date(now.getTime() - 32 * 3600 * 1000).toISOString(), // 16 jam kerja (Tepat Waktu)
                    sla_hours: 24,
                    pic: 'Divisi Hardware LRS',
                    rca: 'Adaptor daya monitor mengalami degradasi kapasitor internal.',
                    capa: 'Penggantian unit power supply monitor baru dan pengecekan cadangan.',
                    audit_trail: [
                        { timestamp: new Date(now.getTime() - 48 * 3600 * 1000).toISOString(), status: 'Open', note: 'Tiket dibuat oleh operator stasiun.' },
                        { timestamp: new Date(now.getTime() - 40 * 3600 * 1000).toISOString(), status: 'In Progress', note: 'Penggantian modul perangkat keras.' },
                        { timestamp: new Date(now.getTime() - 32 * 3600 * 1000).toISOString(), status: 'Resolved', note: 'Monitor berfungsi normal dan telah diverifikasi PIC.' }
                    ]
                },
                {
                    ticket_id: 'TKT-2026-005',
                    client_name: 'Ir. Bambang Trihatmojo',
                    project_name: 'Kereta Cepat Jakarta-Bandung',
                    category: 'Teknis',
                    subject: 'Pembaruan Patch Firmware Radio Komunikasi Kereta',
                    description: 'Permintaan pembaruan patch keamanan radio komunikasi pada kabin trainset 04.',
                    urgency: 'Rendah',
                    status: 'Resolved',
                    created_at: new Date(now.getTime() - 72 * 3600 * 1000).toISOString(),
                    resolved_at: new Date(now.getTime() - 56 * 3600 * 1000).toISOString(), // 16 jam (Tepat Waktu)
                    sla_hours: 48,
                    pic: 'Tim Software Persinyalan',
                    rca: 'Kebutuhan integrasi protokol enkripsi terbaru.',
                    capa: 'Pelaksanaan instalasi firmware patch v2.4 dan pengetesan komunikasi.',
                    audit_trail: [
                        { timestamp: new Date(now.getTime() - 72 * 3600 * 1000).toISOString(), status: 'Open', note: 'Permintaan pembaruan diterima.' },
                        { timestamp: new Date(now.getTime() - 60 * 3600 * 1000).toISOString(), status: 'In Progress', note: 'Proses flashing firmware di depo Tegalluar.' },
                        { timestamp: new Date(now.getTime() - 56 * 3600 * 1000).toISOString(), status: 'Resolved', note: 'Pengujian transmisi suara jernih dan berhasil.' }
                    ]
                },
                {
                    ticket_id: 'TKT-2026-006',
                    client_name: 'Ir. Ahmad Zarkasih',
                    project_name: 'Gardu Traksi Manggarai',
                    category: 'Layanan',
                    subject: 'Permintaan Buku Panduan Pemeliharaan Versi Cetak',
                    description: 'Klien meminta 3 eksemplar tambahan manual book pemeliharaan gardu traksi untuk teknisi lapangan.',
                    urgency: 'Rendah',
                    status: 'Resolved',
                    created_at: new Date(now.getTime() - 96 * 3600 * 1000).toISOString(),
                    resolved_at: new Date(now.getTime() - 76 * 3600 * 1000).toISOString(), // 20 jam (Tepat Waktu)
                    sla_hours: 48,
                    pic: 'Dokumentasi Teknis LRS',
                    rca: 'Kebutuhan dokumen lapangan tambahan saat pergantian shift.',
                    capa: 'Pencetakan dan penyerahan buku manual disertai tanda terima resmi.',
                    audit_trail: [
                        { timestamp: new Date(now.getTime() - 96 * 3600 * 1000).toISOString(), status: 'Open', note: 'Permintaan tercatat di sistem.' },
                        { timestamp: new Date(now.getTime() - 84 * 3600 * 1000).toISOString(), status: 'In Progress', note: 'Pencetakan dokumen teknis.' },
                        { timestamp: new Date(now.getTime() - 76 * 3600 * 1000).toISOString(), status: 'Resolved', note: 'Buku panduan diterima staf administrasi klien.' }
                    ]
                }
            ];
            localStorage.setItem('lrs_complaint_data', JSON.stringify(seedComplaints));
        }
    }

    /**
     * 2. NAVIGASI TABS SWITCHER
     */
    window.switchAdminSection = function (sectionKey) {
        const secOverview = document.getElementById('section-overview');
        const secKanban = document.getElementById('section-kanban');

        const btnOverview = document.getElementById('tab-btn-overview');
        const btnKanban = document.getElementById('tab-btn-kanban');

        if (!secOverview || !secKanban) return;

        if (sectionKey === 'overview') {
            secOverview.classList.remove('hidden');
            secKanban.classList.add('hidden');

            if (btnOverview) {
                btnOverview.className = 'px-4 py-2 rounded-xl text-xs font-bold transition-all bg-[#003B73] text-white shadow-sm flex items-center gap-2';
            }
            if (btnKanban) {
                btnKanban.className = 'px-4 py-2 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-100 flex items-center gap-2';
            }

            renderOverviewData();
        } else if (sectionKey === 'kanban') {
            secOverview.classList.add('hidden');
            secKanban.classList.remove('hidden');

            if (btnKanban) {
                btnKanban.className = 'px-4 py-2 rounded-xl text-xs font-bold transition-all bg-[#003B73] text-white shadow-sm flex items-center gap-2';
            }
            if (btnOverview) {
                btnOverview.className = 'px-4 py-2 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-100 flex items-center gap-2';
            }

            renderKanbanBoard();
        }
    };

    /**
     * 3. RENDER OVERVIEW DATA & METRIK KEPUASAN
     */
    function renderOverviewData() {
        const surveys = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');
        const complaints = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');

        const selectedProject = document.getElementById('filter-overview-project')?.value || 'all';

        // Filter Survei berdasarkan Proyek jika dipilih
        const filteredSurveys = selectedProject === 'all'
            ? surveys
            : surveys.filter(s => (s.project_name || '').toLowerCase() === selectedProject.toLowerCase());

        let totalScorePossible = 0;
        let totalScoreActual = 0;
        let sumKepentingan = 0;
        let sumKepuasan = 0;
        let countAnswers = 0;

        // Metrik per Dimensi
        const dimStats = {
            'A': { title: 'Layanan & Dukungan', actual: 0, possible: 0, imp: 0, sat: 0, count: 0 },
            'B': { title: 'Pelaksanaan Proyek', actual: 0, possible: 0, imp: 0, sat: 0, count: 0 },
            'C': { title: 'Masa Pemeliharaan', actual: 0, possible: 0, imp: 0, sat: 0, count: 0 },
            'D': { title: 'K3L & Keselamatan', actual: 0, possible: 0, imp: 0, sat: 0, count: 0 }
        };

        const activeProjectsSet = new Set();
        const respondentsSet = new Set();
        const lowSatisfactionIndicators = [];

        filteredSurveys.forEach(survey => {
            if (survey.project_name) activeProjectsSet.add(survey.project_name);
            if (survey.respondent_id) respondentsSet.add(survey.respondent_id);

            (survey.answers || []).forEach(ans => {
                const qId = ans.question_id || '';
                const dimKey = qId.charAt(0);
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
                    }

                    // Deteksi temuan kepuasan <= 2 untuk tindak lanjut
                    if (sat <= 2) {
                        lowSatisfactionIndicators.push({
                            source: 'Survei Kepuasan',
                            project: survey.project_name || 'Proyek LRS',
                            id: qId,
                            text: ans.pertanyaan || `Pertanyaan ${qId}`,
                            keterangan: ans.keterangan || 'Penilaian kepuasan klien rendah butuh solusi cepat',
                            score: sat
                        });
                    }
                }
            });
        });

        // Hitung Indeks Kepuasan Persentase
        let csiPercentage = totalScorePossible > 0
            ? (totalScoreActual / totalScorePossible) * 100
            : 85.70;

        csiPercentage = Math.round(csiPercentage * 100) / 100;

        const avgKepentingan = countAnswers > 0 ? (sumKepentingan / countAnswers).toFixed(2) : '4.62';
        const avgKepuasan = countAnswers > 0 ? (sumKepuasan / countAnswers).toFixed(2) : '4.28';
        const kesesuaianNumber = parseFloat(avgKepuasan) - parseFloat(avgKepentingan);
        const kesesuaianStr = (kesesuaianNumber >= 0 ? `+` : ``) + kesesuaianNumber.toFixed(2);

        // Update UI Angka Utama & Gauge
        const csiNumberEl = document.getElementById('csi-score-number');
        const gaugeCenterEl = document.getElementById('gauge-center-val');
        const badgeEl = document.getElementById('csi-status-badge');
        const interpEl = document.getElementById('csi-interpretation-text');

        if (csiNumberEl) csiNumberEl.textContent = `${csiPercentage.toFixed(2)}%`;
        if (gaugeCenterEl) gaugeCenterEl.textContent = `${csiPercentage.toFixed(1)}%`;

        // Update Badge & Narasi Sinkron: Target Mutu >= 80.00%
        if (badgeEl) {
            if (csiPercentage >= TARGET_MUTU_PERCENT) {
                badgeEl.className = 'px-3 py-1 rounded-full text-xs font-extrabold border bg-emerald-50 text-emerald-700 border-emerald-200';
                badgeEl.textContent = 'Memenuhi Target';
                if (interpEl) {
                    interpEl.textContent = 'Kinerja layanan sudah mencapai target perusahaan (≥ 80%).';
                }
            } else {
                badgeEl.className = 'px-3 py-1 rounded-full text-xs font-extrabold border bg-amber-50 text-amber-700 border-amber-200';
                badgeEl.textContent = 'Di Bawah Target';
                if (interpEl) {
                    interpEl.textContent = `Skor saat ini (${csiPercentage.toFixed(2)}%) masih di bawah target (80%). Fokus perbaikan pada area yang nilainya rendah.`;
                }
            }
        }

        // Update 4 Kotak Ringkasan Sejajar di Bawah Kartu Utama
        const elAvgImp = document.getElementById('avg-kepentingan-val');
        const elAvgSat = document.getElementById('avg-kepuasan-val');
        const elGapVal = document.getElementById('gap-index-val');
        const elGapLabel = document.getElementById('gap-label-text');
        const elRatioVal = document.getElementById('participation-ratio-val');
        const elRatioSub = document.getElementById('participation-subtext');

        if (elAvgImp) elAvgImp.textContent = avgKepentingan;
        if (elAvgSat) elAvgSat.textContent = avgKepuasan;

        if (elGapVal) {
            elGapVal.textContent = kesesuaianStr;
            if (kesesuaianNumber >= 0) {
                elGapVal.className = 'text-base md:text-lg font-black text-emerald-600';
                if (elGapLabel) {
                    elGapLabel.className = 'block text-[10px] text-emerald-600 font-bold';
                    elGapLabel.textContent = '(Melampaui Harapan)';
                }
            } else {
                elGapVal.className = 'text-base md:text-lg font-black text-rose-600';
                if (elGapLabel) {
                    elGapLabel.className = 'block text-[10px] text-rose-600 font-bold';
                    elGapLabel.textContent = '(Di Bawah Harapan)';
                }
            }
        }

        // Partisipasi Responden
        const picCount = respondentsSet.size || (filteredSurveys.length > 0 ? filteredSurveys.length : 6);
        const activeProjectsCount = activeProjectsSet.size || (selectedProject !== 'all' ? 1 : 3);
        const targetPics = selectedProject === 'all' ? TARGET_TOTAL_PIC : 3;
        const targetProjects = TARGET_TOTAL_PROYEK;
        const partPct = Math.round((picCount / targetPics) * 100);

        if (elRatioVal) {
            elRatioVal.textContent = `${picCount} dari ${targetPics} PIC (${partPct}%)`;
        }
        if (elRatioSub) {
            elRatioSub.textContent = selectedProject === 'all'
                ? `dari ${activeProjectsCount} / ${targetProjects} Proyek Aktif`
                : `Proyek: ${selectedProject}`;
        }

        // Update 4 Kartu Dimensi
        const ratioFooterText = `${activeProjectsCount}/${targetProjects} Proyek • ${picCount}/${targetPics} PIC`;

        ['A', 'B', 'C', 'D'].forEach(dim => {
            const st = dimStats[dim];
            const pct = st.possible > 0
                ? (st.actual / st.possible) * 100
                : (dim === 'A' ? 88.2 : dim === 'B' ? 84.6 : dim === 'C' ? 83.5 : 90.5);
            const fixedPct = Math.round(pct * 10) / 10;

            const scoreEl = document.getElementById(`dim-score-${dim}`);
            const barEl = document.getElementById(`dim-bar-${dim}`);
            const ratioEl = document.getElementById(`dim-ratio-${dim}`);
            const badgeDimEl = document.getElementById(`dim-badge-${dim}`);

            if (scoreEl) scoreEl.textContent = `${fixedPct}%`;
            if (barEl) barEl.style.width = `${fixedPct}%`;
            if (ratioEl) ratioEl.textContent = ratioFooterText;

            // Status label konsisten mengikuti skor persentase
            if (badgeDimEl) {
                if (fixedPct >= 85.0) {
                    badgeDimEl.className = 'text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-md border text-emerald-700 bg-emerald-50 border-emerald-200';
                    badgeDimEl.textContent = 'Sangat Memuaskan';
                } else if (fixedPct >= 75.0) {
                    badgeDimEl.className = 'text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-md border text-blue-700 bg-blue-50 border-blue-200';
                    badgeDimEl.textContent = 'Memuaskan';
                } else if (fixedPct >= 65.0) {
                    badgeDimEl.className = 'text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-md border text-amber-700 bg-amber-50 border-amber-200';
                    badgeDimEl.textContent = 'Perlu Perhatian';
                } else {
                    badgeDimEl.className = 'text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-md border text-rose-700 bg-rose-50 border-rose-200';
                    badgeDimEl.textContent = 'Perlu Perbaikan';
                }
            }
        });

        // Update Gauge Chart
        renderCsiGaugeChart(csiPercentage);

        // Update Grafik Harapan vs Kenyataan & Kesimpulan Otomatis
        renderIpaComboChart(dimStats);

        // Update Pemantauan & Tindak Lanjut
        renderPemantauanDanTindakLanjut(complaints, lowSatisfactionIndicators, selectedProject);
    }

    /**
     * 4. GAUGE CHART (Doughnut 180 Derajat)
     */
    function renderCsiGaugeChart(csiScore) {
        const canvas = document.getElementById('csiGaugeChart');
        if (!canvas || typeof Chart === 'undefined') return;

        if (csiChartInstance) {
            csiChartInstance.destroy();
        }

        const score = Math.min(Math.max(csiScore, 0), 100);
        const remainder = 100 - score;

        // Warna dinamis segmen aktif
        let scoreColor = '#10B981'; // Green (>= 80%)
        if (score < 65) scoreColor = '#EF4444'; // Red
        else if (score < 80) scoreColor = '#F59E0B'; // Amber

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

    /**
     * 5. GRAFIK HARAPAN VS. KENYATAAN & KESIMPULAN UTAMA OTOMATIS
     */
    function renderIpaComboChart(dimStats) {
        const canvas = document.getElementById('ipaComboChart');
        if (!canvas || typeof Chart === 'undefined') return;

        if (ipaChartInstance) {
            ipaChartInstance.destroy();
        }

        const labels = ['A. Layanan', 'B. Proyek', 'C. Pemeliharaan', 'D. K3L'];
        const dimKeys = ['A', 'B', 'C', 'D'];

        const impData = [
            dimStats.A.count > 0 ? (dimStats.A.imp / dimStats.A.count).toFixed(2) : '4.65',
            dimStats.B.count > 0 ? (dimStats.B.imp / dimStats.B.count).toFixed(2) : '4.58',
            dimStats.C.count > 0 ? (dimStats.C.imp / dimStats.C.count).toFixed(2) : '4.45',
            dimStats.D.count > 0 ? (dimStats.D.imp / dimStats.D.count).toFixed(2) : '4.80'
        ];
        const satData = [
            dimStats.A.count > 0 ? (dimStats.A.sat / dimStats.A.count).toFixed(2) : '4.41',
            dimStats.B.count > 0 ? (dimStats.B.sat / dimStats.B.count).toFixed(2) : '4.23',
            dimStats.C.count > 0 ? (dimStats.C.sat / dimStats.C.count).toFixed(2) : '4.18',
            dimStats.D.count > 0 ? (dimStats.D.sat / dimStats.D.count).toFixed(2) : '4.52'
        ];

        const ctx = canvas.getContext('2d');
        ipaChartInstance = new Chart(ctx, {
            data: {
                labels: labels,
                datasets: [
                    {
                        type: 'line',
                        label: 'Kepuasan yang Dirasakan',
                        data: satData.map(Number),
                        borderColor: '#F59E0B',
                        backgroundColor: '#F59E0B',
                        borderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointBackgroundColor: '#F59E0B',
                        tension: 0.2,
                        yAxisID: 'y'
                    },
                    {
                        type: 'bar',
                        label: 'Harapan Klien (Penting)',
                        data: impData.map(Number),
                        backgroundColor: '#003B73',
                        borderRadius: 8,
                        barPercentage: 0.5,
                        yAxisID: 'y'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 600 },
                scales: {
                    y: {
                        min: 3.0,
                        max: 5.0,
                        ticks: {
                            stepSize: 0.5,
                            font: { size: 10, weight: 'bold' }
                        },
                        grid: { color: '#F1F5F9' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, weight: 'bold' } }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: '#001D3D',
                        padding: 10,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 11 },
                        callbacks: {
                            label: function (context) {
                                return ` ${context.dataset.label}: ${context.parsed.y.toFixed(2)} dari skala 5.0`;
                            }
                        }
                    }
                }
            }
        });

        // Hitung Otomatis Kesimpulan Utama: Cari dimensi dengan selisih (Kepuasan - Harapan) paling rendah/negatif
        const dimFullNames = {
            'A': 'Layanan & Dukungan',
            'B': 'Pelaksanaan Proyek',
            'C': 'Masa Pemeliharaan',
            'D': 'K3L & Keselamatan'
        };

        let minDiff = Infinity;
        let priorityDim = null;

        dimKeys.forEach((key, idx) => {
            const impVal = parseFloat(impData[idx]);
            const satVal = parseFloat(satData[idx]);
            const diff = satVal - impVal;

            if (diff < minDiff) {
                minDiff = diff;
                priorityDim = {
                    key: key,
                    name: dimFullNames[key],
                    sat: satVal.toFixed(2),
                    imp: impVal.toFixed(2),
                    diff: diff
                };
            }
        });

        const conclusionEl = document.getElementById('chart-main-conclusion');
        if (conclusionEl && priorityDim) {
            if (priorityDim.diff < 0) {
                conclusionEl.innerHTML = `<strong>Perlu Perhatian:</strong> Pada bagian <strong>${priorityDim.name}</strong>, kepuasan klien (${priorityDim.sat}) masih di bawah harapan mereka (${priorityDim.imp}). Area ini sebaiknya menjadi prioritas perbaikan.`;
            } else {
                conclusionEl.innerHTML = `<strong>Kabar Baik:</strong> Seluruh area layanan berhasil melampaui harapan klien. Kepuasan tertinggi diraih pada bagian <strong>${priorityDim.name}</strong> (${priorityDim.sat} dari skala 5.0).`;
            }
        }
    }

    /**
     * 6. PEMANTAUAN & TINDAK LANJUT (Ketepatan Waktu, Masalah Prioritas, Status Layanan Dinamis)
     */
    function renderPemantauanDanTindakLanjut(complaints, lowSatisfactionIndicators, selectedProject) {
        // Filter keluhan sesuai proyek jika dipilih
        const filteredComplaints = selectedProject === 'all'
            ? complaints
            : complaints.filter(c => (c.project_name || '').toLowerCase() === selectedProject.toLowerCase());

        const total = filteredComplaints.length;
        let onTime = 0;
        const now = new Date();

        filteredComplaints.forEach(c => {
            const createdAt = new Date(c.created_at);
            const slaLimit = (c.sla_hours || 24) * 3600 * 1000;

            if (c.status === 'Resolved') {
                const resolvedAt = c.resolved_at ? new Date(c.resolved_at) : now;
                if ((resolvedAt - createdAt) <= slaLimit) {
                    onTime++;
                }
            } else {
                const diff = now - createdAt;
                if (diff <= slaLimit) {
                    onTime++;
                }
            }
        });

        const onTimePct = total > 0 ? Math.round((onTime / total) * 100) : 50;

        const elSlaPct = document.getElementById('sla-adherence-pct');
        const elSlaBar = document.getElementById('sla-adherence-bar');
        const elSlaDetail = document.getElementById('sla-detail-text');

        if (elSlaPct) elSlaPct.textContent = `${onTimePct}% Tepat Waktu`;
        if (elSlaBar) {
            elSlaBar.style.width = `${onTimePct}%`;
            elSlaBar.className = onTimePct >= 90
                ? 'h-full bg-emerald-500 rounded-full transition-all duration-500'
                : (onTimePct >= 70
                    ? 'h-full bg-amber-500 rounded-full transition-all duration-500'
                    : 'h-full bg-rose-500 rounded-full transition-all duration-500');
        }
        if (elSlaDetail) elSlaDetail.textContent = `${onTime} dari ${total} keluhan selesai sesuai target`;

        // Daftar Masalah "Perlu Segera Ditindaklanjuti"
        // Sumber: Keluhan 'Open' / 'In Progress' yang mendesak atau overdue, ATAU kepuasan survei <= 2
        const priorityIssues = [];

        filteredComplaints.forEach(c => {
            if (c.status !== 'Resolved') {
                const createdAt = new Date(c.created_at);
                const slaLimit = (c.sla_hours || 24) * 3600 * 1000;
                const isOverdue = (now - createdAt) > slaLimit;

                let tagText = 'Mendesak';
                let tagClass = 'bg-purple-100 text-purple-700 border border-purple-200';

                if (isOverdue) {
                    tagText = 'Melewati Batas Waktu';
                    tagClass = 'bg-rose-100 text-rose-700 border border-rose-200';
                }

                priorityIssues.push({
                    type: 'Keluhan Klien',
                    tag: tagText,
                    tagClass: tagClass,
                    title: c.subject || c.ticket_id,
                    project: c.project_name || 'Proyek LRS',
                    ticketId: c.ticket_id
                });
            }
        });

        lowSatisfactionIndicators.forEach(ind => {
            priorityIssues.push({
                type: 'Survei Kepuasan',
                tag: `Nilai ${ind.score}/5`,
                tagClass: 'bg-amber-100 text-amber-700 border border-amber-200',
                title: ind.keterangan || ind.text,
                project: ind.project
            });
        });

        const openCountEl = document.getElementById('open-capa-count');
        const containerEl = document.getElementById('capa-alerts-container');

        if (openCountEl) {
            openCountEl.textContent = `${priorityIssues.length} Masalah Terbuka`;
        }

        if (containerEl) {
            if (priorityIssues.length === 0) {
                containerEl.innerHTML = `
                    <div class="p-3 text-center text-xs text-slate-400 bg-slate-50 rounded-xl">
                        Tidak ada masalah terbuka. Semua keluhan dan masukan klien tertangani dengan baik.
                    </div>
                `;
            } else {
                containerEl.innerHTML = priorityIssues.map(item => `
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-slate-50/80 hover:bg-white hover:border-slate-300 shadow-2xs hover:shadow-xs transition-all flex items-start justify-between gap-2.5">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 mb-1">
                                <span class="px-2 py-0.5 text-[9px] font-extrabold uppercase rounded shadow-2xs ${item.tagClass}">
                                    ${item.tag}
                                </span>
                                <span class="text-[10px] font-semibold text-slate-400 truncate">
                                    ${item.project}
                                </span>
                            </div>
                            <div class="text-xs font-bold text-slate-800 line-clamp-1 leading-snug">
                                ${item.title}
                            </div>
                        </div>
                        ${item.ticketId ? `
                            <button type="button"
                                    onclick="openTicketModal('${item.ticketId}')"
                                    class="shrink-0 text-[10px] font-bold text-[#003B73] hover:text-[#002B54] px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 border border-blue-200 shadow-2xs transition-colors">
                                Lihat Detail
                            </button>
                        ` : ''}
                    </div>
                `).join('');
            }
        }

        // Status Layanan Dinamis di Pojok Kanan Bawah
        const statusLayananBadge = document.getElementById('status-layanan-badge');
        if (statusLayananBadge) {
            const hasUnresolvedIssues = priorityIssues.length > 0;
            if (onTimePct < 90 || hasUnresolvedIssues) {
                statusLayananBadge.className = 'font-bold text-amber-600';
                statusLayananBadge.textContent = '● Status Layanan: Perlu Perhatian';
            } else {
                statusLayananBadge.className = 'font-bold text-emerald-600';
                statusLayananBadge.textContent = '● Status Layanan: Baik';
            }
        }
    }

    /**
     * 7. RENDER PAPAN KELUHAN KANBAN
     */
    function renderKanbanBoard() {
        const complaints = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');

        const searchQuery = (document.getElementById('filter-kanban-search')?.value || '').toLowerCase().trim();
        const selectedCategory = document.getElementById('filter-kanban-category')?.value || 'all';

        // Filter tickets
        const filtered = complaints.filter(ticket => {
            const matchCategory = selectedCategory === 'all' || ticket.category === selectedCategory;
            const matchSearch = !searchQuery ||
                (ticket.ticket_id || '').toLowerCase().includes(searchQuery) ||
                (ticket.client_name || '').toLowerCase().includes(searchQuery) ||
                (ticket.project_name || '').toLowerCase().includes(searchQuery) ||
                (ticket.subject || '').toLowerCase().includes(searchQuery);

            return matchCategory && matchSearch;
        });

        // Hitung SLA Ringkasan Pills
        let countOverdue = 0;
        let countWarning = 0;
        let countOnTrack = 0;
        const now = new Date();

        filtered.forEach(ticket => {
            const createdAt = new Date(ticket.created_at);
            const slaLimit = (ticket.sla_hours || 24) * 3600 * 1000;
            const elapsed = now - createdAt;
            const remaining = slaLimit - elapsed;

            if (ticket.status !== 'Resolved') {
                if (remaining < 0) {
                    countOverdue++;
                } else if (remaining < 6 * 3600 * 1000) {
                    countWarning++;
                } else {
                    countOnTrack++;
                }
            } else {
                countOnTrack++;
            }
        });

        const pillOverdue = document.getElementById('sla-pill-overdue');
        const pillWarning = document.getElementById('sla-pill-warning');
        const pillOnTrack = document.getElementById('sla-pill-ontrack');

        if (pillOverdue) pillOverdue.textContent = `${countOverdue} Melewati Batas`;
        if (pillWarning) pillWarning.textContent = `${countWarning} Mendekati Batas`;
        if (pillOnTrack) pillOnTrack.textContent = `${countOnTrack} Tepat Waktu`;

        // Pisahkan ke dalam 3 kolom
        const cols = {
            'Open': filtered.filter(t => t.status === 'Open'),
            'In Progress': filtered.filter(t => t.status === 'In Progress'),
            'Resolved': filtered.filter(t => t.status === 'Resolved')
        };

        // Render per Kolom
        renderColumnCards('cards-col-open', cols['Open']);
        renderColumnCards('cards-col-progress', cols['In Progress']);
        renderColumnCards('cards-col-resolved', cols['Resolved']);

        // Update Counter
        const countOpenEl = document.getElementById('count-col-open');
        const countProgEl = document.getElementById('count-col-progress');
        const countResEl = document.getElementById('count-col-resolved');

        if (countOpenEl) countOpenEl.textContent = cols['Open'].length;
        if (countProgEl) countProgEl.textContent = cols['In Progress'].length;
        if (countResEl) countResEl.textContent = cols['Resolved'].length;
    }

    /**
     * 8. RENDER KARTU TIKET DI KOLOM KANBAN
     */
    function renderColumnCards(containerId, tickets) {
        const container = document.getElementById(containerId);
        if (!container) return;

        if (tickets.length === 0) {
            container.innerHTML = `
                <div class="h-32 border-2 border-dashed border-slate-200 rounded-xl flex items-center justify-center text-xs text-slate-400 font-medium">
                    Tidak ada tiket
                </div>
            `;
            return;
        }

        const now = new Date();

        container.innerHTML = tickets.map(ticket => {
            const createdAt = new Date(ticket.created_at);
            const slaLimit = (ticket.sla_hours || 24) * 3600 * 1000;
            const elapsed = now - createdAt;
            const remaining = slaLimit - elapsed;

            let slaBadgeHtml = '';
            if (ticket.status === 'Resolved') {
                slaBadgeHtml = `
                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100 flex items-center gap-1">
                        ✓ Selesai
                    </span>
                `;
            } else if (remaining < 0) {
                const overdueHours = Math.abs(Math.round(remaining / 3600000));
                slaBadgeHtml = `
                    <span class="text-[10px] font-extrabold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200 flex items-center gap-1">
                        ⏱ Lewat ${overdueHours}j
                    </span>
                `;
            } else {
                const remHours = Math.round(remaining / 3600000);
                const isWarning = remHours <= 6;
                slaBadgeHtml = `
                    <span class="text-[10px] font-bold ${isWarning ? 'text-amber-700 bg-amber-50 border-amber-200' : 'text-slate-600 bg-slate-100 border-slate-200'} px-2 py-0.5 rounded-md border flex items-center gap-1">
                        ⏱ Sisa ${remHours}j
                    </span>
                `;
            }

            const priorityBadgeClass = ticket.urgency === 'Kritis'
                ? 'bg-rose-100 text-rose-700 border-rose-200'
                : (ticket.urgency === 'Tinggi' ? 'bg-amber-100 text-amber-700 border-amber-200' : 'bg-slate-100 text-slate-600 border-slate-200');

            return `
                <div class="kanban-card bg-white rounded-xl p-3.5 border border-slate-200/90 shadow-[0_2px_6px_rgba(15,23,42,0.04),0_1px_2px_rgba(15,23,42,0.02)] hover:shadow-[0_8px_20px_-2px_rgba(15,23,42,0.08)] hover:border-slate-300 hover:-translate-y-0.5 transition-all duration-200 cursor-grab active:cursor-grabbing select-none"
                     draggable="true"
                     ondragstart="handleDragStart(event, '${ticket.ticket_id}')">

                    <div class="flex items-center justify-between gap-2 mb-1.5">
                        <span class="text-xs font-black text-[#003B73]">#${ticket.ticket_id}</span>
                        <div class="flex items-center gap-1">
                            <span class="text-[10px] font-extrabold uppercase px-1.5 py-0.5 rounded border shadow-2xs ${priorityBadgeClass}">
                                ${ticket.urgency || 'Normal'}
                            </span>
                        </div>
                    </div>

                    <div class="text-[11px] font-semibold text-slate-400 mb-1 flex items-center gap-1">
                        <span>🏢</span>
                        <span class="truncate">${ticket.project_name || 'Proyek LRS'}</span>
                    </div>

                    <h4 class="text-xs font-bold text-slate-800 line-clamp-2 leading-snug mb-2.5">
                        ${ticket.subject}
                    </h4>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                        ${slaBadgeHtml}
                        <button type="button"
                                onclick="openTicketModal('${ticket.ticket_id}')"
                                class="text-[11px] font-bold text-[#003B73] hover:text-[#002B54] flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-blue-50/70 transition-colors">
                            <span>Detail</span>
                            <span>→</span>
                        </button>
                    </div>

                </div>
            `;
        }).join('');
    }

    /**
     * 9. HTML5 DRAG & DROP HANDLERS
     */
    window.handleDragStart = function (e, ticketId) {
        activeTicketId = ticketId;
        e.dataTransfer.setData('text/plain', ticketId);
        e.dataTransfer.effectAllowed = 'move';
    };

    window.handleDragOver = function (e, targetStatus) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        const colEl = document.getElementById(
            targetStatus === 'Open' ? 'col-open' : (targetStatus === 'In Progress' ? 'col-progress' : 'col-resolved')
        );
        if (colEl) colEl.classList.add('bg-blue-50/70', 'border-blue-300');
    };

    window.handleDragLeave = function (e, targetStatus) {
        const colEl = document.getElementById(
            targetStatus === 'Open' ? 'col-open' : (targetStatus === 'In Progress' ? 'col-progress' : 'col-resolved')
        );
        if (colEl) colEl.classList.remove('bg-blue-50/70', 'border-blue-300');
    };

    window.handleDrop = function (e, targetStatus) {
        e.preventDefault();
        const colEl = document.getElementById(
            targetStatus === 'Open' ? 'col-open' : (targetStatus === 'In Progress' ? 'col-progress' : 'col-resolved')
        );
        if (colEl) colEl.classList.remove('bg-blue-50/70', 'border-blue-300');

        const ticketId = e.dataTransfer.getData('text/plain') || activeTicketId;
        if (!ticketId) return;

        const complaints = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');
        const idx = complaints.findIndex(c => c.ticket_id === ticketId);

        if (idx !== -1) {
            const ticket = complaints[idx];
            if (ticket.status !== targetStatus) {
                ticket.status = targetStatus;

                if (targetStatus === 'Resolved' && !ticket.resolved_at) {
                    ticket.resolved_at = new Date().toISOString();
                }

                // Tambah riwayat penanganan
                ticket.audit_trail = ticket.audit_trail || [];
                const statusLabel = targetStatus === 'Open' ? 'Masuk' : (targetStatus === 'In Progress' ? 'Dalam Proses' : 'Selesai');
                ticket.audit_trail.push({
                    timestamp: new Date().toISOString(),
                    status: targetStatus,
                    note: `Status dipindahkan ke ${statusLabel} melalui papan keluhan.`
                });

                complaints[idx] = ticket;
                localStorage.setItem('lrs_complaint_data', JSON.stringify(complaints));

                renderKanbanBoard();
                renderOverviewData();
                showAdminToast(`Status tiket #${ticketId} diubah menjadi ${statusLabel}.`);
            }
        }
    };

    /**
     * 10. MODAL DETAIL KELUHAN & PEMERIKSAAN
     */
    window.openTicketModal = function (ticketId) {
        const complaints = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');
        const ticket = complaints.find(c => c.ticket_id === ticketId);
        if (!ticket) return;

        activeTicketId = ticketId;

        document.getElementById('modal-ticket-id').textContent = `#${ticket.ticket_id}`;
        document.getElementById('modal-project-name').textContent = `${ticket.project_name || 'Proyek LRS'} • Pelapor: ${ticket.client_name || 'PIC Klien'}`;
        document.getElementById('modal-subject').textContent = ticket.subject;
        document.getElementById('modal-description').textContent = ticket.description;
        document.getElementById('modal-category').textContent = ticket.category;
        document.getElementById('modal-created-at').textContent = new Date(ticket.created_at).toLocaleDateString('id-ID', {
            day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
        });

        // Status badge
        const badgeEl = document.getElementById('modal-status-badge');
        const statusLabel = ticket.status === 'Open' ? 'Masuk' : (ticket.status === 'In Progress' ? 'Dalam Proses' : 'Selesai');
        badgeEl.textContent = statusLabel;
        badgeEl.className = ticket.status === 'Resolved'
            ? 'px-2.5 py-0.5 rounded-full text-xs font-extrabold border bg-emerald-50 text-emerald-700 border-emerald-200'
            : (ticket.status === 'In Progress'
                ? 'px-2.5 py-0.5 rounded-full text-xs font-extrabold border bg-amber-50 text-amber-700 border-amber-200'
                : 'px-2.5 py-0.5 rounded-full text-xs font-extrabold border bg-rose-50 text-rose-700 border-rose-200');

        // Priority badge
        const priorityEl = document.getElementById('modal-priority-badge');
        priorityEl.textContent = `Urgensi ${ticket.urgency || 'Normal'}`;

        // SLA remaining text
        const now = new Date();
        const createdAt = new Date(ticket.created_at);
        const slaLimit = (ticket.sla_hours || 24) * 3600 * 1000;
        const remaining = slaLimit - (now - createdAt);
        const slaEl = document.getElementById('modal-sla-text');
        if (ticket.status === 'Resolved') {
            slaEl.textContent = 'Tiket Sudah Selesai';
        } else if (remaining < 0) {
            slaEl.textContent = `Melewati batas (${Math.abs(Math.round(remaining / 3600000))} jam)`;
        } else {
            slaEl.textContent = `Sisa ${Math.round(remaining / 3600000)} jam (Target: ${ticket.sla_hours || 24} jam)`;
        }

        // Audit Trail (Riwayat Penanganan)
        const trailContainer = document.getElementById('modal-audit-trail');
        const trails = ticket.audit_trail || [];
        if (trails.length === 0) {
            trailContainer.innerHTML = '<div class="text-slate-400 italic">Belum ada riwayat tercatat.</div>';
        } else {
            trailContainer.innerHTML = trails.map(t => `
                <div class="flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#003B73] mt-1.5 shrink-0"></span>
                    <div class="flex-1">
                        <div class="flex items-center justify-between text-[10px] text-slate-400">
                            <span class="font-bold text-slate-600">${new Date(t.timestamp).toLocaleString('id-ID')}</span>
                            <span class="font-bold px-1.5 py-0.2 rounded bg-slate-200 text-slate-700">${t.status}</span>
                        </div>
                        <div class="text-slate-700 mt-0.5 leading-snug">${t.note || '-'}</div>
                    </div>
                </div>
            `).join('');
        }

        // Form Fields: Penyebab Masalah & Langkah Perbaikan
        document.getElementById('modal-rca-input').value = ticket.rca || '';
        document.getElementById('modal-capa-input').value = ticket.capa || '';
        document.getElementById('modal-pic-input').value = ticket.pic || '';
        document.getElementById('modal-status-select').value = ticket.status || 'Open';

        const modal = document.getElementById('ticketModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    window.closeTicketModal = function () {
        const modal = document.getElementById('ticketModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        activeTicketId = null;
    };

    window.saveTicketAnalysis = function () {
        if (!activeTicketId) return;

        const rcaVal = document.getElementById('modal-rca-input')?.value.trim() || '';
        const capaVal = document.getElementById('modal-capa-input')?.value.trim() || '';
        const picVal = document.getElementById('modal-pic-input')?.value.trim() || '';
        const statusVal = document.getElementById('modal-status-select')?.value || 'Open';

        const complaints = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');
        const idx = complaints.findIndex(c => c.ticket_id === activeTicketId);

        if (idx !== -1) {
            const ticket = complaints[idx];
            const oldStatus = ticket.status;

            ticket.rca = rcaVal;
            ticket.capa = capaVal;
            ticket.pic = picVal;
            ticket.status = statusVal;

            if (statusVal === 'Resolved' && !ticket.resolved_at) {
                ticket.resolved_at = new Date().toISOString();
            }

            ticket.audit_trail = ticket.audit_trail || [];
            const statusLabel = statusVal === 'Open' ? 'Masuk' : (statusVal === 'In Progress' ? 'Dalam Proses' : 'Selesai');

            let noteText = `Data pemeriksaan diperbarui oleh ${picVal || 'Admin'}.`;
            if (oldStatus !== statusVal) {
                noteText += ` Status berubah dari ${oldStatus} menjadi ${statusLabel}.`;
            }

            ticket.audit_trail.push({
                timestamp: new Date().toISOString(),
                status: statusVal,
                note: noteText
            });

            complaints[idx] = ticket;
            localStorage.setItem('lrs_complaint_data', JSON.stringify(complaints));

            closeTicketModal();
            renderKanbanBoard();
            renderOverviewData();
            showAdminToast(`Data tiket #${activeTicketId} berhasil disimpan.`);
        }
    };

    /**
     * 11. UNDUH LAPORAN (CSV BERSIH TANPA JARGON)
     */
    window.exportIsoReport = function () {
        const surveys = JSON.parse(localStorage.getItem('lrs_survey_data') || '[]');
        const complaints = JSON.parse(localStorage.getItem('lrs_complaint_data') || '[]');

        let csvContent = 'data:text/csv;charset=utf-8,';
        csvContent += 'LAPORAN KEPUASAN PELANGGAN & PENANGANAN KELUHAN - PT LEN RAILWAYS SYSTEMS\r\n';
        csvContent += `Tanggal Unduh,${new Date().toLocaleString('id-ID')}\r\n\r\n`;

        // Ringkasan Survei
        csvContent += 'DATA SURVEI KEPUASAN KLIEN\r\n';
        csvContent += 'ID Responden,Nama Klien,Proyek,Tanggal Submit,Rata-rata Harapan,Rata-rata Kepuasan,Kesesuaian,Saran Umum\r\n';

        surveys.forEach(s => {
            const answers = s.answers || [];
            let totalImp = 0;
            let totalSat = 0;
            answers.forEach(a => {
                totalImp += parseInt(a.kepentingan, 10) || 0;
                totalSat += parseInt(a.kepuasan, 10) || 0;
            });
            const avgImp = answers.length > 0 ? (totalImp / answers.length).toFixed(2) : '0';
            const avgSat = answers.length > 0 ? (totalSat / answers.length).toFixed(2) : '0';
            const diff = (parseFloat(avgSat) - parseFloat(avgImp)).toFixed(2);
            const saranSanitized = (s.saran_umum || '').replace(/[\r\n,]/g, ' ');

            csvContent += `"${s.respondent_id}","${s.client_name || 'Bapak/Ibu PIC'}","${s.project_name}","${s.submitted_at}","${avgImp}","${avgSat}","${diff}","${saranSanitized}"\r\n`;
        });

        // Ringkasan Keluhan
        csvContent += '\r\nDATA KELUHAN & TINDAK LANJUT\r\n';
        csvContent += 'ID Tiket,Proyek,Pelapor,Kategori,Judul Keluhan,Urgensi,Status,Penyebab Masalah,Langkah Perbaikan,PIC Penanggung Jawab\r\n';

        complaints.forEach(c => {
            const statusLabel = c.status === 'Open' ? 'Masuk' : (c.status === 'In Progress' ? 'Dalam Proses' : 'Selesai');
            const subjSanitized = (c.subject || '').replace(/[\r\n,]/g, ' ');
            const rcaSanitized = (c.rca || '').replace(/[\r\n,]/g, ' ');
            const capaSanitized = (c.capa || '').replace(/[\r\n,]/g, ' ');

            csvContent += `"${c.ticket_id}","${c.project_name}","${c.client_name}","${c.category}","${subjSanitized}","${c.urgency}","${statusLabel}","${rcaSanitized}","${capaSanitized}","${c.pic || '-'}"\r\n`;
        });

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', `Laporan_Kepuasan_Layanan_PTLRS_${new Date().toISOString().slice(0, 10)}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        showAdminToast('Laporan berhasil diunduh.');
    };

    /**
     * 12. TOAST NOTIFIKASI
     */
    function showAdminToast(message) {
        let toast = document.getElementById('admin-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'admin-toast';
            toast.className = 'fixed bottom-5 right-5 z-50 bg-[#001D3D] text-white text-xs font-semibold px-4 py-3 rounded-xl shadow-xl flex items-center gap-2 transition-all transform duration-200 border border-slate-700 pointer-events-none opacity-0 translate-y-2';
            document.body.appendChild(toast);
        }

        toast.innerHTML = `<span>✓</span><span>${message}</span>`;
        toast.classList.remove('opacity-0', 'translate-y-2');
        toast.classList.add('opacity-100', 'translate-y-0');

        setTimeout(() => {
            toast.classList.remove('opacity-100', 'translate-y-0');
            toast.classList.add('opacity-0', 'translate-y-2');
        }, 2800);
    }

    /**
     * 13. FILTER EVENT LISTENERS
     */
    window.onFilterChange = function () {
        renderOverviewData();
    };

    window.onKanbanFilterChange = function () {
        renderKanbanBoard();
    };

    /**
     * INISIALISASI SAAT DOM READY
     */
    document.addEventListener('DOMContentLoaded', function () {
        seedFallbackData();
        renderOverviewData();
        renderKanbanBoard();

        if (window.location.hash === '#kanban') {
            window.switchAdminSection('kanban');
        } else if (window.location.hash === '#overview') {
            window.switchAdminSection('overview');
        }
    });

})();
