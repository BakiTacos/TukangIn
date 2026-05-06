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
    public function index(Request $request): View
    {
        // 1. Inisialisasi Query dari Model Service
        $query = Service::query();

        // 2. Logika Pencarian (Search)
        // Mencari berdasarkan judul atau deskripsi layanan
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // 3. Logika Filter Kategori
        // Jika kategori dipilih dan bukan 'Semua', lakukan filter
        if ($request->filled('category') && $request->category !== 'Semua') {
            $query->where('category', $request->category);
        }

        // 4. Eksekusi Pagination
        // Menampilkan 8 item per halaman dan mempertahankan parameter URL (search/category)
        $services = $query->latest()->paginate(8)->withQueryString();

        // 5. Kirim data ke View 'layanan.blade.php'
        return view('layanan', compact('services'));
    }
}