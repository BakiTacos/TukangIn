<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Ambil data pesanan milik user yang sedang login
        // Gunakan with() untuk eager loading agar tidak berat di database Supabase
        $orders = Order::with(['tukang', 'service', 'address'])
                    ->where('user_id', $user->id)
                    ->latest()
                    ->get();

        // 2. Hitung statistik untuk sidebar
        $totalPesananBulanIni = $orders->where('created_at', '>=', now()->startOfMonth())->count();
        $totalPengeluaran = $orders->where('status', 'selesai')->sum('total_cost');

        // 3. KIRIM DATA KE VIEW (PENTING!)
        return view('dashboard', compact('orders', 'totalPesananBulanIni', 'totalPengeluaran'));
    }
}