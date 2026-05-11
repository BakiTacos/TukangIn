<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TukangController extends Controller
{
    // 1. HALAMAN DAFTAR TUKANG UMUM
    public function index(Request $request)
    {       
        $query = \App\Models\User::where('role', 'tukang');

        // Filter 1: Tombol manual ketersediaan tukang wajib ON (Tersedia)
        $query->where('is_available', true);

        // ⚡ Filter 2: KETERSEDIAAN JADWAL HARI INI (DINAMIS)
        $todayNumber = Carbon::now('Asia/Jakarta')->dayOfWeekIso; // Mengembalikan angka 1 (Senin) - 7 (Minggu)
        $daysMap = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 
            5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
        ];
        $todayIndo = $daysMap[$todayNumber];

        // Ambil waktu jam menit detik saat ini (Format HH:MM:SS) berdasarkan Zona Jakarta
        $currentTime = Carbon::now('Asia/Jakarta')->format('H:i:s');

        $query->whereHas('schedules', function($q) use ($todayIndo, $currentTime) {
            $q->where('day', $todayIndo)
              ->where('is_active', true)
              // ⚡ SOLUSI: Menggunakan whereTime agar dicast dengan sempurna oleh PostgreSQL Supabase
              ->whereTime('start_time', '<=', $currentTime)
              ->whereTime('end_time', '>=', $currentTime);
        });

        // Load data penilaian & transaksi selesai aktual
        $query->withCount('completedOrders') 
              ->withAvg('reviews', 'rating');

        // Filter Kategori Bawaan (Tetap Aman)
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Fitur Search Bawaan (Tetap Aman)
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
        $service = Service::where('slug', $slug)->firstOrFail();

        // ⚡ DETEKSI HARI INI (DINAMIS)
        $todayNumber = Carbon::now('Asia/Jakarta')->dayOfWeekIso;
        $daysMap = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 
            5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
        ];
        $todayIndo = $daysMap[$todayNumber];

        // Ambil waktu jam menit detik saat ini berdasarkan Zona Jakarta
        $currentTime = Carbon::now('Asia/Jakarta')->format('H:i:s');

        $tukangs = User::where('role', 'tukang')
                       ->where('category', $service->category) 
                       ->where('is_available', true) 
                       // ⚡ KUNCI UTAMA: Sinkronisasikan logika pencarian hari & jam operasional yang sama di sini
                       ->whereHas('schedules', function($q) use ($todayIndo, $currentTime) {
                           $q->where('day', $todayIndo)
                             ->where('is_active', true)
                             // ⚡ SOLUSI: Gunakan whereTime di sini juga agar sinkron saat checkout!
                             ->whereTime('start_time', '<=', $currentTime)
                             ->whereTime('end_time', '>=', $currentTime);
                       })
                       ->withCount('completedOrders')
                       ->withAvg('reviews', 'rating')
                       ->orderBy('rating', 'desc') 
                       ->paginate(6);

        return view('tukang.pilih', compact('service', 'tukangs'));
    }

    // 3. HALAMAN DETAIL PROFIL TUKANG
    public function show($id, Request $request)
    {
        $tukang = \App\Models\User::where('role', 'tukang')
                    ->with([
                        'reviews' => function($query) {
                            $query->latest(); 
                        },
                        'services',
                        'schedules'
                    ])
                    ->withCount(['reviews', 'completedOrders']) 
                    ->withAvg('reviews', 'rating') 
                    ->findOrFail($id);

        $availableServices = $tukang->services; 

        $serviceId = $request->query('service_id');
        
        $service = null;
        if ($serviceId) {
            $service = \App\Models\Service::where('id', $serviceId)
                        ->where('category', $tukang->category)
                        ->first();
        }
        
        return view('tukang.show', compact('tukang', 'service', 'availableServices'));
    }
}