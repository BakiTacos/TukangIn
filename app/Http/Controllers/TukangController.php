<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service; // WAJIB DIIMPORT untuk nyari kategori
use Illuminate\Http\Request;

class TukangController extends Controller
{
    /**
     * Halaman daftar semua tukang (General Index)
     */
    public function index(Request $request)
    {       
        $query = \App\Models\User::where('role', 'tukang');

        $query->where('is_available', true);

        // Filter Kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Fitur Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('specialty', 'like', '%' . $request->search . '%');
            });
        }

        $tukangs = $query->paginate(6)->withQueryString();

        return view('tukang.index', compact('tukangs'));
    }

    /**
     * Halaman Matchmaking (Pilih Tukang Berdasarkan Kategori Layanan)
     */
    public function pilihTukang($slug)
    {
        // 1. Cari data layanan di Supabase berdasarkan slug-nya
        $service = Service::where('slug', $slug)->firstOrFail();

        // 2. Filter User yang rolenya 'tukang' DAN punya kategori yang sama dengan layanan
        // Contoh: Layanan "Cuci Toren" kategorinya "Plumbing", maka yang muncul cuma tukang Plumbing
        $tukangs = User::where('role', 'tukang')
                       ->where('category', $service->category) 
                       ->orderBy('rating', 'desc') // Biar user dapet teknisi terbaik di urutan atas
                       ->paginate(6);

        // Kirim data $service juga ke view buat nampilin judul "Pilih Teknisi untuk [Nama Layanan]"
        return view('tukang.pilih', compact('service', 'tukangs'));
    }

    // app/Http/Controllers/TukangController.php

    public function show($id, Request $request)
    {
        // Mengambil tukang, ulasan, serta layanan yang sesuai dengan kategori tukang tersebut
        $tukang = \App\Models\User::where('role', 'tukang')
                    ->with([
                        'reviews' => function($query) {
                            $query->latest(); // Urutkan ulasan terbaru
                        },
                        'services' // Eager load layanan berdasarkan kesamaan kategori (BARU)
                    ])
                    ->withCount('reviews') // Menghitung total ulasan otomatis
                    ->findOrFail($id);

        // KUNCI UTAMA: Dropdown hanya menampilkan layanan yang dikuasai tukang ini
        $availableServices = $tukang->services; 

        $serviceId = $request->query('service_id');
        
        // Cari layanan dari parameter, pastikan kategorinya cocok dengan si tukang
        $service = null;
        if ($serviceId) {
            $service = \App\Models\Service::where('id', $serviceId)
                        ->where('category', $tukang->category)
                        ->first();
        }
        
        return view('tukang.show', compact('tukang', 'service', 'availableServices'));
    }
}