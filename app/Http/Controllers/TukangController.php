<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;

class TukangController extends Controller
{
    // 1. HALAMAN DAFTAR TUKANG UMUM
    public function index(Request $request)
    {       
        $query = \App\Models\User::where('role', 'tukang');

        $query->where('is_available', true);

        // ⚡ AKTUALISASI DATA: Ambil jumlah proyek selesai & rating rata-rata dari database
        $query->withCount('completedOrders') 
              ->withAvg('reviews', 'rating');

        // Filter Kategori (Tetap Aman)
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Fitur Search (Tetap Aman)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('specialty', 'like', '%' . $request->search . '%');
            });
        }

        $tukangs = $query->paginate(6)->withQueryString();

        return view('tukang.index', compact('tukangs'));
    }

    // 2. HALAMAN PILIH TUKANG SETELAH PILIH LAYANAN
    public function pilihTukang($slug)
    {
        // 1. Cari data layanan di Supabase berdasarkan slug-nya (Tetap Aman)
        $service = Service::where('slug', $slug)->firstOrFail();

        // 2. Filter User yang rolenya 'tukang' DAN punya kategori yang sama dengan layanan
        $tukangs = User::where('role', 'tukang')
                       ->where('category', $service->category) 
                       ->where('is_available', true) 
                       // ⚡ AKTUALISASI DATA: Hitung project selesai & rata-rata rating aktual
                       ->withCount('completedOrders')
                       ->withAvg('reviews', 'rating')
                       // Urutkan berdasarkan rating kolom bawaan agar sorting tidak patah/error
                       ->orderBy('rating', 'desc') 
                       ->paginate(6);

        // Kirim data $service juga ke view buat nampilin judul (Tetap Aman)
        return view('tukang.pilih', compact('service', 'tukangs'));
    }

    // 3. HALAMAN DETAIL PROFIL TUKANG
    public function show($id, Request $request)
    {
        // Mengambil tukang, ulasan, serta layanan yang sesuai dengan kategori tukang tersebut
        $tukang = \App\Models\User::where('role', 'tukang')
                    ->with([
                        'reviews' => function($query) {
                            $query->latest(); // Urutkan ulasan terbaru (Tetap Aman)
                        },
                        'services' // Eager load layanan berdasarkan kesamaan kategori (Tetap Aman)
                    ])
                    // ⚡ AKTUALISASI DATA: Hitung total ulasan riil & total order selesai aktual secara bersamaan
                    ->withCount(['reviews', 'completedOrders']) 
                    ->withAvg('reviews', 'rating') // ⚡ Hitung rata-rata rating dari ulasan asli
                    ->findOrFail($id);

        // KUNCI UTAMA: Dropdown hanya menampilkan layanan yang dikuasai tukang ini (Tetap Aman)
        $availableServices = $tukang->services; 

        $serviceId = $request->query('service_id');
        
        // Cari layanan dari parameter, pastikan kategorinya cocok dengan si tukang (Tetap Aman)
        $service = null;
        if ($serviceId) {
            $service = \App\Models\Service::where('id', $serviceId)
                        ->where('category', $tukang->category)
                        ->first();
        }
        
        return view('tukang.show', compact('tukang', 'service', 'availableServices'));
    }
}