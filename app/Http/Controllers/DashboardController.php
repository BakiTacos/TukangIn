<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        // 1. Ambil parameter tab dari URL (default: 'semua')
        $currentTab = $request->query('tab', 'semua');

        // 2. ON-THE-FLY TRIGGER: Sapu bersih order pending > 24 jam
        Order::where('user_id', $userId)
            ->where('status', 'pending')
            ->where('created_at', '<', now()->subHours(24))
            ->update([
                'status' => 'batal',
                'cancel_reason' => 'Waktu Pembayaran Habis',
                'cancel_description' => 'Sistem otomatis membatalkan pesanan karena pembayaran tidak diselesaikan dalam batas waktu 24 jam.'
            ]);

        // 3. Bangun query dasar
        $query = Order::where('user_id', $userId)
            ->with(['service', 'tukang', 'address']);

        // 4. Filter status di tingkat database berdasarkan tab aktif
        if ($currentTab === 'pengerjaan') {
            $query->whereIn('status', ['pending', 'pengerjaan']);
        } elseif ($currentTab === 'dikomplain') {
            $query->where('status', 'dikomplain');
        } elseif ($currentTab === 'selesai') {
            $query->where('status', 'selesai');
        }

        // 5. Paginate dengan tetap membawa query string di link "Next" dan "Previous"
        $orders = $query->latest()->paginate(5)->withQueryString();

        // 6. Hitung statistik global (tidak terpengaruh oleh tab aktif)
        $totalPesananBulanIni = Order::where('user_id', $userId)
            ->where('status', 'selesai')
            ->whereMonth('created_at', now()->month)
            ->count();

        $totalPengeluaran = Order::where('user_id', $userId)
            ->where('status', 'selesai')
            ->sum('total_cost');

        $orders = $query->with(['service', 'tukang', 'address', 'review'])->latest()->paginate(5)->withQueryString();

        return view('dashboard', compact(
            'orders', 
            'totalPesananBulanIni', 
            'totalPengeluaran',
            'currentTab'
        ));
    }
}