<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Data Kuesioner Survei Kepuasan Pelanggan (FRM-QOP-06-02)
    |--------------------------------------------------------------------------
    |
    | Struktur 3 Kategori Utama dan 21 Indikator Pertanyaan CSI.
    | Setiap indikator dinilai dengan Dual-Metric:
    | - Tingkat Kepentingan (1-5)
    | - Tingkat Kepuasan (1-5)
    |
    */

    'categories' => [
        [
            'code' => 'A',
            'name' => 'Layanan & Dukungan',
            'icon' => '🤝',
            'badge_class' => 'category-icon-a',
            'description' => 'Evaluasi keramahan, komunikasi, pemahaman kebutuhan pelanggan, kualitas solusi, dan kejelasan teknis personel PT LRS.',
            'indicators' => [
                ['code' => 'A1', 'text' => 'Keramahan personal PT LRS'],
                ['code' => 'A2', 'text' => 'Kemampuan komunikasi personal PT LRS'],
                ['code' => 'A3', 'text' => 'Pemahaman kebutuhan pelanggan'],
                ['code' => 'A4', 'text' => 'Kualitas dan relevansi solusi/saran yang ditawarkan'],
                ['code' => 'A5', 'text' => 'Kejelasan penjelasan teknis'],
            ],
        ],
        [
            'code' => 'B',
            'name' => 'Pelaksanaan Proyek',
            'icon' => '🏗️',
            'badge_class' => 'category-icon-b',
            'description' => 'Evaluasi ketepatan waktu, koordinasi tim, kompetensi teknis, kerapihan kerja, penanganan kendala, dan budaya K3L.',
            'indicators' => [
                ['code' => 'B1', 'text' => 'Ketepatan waktu pelaksanaan proyek'],
                ['code' => 'B2', 'text' => 'Kerjasama dan koordinasi tim proyek'],
                ['code' => 'B3', 'text' => 'Kompetensi teknis tim proyek'],
                ['code' => 'B4', 'text' => 'Kerapihan pekerjaan tim proyek'],
                ['code' => 'B5', 'text' => 'Tingkat kesalahan/pengulangan pekerjaan'],
                ['code' => 'B6', 'text' => 'Kecepatan dalam menangani kendala di lapangan'],
                ['code' => 'B7', 'text' => 'Komunikasi dan pelaporan progres'],
                ['code' => 'B8', 'text' => 'Budaya K3L PT. LRS'],
                ['code' => 'B9', 'text' => 'Kelengkapan dan kepatuhan penggunaan APD'],
                ['code' => 'B10', 'text' => 'Sosialisasi aturan K3L di lokasi kerja'],
                ['code' => 'B11', 'text' => 'Kompetensi K3L personil PT. LRS'],
            ],
        ],
        [
            'code' => 'C',
            'name' => 'Masa Pemeliharaan',
            'icon' => '🔧',
            'badge_class' => 'category-icon-c',
            'description' => 'Evaluasi keandalan fungsi produk, kemudahan operasi & pemeliharaan, respon after sales, dan durabilitas produk.',
            'indicators' => [
                ['code' => 'C1', 'text' => 'Fungsi produk sesuai harapan'],
                ['code' => 'C2', 'text' => 'Kemudahan dalam pengoperasian produk'],
                ['code' => 'C3', 'text' => 'Kemudahan proses pemeliharaan'],
                ['code' => 'C4', 'text' => 'Respon dan kecepatan layanan after sales'],
                ['code' => 'C5', 'text' => 'Ketahanan/durabilitas produk'],
            ],
        ],
    ],
];
