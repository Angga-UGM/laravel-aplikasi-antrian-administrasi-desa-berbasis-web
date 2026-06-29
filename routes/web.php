<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AntrianController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// ========== ROUTE YANG TIDAK PERLU LOGIN ==========
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Route Registrasi dan Lupa Password dari Breeze
Route::get('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\NewPasswordController::class, 'store'])->name('password.store');

// ========== ROUTE GANTI PASSWORD LANGSUNG (TANPA EMAIL) ==========
Route::post('/password/update-direct', function (Request $request) {
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'password' => 'required|min:8|confirmed',
    ]);

    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    return redirect()->route('login')->with('status', 'Password berhasil diubah! Silakan login dengan password baru.');
})->name('password.update.direct');

// ========== ROUTE YANG WAJIB LOGIN ==========
Route::middleware(['auth'])->group(function () {

    // Halaman utama (form pendaftaran)
    Route::get('/', [AntrianController::class, 'form'])->name('antrian.form');
    Route::post('/antrian/store', [AntrianController::class, 'store'])->name('antrian.store');
    Route::get('/antrian/cetak/{id}', [AntrianController::class, 'cetak'])->name('antrian.cetak');

    // Layar informasi antrian
    Route::get('/layar', [AntrianController::class, 'layar'])->name('antrian.layar');
    Route::get('/api/antrian-layar', [AntrianController::class, 'getAntrianLayar'])->name('antrian.getAntrianLayar');

    // PROFILE ROUTES (LENGKAP)
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // ADMIN ROUTES
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/kalender', [AdminController::class, 'kalender'])->name('admin.kalender');
        Route::get('/api/antrian-by-date', [AdminController::class, 'getAntrianByDate'])->name('admin.antrianByDate');
        Route::post('/panggil/{id}', [AdminController::class, 'panggil'])->name('admin.panggil');
        Route::post('/selesai/{id}', [AdminController::class, 'selesai'])->name('admin.selesai');
        Route::post('/batal/{id}', [AdminController::class, 'batal'])->name('admin.batal');
        Route::get('/api/antrian-realtime', [AdminController::class, 'getAntrianRealtime'])->name('admin.getAntrianRealtime');
    });
});

// Redirect default dashboard ke admin dashboard
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

// Breeze Auth routes (include file auth.php jika ada)
require __DIR__.'/auth.php';
