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
    // 1. HALAMAN DAFTAR TUKANG UMUM (DENGAN TOGGLE OFFLINE & ANTI-CRASH VERCEL)
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
        $currentTime = Carbon::now('Asia/Jakarta')->format('H:i:s');

        // ⚡ OPTIMASI N+1: Ambil jadwal hari ini saja untuk kalkulasi visual status di Blade
        $query->with(['schedules' => function($q) use ($todayIndo) {
            $q->where('day', $todayIndo);
        }]);

        // ⚡ KONTROL JADWAL OPERASIONAL (DEFAULT: HANYA YANG AKTIF)
        $showInactive = $request->boolean('show_inactive');

        if (!$showInactive) {
            $query->where('is_available', true)
                  ->whereHas('schedules', function($q) use ($todayIndo, $currentTime) {
                      $q->where('day', $todayIndo)
                        ->where('is_active', true)
                        ->whereTime('start_time', '<=', $currentTime)
                        ->whereTime('end_time', '>=', $currentTime);
                  });
        }

        // ⚡ FILTER LOKASI PROVINSI & KOTA (Menggunakan ilike agar aman dari sensitivitas huruf di Supabase)
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

        // ⚡ FITUR SEARCH BAWAAN (Menggunakan ilike agar 'wawan' tetap mendeteksi 'Wawan')
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'ilike', '%' . $request->search . '%')
                  ->orWhere('specialty', 'ilike', '%' . $request->search . '%');
            });
        }

        $tukangs = $query->paginate(6)->withQueryString();

        // ⚡ PROSES DATA LOKASI MASTER SECARA AMAN (Array PHP Murni untuk Komponen)
        $provinces = Province::orderBy('name', 'asc')->pluck('name');
        
        $citiesMap = [];
        $dbProvinces = Province::with('cities')->get();
        foreach ($dbProvinces as $prov) {
            $citiesMap[$prov->name] = $prov->cities->sortBy('name')->pluck('name')->toArray();
        }

        return view('tukang.index', compact('tukangs', 'provinces', 'citiesMap'));
    }

    // 2. HALAMAN PILIH TUKANG SETELAH PILIH LAYANAN (DENGAN TOGGLE OFFLINE & ANTI-CRASH VERCEL)
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
        $currentTime = Carbon::now('Asia/Jakarta')->format('H:i:s');

        // ⚡ FILTER KATEGORI SENSITIF (Menggunakan ilike agar 'Interior' cocok dengan 'interior')
        $query = User::where('role', 'tukang')
                       ->where('category', 'ilike', $service->category);

        // ⚡ OPTIMASI N+1: Ambil jadwal hari ini saja untuk kalkulasi status di Blade
        $query->with(['schedules' => function($q) use ($todayIndo) {
            $q->where('day', $todayIndo);
        }]);

        // ⚡ KONTROL JADWAL OPERASIONAL (DEFAULT: HANYA YANG AKTIF)
        $showInactive = $request->boolean('show_inactive');

        if (!$showInactive) {
            $query->where('is_available', true)
                  ->whereHas('schedules', function($q) use ($todayIndo, $currentTime) {
                      $q->where('day', $todayIndo)
                        ->where('is_active', true)
                        ->whereTime('start_time', '<=', $currentTime)
                        ->whereTime('end_time', '>=', $currentTime);
                  });
        }

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

        // ⚡ PROSES DATA LOKASI MASTER SECARA AMAN (Array PHP Murni untuk Komponen)
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
        
        return view('tukang.show', compact('tukang', 'service', 'availableServices'));
    }

    // 4. API KOTA (Tetap dipertahankan untuk kebutuhan eksternal jika ada)
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
}