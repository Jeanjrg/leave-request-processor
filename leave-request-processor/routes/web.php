<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\LeaderApprovalController;
use App\Http\Controllers\HRDApprovalController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DivisionController;
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

    // Rute Verifikasi Akhir (Final Approval)
    Route::get('approvals', [HRDApprovalController::class, 'index'])->name('approvals.index');
    Route::get('approvals/{leave}', [HRDApprovalController::class, 'show'])->name('approvals.show');
    Route::post('approvals/{leave}/approve', [HRDApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('approvals/{leave}/reject', [HRDApprovalController::class, 'reject'])->name('approvals.reject');
});


// --- KETUA DIVISI (First Approval) ---
Route::middleware(['auth', 'role:Ketua Divisi'])->prefix('leader')->name('leader.')->group(function () {
    // Rute Dashboard Ketua Divisi
    Route::get('/dashboard', [DashboardController::class, 'leaderIndex'])->name('dashboard');

    // Rute Verifikasi Awal (First Approval)
    Route::get('approvals', [LeaderApprovalController::class, 'index'])->name('approvals.index');
    Route::get('approvals/{leave}', [LeaderApprovalController::class, 'show'])->name('approvals.show');
    Route::post('approvals/{leave}/approve', [LeaderApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('approvals/{leave}/reject', [LeaderApprovalController::class, 'reject'])->name('approvals.reject');
});


// --- KARYAWAN (Self-Service) ---
Route::middleware(['auth', 'verified'])->group(function () {
    // Rute Pengajuan Cuti (Tahap 4)
    Route::resource('leaves', LeaveApplicationController::class);
});


require __DIR__.'/auth.php';