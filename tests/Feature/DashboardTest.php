<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardTest extends TestCase
{
    /**
     * Test bahwa halaman dashboard portal klien dapat diakses dengan sukses (HTTP 200).
     */
    public function test_client_portal_dashboard_page_loads_successfully(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('PT. Len Railways Systems');
        $response->assertSee('Survei Kepuasan Pelanggan');
        $response->assertSee('Isi Survei');
        $response->assertSee('Beri Masukan');
        $response->assertSee('Buat Keluhan');
        $response->assertSee('Ringkasan Kepuasan');
        $response->assertSee('Daftar Keluhan');
        $response->assertSee('Portal Klien');
        $response->assertSee('Layanan &amp; Dukungan', false);
    }
}
