<?php

namespace App\Http\Controllers;

use App\Models\User; // Pastikan model User atau Tukang tersedia
use Illuminate\Http\Request;

class TukangController extends Controller
{
    public function index()
    {
        // Mengambil user dengan role 'tukang' (asumsi kamu punya kolom role)
        $tukangs = User::where('role', 'tukang')->paginate(4); 
        
        return view('tukang.index', compact('tukangs'));
    }
}