<?php

namespace Tests\Feature;

use Tests\TestCase;

class GlobalFooterTest extends TestCase
{
    /**
     * Test bahwa Global Footer ter-render lengkap di halaman Portal Klien.
     */
    public function test_global_footer_renders_on_client_portal_page(): void
    {
        $response = $this->get(route('form'));

        $response->assertStatus(200);

        // 1. Kolom 1 — Our Address
        $response->assertSee('Our Address');
        $response->assertSee('PT Len Railway Systems');
        $response->assertSee('Grha Giri Suseno (Ged. T) Len Business Park');
        $response->assertSee('+62-22-5231685');
        $response->assertSee('tel:+62225231685', false);
        $response->assertSee('info@lrs.co.id');
        $response->assertSee('mailto:info@lrs.co.id', false);

        // 2. Kolom 2 — Social Media
        $response->assertSee('Social Media');
        $response->assertSee('aria-label="TikTok PT Len Railway Systems"', false);
        $response->assertSee('aria-label="Instagram PT Len Railway Systems"', false);
        $response->assertSee('aria-label="YouTube PT Len Railway Systems"', false);

        // 3. Kolom 3 — Whistleblowing System
        $response->assertSee('Whistleblowing System');
        $response->assertSee('wbs@lrs.co.id');
        $response->assertSee('mailto:wbs@lrs.co.id', false);
        $response->assertSee('Laporkan Pelanggaran (Report)');

        // 4. Baris Sertifikasi
        $response->assertSee('Sertifikasi SMK3');
        $response->assertSee('Sertifikasi BSI');

        // 5. Bottom Copyright Bar
        $response->assertSee('Copyright &copy; '.date('Y').' PT Len Railway Systems. All Rights Reserved.', false);
        $response->assertSee('Privacy Policy');
    }

    /**
     * Test bahwa Global Footer ter-render lengkap di halaman alias /dashboard.
     */
    public function test_global_footer_renders_on_client_dashboard_alias_page(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);

        // Kolom 1, 2, 3 & Footer
        $response->assertSee('Our Address');
        $response->assertSee('Social Media');
        $response->assertSee('Whistleblowing System');
        $response->assertSee('Privacy Policy');
    }

    /**
     * Test bahwa halaman Verifikasi Akses Zero-Login dapat diakses dan memuat Global Footer.
     */
    public function test_verification_page_loads_and_has_global_footer(): void
    {
        $response = $this->get(route('verify'));

        $response->assertStatus(200);

        // Halaman Verifikasi
        $response->assertSee('Portal Layanan & Umpan Balik Klien', false);
        $response->assertSee('Nomor Kontrak / Kode Proyek');
        $response->assertSee('Akses Portal Klien');

        // Global Footer
        $response->assertSee('Our Address');
        $response->assertSee('Social Media');
        $response->assertSee('Whistleblowing System');
        $response->assertSee('Sertifikasi SMK3');
        $response->assertSee('Privacy Policy');
    }
}
