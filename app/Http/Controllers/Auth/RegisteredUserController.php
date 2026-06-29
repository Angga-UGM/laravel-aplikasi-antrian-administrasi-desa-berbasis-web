<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // VALIDASI DATA INPUT
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // SIMPAN DATA USER BARU KE DATABASE
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // KIRIM EVENT REGISTRASI (untuk verifikasi email dll)
        event(new Registered($user));

        // ========== PERUBAHAN DI SINI ==========
        // BARIS INI DIHAPUS / DIKOMENTAR
        // Tujuannya: AGAR USER TIDAK OTOMATIS LOGIN SETELAH REGISTRASI
        // Auth::login($user);  <-- DIKOMENTAR, JADI TIDAK DIJALANKAN

        // ========== PERUBAHAN DI SINI ==========
        // REDIRECT KE HALAMAN LOGIN (BUKAN DASHBOARD)
        // Tujuannya: AGAR USER HARUS LOGIN MANUAL DULU
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}