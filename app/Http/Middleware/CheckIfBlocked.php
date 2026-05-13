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
        if (Auth::check() && Auth::user()->is_blocked) {
            
            $reason = Auth::user()->blocked_reason ?? 'Melanggar ketentuan standard komunitas platform TUKANG.IN.';
            
            // 1. Eksekusi Pencabutan Hak Sesi Akun
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // 2. AMANKAN KONDISI AJAX / LIVEWIRE REQUEST (Biar gak bocor masuk ke komponen aktif)
            if ($request->ajax() || $request->wantsJson() || $request->hasHeader('X-Livewire')) {
                return response()->json(['redirect' => route('login')], 401)
                    ->header('X-Livewire-Redirect', route('login'))
                    ->header('X-Redirect', route('login'));
            }

            // 3. AMANKAN GET REQUEST (Paksa matikan cache browser agar tidak mengedipkan halaman admin)
            $response = redirect()->route('login')->with('account_blocked', $reason);
            
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');

            return $response;
        }

        return $next($request);
    }
}