<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QueueController;
use Illuminate\Support\Facades\Route;

// Halaman Pengguna & Endpoint Realtime
Route::get('/', [QueueController::class, 'index'])->name('queue.index');
Route::post('/antrian/ambil', [QueueController::class, 'store'])->name('queue.store');
Route::get('/antrian/status', [QueueController::class, 'status'])->name('queue.status');

// Otentikasi Admin
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Panel Admin
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
   Route::get('/dashboard', [QueueController::class, 'dashboard'])->name('dashboard');
   Route::post('/antrian/next', [QueueController::class, 'next'])->name('queue.next');
   Route::post('/antrian/prev', [QueueController::class, 'prev'])->name('queue.prev');
});