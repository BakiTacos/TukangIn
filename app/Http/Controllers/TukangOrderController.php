<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TukangOrderController extends Controller
{
    // 1. Aksi Terima Pekerjaan (Pending -> Pengerjaan)
    public function accept($id)
    {
        // Cari order secara manual berdasarkan ID murni (Antigagal)
        $order = Order::findOrFail($id);

        // Keamanan: Pastikan order ini memang ditugaskan untuk tukang yang sedang login
        if ((int)$order->tukang_id !== (int)Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak berwenang mengambil pekerjaan ini.');
        }

        $order->status = 'pengerjaan';
        $order->save();

        return redirect()->back()->with('success', "Order {$order->order_number} berhasil diambil! Segera hubungi pelanggan.");
    }

    // 2. Aksi Selesaikan Pekerjaan (Pengerjaan -> Selesai)
    // app/Http/Controllers/TukangOrderController.php

public function complete(Request $request, $id)
    {
        // 1. Validasi super ketat: File wajib ada, tipe gambar, maks 2MB (2048 KB)
        $request->validate([
            'completion_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // 2. Ambil data order dari Supabase
        $order = Order::findOrFail($id);

        // 3. Proteksi Keamanan: Pastikan hanya Tukang yang ditugaskan yang bisa menyelesaikan
        if ((int)$order->tukang_id !== (int)Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk menyelesaikan order ini.');
        }

        // 4. Proses Upload ke Supabase Storage via disk 's3-completion'
        if ($request->hasFile('completion_photo')) {
            
            // Hapus foto lama di Supabase jika ada (menghindari tumpukan sampah storage)
            if ($order->completion_photo) {
                Storage::disk('s3-completion')->delete($order->completion_photo);
            }

            // Simpan foto baru ke folder 'completion_photos' di dalam bucket 'tukangin-completion'
            $path = $request->file('completion_photo')->store('completion_photos', 's3-completion');
            
            // Simpan path relatif ke database
            $order->completion_photo = $path;
        }

        // 5. Ubah status order menjadi 'selesai' dan simpan perubahan
        $order->status = 'selesai';
        $order->save();

        return redirect()->back()->with('success', "Kerja bagus! Order #{$order->order_number} berhasil diselesaikan dan foto bukti telah diunggah.");
    }

    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancel_reason' => 'required|string',
            'cancel_description' => 'required|string',
        ]);

        $order = Order::findOrFail($id);

        if ((int)$order->tukang_id !== (int)Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses.');
        }

        $order->status = 'batal';
        $order->cancel_reason = $request->cancel_reason;
        $order->cancel_description = $request->cancel_description . ' (Dibatalkan oleh Teknisi)';
        $order->save();

        return redirect()->route('dashboard')->with('success', "Order #{$order->order_number} berhasil ditolak/dibatalkan.");
    }
}