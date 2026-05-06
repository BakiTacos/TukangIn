<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil user dengan role 'tukang' secara acak dari Supabase
        // limit(3) agar hanya 3 yang tampil di homepage
        $tukangs = User::where('role', 'tukang')
                       ->inRandomOrder()
                       ->limit(3)
                       ->get();

        return view('welcome', compact('tukangs'));
    }

    public function about()
{
    return view('about');
}

public function help()
{
    return view('help');
}
}