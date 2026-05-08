<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // app/Http/Controllers/OrderController.php

    public function checkout(Service $service, User $tukang)
    {
        $user = Auth::user();
        
        // Ambil alamat utama
        $address = $user->addresses()->where('is_primary', true)->first() ?? $user->addresses()->first();

        // 1. Ambil harga layanan dari DB (Otomatis bersih jika sudah pakai Accessor)
        $serviceFee = $service->price; 

        // 2. AMBIL BIAYA TEKNISI SECARA DINAMIS (BARU & ANTI-BUG DESIMAL)
        // Kita ambil kolom 'price_kunjungan' dari model $tukang. Jika kosong, beri fallback 75000.
        $rawPriceKunjungan = $tukang->price_kunjungan;
        
        $technicianFee = is_string($rawPriceKunjungan) 
            ? (int) str_replace('.', '', $rawPriceKunjungan) 
            : (int) ($rawPriceKunjungan ?? 75000); 

        // 3. Kalkulasi Pajak 2% dari (Biaya Jasa + Biaya Teknisi)
        $taxRate = 0.02; 
        $taxAmount = ($serviceFee + $technicianFee) * $taxRate;
        
        // Total Pembayaran Akhir
        $totalPayment = $serviceFee + $technicianFee + $taxAmount;

        return view('checkout', compact(
            'service', 
            'tukang', 
            'address', 
            'serviceFee', 
            'technicianFee', // Sekarang nilainya dinamis (misal: 120000)
            'taxAmount', 
            'totalPayment'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required',
            'tukang_id' => 'required',
            'payment_method' => 'required'
        ]);

        // Simpan ke Supabase via Eloquent
        Order::create([
            'user_id' => Auth::id(),
            'service_id' => $request->service_id,
            'tukang_id' => $request->tukang_id,
            'address_id' => $request->address_id,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'total_cost' => $request->total_payment, // Pastikan dikirim dari form atau hitung ulang
        ]);

        return redirect()->route('dashboard')->with('success', 'Pesanan berhasil dibuat!');
    }
}