<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Menampilkan daftar layanan dengan fitur search, filter kategori, dan pagination.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Service::query();

        // 1. Logika Pencarian
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // 2. Logika Sortir Harga
        if ($request->filled('sort')) {
            // Kita hilangkan titik dulu di database supaya bisa disortir sebagai angka murni
            $orderRaw = "CAST(REPLACE(price, '.', '') AS INTEGER)";
            
            if ($request->sort === 'murah') {
                $query->orderByRaw("$orderRaw ASC");
            } elseif ($request->sort === 'mahal') {
                $query->orderByRaw("$orderRaw DESC");
            }
        } else {
            $query->latest();
        }

        $services = $query->paginate(8)->withQueryString();

        return view('layanan', compact('services'));
    }

    public function show($slug)
    {
        // Cari layanan berdasarkan slug, jika tidak ada tampilkan 404
        $service = Service::where('slug', $slug)->firstOrFail();

        // Kirim data ke view detail
        return view('services.show', compact('service'));
    }
}