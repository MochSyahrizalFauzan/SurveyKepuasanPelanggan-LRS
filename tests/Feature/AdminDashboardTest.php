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
        $response->assertSee('PT. Len Railways Systems');
        $response->assertSee('Ringkasan Kepuasan & Layanan', false);
        $response->assertSee('Indeks Kepuasan Pelanggan');
        $response->assertSee('Unduh Laporan');
        $response->assertSee('Harapan vs. Kenyataan');
        $response->assertSee('Pemantauan & Tindak Lanjut', false);
        $response->assertSee('Daftar & Progres Keluhan', false);
        $response->assertSee('Penyebab Masalah');
        $response->assertSee('Langkah Perbaikan yang Dilakukan');

        // Pastikan istilah teknis / jargon audit lama sudah dibersihkan
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
        $response->assertSee('Indeks Kepuasan Pelanggan');
        $response->assertSee('Harapan vs. Kenyataan');
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

        // Konsistensi Pembulatan Skor Kepuasan (1 angka di belakang koma)
        $response->assertSee('85.7%');
        $response->assertSee('Target Perusahaan: ≥ 80.0%');

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
        $response->assertSee('id="setting-toggle-sound"', false);
        $response->assertSee('id="setting-toggle-whatsapp"', false);
        $response->assertSee('id="table-notif-logs-body"', false);
    }
}
