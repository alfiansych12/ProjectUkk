<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // Safe Redirects for modal-based workflow
        Route::get('users/create', fn() => redirect()->route('admin.users.index'));
        Route::get('users/{user}', fn() => redirect()->route('admin.users.index'));
        Route::get('users/{user}/edit', fn() => redirect()->route('admin.users.index'));

        Route::get('alats/create', fn() => redirect()->route('admin.alats.index'));
        Route::get('alats/{alat}', fn() => redirect()->route('admin.alats.index'));
        Route::get('alats/{alat}/edit', fn() => redirect()->route('admin.alats.index'));

        Route::get('kategoris/create', fn() => redirect()->route('admin.kategoris.index'));
        Route::get('kategoris/{kategori}', fn() => redirect()->route('admin.kategoris.index'));
        Route::get('kategoris/{kategori}/edit', fn() => redirect()->route('admin.kategoris.index'));

        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['create', 'edit', 'show']);
        Route::resource('alats', \App\Http\Controllers\Admin\AlatController::class)->except(['create', 'edit', 'show']);
        Route::resource('kategoris', \App\Http\Controllers\Admin\KategoriController::class)->except(['create', 'edit', 'show']);
        Route::get('logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('logs.index');
    });

    // Petugas & Admin (overlapping features)
    Route::middleware('role:admin,petugas')->group(function () {
        Route::get('loans/manage', [\App\Http\Controllers\LoanController::class, 'manage'])->name('loans.manage');
        Route::post('loans/offline/otp', [\App\Http\Controllers\LoanController::class, 'sendOfflineOtp'])->name('loans.sendOfflineOtp');
        Route::post('loans/offline', [\App\Http\Controllers\LoanController::class, 'storeOffline'])->name('loans.storeOffline');
        Route::post('loans/{loan}/approve', [\App\Http\Controllers\LoanController::class, 'approve'])->name('loans.approve');
        Route::post('loans/{loan}/reject', [\App\Http\Controllers\LoanController::class, 'reject'])->name('loans.reject');
        Route::post('loans/{loan}/return', [\App\Http\Controllers\LoanController::class, 'returnTool'])->name('loans.return');
        Route::put('loans/{loan}', [\App\Http\Controllers\LoanController::class, 'update'])->name('loans.update');
        Route::delete('loans/{loan}', [\App\Http\Controllers\LoanController::class, 'destroy'])->name('loans.destroy');
        Route::get('loans/report', [\App\Http\Controllers\LoanController::class, 'report'])->name('loans.report');
    });

    // Peminjam Routes
    Route::middleware('role:peminjam')->group(function () {
        Route::post('loans/request', [\App\Http\Controllers\LoanController::class, 'store'])->name('loans.store');
        Route::post('loans/{loan}/request-return', [\App\Http\Controllers\LoanController::class, 'requestReturn'])->name('loans.requestReturn');
    });

    // Semua user yang login (admin, petugas, peminjam) bisa akses alats dan my-loans
    Route::middleware('auth')->group(function () {
        Route::get('alats', [\App\Http\Controllers\Admin\AlatController::class, 'index'])->name('alats.index');
        Route::get('my-loans', [\App\Http\Controllers\LoanController::class, 'index'])->name('loans.index');
    });
});

require __DIR__ . '/auth.php';
