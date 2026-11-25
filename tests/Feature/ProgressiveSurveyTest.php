<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProgressiveSurveyTest extends TestCase
{
    /**
     * Test bahwa halaman formulir survei memuat alur 2 langkah dengan 3 kategori pilihan.
     */
    public function test_client_portal_loads_progressive_survey_page_with_3_categories(): void
    {
        $response = $this->get(route('form'));

        $response->assertStatus(200);

        // Langkah 1: Pemilihan Kategori
        $response->assertSee('Pilih Kategori Survei Kepuasan');

        // 3 Kartu Kategori
        $response->assertSee('Kategori A');
        $response->assertSee('Layanan &amp; Dukungan', false);

        $response->assertSee('Kategori B');
        $response->assertSee('Pelaksanaan Proyek');

        $response->assertSee('Kategori C');
        $response->assertSee('Masa Pemeliharaan');

        $response->assertSee('Pilih Kategori Ini');
    }

    /**
     * Test bahwa seluruh 21 indikator dari 3 kategori dirender dalam grup masing-masing.
     */
    public function test_progressive_survey_renders_all_21_indicators_within_their_category_groups(): void
    {
        $response = $this->get(route('form'));

        $response->assertStatus(200);

        // Kategori A: 5 Indikator (A1 - A5)
        $response->assertSee('id="category-group-A"', false);
        $response->assertSee('id="card-A1"', false);
        $response->assertSee('Keramahan personal PT Len Railway Systems');
        $response->assertSee('id="card-A2"', false);
        $response->assertSee('Kemampuan komunikasi personal PT Len Railway Systems');
        $response->assertSee('id="card-A3"', false);
        $response->assertSee('Pemahaman kebutuhan pelanggan');
        $response->assertSee('id="card-A4"', false);
        $response->assertSee('Kualitas dan relevansi solusi/saran yang ditawarkan');
        $response->assertSee('id="card-A5"', false);
        $response->assertSee('Kejelasan penjelasan teknis');

        // Kategori B: 11 Indikator (B1 - B11)
        $response->assertSee('id="category-group-B"', false);
        $response->assertSee('id="card-B1"', false);
        $response->assertSee('Ketepatan waktu pelaksanaan proyek');
        $response->assertSee('id="card-B2"', false);
        $response->assertSee('Kerjasama dan koordinasi tim proyek');
        $response->assertSee('id="card-B3"', false);
        $response->assertSee('Kompetensi teknis tim proyek');
        $response->assertSee('id="card-B4"', false);
        $response->assertSee('Kerapihan pekerjaan tim proyek');
        $response->assertSee('id="card-B5"', false);
        $response->assertSee('Tingkat kesalahan/pengulangan pekerjaan');
        $response->assertSee('id="card-B6"', false);
        $response->assertSee('Kecepatan dalam menangani kendala di lapangan');
        $response->assertSee('id="card-B7"', false);
        $response->assertSee('Komunikasi dan pelaporan progres');
        $response->assertSee('id="card-B8"', false);
        $response->assertSee('Budaya K3L PT Len Railway Systems');
        $response->assertSee('id="card-B9"', false);
        $response->assertSee('Kelengkapan dan kepatuhan penggunaan APD');
        $response->assertSee('id="card-B10"', false);
        $response->assertSee('Sosialisasi aturan K3L di lokasi kerja');
        $response->assertSee('id="card-B11"', false);
        $response->assertSee('Kompetensi K3L personil PT Len Railway Systems');

        // Kategori C: 5 Indikator (C1 - C5)
        $response->assertSee('id="category-group-C"', false);
        $response->assertSee('id="card-C1"', false);
        $response->assertSee('Fungsi produk sesuai harapan');
        $response->assertSee('id="card-C2"', false);
        $response->assertSee('Kemudahan dalam pengoperasian produk');
        $response->assertSee('id="card-C3"', false);
        $response->assertSee('Kemudahan proses pemeliharaan');
        $response->assertSee('id="card-C4"', false);
        $response->assertSee('Respon dan kecepatan layanan after sales');
        $response->assertSee('id="card-C5"', false);
        $response->assertSee('Ketahanan/durabilitas produk');
    }

    /**
     * Test bahwa setiap indikator memiliki penilaian ganda (Dual-Metric) dan panduan visual skala.
     */
    public function test_indicators_have_dual_metric_and_scale_guide(): void
    {
        $response = $this->get(route('form'));

        $response->assertStatus(200);

        // Dua Baris Metrik
        $response->assertSee('Tingkat Kepentingan');
        $response->assertSee('Tingkat Kepuasan');

        // Visualisasi Skala di Tengah (Kurang Puas / Tidak Penting - Netral - Sangat Puas / Sangat Penting)
        $response->assertSee('Kurang Puas / Tidak Penting');
        $response->assertSee('Netral');
        $response->assertSee('Sangat Puas / Sangat Penting');
    }

    /**
     * Test blok keterangan kondisional (*Keterangan Wajib Diisi) muncul pada indikator.
     */
    public function test_indicators_have_conditional_explanation_block_and_no_legacy_textarea(): void
    {
        $response = $this->get(route('form'));

        $response->assertStatus(200);

        // Pastikan tidak ada class keterangan-textarea lama di dalam indikator
        $response->assertDontSee('keterangan-textarea');
        $response->assertDontSee('ket-wrap-A1');
        $response->assertDontSee('ket-wrap-B1');
        $response->assertDontSee('ket-wrap-C1');

        // Pastikan blok keterangan wajib kondisional ada di setiap indikator
        $response->assertSee('*Keterangan Wajib Diisi');
        $response->assertSee('id="reason-block-A1"', false);
        $response->assertSee('id="reason-A1"', false);
        $response->assertSee('id="reason-block-B1"', false);
        $response->assertSee('id="reason-block-C1"', false);
    }

    /**
     * Test ketersediaan tombol navigasi Langkah 2, input upload file dummy, dan tombol Kirim Survei.
     */
    public function test_step_2_has_change_category_dummy_upload_and_submit(): void
    {
        $response = $this->get(route('form'));

        $response->assertStatus(200);

        // Tombol Ganti Kategori di Langkah 2
        $response->assertSee('Ganti Kategori');
        $response->assertSee('id="btn-change-category"', false);

        // Upload File (Dummy)
        $response->assertSee('Upload File / Dokumen Pendukung (Dummy)');
        $response->assertSee('id="survey-attachment-input"', false);
        $response->assertSee('id="survey-upload-dropzone"', false);

        // Tombol Kirim Survei
        $response->assertSee('id="btn-submit-survey"', false);
        $response->assertSee('Kirim Survei');
    }

    /**
     * Test bahwa 3 kartu kategori memiliki desain 3 lapis (Full-Bleed + Soft Blur + Gradient Overlay).
     */
    public function test_category_cards_have_header_images_and_floating_badges(): void
    {
        $response = $this->get(route('form'));

        $response->assertStatus(200);

        // Lapisan 1: Gambar Full-Bleed + Soft Blur + Object-Top
        $response->assertSee('images/card/dukunganLayanan.jpg');
        $response->assertSee('loading="lazy"', false);
        $response->assertSee('category-card-img absolute inset-0 w-full h-full object-cover object-top', false);

        // Lapisan 2: Gradient Overlay Halus & Kontras Tinggi
        $response->assertSee('from-[#001A38]/95 via-[#002E63]/70 via-45% to-transparent', false);

        // Lapisan 3: Proporsi Card Elegan
        $response->assertSee('category-select-card', false);
        $response->assertSee('min-h-[280px]', false);
        $response->assertSee('lg:min-h-[420px]', false);
        $response->assertSee('rounded-3xl', false);
    }

    /**
     * Test ketersediaan tampilan sukses Langkah 3 beserta tombol pintasan keluhan dan kartu SMAP ISO 37001.
     */
    public function test_success_view_has_required_action_buttons_and_smap_card(): void
    {
        $response = $this->get(route('form'));

        $response->assertStatus(200);

        $response->assertSee('id="survey-success-container"', false);
        $response->assertSee('Terima Kasih!');
        $response->assertSee('id="btn-header-complaint"', false);
        $response->assertSee('Sampaikan Keluhan');
        $response->assertSee(route('client.complaint'), false);

        // Komitmen SMAP ISO 37001
        $response->assertSee('Sistem Manajemen Anti Penyuapan (SMAP) ISO 37001');
        $response->assertSee('https://lrs.co.id/about-us/smap');
        $response->assertSee('target="_blank"', false);
        $response->assertSee('rel="noopener noreferrer"', false);

        // Desain Responsif & Fitur Baru Layanan Terima Kasih V4.0
        $response->assertSee('min-h-[calc(100dvh-5.5rem)]', false);
        $response->assertSee('max-w-4xl', false);
        $response->assertSee('from-[#1a336e] via-[#2563eb] to-[#dc2626]', false);
        $response->assertSee('id="btn-copy-ref"', false);
        $response->assertSee('id="txt-copy-ref"', false);
        $response->assertSee('Salin');
        $response->assertSee('<dl', false);
        $response->assertSee('<dt', false);
        $response->assertSee('<dd', false);
    }

    /**
     * Test ketersediaan tombol bilingual switcher dan data teks bilingual.
     */
    public function test_client_portal_has_bilingual_switcher_and_attributes(): void
    {
        $response = $this->get(route('form'));

        $response->assertStatus(200);

        // Switcher ID | EN
        $response->assertSee('id="btn-lang-id"', false);
        $response->assertSee('id="btn-lang-en"', false);

        // Bilingual Data Attributes
        $response->assertSee('data-category-en="Services &amp; Support"', false);
        $response->assertSee('data-category-en="Project Execution"', false);
        $response->assertSee('data-category-en="Maintenance Period"', false);
        $response->assertSee('data-text-en="Friendliness of PT Len Railway Systems personnel"', false);
    }

    /**
     * Test standardisasi nama PT Len Railway Systems dan ketiadaan varian non-standar.
     */
    public function test_client_portal_has_standardized_company_name(): void
    {
        $response = $this->get(route('form'));

        $response->assertStatus(200);

        $response->assertSee('PT Len Railway Systems');
        $response->assertDontSee('PT. Len Railways Systems');
        $response->assertDontSee('PT LRS');
        $response->assertDontSee('PT. LRS');

        // Cek juga pada halaman verify
        $verifyResponse = $this->get(route('verify'));
        $verifyResponse->assertStatus(200);
        $verifyResponse->assertSee('PT Len Railway Systems');
        $verifyResponse->assertDontSee('PT. Len Railways Systems');
    }

    /**
     * Test bahwa config survey memiliki konfigurasi bilingual lengkap.
     */
    public function test_survey_config_has_complete_bilingual_structure(): void
    {
        $categories = config('survey.categories');

        $this->assertIsArray($categories);
        $this->assertCount(3, $categories);

        foreach ($categories as $cat) {
            $this->assertArrayHasKey('name_en', $cat);
            $this->assertArrayHasKey('description_en', $cat);
            $this->assertArrayHasKey('indicators', $cat);

            foreach ($cat['indicators'] as $ind) {
                $this->assertArrayHasKey('text_en', $ind);
                $this->assertNotEmpty($ind['text_en']);
            }
        }
    }
}
