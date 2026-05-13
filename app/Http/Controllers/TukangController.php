<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use App\Models\Province;
use App\Models\City;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TukangController extends Controller
{
    // 1. HALAMAN DAFTAR TUKANG UMUM (MITRA OFFLINE TETAP TAMPIL DI CARD)
    public function index(Request $request)
    {       
        $query = User::where('role', 'tukang');

        // Deteksi hari ini dinamis (Jakarta)
        $todayNumber = Carbon::now('Asia/Jakarta')->dayOfWeekIso;
        $daysMap = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 
            5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
        ];
        $todayIndo = $daysMap[$todayNumber];

        // ⚡ OPTIMASI N+1: Tetap ambil jadwal hari ini saja untuk kalkulasi visual status di dalam Blade
        $query->with(['schedules' => function($q) use ($todayIndo) {
            $q->where('day', $todayIndo);
        }]);

        // =====================================================================
        // ⚡ LOGIKA PEMANGKASAN DATA DIHAPUS (SEKARANG ALL MITRA TETAP TAMPIL)
        // Kueri ketat `whereHas('schedules')` dilepas agar teknisi yang sedang 
        // istirahat atau di luar jam kerja tidak dibuang dari hasil database.
        // =====================================================================

        // ⚡ FILTER LOKASI PROVINSI & KOTA (Menggunakan ilike agar aman di Supabase)
        if ($request->filled('province')) {
            $query->where('province', 'ilike', $request->province);
        }
        if ($request->filled('city')) {
            $query->where('city', 'ilike', $request->city);
        }

        // Load data penilaian & transaksi selesai aktual
        $query->withCount('completedOrders') 
              ->withAvg('reviews', 'rating');

        // ⚡ FILTER KATEGORI (Menggunakan ilike)
        if ($request->filled('category')) {
            $query->where('category', 'ilike', $request->category);
        }

        // ⚡ FITUR SEARCH BAWAAN (Menggunakan ilike)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'ilike', '%' . $request->search . '%')
                  ->orWhere('specialty', 'ilike', '%' . $request->search . '%');
            });
        }

        $tukangs = $query->paginate(6)->withQueryString();

        // Data lokasi master untuk komponen Dropdown Wilayah
        $provinces = Province::orderBy('name', 'asc')->pluck('name');
        
        $citiesMap = [];
        $dbProvinces = Province::with('cities')->get();
        foreach ($dbProvinces as $prov) {
            $citiesMap[$prov->name] = $prov->cities->sortBy('name')->pluck('name')->toArray();
        }

        return view('tukang.index', compact('tukangs', 'provinces', 'citiesMap'));
    }

    // 2. HALAMAN PILIH TUKANG SETELAH PILIH LAYANAN (MITRA OFFLINE TETAP TAMPIL DI CARD)
    public function pilihTukang(Request $request, $slug)
    {
        $service = Service::where('slug', $slug)->firstOrFail();

        // Deteksi hari ini dinamis (Jakarta)
        $todayNumber = Carbon::now('Asia/Jakarta')->dayOfWeekIso;
        $daysMap = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 
            5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
        ];
        $todayIndo = $daysMap[$todayNumber];

        // Filter Kategori Bawaan Layanan
        $query = User::where('role', 'tukang')
                       ->where('category', 'ilike', $service->category);

        // ⚡ OPTIMASI N+1: Ambil jadwal hari ini saja untuk kalkulasi status di dalam Blade
        $query->with(['schedules' => function($q) use ($todayIndo) {
            $q->where('day', $todayIndo);
        }]);

        // =====================================================================
        // ⚡ LOGIKA PEMANGKASAN DATA DIHAPUS (SEKARANG ALL MITRA TETAP TAMPIL)
        // =====================================================================

        // ⚡ FILTER LOKASI PROVINSI & KOTA (Menggunakan ilike)
        if ($request->filled('province')) {
            $query->where('province', 'ilike', $request->province);
        }
        if ($request->filled('city')) {
            $query->where('city', 'ilike', $request->city);
        }

        $tukangs = $query->withCount('completedOrders')
                         ->withAvg('reviews', 'rating')
                         ->orderBy('rating', 'desc') 
                         ->paginate(6)
                         ->withQueryString();

        // Data lokasi master untuk komponen Dropdown Wilayah
        $provinces = Province::orderBy('name', 'asc')->pluck('name');

        $citiesMap = [];
        $dbProvinces = Province::with('cities')->get();
        foreach ($dbProvinces as $prov) {
            $citiesMap[$prov->name] = $prov->cities->sortBy('name')->pluck('name')->toArray();
        }

        return view('tukang.pilih', compact('service', 'tukangs', 'provinces', 'citiesMap'));
    }

    // 3. HALAMAN DETAIL PROFIL TUKANG (Tetap Aman)
    public function show($id, Request $request)
    {
        $tukang = User::where('role', 'tukang')
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
            $service = Service::where('id', $serviceId)
                        ->where('category', $tukang->category)
                        ->first();
        }

        // Jika user sudah login, cek apakah relasi favoritnya ada di tabel pivot
    $isFavorited = auth()->check() 
        ? \DB::table('tukang_favorites')->where('user_id', auth()->id())->where('tukang_id', $id)->exists() 
        : false;
        
        return view('tukang.show', compact('tukang', 'service', 'availableServices','isFavorited'));
    }

    // 4. API KOTA
    public function getCitiesApi(Request $request)
    {
        $provinceName = $request->query('province');

        if (!$provinceName) {
            return response()->json([]);
        }

        $province = Province::where('name', $provinceName)->first();
        if (!$province) {
            return response()->json([]);
        }

        $cities = $province->cities()->orderBy('name', 'asc')->pluck('name');

        return response()->json($cities);
    }

    // 📂 app/Http/Controllers/TukangController.php

public function toggleFavorite($id)
{
    $user = auth()->user();
    
    // Pastikan teknisi yang difavoritkan benar-benar ada
    $tukang = User::where('role', 'tukang')->findOrFail($id);

    // Contoh menggunakan DB structural check (jika lo pakai tabel pivot 'tukang_favorites')
    // Kolom: user_id (pelanggan), tukang_id (teknisi)
    $favoriteCheck = \DB::table('tukang_favorites')
        ->where('user_id', $user->id)
        ->where('tukang_id', $tukang->id)
        ->first();

    if ($favoriteCheck) {
        // Jika sudah ada, hapus (Unfavorite)
        \DB::table('tukang_favorites')
            ->where('user_id', $user->id)
            ->where('tukang_id', $tukang->id)
            ->delete();
            
        return response()->json(['success' => true, 'status' => 'removed']);
    } else {
        // Jika belum ada, masukkan data baru (Favorite)
        \DB::table('tukang_favorites')->insert([
            'user_id' => $user->id,
            'tukang_id' => $tukang->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return response()->json(['success' => true, 'status' => 'added']);
    }
}
}