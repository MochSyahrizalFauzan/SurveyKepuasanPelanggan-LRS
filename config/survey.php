<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Data Kuesioner Survei Kepuasan Pelanggan (FRM-QOP-06-02)
    |--------------------------------------------------------------------------
    |
    | Struktur 3 Kategori Utama dan 21 Indikator Pertanyaan CSI (Bilingual ID/EN).
    | Setiap indikator dinilai dengan Dual-Metric:
    | - Tingkat Kepentingan (1-5)
    | - Tingkat Kepuasan (1-5)
    | - Keterangan Wajib Kondisional jika Kepuasan < 4 (1, 2, atau 3)
    |
    | Standar Penulisan Nama Perusahaan: "PT Len Railway Systems"
    |
    */

    'categories' => [
        [
            'code' => 'A',
            'name' => 'Layanan & Dukungan',
            'name_en' => 'Services & Support',
            'icon' => '🤝',
            'badge_class' => 'category-icon-a',
            'description' => 'Evaluasi keramahan, komunikasi, pemahaman kebutuhan pelanggan, kualitas solusi, dan kejelasan teknis personel PT Len Railway Systems.',
            'description_en' => 'Evaluation of friendliness, communication, understanding of customer needs, solution quality, and technical clarity of PT Len Railway Systems personnel.',
            'indicators' => [
                [
                    'code' => 'A1',
                    'text' => 'Keramahan personal PT Len Railway Systems',
                    'text_en' => 'Friendliness of PT Len Railway Systems personnel',
                ],
                [
                    'code' => 'A2',
                    'text' => 'Kemampuan komunikasi personal PT Len Railway Systems',
                    'text_en' => 'Communication skills of PT Len Railway Systems personnel',
                ],
                [
                    'code' => 'A3',
                    'text' => 'Pemahaman kebutuhan pelanggan',
                    'text_en' => 'Understanding of customer needs',
                ],
                [
                    'code' => 'A4',
                    'text' => 'Kualitas dan relevansi solusi/saran yang ditawarkan',
                    'text_en' => 'Quality and relevance of proposed solutions/advice',
                ],
                [
                    'code' => 'A5',
                    'text' => 'Kejelasan penjelasan teknis',
                    'text_en' => 'Clarity of technical explanations',
                ],
            ],
        ],
        [
            'code' => 'B',
            'name' => 'Pelaksanaan Proyek',
            'name_en' => 'Project Execution',
            'icon' => '🏗️',
            'badge_class' => 'category-icon-b',
            'description' => 'Evaluasi ketepatan waktu, koordinasi tim, kompetensi teknis, kerapihan kerja, penanganan kendala, dan budaya K3L.',
            'description_en' => 'Evaluation of timeliness, team coordination, technical competence, workmanship, issue handling, and HSE culture.',
            'indicators' => [
                [
                    'code' => 'B1',
                    'text' => 'Ketepatan waktu pelaksanaan proyek',
                    'text_en' => 'Project execution timeliness',
                ],
                [
                    'code' => 'B2',
                    'text' => 'Kerjasama dan koordinasi tim proyek',
                    'text_en' => 'Cooperation and coordination of the project team',
                ],
                [
                    'code' => 'B3',
                    'text' => 'Kompetensi teknis tim proyek',
                    'text_en' => 'Technical competence of the project team',
                ],
                [
                    'code' => 'B4',
                    'text' => 'Kerapihan pekerjaan tim proyek',
                    'text_en' => 'Workmanship and neatness of the project team',
                ],
                [
                    'code' => 'B5',
                    'text' => 'Tingkat kesalahan/pengulangan pekerjaan',
                    'text_en' => 'Rate of errors/rework in project execution',
                ],
                [
                    'code' => 'B6',
                    'text' => 'Kecepatan dalam menangani kendala di lapangan',
                    'text_en' => 'Promptness in handling on-site issues',
                ],
                [
                    'code' => 'B7',
                    'text' => 'Komunikasi dan pelaporan progres',
                    'text_en' => 'Communication and progress reporting',
                ],
                [
                    'code' => 'B8',
                    'text' => 'Budaya K3L PT Len Railway Systems',
                    'text_en' => 'HSE (K3L) culture of PT Len Railway Systems',
                ],
                [
                    'code' => 'B9',
                    'text' => 'Kelengkapan dan kepatuhan penggunaan APD',
                    'text_en' => 'Completeness and compliance with PPE usage',
                ],
                [
                    'code' => 'B10',
                    'text' => 'Sosialisasi aturan K3L di lokasi kerja',
                    'text_en' => 'Dissemination of HSE regulations at the work site',
                ],
                [
                    'code' => 'B11',
                    'text' => 'Kompetensi K3L personil PT Len Railway Systems',
                    'text_en' => 'HSE competence of PT Len Railway Systems personnel',
                ],
            ],
        ],
        [
            'code' => 'C',
            'name' => 'Masa Pemeliharaan',
            'name_en' => 'Maintenance Period',
            'icon' => '🔧',
            'badge_class' => 'category-icon-c',
            'description' => 'Evaluasi keandalan fungsi produk, kemudahan operasi & pemeliharaan, respon after sales, dan durabilitas produk.',
            'description_en' => 'Evaluation of product functional reliability, ease of operation & maintenance, after-sales responsiveness, and product durability.',
            'indicators' => [
                [
                    'code' => 'C1',
                    'text' => 'Fungsi produk sesuai harapan',
                    'text_en' => 'Product functionality as expected',
                ],
                [
                    'code' => 'C2',
                    'text' => 'Kemudahan dalam pengoperasian produk',
                    'text_en' => 'Ease of product operation',
                ],
                [
                    'code' => 'C3',
                    'text' => 'Kemudahan proses pemeliharaan',
                    'text_en' => 'Ease of maintenance process',
                ],
                [
                    'code' => 'C4',
                    'text' => 'Respon dan kecepatan layanan after sales',
                    'text_en' => 'Responsiveness and promptness of after-sales service',
                ],
                [
                    'code' => 'C5',
                    'text' => 'Ketahanan/durabilitas produk',
                    'text_en' => 'Product durability and reliability',
                ],
            ],
        ],
    ],
];
