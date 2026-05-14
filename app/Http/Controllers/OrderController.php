<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{

// app/Http/Controllers/OrderController.php

    // app/Http/Controllers/OrderController.php

    public function show(Order $order)
    {
        $user = Auth::user();
        // Proteksi agar user lain tidak bisa mengintip orderan orang lain
        if ((int)$order->user_id !== (int)$user->id && (int)$order->tukang_id !== (int)$user->id) {
        abort(403, 'Anda tidak memiliki hak akses untuk melihat rincian pesanan ini.');
        }

        // Eager Load untuk performa database optimal
        $order->load(['service', 'tukang', 'address', 'review']);

        // Hitung rincian biaya secara dinamis untuk invoice
        $serviceFee = (int) $order->service->price;
        $technicianFee = is_string($order->tukang->price_kunjungan) 
            ? (int) str_replace('.', '', $order->tukang->price_kunjungan) 
            : (int) ($order->tukang->price_kunjungan ?? 75000);

        $taxAmount = ($serviceFee + $technicianFee) * 0.05;

        return view('order.show', compact('order', 'serviceFee', 'technicianFee', 'taxAmount'));
    }

    // app/Http/Controllers/OrderController.php

    // app/Http/Controllers/OrderController.php

    public function complain(Request $request, $id)
    {
        // 1. Validasi Input Ketat (Maksimal Gambar 2MB)
        $request->validate([
            'cancel_reason' => 'required|string|max:255',
            'cancel_description' => 'required|string|max:1000',
            'complaint_image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Batasi 2MB biar hemat bandwith bucket
        ]);

        $order = Order::where('id', $id)
            ->where('user_id', Auth::id()) // Proteksi: Memastikan yang komplain beneran pemilik invoice
            ->firstOrFail();

        // 2. Siapkan Payload Mutasi Status Utama & Sub-Status Sengketa
        $updateData = [
            'status' => 'dikomplain',
            'sub_status' => 'proses_banding', // Mengunci sub-status ke fase mediasi awal
            'cancel_reason' => $request->cancel_reason,
            'cancel_description' => $request->cancel_description,
        ];

        // 3. PROSES EKSEKUSI UNGGAH FOTO KE SUPABASE BUCKET `tukangin-complain`
        if ($request->hasFile('complaint_image')) {
            
            // File otomatis dilempar ke cloud Supabase Cloud melalui driver s3 disk kita
            $filePath = $request->file('complaint_image')->store('complaints', 'supabase_complain');
            
            // Simpan path unik file-nya ke kolom database orders
            $updateData['complaint_image'] = $filePath;
        }

        // 4. Jalankan Query Update Mass-Assignment ke Supabase Postgres
        $order->update($updateData);

        return redirect()->back()->with('success', 'Berkas sengketa resmi diajukan! Dana pengerjaan ditahan sementara untuk proses audit HQ Admin TUKANG.IN.');
    }
    
    public function checkout(Service $service, User $tukang)
    {
        $user = Auth::user();
        $address = $user->addresses()->where('is_primary', true)->first() ?? $user->addresses()->first();

        $serviceFee = (int) $service->price; 
        
        // Ambil tarif kunjungan teknisi secara dinamis
        $rawPriceKunjungan = $tukang->price_kunjungan;
        $technicianFee = is_string($rawPriceKunjungan) 
            ? (int) str_replace('.', '', $rawPriceKunjungan) 
            : (int) ($rawPriceKunjungan ?? 75000); 

        $taxRate = 0.02; // Pajak Platform 2%
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
        'service_id' => 'required|exists:services,id',
        'tukang_id' => 'required|exists:users,id',
        'address_id' => 'required|exists:addresses,id',
        'payment_method' => 'required|string',
        'payment_bank' => 'nullable|string',
        'promo_code' => 'nullable|string',
    ]);

    $service = Service::findOrFail($request->service_id);
    $tukang = User::findOrFail($request->tukang_id);

    // 1. Ambil Nilai Mentah Biaya Layanan & Teknisi
    $serviceFee = (int) $service->price;
    $technicianFee = is_string($tukang->price_kunjungan) 
        ? (int) str_replace('.', '', $tukang->price_kunjungan) 
        : (int) ($tukang->price_kunjungan ?? 75000);
        
    $baseAmount = $serviceFee + $technicianFee; // Subtotal (Rp 280.000)

    // 2. FIX: Ubah Pajak Platform dari 2% menjadi 5% agar presisi dengan UI Amarta
    $taxAmount = (int) round($baseAmount * 0.05); // Menghasilkan Rp 14.000

    // 3. Hitung Biaya Admin Metode Pembayaran secara Aman
    $paymentFee = 0;
    switch ($request->payment_method) {
        case 'gopay': 
            $paymentFee = (int) round($baseAmount * 0.02); // Rp 5.600
            break;
        case 'dana': 
            $paymentFee = (int) round($baseAmount * 0.015); 
            break;
        case 'qris': 
            $paymentFee = (int) round($baseAmount * 0.007); 
            break;
        case 'bank_transfer': 
            $paymentFee = 4000; 
            break;
    }

    // 4. Hitung Potongan Diskon Promo
    $promoCode = strtoupper($request->promo_code);
    $promoDiscount = 0;
    if ($promoCode === 'NEWUSERDANCE') {
        $promoDiscount = $baseAmount + $taxAmount; 
    } elseif ($promoCode === 'NEWUSERKING') {
        $promoDiscount = (int) round($baseAmount * 0.5); 
    } elseif ($promoCode === 'NEWUSERKANG') {
        $promoDiscount = (int) round($baseAmount * 0.2); // Rp 56.000
    }

    // 5. Total Pembayaran Akhir yang Sah
    $finalTotal = ($baseAmount + $taxAmount + $paymentFee) - $promoDiscount;
    if ($finalTotal < 0) {
        $finalTotal = 0;
    }

    // 6. SIMPAN SNAPSHOT HARGA SECARA LENGKAP KE SUPABASE
    $order = Order::create([
    'order_number' => 'TKG-' . strtoupper(Str::random(5)) . '-' . date('Ymd'),
    'user_id' => Auth::id(),
    'service_id' => $service->id,
    'tukang_id' => $tukang->id,
    'address_id' => $request->address_id,
    'payment_method' => $request->payment_method,
    'payment_bank' => $request->payment_bank,
    
    // MEMASUKKAN SNAPSHOT HARGA YANG DIKUNCI KE KOLOM SUPABASE YANG BENAR
    'service_fee' => $serviceFee,       // <--- Menyimpan Biaya Layanan (Rp 150.000)
    'technician_fee' => $technicianFee,  // <--- Menyimpan Biaya Teknisi (Rp 120.000)
    'tax_amount' => $taxAmount,          // <--- Pajak Platform 5% (Rp 13.500)
    'platform_fee' => $paymentFee,       // <--- UBAH KEY MENJADI 'platform_fee' (Menyimpan Admin Fee)
    
    'promo_code' => $promoCode ?: null,
    'discount_amount' => $promoDiscount,
    'total_cost' => $finalTotal,
    'status' => 'pending', 
    'schedule_date' => now()->addDays(1), 
]);

    // ALAHKAN KE HALAMAN PEMBAYARAN BARU
    return redirect()->route('orders.payment', $order->id);
}

    /**
     * Menampilkan Halaman Panduan Pembayaran (Mock/Simulasi Midtrans)
     */
    public function payment(Order $order)
    {
        // Proteksi agar user lain tidak bisa mengintip orderan orang lain
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Generate Nomor VA Bohongan berdasarkan ID Order & Bank
        $vaNumber = '';
        if ($order->payment_method === 'bank_transfer') {
            $bankCodes = ['bca' => '80777', 'mandiri' => '89022', 'bni' => '8807', 'bri' => '80201'];
            $prefix = $bankCodes[$order->payment_bank] ?? '80000';
            $vaNumber = $prefix . str_pad($order->id, 8, '0', STR_PAD_LEFT);
        }

        return view('order.payment', compact('order', 'vaNumber'));
    }

    /**
     * Memproses Simulasi Pembayaran Sukses (POST)
     */
    public function simulatePayment(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Ganti 'completed' menjadi 'selesai' agar lolos CHECK Constraint DB lo
        $order->update(['status' => 'pengerjaan']); 

        return redirect()->route('dashboard')->with('success', 'Pembayaran Berhasil! Pesanan Anda segera dikerjakan.');
    }

    /**
 * Membatalkan pesanan secara aman di database
 */
   // app/Http/Controllers/OrderController.php

/**
 * Membatalkan pesanan secara aman di database dengan pengaman 12 jam
 */
    // app/Http/Controllers/OrderController.php

/**
 * Membatalkan pesanan secara aman di database dengan alasan wajib
 */
    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // PENGAMAN 12 JAM: Cek jika statusnya sudah masuk 'pengerjaan'
        if ($order->status === 'pengerjaan') {
            if ($order->created_at->lt(now()->subHours(12))) {
                return redirect()->back()->with('error', 'Batas waktu pembatalan 12 jam untuk pesanan ini telah habis.');
            }
        } elseif ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        // Validasi input alasan & deskripsi pembatalan secara ketat
        $request->validate([
            'cancel_reason' => 'required|string|max:255',
            'cancel_description' => 'required|string|max:256',
        ]);

        // Update status menjadi 'batal' beserta detail alasan pembatalan
        $order->update([
            'status' => 'batal',
            'cancel_reason' => $request->cancel_reason,
            'cancel_description' => $request->cancel_description,
        ]);

        return redirect()->route('dashboard')->with('info', 'Pemesanan jasa berhasil dibatalkan.');
    }
}