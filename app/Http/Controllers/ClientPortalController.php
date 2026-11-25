<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ClientPortalController extends Controller
{
    /**
     * Tampilkan halaman portal klien (Survei, Masukan, dan Keluhan).
     *
     * Halaman ini beroperasi secara Zero-Login pada fase prototipe,
     * merender kuesioner dari konfigurasi dan menyimpan interaksi ke localStorage.
     */
    public function index(): View
    {
        /** @var array<int, array{code: string, name: string, name_en?: string, icon: string, badge_class: string, description?: string, description_en?: string, indicators: array<int, array{code: string, text: string, text_en?: string}>}> $categories */
        $categories = config('survey.categories', []);
        $clientName = 'Bapak/Ibu PIC';

        // Hitung total pertanyaan untuk progress bar (21 pertanyaan * 2 metrik = 42 input)
        $totalQuestions = 0;
        foreach ($categories as $category) {
            $totalQuestions += count($category['indicators']);
        }
        $totalInputs = $totalQuestions * 2;

        return view('clients.form', [
            'categories' => $categories,
            'clientName' => $clientName,
            'totalQuestions' => $totalQuestions,
            'totalInputs' => $totalInputs,
            'isGeneralMode' => request()->query('mode') === 'general',
        ]);
    }

    /**
     * Tampilkan halaman Verifikasi Akses Klien (Zero-Login via Nomor Kontrak / Token).
     */
    public function verify(): View
    {
        return view('clients.verify');
    }
}
