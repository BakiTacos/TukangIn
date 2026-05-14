<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // 📊 MENU 1: DASBOR VISUALISASI UTAMA
    public function dashboard()
    {
        // ⚡ GATING INLINE (Foolproof & Pasti Berhasil)
        if (auth()->user()->role !== 'admin') { 
            abort(403, 'Akses HQ Terkunci.'); 
        }

        $totalGmv = Order::where('status', 'selesai')->sum('total_cost');
        $totalTransactions = Order::count();
        $avgPurchaseValue = $totalTransactions > 0 ? ($totalGmv / Order::where('status', 'selesai')->count()) : 0;

        $totalPelanggan = User::where('role', 'user')->count();
        $totalMitraTeknisi = User::where('role', 'tukang')->count();
        $criticalOrdersCount = Order::whereIn('status', ['menunggu', 'proses', 'dikomplain'])->count();

        return view('admin.dashboard', compact(
            'totalGmv', 'totalTransactions', 'avgPurchaseValue', 
            'totalPelanggan', 'totalMitraTeknisi', 'criticalOrdersCount'
        ));
    }

    // 🛡️ MENU 2: HALAMAN KHUSUS MANAJEMEN AKUN
    public function manageUsers(Request $request)
{
    // Gating Keamanan Hak Akses HQ Admin
    if (auth()->user()->role !== 'admin') { 
        abort(403, 'Akses HQ Terkunci.'); 
    }

    // ⚡ AMBIL PARAMETER FILTER STATUS DAN PENCARIAN
    $searchUser = $request->query('search_user');
    $statusFilter = $request->query('status', 'semua');

    $userQuery = User::where('id', '!=', auth()->id()); // Proteksi: Jangan tampilkan diri sendiri

    // ⚡ EKSEKUSI FILTERING STATUS MODERASI BLOKIR
    if ($statusFilter === 'terblokir') {
        $userQuery->where('is_blocked', true);
    } elseif ($statusFilter === 'aktif') {
        $userQuery->where('is_blocked', false);
    }

    // Eksekusi Kondisi Pencarian Nama
    if ($searchUser) {
        $userQuery->where('name', 'ilike', '%' . $searchUser . '%');
    }

    // Ambil data dengan pagination 10 item per halaman
    $users = $userQuery->latest()->paginate(10)->withQueryString();
    
    return view('admin.users', compact('users', 'statusFilter'));
}
    // 📋 MENU 3: HALAMAN KHUSUS LOG TRANSAKSI & SENGKETA
    // 📂 app/Http/Controllers/AdminController.php

public function manageOrders(Request $request)
{
    // Gating Keamanan Hak Akses HQ
    if (auth()->user()->role !== 'admin') { 
        abort(403, 'Akses HQ Terkunci.'); 
    }

    $statusFilter = $request->query('status', 'semua');
    $orderQuery = Order::with(['user', 'tukang', 'service']);

    // ⚡ PERLUASAN FITUR FILTERING DATA (SUPABASE POSTGRES COMPATIBLE)
    if ($statusFilter === 'selesai') {
        $orderQuery->where('status', 'selesai');
    } elseif ($statusFilter === 'batal') {
        $orderQuery->where('status', 'batal');
    } elseif ($statusFilter === 'pengerjaan') {
        // Mengamankan jika di DB lo menggunakan istilah 'proses' atau 'pengerjaan'
        $orderQuery->whereIn('status', ['proses', 'pengerjaan']);
    } elseif ($statusFilter === 'dikomplain') {
        $orderQuery->where('status', 'dikomplain');
    }

    // Eksekusi data dengan Pagination 10 item per halaman
    $recentOrders = $orderQuery->latest()->paginate(10)->withQueryString();
    
    return view('admin.orders', compact('recentOrders', 'statusFilter'));
}

    // ⚡ PROSES BLOCK AKUN
    public function blockUser(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') { abort(403); }

        $request->validate(['blocked_reason' => 'required|string|max:500']);
        $user = User::findOrFail($id);
        $user->update([
            'is_blocked' => true,
            'blocked_reason' => $request->blocked_reason
        ]);

        return redirect()->back()->with('success', "Akun {$user->name} berhasil diblokir.");
    }

    // ⚡ PROSES UNBLOCK AKUN
    public function unblockUser($id)
    {
        if (auth()->user()->role !== 'admin') { abort(403); }

        $user = User::findOrFail($id);
        $user->update([
            'is_blocked' => false,
            'blocked_reason' => null
        ]);

        return redirect()->back()->with('success', "Blokir akun {$user->name} telah dibuka.");
    }

    // ⚖️ PUSAT AUDIT SENGKETA
    public function reviewOrder($id)
    {
        if (auth()->user()->role !== 'admin') { abort(403); }

        $order = Order::with(['user', 'tukang', 'service', 'address'])->findOrFail($id);
        return view('admin.review', compact('order'));
    }

    // ⚖️ EKSEKUSI RESOLUSI KEPUTUSAN SENGKETA
    // 📂 app/Http/Controllers/AdminController.php

// 📂 app/Http/Controllers/AdminController.php

// 📂 app/Http/Controllers/AdminController.php

public function resolveOrder(Request $request, $id)
{
    if (auth()->user()->role !== 'admin') { abort(403); }

    $request->validate([
        'status' => 'required|in:selesai,batal,dikomplain,pengerjaan', // ⚡ Pastikan 'pengerjaan' masuk daftar whitelist
        'admin_note' => 'required|string|max:1000'
    ]);

    $order = Order::findOrFail($id);
    
    $updateData = [
        'status' => $request->status,
        'admin_note' => $request->admin_note
    ];

    // Tentukan sub_status berdasarkan keputusan sidang resmi admin
    if ($request->status === 'selesai') {
        $updateData['sub_status'] = 'komplain_ditolak';
    } elseif ($request->status === 'batal') {
        $updateData['sub_status'] = 'komplain_diterima';
    } elseif ($request->status === 'dikomplain') {
        $updateData['sub_status'] = 'proses_banding';
    } elseif ($request->status === 'pengerjaan') { 
        // ⚡ Jika dikembalikan ke lapangan, sub_status dicatat sebagai garansi_perbaikan
        $updateData['sub_status'] = 'garansi_perbaikan';
    }

    $order->update($updateData);

    return redirect()->route('admin.orders.index')->with('success', 'Putusan sidang arbitrase berhasil dieksekusi!');
}
}