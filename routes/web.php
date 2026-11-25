<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ClientPortalController;
use Illuminate\Support\Facades\Route;

/*
 * Portal Klien — Halaman Beranda & Formulir Survei
 */
Route::get('/', [ClientPortalController::class, 'index'])->name('form');
Route::get('/form', [ClientPortalController::class, 'index']);
Route::get('/dashboard', [ClientPortalController::class, 'index'])->name('dashboard');
Route::get('/clients', [ClientPortalController::class, 'index']);
Route::get('/clients/form', [ClientPortalController::class, 'index']);

Route::get('/verify', [ClientPortalController::class, 'verify'])->name('verify');

Route::get('/keluhan', [ClientPortalController::class, 'complaint'])->name('client.complaint');

/*
 * Portal Admin — Pemantauan Kepuasan & Layanan, Papan Keluhan
 */
Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);
