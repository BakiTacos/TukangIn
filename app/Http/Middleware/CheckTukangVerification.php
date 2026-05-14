<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTukangVerification
{
    // 📂 app/Http/Middleware/CheckTukangVerification.php

public function handle(Request $request, Closure $next)
{
    if (auth()->check() && auth()->user()->role === 'tukang') {
        
        // ⚡ Cegat jika 'menunggu' ATAU 'ditolak'
        if (in_array(auth()->user()->status_verifikasi, ['menunggu', 'ditolak']) && 
            !$request->routeIs('register.pending') && 
            !$request->routeIs('logout')) {
            
            return redirect()->route('register.pending');
        }
    }

    return $next($request);
}
}