<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    /**
     * Test bahwa halaman Admin Portal dapat diakses via route /admin dengan sukses (HTTP 200).
     */
    public function test_admin_dashboard_page_loads_via_admin_route(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('PT Len Railway Systems');
        $response->assertDontSee('PT. Len Railways Systems');
        $response->assertDontSee('PT Len Railways Systems');
        $response->assertDontSee('PT. Len Railway Systems');

        $response->assertSee('Ringkasan Kepuasan & Layanan', false);
        $response->assertSee('Indeks Kepuasan Pelanggan');
        $response->assertSee('Unduh Laporan');
        $response->assertSee('w-full sm:w-auto', false);
        $response->assertSee('Kepentingan dan Kepuasan');
        $response->assertSee('Tingkat Kepuasan');
        $response->assertSee('Tingkat Kepentingan');
        $response->assertSee('Selisih Kepentingan & Kepuasan');
        $response->assertSee('Pemantauan & Tindak Lanjut', false);
        $response->assertSee('Daftar & Progres Keluhan', false);
        $response->assertSee('Penyebab Masalah');
        $response->assertSee('Langkah Perbaikan yang Dilakukan');

        // Pastikan istilah lama dan skala bintang/5.0 sudah dibersihkan
        $response->assertDontSee('Harapan vs');
        $response->assertDontSee('Tingkat Harapan');
        $response->assertDontSee('Kesesuaian Harapan');
        $response->assertDontSee('dari skala 5.0');
        $response->assertDontSee('★');
        $response->assertDontSee('GRC');
        $response->assertDontSee('ISO 9001');
        $response->assertDontSee('ISO 10002');
        $response->assertDontSee('IPA Matrix');
        $response->assertDontSee('Gap Index');
        $response->assertDontSee('SLA Adherence');
    }

    /**
     * Test bahwa halaman Admin Portal juga dapat diakses via alias /admin/dashboard.
     */
    public function test_admin_dashboard_page_loads_via_alias_url(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('PT Len Railway Systems');
        $response->assertSee('Indeks Kepuasan Pelanggan');
        $response->assertSee('Kepentingan dan Kepuasan');
    }

    /**
     * Test ketersediaan sistem notifikasi real-time, badge keluhan, dan toast popup.
     */
    public function test_admin_dashboard_has_realtime_notification_elements(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Ikon Lonceng Notifikasi & Badge Angka
        $response->assertSee('id="notification-bell-wrapper"', false);
        $response->assertSee('id="btn-bell-notification"', false);
        $response->assertSee('id="bell-unread-badge"', false);

        // Dropdown Notifikasi
        $response->assertSee('id="notification-dropdown"', false);
        $response->assertSee('Notifikasi Terbaru');
        $response->assertSee('Tandai semua dibaca');
        $response->assertSee('id="notification-list-container"', false);

        // Badge Angka pada Tab "Daftar Keluhan"
        $response->assertSee('id="tab-complaint-badge"', false);

        // Kontainer Toast Popup Melayang
        $response->assertSee('id="admin-toast-container"', false);
    }

    /**
     * Test sinkronisasi 3 kartu kategori (A, B, C) dan konsistensi format 1 angka di belakang koma.
     */
    public function test_admin_dashboard_has_3_categories_and_consistent_metrics(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // 3 Kartu Kategori Sesuai PRD.md
        $response->assertSee('grid-cols-1 md:grid-cols-3', false);
        $response->assertSee('id="dim-score-A"', false);
        $response->assertSee('id="dim-score-B"', false);
        $response->assertSee('id="dim-score-C"', false);
        $response->assertDontSee('id="dim-score-D"');

        // Konsistensi Pembulatan Skor Kepuasan & Kepentingan dalam Persentase (%)
        $response->assertSee('81.2%');
        $response->assertSee('82.4%');
        $response->assertSee('78.0%');
        $response->assertSee('+4.4%');
        $response->assertSee('Target Perusahaan:');
        $response->assertSee('(≥ 80.0%)');
        $response->assertSee('whitespace-nowrap', false);
        $response->assertSee('id="dim-imp-A"', false);
        $response->assertSee('id="dim-imp-B"', false);
        $response->assertSee('id="dim-imp-C"', false);

        // Kartu Partisipasi Responden dengan Hierarki Seragam
        $response->assertSee('id="card-partisipasi-responden"', false);
        $response->assertSee('id="participation-ratio-val"', false);
        $response->assertSee('id="participation-subtext"', false);
    }

    /**
     * Test ketersediaan 6 section SPA dan menu navigasi administrator.
     */
    public function test_admin_dashboard_has_all_6_spa_sections_and_navigation(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Sidebar Khusus Admin & Fitur Mode Fokus
        $response->assertSee('id="admin-sidebar"', false);
        $response->assertSee('id="admin-main-wrapper"', false);
        $response->assertSee('id="admin-topbar"', false);
        $response->assertSee('id="btn-toggle-sidebar-collapse"', false);
        $response->assertSee('id="btn-admin-sidebar-toggle"', false);
        $response->assertSee('id="sidebar-backdrop"', false);

        // 5 Tab Utama di Sidebar & Tombol Pengaturan
        $response->assertSee('id="tab-btn-ringkasan"', false);
        $response->assertSee('id="tab-btn-data-survei"', false);
        $response->assertSee('id="tab-btn-keluhan"', false);
        $response->assertSee('id="tab-btn-qr-tautan"', false);
        $response->assertSee('id="tab-btn-proyek-personel"', false);
        $response->assertSee('id="tab-btn-pengaturan"', false);

        // Verifikasi Ikon Sidebar dari Blade UI Kit (Remix Icon SVG viewBox)
        $response->assertSee('viewBox="0 0 24 24"', false);

        // Mobile Drawer & Toggle
        $response->assertSee('id="btn-admin-mobile-menu"', false);
        $response->assertSee('id="admin-mobile-drawer"', false);

        // 6 Kontainer Section SPA
        $response->assertSee('id="section-overview"', false);
        $response->assertSee('id="section-surveys"', false);
        $response->assertSee('id="section-complaints"', false);
        $response->assertSee('id="section-qr"', false);
        $response->assertSee('id="section-projects-personnel"', false);
        $response->assertSee('id="section-settings"', false);

        // Blok Area Prioritas Perbaikan di Ringkasan
        $response->assertSee('Area Prioritas Perbaikan');
        $response->assertSee('id="priority-indicators-container"', false);
    }

    /**
     * Test modul Data Survei, Keluhan, QR Generator, Proyek & Personel, dan Pengaturan.
     */
    public function test_admin_dashboard_has_all_management_modules_and_modals(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Modul Data Survei & Modal Detail
        $response->assertSee('Rekap Data Survei Kepuasan');
        $response->assertSee('id="table-surveys-body"', false);
        $response->assertSee('id="surveyDetailModal"', false);
        $response->assertSee('id="modal-survey-answers-body"', false);

        // Modul Keluhan (Tabel & Kanban Switcher)
        $response->assertSee('id="container-complaint-table"', false);
        $response->assertSee('id="container-complaint-kanban"', false);
        $response->assertSee('id="ticketModal"', false);

        // Modul QR & Tautan (QRCode.js, Form, Card Preview, History)
        $response->assertSee('qrcode.min.js', false);
        $response->assertSee('Generator QR Code');
        $response->assertSee('id="qrcode-canvas-container"', false);
        $response->assertSee('id="table-qr-history-body"', false);

        // Modul Proyek & Personel (Sub-tabs & Modals)
        $response->assertSee('Proyek & Personel Pendamping', false);
        $response->assertSee('id="projectModal"', false);
        $response->assertSee('id="personnelModal"', false);

        // Modul Pengaturan (Target, Audio Toggle, WhatsApp Simulation Toggle, Log Table)
        $response->assertSee('Pengaturan & Preferensi Sistem', false);
        $response->assertSee('id="setting-target-score"', false);
        $response->assertSee('Kontrol Notifikasi & Audio', false);
        $response->assertSee('Aktifkan Efek Suara Notifikasi');
        $response->assertSee('Bunyikan nada lembut saat survei atau keluhan baru masuk dari Portal Klien');
        $response->assertSee('id="setting-toggle-sound"', false);
        $response->assertSee('id="btn-test-sound"', false);
        $response->assertSee('Uji Suara');
        $response->assertSee('id="setting-toggle-whatsapp"', false);
        $response->assertSee('id="table-notif-logs-body"', false);
    }

    /**
     * Test fitur Tautan Survei Umum (Proyek Belum Berkontrak) & filter Non-Kontrak di Dashboard Admin.
     */
    public function test_admin_dashboard_has_general_survey_link_and_filters(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Kartu Tautan Survei Umum di Modul 4
        $response->assertSee('Tautan Survei Umum (Proyek Belum Berkontrak)');
        $response->assertSee('id="general-survey-link-card"', false);
        $response->assertSee('id="general-survey-url"', false);
        $response->assertSee('mode=general', false);
        $response->assertSee('Salin Tautan Umum');
        $response->assertSee('Buka Tautan');
        $response->assertSee('Tampilkan QR Umum');
        $response->assertSee('id="general-qr-panel"', false);

        // Opsi Filter Survei Umum (Non-Kontrak) di Modul 1 & Modul 2
        $response->assertSee('id="filter-overview-project"', false);
        $response->assertSee('id="filter-surveys-project"', false);
        $response->assertSee('Survei Umum (Non-Kontrak)');
    }

    /**
     * Test akses langsung route /form?mode=general tanpa redirect ke /verify.
     */
    public function test_client_portal_general_mode_loads_directly_with_optional_identity_fields(): void
    {
        $response = $this->get('/form?mode=general');

        $response->assertStatus(200);
        $response->assertSee('PT Len Railway Systems');
        $response->assertSee('Survei Umum (Non-Kontrak)');
        $response->assertSee('id="general-mode-identity-card"', false);
        $response->assertSee('id="general-project-name"', false);
        $response->assertSee('id="general-client-institution"', false);
        $response->assertSee('id="general-respondent-name"', false);
    }

    /**
     * Test Tab Navigasi "Perhitungan CSI" modular, 4 KPI card, matriks 2 baris header, dan pagination di #section-surveys.
     */
    public function test_admin_surveys_section_has_csi_tab_matrix_and_pagination(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Segmented Tab Switcher pada Data Survei
        $response->assertSee('id="tab-btn-surveys-recap"', false);
        $response->assertSee('Rekap Responden');
        $response->assertSee('id="tab-btn-surveys-csi"', false);
        $response->assertSee('Perhitungan CSI');
        $response->assertSee('id="panel-surveys-recap"', false);
        $response->assertSee('id="panel-surveys-csi"', false);

        // Pagination Tab 1 Rekap Responden (10 / 25 / 50)
        $response->assertSee('id="surveys-recap-pagination-bar"', false);
        $response->assertSee('id="surveys-recap-per-page"', false);
        $response->assertSee('value="25"', false);
        $response->assertSee('value="50"', false);

        // 4 Kartu Ringkasan KPI CSI
        $response->assertSee('id="csi-kpi-total-val"', false);
        $response->assertSee('id="csi-kpi-quality-badge"', false);
        $response->assertSee('id="csi-kpi-avg-s"', false);
        $response->assertSee('id="csi-kpi-avg-i"', false);
        $response->assertSee('id="csi-kpi-worst-gap-val"', false);
        $response->assertSee('id="csi-kpi-respondents-count"', false);

        // Toolbar Kontrol & Filter Dinamis CSI
        $response->assertSee('id="csi-filter-project"', false);
        $response->assertSee('id="csi-filter-category"', false);
        $response->assertSee('id="csi-filter-search"', false);
        $response->assertSee('id="btn-export-csi-csv"', false);
        $response->assertSee('Ekspor Matriks CSI (CSV)');

        // Struktur Tabel Matriks CSI 2 Baris Header & Kolom Sticky
        $response->assertSee('id="csi-matrix-table"', false);
        $response->assertSee('sticky left-0', false);
        $response->assertSee('sticky left-12', false);
        $response->assertSee('Atribut / Indikator');
        $response->assertSee('Kepentingan (1–5)');
        $response->assertSee('Indeks (I)');
        $response->assertSee('Kepuasan (1–5)');
        $response->assertSee('Indeks (S)');
        $response->assertSee('CSI Per Fasa');
        $response->assertSee('CSI Total');

        // Pagination & Empty State Matriks CSI serta Script Engine Modular
        $response->assertSee('id="csi-pagination-bar"', false);
        $response->assertSee('id="csi-per-page"', false);
        $response->assertSee('id="csi-empty-state"', false);
        $response->assertSee('js/admin/csi-engine.js', false);
        $response->assertSee('Budaya K3L PT Len Railway Systems', false);

        // Modal Edit Metadata Responden Modular
        $response->assertSee('id="editSurveyMetadataModal"', false);
        $response->assertSee('Edit Metadata Responden');
        $response->assertSee('id="edit-meta-ref-badge"', false);
        $response->assertSee('id="edit-meta-project"', false);
        $response->assertSee('id="edit-meta-client"', false);
        $response->assertSee('id="edit-meta-category"', false);
        $response->assertSee('id="edit-meta-category-warning"', false);
        $response->assertSee('Mengubah kategori akan memengaruhi pengelompokan data pada counter dan perhitungan CSI.');
        $response->assertSee('id="edit-meta-personnel"', false);
        $response->assertSee('id="btn-save-edit-metadata"', false);
        $response->assertSee('js/admin/survey-metadata-modal.js', false);
    }
}
