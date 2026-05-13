<?php
// 📂 app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // ⚡ GATING PATROL: Tendang jika yang masuk bukan admin resmi
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Maaf, halaman ini hanya bisa diakses oleh Direksi TUKANG.IN.');
        }

        // 1. Hitung Keuangan (Total GMV dari orderan berstatus selesai)
        $totalGmv = Order::where('status', 'selesai')->sum('total_price');

        // 2. Hitung Sensus Pengguna di Database
        $totalPelanggan = User::where('role', 'user')->count();
        $totalMitraTeknisi = User::where('role', 'tukang')->count();

        // 3. Hitung Pekerjaan Kritis (Menunggu, Proses pengerjaan, atau sedang Dikomplain)
        $criticalOrdersCount = Order::whereIn('status', ['menunggu', 'proses', 'dikomplain'])->count();

        // 4. Ambil 6 Transaksi Terbaru untuk Tabel Monitoring Live
        $recentOrders = Order::with(['user', 'tukang', 'service'])
            ->latest()
            ->paginate(6);

        return view('admin.dashboard', compact(
            'totalGmv', 
            'totalPelanggan', 
            'totalMitraTeknisi', 
            'criticalOrdersCount', 
            'recentOrders'
        ));
    }
}
