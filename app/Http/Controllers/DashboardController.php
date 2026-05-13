<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Service; // Impor model Service agar pemanggilan query di bawah lebih bersih
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Province;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        // =========================================================================
        // GERBANG 1: ALUR KERJA KHUSUS MITRA TEKNISI (TUKANG)
        // =========================================================================
        if ($user->role === 'tukang') {
    $user->loadCount('completedOrders'); 
    $user->loadAvg('reviews', 'rating');  

    // ⚡ OTO-INISIALISASI JADWAL: Sekarang default-nya langsung TRUE (Aktif)
    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    foreach ($days as $day) {
        $user->schedules()->firstOrCreate(
            ['day' => $day],
            [
                'is_active' => true, // ⚡ UBAH MENJADI TRUE
                'start_time' => '08:00', 
                'end_time' => '17:00'
            ]
        );
    }

    $allCities = [];
    $dbProvinces = Province::with('cities')->get();
    foreach ($dbProvinces as $prov) {
        foreach ($prov->cities as $city) {
            $allCities[] = [
                'name' => $city->name,
                'province' => $prov->name
            ];
        }
    }

    usort($allCities, function($a, $b) {
        return strcmp($a['name'], $b['name']);
    });

            // Total Pendapatan Aktual
            $totalEarnings = Order::where('tukang_id', $userId)
                ->where('status', 'selesai')
                ->sum('technician_fee');

            // Pekerjaan Berjalan Aktual
            $activeJobs = Order::where('tukang_id', $userId)
                ->where('status', 'pengerjaan')
                ->with(['service', 'user', 'address'])
                ->latest()
                ->get();
            $activeJobsCount = $activeJobs->count();

            // SINKRONISASI TIMEZONE: Filter orderan masuk kompatibel dengan local & server Supabase
            $incomingOrders = Order::where('tukang_id', $userId)
                ->where('status', 'pending')
                ->where(function($q) {
                    $q->where('created_at', '>=', now('Asia/Jakarta')->subHours(24))
                    ->orWhere('created_at', '>=', now('UTC')->subHours(24))
                    ->orWhere('created_at', '>=', now()->subHours(24));
                })
                ->with(['service', 'user', 'address'])
                ->latest()
                ->get();

            // Riwayat Pekerjaan Aktual dengan Pagination (Maksimal 5 item)
            $jobHistory = Order::where('tukang_id', $userId)
                ->whereIn('status', ['selesai', 'batal', 'dikomplain'])
                ->with(['service', 'user', 'address', 'review'])
                ->latest()
                ->paginate(5)
                ->withQueryString();

            // Urutkan Jadwal Kerja secara logis (Senin -> Minggu)
            $schedules = $user->schedules->sortBy(function($schedule) {
                $dayOrder = [
                    'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 
                    'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7
                ];
                return $dayOrder[$schedule->day] ?? 8;
            });

            return view('tukang.dashboard', compact(
                'activeJobs', 'activeJobsCount', 'totalEarnings', 'jobHistory', 'schedules',
                'allCities'
            ));
        }

        // =========================================================================
        // GERBANG 2: ALUR KERJA KHUSUS PELANGGAN REGULER (USER)
        // =========================================================================
        $currentTab = $request->query('tab', 'semua');

        // Otomatis batalkan orderan pending yang melewati batas 24 jam pembayaran
        Order::where('user_id', $userId)
            ->where('status', 'pending')
            ->where('created_at', '<', now()->subHours(24))
            ->update([
                'status' => 'batal',
                'cancel_reason' => 'Waktu Pembayaran Habis',
                'cancel_description' => 'Sistem otomatis membatalkan pesanan karena pembayaran tidak diselesaikan dalam batas waktu 24 jam.'
            ]);

        $query = Order::where('user_id', $userId);

        if ($currentTab === 'pengerjaan') {
            $query->whereIn('status', ['pending', 'pengerjaan']);
        } elseif ($currentTab === 'dikomplain') {
            $query->where('status', 'dikomplain');
        } elseif ($currentTab === 'selesai') {
            $query->where('status', 'selesai');
        }

        $orders = $query->with(['service', 'tukang', 'address', 'review'])
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $totalPesananBulanIni = Order::where('user_id', $userId)
            ->where('status', 'selesai')
            ->whereMonth('created_at', now()->month)
            ->count();

        $totalPengeluaran = Order::where('user_id', $userId)
            ->where('status', 'selesai')
            ->sum('total_cost');

        return view('dashboard', compact('orders', 'totalPesananBulanIni', 'totalPengeluaran', 'currentTab'));
    }

    public function updateSchedule(Request $request)
{
    // 1. Cukup validasi bahwa data schedules yang masuk harus berupa array
    $request->validate([
        'schedules' => 'required|array',
    ]);

    $user = Auth::user();

    // 2. Kunci daftar 7 hari kerja secara statis agar looping tidak bocor
    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    $submittedSchedules = $request->input('schedules', []);

    foreach ($days as $day) {
        // JIKA hari tersebut dicentang di browser, maka is_active = true.
        // JIKA tidak dicentang (atau datanya tidak dikirim browser karena disabled), maka is_active = false.
        $isActive = isset($submittedSchedules[$day]['is_active']);

        $updateData = [
            'is_active' => $isActive,
        ];

        // Hanya perbarui jam jika dikirim oleh browser (ketika hari berstatus aktif)
        // Jika dinonaktifkan (disabled), kita pertahankan jam lama di DB agar tidak memicu error NULL/kosong
        if (isset($submittedSchedules[$day]['start_time'])) {
            $updateData['start_time'] = $submittedSchedules[$day]['start_time'];
        }
        if (isset($submittedSchedules[$day]['end_time'])) {
            $updateData['end_time'] = $submittedSchedules[$day]['end_time'];
        }

        // Jalankan query update ke Supabase secara presisi berdasarkan nama hari
        $user->schedules()->where('day', $day)->update($updateData);
    }

    return redirect()->back()->with('success', 'Jadwal kerja operasional Anda berhasil diperbarui!');
} #tes

    public function toggleAvailability(Request $request)
    {
        $user = Auth::user();
        
        // Balikkan nilai boolean status aktif/istirahat
        $user->is_available = !$user->is_available;
        $user->save();

        return response()->json([
            'success' => true,
            'is_available' => $user->is_available
        ]);
    }

    public function editProfile()
{
    $user = auth()->user();

    // 1. Ambil semua data provinsi master
    $provinces = \App\Models\Province::orderBy('name', 'asc')->pluck('name');

    // 2. ⚡ AMAN VERCEL: Petakan kota berdasarkan provinsi dalam bentuk array PHP murni
    $citiesMap = [];
    $dbProvinces = \App\Models\Province::with('cities')->get();
    foreach ($dbProvinces as $prov) {
        $citiesMap[$prov->name] = $prov->cities->sortBy('name')->pluck('name')->toArray();
    }

    return view('dashboard.profile', compact('user', 'provinces', 'citiesMap'));
}

// app/Http/Controllers/DashboardController.php

public function updateLocation(Request $request)
{
    $request->validate([
        'city_data' => 'required|string',
    ]);

    // Memecah string "Nama Kota|Nama Provinsi" yang dikirim oleh Form
    $locationParts = explode('|', $request->city_data);
    
    if (count($locationParts) === 2) {
        auth()->user()->update([
            'city' => $locationParts[0],
            'province' => $locationParts[1],
        ]);
        
        return redirect()->back()->with('success', 'Wilayah operasional kota Anda berhasil diperbarui!');
    }

    return redirect()->back()->with('error', 'Format pilihan lokasi tidak valid.');
}
}