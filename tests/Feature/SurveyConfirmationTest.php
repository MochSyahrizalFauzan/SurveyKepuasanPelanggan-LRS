<?php

namespace Tests\Feature;

use Tests\TestCase;

class SurveyConfirmationTest extends TestCase
{
    /**
     * Test bahwa komponen utama konfirmasi Blade dirender di halaman formulir portal survei.
     */
    public function test_confirmation_component_is_rendered_on_survey_portal(): void
    {
        $response = $this->get(route('form'));

        $response->assertStatus(200);

        // Container utama konfirmasi
        $response->assertSee('id="survey-success-container"', false);
        $response->assertSee('Terima Kasih!');
        $response->assertSee('id="hero-icon-container"', false);
        $response->assertSee('id="txt-success-title"', false);
        $response->assertSee('id="txt-success-msg"', false);

        // Tombol Pintasan Keluhan Pelanggan (/keluhan)
        $response->assertSee('id="btn-goto-complaint"', false);
        $response->assertSee('Sampaikan Keluhan');
        $response->assertSee(route('client.complaint'), false);

        // Confetti Canvas untuk Skor 5
        $response->assertSee('id="confirmation-confetti-canvas"', false);
    }

    /**
     * Test bahwa sub-komponen Digital Receipt dirender dengan No. Referensi, DL semantic, dan tombol Salin.
     */
    public function test_confirmation_receipt_component_contains_print_and_download_actions(): void
    {
        $response = $this->get(route('form'));

        $response->assertStatus(200);

        // Receipt Card & 3D Tilt container
        $response->assertSee('id="receipt-card-container"', false);
        $response->assertSee('id="txt-summary-title"', false);

        // No. Referensi & Tombol Salin
        $response->assertSee('id="txt-ref-label"', false);
        $response->assertSee('id="success-reference-no"', false);
        $response->assertSee('id="btn-copy-ref"', false);
        $response->assertSee('id="txt-copy-ref"', false);
        $response->assertSee('Salin');

        // Semantic <dl>, <dt>, <dd>
        $response->assertSee('<dl', false);
        $response->assertSee('id="lbl-resp-id"', false);
        $response->assertSee('id="success-respondent-id"', false);
        $response->assertSee('id="lbl-proj-name"', false);
        $response->assertSee('id="success-project-name"', false);
        $response->assertSee('id="lbl-cat-summary"', false);
        $response->assertSee('id="success-category-summary"', false);
        $response->assertSee('id="lbl-attach-name"', false);
        $response->assertSee('id="success-attachment-name"', false);
        $response->assertSee('id="success-submitted-time"', false);
    }

    /**
     * Test bahwa sub-komponen Audio Chime dirender dengan Web Audio API Synthesizer dan Visualizer 4 Bar.
     */
    public function test_confirmation_audio_component_renders_chime_controls_and_visualizer(): void
    {
        $view = $this->blade('<x-survey.confirmation-audio />');

        // Audio Widget
        $view->assertSee('id="confirmation-audio-widget"', false);
        $view->assertSee('Chime Konfirmasi');

        // 4 Bar Visualizer
        $view->assertSee('id="eq-bar-1"', false);
        $view->assertSee('id="eq-bar-2"', false);
        $view->assertSee('id="eq-bar-3"', false);
        $view->assertSee('id="eq-bar-4"', false);

        // Tombol Replay & Mute
        $view->assertSee('id="btn-replay-chime"', false);
        $view->assertSee('id="btn-mute-chime"', false);
        $view->assertSee('id="icon-sound-on"', false);
        $view->assertSee('id="icon-sound-muted"', false);

        // Sintesis C5 -> G5
        $view->assertSee('523.25', false);
        $view->assertSee('783.99', false);
    }

    /**
     * Test bahwa sub-komponen SMAP Ticker dirender dengan CSS Marquee murni dan tautan ke lrs.co.id.
     */
    public function test_confirmation_smap_ticker_component_renders_modern_marquee_and_smap_link(): void
    {
        $response = $this->get(route('form'));

        $response->assertStatus(200);

        // Kartu SMAP ISO 37001
        $response->assertSee('id="smap-card-title"', false);
        $response->assertSee('Sistem Manajemen Anti Penyuapan (SMAP) ISO 37001');
        $response->assertSee('PT Len Railway Systems');
        $response->assertSee('id="smap-card-desc"', false);
        $response->assertSee('Prinsip 4 NOs');
        $response->assertSee('id="smap-card-link"', false);
        $response->assertSee('https://lrs.co.id/about-us/smap');
        $response->assertSee('https://lrs.co.id');

        // Pure CSS Marquee Ticker (Tanpa Tag <marquee>)
        $response->assertDontSee('<marquee');
        $response->assertSee('lrs-marquee-track', false);
        $response->assertSee('group-hover/ticker:[animation-play-state:paused]', false);
        $response->assertSee('wbs@lrs.co.id');
    }

    /**
     * Test bahwa controller script konfirmasi Blade mendengarkan event lrs:survey-submitted.
     */
    public function test_confirmation_component_has_event_listener_for_survey_submitted(): void
    {
        $response = $this->get(route('form'));

        $response->assertStatus(200);

        $response->assertSee("window.addEventListener('lrs:survey-submitted'", false);
        $response->assertSee('launchConfettiScore5', false);
        $response->assertSee('updateHeroByScore', false);
        $response->assertSee('hero-score-badge', false);
    }
}
