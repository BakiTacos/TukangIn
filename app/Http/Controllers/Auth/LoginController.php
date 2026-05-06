<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Pastikan file resources/views/auth/login.blade.php tersedia
    }

    /**
     * Menangani proses login.
     */
    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'login'    => 'required|string', // Ini adalah input Email dari form
            'password' => 'required|string',
        ]);

        // 2. Persiapan Kredensial (Memetakan input 'login' ke kolom 'email' di DB)
        $credentials = [
            'email'    => $request->login,
            'password' => $request->password,
        ];

        // 3. Mencoba Login (Attempt Authentication)
        // 'remember' bisa ditambahkan jika kamu ingin fitur 'Ingat Saya'
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            
            // Regenerasi session untuk keamanan (Mencegah Session Fixation)
            $request->session()->regenerate();

            // Alihkan ke halaman utama atau halaman yang dituju sebelumnya
            return redirect()->intended('/')
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        // 4. Jika Login Gagal
        throw ValidationException::withMessages([
            'login' => ['Email atau kata sandi yang Anda masukkan salah.'],
        ]);
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('info', 'Anda telah berhasil keluar.');
    }
}