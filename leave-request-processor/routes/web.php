<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController; // Tambahkan ini
use App\Http\Controllers\Admin\UserController; // Akan kita buat nanti
use App\Http\Controllers\Admin\DivisionController; // Akan kita buat nanti
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes & Default Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Ubah dashboard default agar mengarah ke controller yang menangani role
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard'); // Rute ini akan mengarahkan user ke dashboard sesuai rolenya

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Role-Specific Routes (CMS Modules)
|--------------------------------------------------------------------------
*/

// --- ADMIN (Full Access CMS) ---
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    // Rute Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('dashboard');

    // Tahap 3: Manajemen Pengguna
    Route::resource('users', UserController::class);

    // Tahap 3: Manajemen Divisi
    Route::resource('divisions', DivisionController::class);

    // Tambahkan rute lain di sini (CRUD Hari Libur, dll.)
});


// --- HRD (Final Approval & Reporting) ---
Route::middleware(['auth', 'role:HRD'])->prefix('hrd')->name('hrd.')->group(function () {
    // Rute Dashboard HRD
    Route::get('/dashboard', [DashboardController::class, 'hrdIndex'])->name('dashboard');

    // Rute Verifikasi Akhir (Tahap 5)
    // Route::get('approvals', [ApprovalController::class, 'finalList'])->name('approvals.final');
    // ...
});


// --- KETUA DIVISI (First Approval) ---
Route::middleware(['auth', 'role:Ketua Divisi'])->prefix('leader')->name('leader.')->group(function () {
    // Rute Dashboard Ketua Divisi
    Route::get('/dashboard', [DashboardController::class, 'leaderIndex'])->name('dashboard');

    // Rute Verifikasi Awal (Tahap 5)
    // Route::get('approvals', [ApprovalController::class, 'leaderList'])->name('approvals.pending');
    // ...
});


// --- KARYAWAN (Self-Service) ---
// Route::middleware(['auth', 'role:Karyawan|Ketua Divisi'])->group(function () {
    // Rute Pengajuan Cuti (Tahap 4)
    // Route::resource('leaves', LeaveApplicationController::class);
// });


require __DIR__.'/auth.php';