<?php

namespace App\Http\Controllers;

use App\Models\User; // Pastikan model User atau Tukang tersedia
use Illuminate\Http\Request;

class TukangController extends Controller
{
    public function index(Request $request)
{
    $query = \App\Models\User::where('role', 'tukang');

    // Fitur Search
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('specialty', 'like', '%' . $request->search . '%');
    }

    $tukangs = $query->paginate(6)->withQueryString();

    return view('tukang.index', compact('tukangs'));
}
}