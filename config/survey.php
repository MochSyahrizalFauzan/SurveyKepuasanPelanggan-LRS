<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Data Kuesioner Survei Kepuasan Pelanggan (FRM-QOP-06-02)
    |--------------------------------------------------------------------------
    |
    | Struktur 4 Dimensi Kategori dan 21 Indikator Pertanyaan CSI.
    | Setiap indikator dinilai dengan Dual-Metric:
    | - Tingkat Kepentingan (1-5)
    | - Tingkat Kepuasan (1-5)
    |
    */

    'categories' => [
        [
            'code' => 'A',
            'name' => 'Layanan & Dukungan',
            'icon' => '',
            'badge_class' => 'category-icon-a',
            'indicators' => [
                ['code' => 'A1', 'text' => 'Keramahan personal PT. Len Railways Systems'],
                ['code' => 'A2', 'text' => 'Kemampuan komunikasi personal PT. LRS'],
                ['code' => 'A3', 'text' => 'Pemahaman terhadap kebutuhan pelanggan'],
                ['code' => 'A4', 'text' => 'Kualitas dan relevansi solusi/saran yang ditawarkan'],
                ['code' => 'A5', 'text' => 'Kejelasan penjelasan teknis yang disampaikan'],
            ],
        ],
        [
            'code' => 'B',
            'name' => 'Pelaksanaan Proyek',
            'icon' => '',
            'badge_class' => 'category-icon-b',
            'indicators' => [
                ['code' => 'B1', 'text' => 'Ketepatan waktu pelaksanaan proyek'],
                ['code' => 'B2', 'text' => 'Kerjasama dan koordinasi tim proyek'],
                ['code' => 'B3', 'text' => 'Kompetensi teknis tim proyek'],
                ['code' => 'B4', 'text' => 'Kerapihan pekerjaan tim proyek'],
                ['code' => 'B5', 'text' => 'Tingkat kesalahan / pengulangan pekerjaan'],
                ['code' => 'B6', 'text' => 'Kecepatan dalam menangani kendala di lapangan'],
                ['code' => 'B7', 'text' => 'Komunikasi dan pelaporan progres proyek'],
            ],
        ],
        [
            'code' => 'C',
            'name' => 'Masa Pemeliharaan',
            'icon' => '',
            'badge_class' => 'category-icon-c',
            'indicators' => [
                ['code' => 'C1', 'text' => 'Fungsi produk sesuai dengan harapan'],
                ['code' => 'C2', 'text' => 'Kemudahan dalam pengoperasian produk'],
                ['code' => 'C3', 'text' => 'Kemudahan proses pemeliharaan produk'],
                ['code' => 'C4', 'text' => 'Respon dan kecepatan layanan after sales'],
                ['code' => 'C5', 'text' => 'Ketahanan / durabilitas produk'],
            ],
        ],
        [
            'code' => 'D',
            'name' => 'K3L',
            'icon' => '',
            'badge_class' => 'category-icon-d',
            'indicators' => [
                ['code' => 'D1', 'text' => 'Budaya K3L PT. Len Railways Systems'],
                ['code' => 'D2', 'text' => 'Kelengkapan dan kepatuhan penggunaan APD'],
                ['code' => 'D3', 'text' => 'Sosialisasi aturan K3L di lokasi kerja'],
                ['code' => 'D4', 'text' => 'Kompetensi K3L personil PT. LRS'],
            ],
        ],
    ],
];
