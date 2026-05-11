<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        if ($user->role === 'tukang') {
        $user->loadCount('completedOrders'); // menghasilkan completed_orders_count
        $user->loadAvg('reviews', 'rating');  // menghasilkan reviews_avg_rating
    }

        // =========================================================================
        // GERBANG 1: ALUR KERJA KHUSUS MITRA TEKNISI (TUKANG)
        // =========================================================================
        // app/Http/Controllers/DashboardController.php

if ($user->role === 'tukang') {
    $totalEarnings = Order::where('tukang_id', $userId)
        ->where('status', 'selesai')
        ->sum('technician_fee');

    $activeJobs = Order::where('tukang_id', $userId)
        ->where('status', 'pengerjaan')
        ->with(['service', 'user', 'address'])
        ->latest()
        ->get();
    $activeJobsCount = $activeJobs->count();

    // SINKRONISASI TIMEZONE: Gabungkan filter agar kompatibel dengan local & server Supabase
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

    $jobHistory = Order::where('tukang_id', $userId)
        ->whereIn('status', ['selesai', 'batal', 'dikomplain'])
        ->with(['service', 'user', 'address', 'review'])
        ->latest()
        ->paginate(5)
        ->withQueryString();

    return view('tukang.dashboard', compact(
        'totalEarnings', 
        'activeJobs', 
        'activeJobsCount', 
        'incomingOrders', 
        'jobHistory'
    ));
}

        // =========================================================================
        // GERBANG 2: ALUR KERJA KHUSUS PELANGGAN REGULER (USER)
        // =========================================================================
        $currentTab = $request->query('tab', 'semua');

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

    // =========================================================================
    // METHOD BARU: TOGGLE STATUS IS_AVAILABLE TUKANG (AJAX API)
    // =========================================================================
    public function toggleAvailability(Request $request)
    {
        $user = Auth::user();
        
        // Balikkan nilai boolean saat ini (True -> False / False -> True)
        $user->is_available = !$user->is_available;
        $user->save();

        return response()->json([
            'success' => true,
            'is_available' => $user->is_available
        ]);
    }
}