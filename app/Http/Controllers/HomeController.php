<?php

namespace App\Http\Controllers;

// Import model agar tidak perlu menulis path lengkap di dalam fungsi
use App\Models\Service;
use App\Models\User; 
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Pastikan koneksi Supabase di .env sudah benar (Port 6543)
        // Ambil semua data layanan sebagai Objek Eloquent
        $services = Service::all(); 

        // Ambil 3 user dengan role tukang secara acak
        // Pastikan di tabel users sudah ada kolom 'role' dengan isi 'tukang'
        $tukangs = User::where('role', 'tukang')
                       ->inRandomOrder()
                       ->limit(3)
                       ->get();

        return view('welcome', compact('services', 'tukangs'));
    }

    public function about()
    {
        return view('about');
    }

    public function help()
    {
        return view('help');
    }
}