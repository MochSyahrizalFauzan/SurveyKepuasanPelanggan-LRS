<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Tampilkan halaman Dashboard Admin (CSI & GRC Overview serta Papan Keluhan Kanban).
     */
    public function index(): View
    {
        return view('admin.dashboard');
    }
}
