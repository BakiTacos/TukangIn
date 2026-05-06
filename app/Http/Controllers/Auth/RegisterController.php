<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    // app/Http/Controllers/Auth/RegisterController.php
public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'login' => 'required|string|unique:users,email', // Asumsi kolom email
        'password' => 'required|string|min:8',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->login,
        'password' => Hash::make($request->password),
        'role' => 'user', // Default role untuk pendaftar baru
    ]);

    auth()->login($user);

    return redirect('/');
}
}
