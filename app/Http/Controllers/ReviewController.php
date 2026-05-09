<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Tampilkan Halaman Formulir Ulasan
     */
    public function create(Order $order)
    {
        // Proteksi kepemilikan order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Hanya pesanan berstatus selesai yang boleh diulas
        if ($order->status !== 'selesai') {
            return redirect()->route('dashboard')->with('error', 'Pesanan belum selesai.');
        }

        // Cek jika user sudah pernah memberi ulasan
        if ($order->review) {
            return redirect()->route('orders.show', $order->id)->with('info', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        $order->load(['service', 'tukang']);
        return view('order.review', compact('order'));
    }

    /**
     * Simpan Ulasan ke Database
     */
    // app/Http/Controllers/ReviewController.php

    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'selesai' || $order->review) {
            return redirect()->route('orders.show', $order->id)->with('error', 'Aksi tidak valid.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500', // Wajib diisi sesuai schema "text" lo
        ]);

        // Simpan data ulasan baru menyesuaikan kolom di database lo
        Review::create([
            'order_id' => $order->id,
            'tukang_id' => $order->tukang_id,
            'user_name' => Auth::user()->name, // Auto-ambil nama user yang login
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('orders.show', $order->id)->with('success', 'Terima kasih! Ulasan Anda telah berhasil disimpan.');
    }
}