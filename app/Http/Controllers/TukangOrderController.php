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
    public function complete($id)
    {
        $order = Order::findOrFail($id);

        if ((int)$order->tukang_id !== (int)Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak berwenang menyelesaikan pekerjaan ini.');
        }

        $order->status = 'selesai';
        $order->save();

        return redirect()->back()->with('success', "Kerja bagus! Order {$order->order_number} telah dinyatakan selesai.");
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

        $order->update([
            'status' => 'batal',
            'cancel_reason' => $request->cancel_reason,
            'cancel_description' => $request->cancel_description . ' (Dibatalkan oleh Teknisi)',
        ]);

        return redirect()->route('dashboard')->with('success', "Order #{$order->order_number} berhasil ditolak/dibatalkan.");
    }
}