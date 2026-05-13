<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIfBlocked
{
    public function handle(Request $request, Closure $next): Response
    {
        // ⚡ CEK APAKAH USER AUTHENTICATED & STATUSNYA TERBLOKIR DI SUPABASE
        if (Auth::check() && Auth::user()->is_blocked) {
            
            // Ambil alasan penangguhan dari database
            $reason = Auth::user()->blocked_reason ?? 'Melanggar ketentuan standard komunitas platform TUKANG.IN.';
            
            // Eksekusi Kick / Auto-Logout Paksa
            Auth::logout();
            
            // Hancurkan Sesi Komputer & Regenerate Token CSRF biar gak disalahgunakan
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Alihkan setir ke halaman login bawa pesan flash data sengketa akun
            return redirect()->route('login')->with('account_blocked', $reason);
        }

        return $next($request);
    }
}