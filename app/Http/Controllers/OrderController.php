<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function checkout(Service $service, User $tukang)
    {
        $user = Auth::user();
        
        // Ambil alamat utama (Primary)
        $address = $user->addresses()->where('is_primary', true)->first() ?? $user->addresses()->first();

        // LOGIKA BIAYA DINAMIS
        $serviceFee = $service->price; // Misal: 100.000 dari DB
        $technicianFee = 85000;       // Biaya jasa teknisi (Ferry)
        
        $taxRate = 0.02; // 2%
        $taxAmount = ($serviceFee + $technicianFee) * $taxRate;
        $totalPayment = $serviceFee + $technicianFee + $taxAmount;

        return view('checkout', compact(
            'service', 
            'tukang', 
            'address', 
            'serviceFee', 
            'technicianFee', 
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