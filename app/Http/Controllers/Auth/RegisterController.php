<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Province;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // app/Http/Controllers/Auth/RegisterController.php
public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'login' => 'required|string|unique:users,email', // Asumsi kolom email
        'password' => 'required|string|min:8',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->login,
        'password' => Hash::make($request->password),
        'role' => 'user', // Default role untuk pendaftar baru
        'status_verifikasi' => 'disetujui',
        'is_blocked' => false,
    ]);

    auth()->login($user);

    return redirect('/');
}

public function showTukangRegisterForm()
    {
        // 1. Ambil Kategori unik dari tabel Services untuk sinkronisasi matching
        $categories = Service::distinct()->pluck('category');

        // 2. Ambil data wilayah untuk Dependent Dropdown
        $provinces = Province::with('cities')->orderBy('name', 'asc')->get();
        
        $citiesMap = [];
        foreach ($provinces as $prov) {
            $citiesMap[$prov->name] = $prov->cities->sortBy('name')->pluck('name')->toArray();
        }

        return view('auth.register-tukang', [
            'categories' => $categories,
            'provinces' => $provinces->pluck('name'),
            'citiesMap' => $citiesMap
        ]);
    }

    public function registerTukang(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|confirmed|min:8',
        'nik' => 'required|numeric|digits:16|unique:users,nik',
        'phone' => 'required|string|max:15',
        'category' => 'required|string',
        'province' => 'required|string',
        'city' => 'required|string',
        'address' => 'required|string|max:500',
    ]);

    $user = clone new User(); // Pakai instansiasi manual agar mutlak
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = \Hash::make($request->password);
    $user->role = 'tukang';
    $user->nik = $request->nik;
    $user->phone = $request->phone;
    $user->category = $request->category;
    $user->province = $request->province;
    $user->city = $request->city;
    $user->address = $request->address;
    
    // ⚡ LOGIKA ONBOARDING BARU
    $user->status_verifikasi = 'menunggu'; // Masuk antrean verifikasi Admin HQ
    $user->is_available = false;           // Belum bisa tampil di pencarian user
    $user->is_blocked = false;             // Bukan akun terhukum, jadi false
    
    $user->save();

    // Login otomatis setelah mendaftar
    auth()->login($user);

    // Lempar ke Dashboard (Nantinya kita akan cegat di Dashboard pakai middleware/blade)
    return redirect()->route('register.pending');
}
}
