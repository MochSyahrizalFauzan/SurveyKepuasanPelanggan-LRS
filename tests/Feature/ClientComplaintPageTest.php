<?php

namespace Tests\Feature;

use Tests\TestCase;

class ClientComplaintPageTest extends TestCase
{
    /**
     * Test halaman mandiri keluhan pelanggan dapat diakses langsung tanpa redirect (Zero-Login).
     */
    public function test_complaint_page_is_accessible_directly_without_redirect(): void
    {
        $response = $this->get(route('client.complaint'));

        $response->assertStatus(200);
        $response->assertSee('PT Len Railway Systems');
        $response->assertDontSee('PT. Len Railways Systems');
        $response->assertSee('PUSAT KELUHAN KLIEN');
        $response->assertSee('Survei Kepuasan');
        $response->assertSee(route('form'), false);
        $response->assertSee('id="btn-lang-id"', false);
        $response->assertSee('id="btn-lang-en"', false);
    }

    /**
     * Test halaman keluhan memuat tab pengajuan, tab pelacakan tiket, formulir lengkap, dan footer.
     */
    public function test_complaint_page_contains_submission_form_and_tracking_tab(): void
    {
        $response = $this->get(route('client.complaint'));

        $response->assertStatus(200);

        // Navigasi 2 Tab
        $response->assertSee('id="tab-btn-submit"', false);
        $response->assertSee('Buat Laporan Keluhan');
        $response->assertSee('id="tab-btn-track"', false);
        $response->assertSee('Lacak Status Tiket');

        // Formulir Pengajuan Keluhan (Tab 1)
        $response->assertSee('id="form-client-complaint"', false);
        $response->assertSee('id="c-reporter"', false);
        $response->assertSee('id="c-project"', false);
        $response->assertSee('id="c-contact"', false);
        $response->assertSee('id="c-category"', false);
        $response->assertSee('Rendah');
        $response->assertSee('Sedang');
        $response->assertSee('Tinggi');
        $response->assertSee('Kritis');
        $response->assertSee('id="c-title"', false);
        $response->assertSee('id="c-description"', false);
        $response->assertSee('id="c-attachment-input"', false);
        $response->assertSee('id="btn-submit-complaint"', false);

        // Layar Konfirmasi Tiket
        $response->assertSee('id="complaint-success-container"', false);
        $response->assertSee('id="confirm-ticket-id"', false);
        $response->assertSee('id="btn-copy-ticket"', false);
        $response->assertSee('https://lrs.co.id/about-us/smap');
        $response->assertSee('id="btn-track-created-ticket"', false);
        $response->assertSee('id="btn-create-another-complaint"', false);

        // Panel Lacak Status Tiket (Tab 2) & Struktur Adaptive Timeline
        $response->assertSee('id="track-ticket-input"', false);
        $response->assertSee('id="btn-track-ticket"', false);
        $response->assertSee('Tiket Terbaru Anda');
        $response->assertSee('id="recent-tickets-container"', false);
        $response->assertSee('id="track-result-container"', false);
        $response->assertSee('id="ticket-timeline-list"', false);
        $response->assertSee('flex flex-col md:flex-row md:items-start gap-0', false);
        $response->assertSee('<ol', false);
        $response->assertSee('<li', false);
        $response->assertSee('<time', false);
        $response->assertSee('id="track-meta-val-date"', false);
        $response->assertSee('id="track-followup-container"', false);

        // Footer & Script aktif
        $response->assertSee('js/client-complaint.js', false);
        $response->assertSee('SMAP ISO 37001:2016 PT Len Railway Systems');
        $response->assertSee('Whistleblowing System');
    }

    /**
     * Test halaman survei memiliki tautan pintasan menuju halaman mandiri /keluhan.
     */
    public function test_survey_page_links_to_standalone_complaint_page(): void
    {
        $response = $this->get(route('form'));

        $response->assertStatus(200);
        $response->assertSee('id="btn-header-complaint"', false);
        $response->assertSee('id="btn-goto-complaint"', false);
        $response->assertSee(route('client.complaint'), false);
    }
}
