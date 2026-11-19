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
}
