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
        $response->assertSee('Langkah 1 dari 2');
        $response->assertSee('Pilih Kategori Penilaian');
        $response->assertSee('Pilih Kategori Survei Kepuasan');

        // 3 Kartu Kategori
        $response->assertSee('Kategori A');
        $response->assertSee('Layanan &amp; Dukungan', false);
        $response->assertSee('5 Indikator');

        $response->assertSee('Kategori B');
        $response->assertSee('Pelaksanaan Proyek');
        $response->assertSee('11 Indikator');

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
        $response->assertSee('Keramahan personal PT LRS');
        $response->assertSee('id="card-A2"', false);
        $response->assertSee('Kemampuan komunikasi personal PT LRS');
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
        $response->assertSee('Budaya K3L PT. LRS');
        $response->assertSee('id="card-B9"', false);
        $response->assertSee('Kelengkapan dan kepatuhan penggunaan APD');
        $response->assertSee('id="card-B10"', false);
        $response->assertSee('Sosialisasi aturan K3L di lokasi kerja');
        $response->assertSee('id="card-B11"', false);
        $response->assertSee('Kompetensi K3L personil PT. LRS');

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

        // Visualisasi Skala di Tengah (Kurang - Netral - Sangat)
        $response->assertSee('1: Kurang');
        $response->assertSee('3: Netral');
        $response->assertSee('5: Sangat');
    }

    /**
     * ATURAN KETAT: Test bahwa TIDAK ADA textarea catatan/komentar di dalam kartu indikator.
     */
    public function test_indicators_do_not_contain_comment_textarea(): void
    {
        $response = $this->get(route('form'));

        $response->assertStatus(200);

        // Pastikan tidak ada class keterangan-textarea di dalam indikator
        $response->assertDontSee('keterangan-textarea');
        $response->assertDontSee('ket-wrap-A1');
        $response->assertDontSee('ket-wrap-B1');
        $response->assertDontSee('ket-wrap-C1');
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
        $response->assertSee('absolute inset-0 w-full h-full object-cover object-top scale-105 blur-[1px]', false);

        // Lapisan 2: Gradient Overlay Halus & Kontras Tinggi
        $response->assertSee('from-[#001A38]/95 via-[#002E63]/70 via-45% to-transparent', false);

        // Lapisan 3: Badge Glassmorphism & Proporsi Card Elegan
        $response->assertSee('bg-white/20 backdrop-blur-md text-white border border-white/30', false);
        $response->assertSee('min-h-[280px]', false);
        $response->assertSee('lg:min-h-[420px]', false);
        $response->assertSee('rounded-3xl', false);
    }

    /**
     * Test ketersediaan tampilan sukses beserta 2 tombol aksi ("Isi Kategori Lain" dan "Kembali ke Beranda").
     */
    public function test_success_view_has_required_action_buttons(): void
    {
        $response = $this->get(route('form'));

        $response->assertStatus(200);

        $response->assertSee('id="survey-success-container"', false);
        $response->assertSee('Terima Kasih!');
        $response->assertSee('id="btn-fill-other-category"', false);
        $response->assertSee('Isi Kategori Lain');
        $response->assertSee('id="btn-back-to-home"', false);
        $response->assertSee('Kembali ke Beranda');
    }
}
