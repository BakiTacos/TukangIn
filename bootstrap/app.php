<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckIfBlocked;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // ⚡ GLOBAL CHECK: Jalankan untuk semua rute web
        $middleware->web(append: [
            \App\Http\Middleware\CheckIfBlocked::class,
            \App\Http\Middleware\CheckTukangVerification::class, // Tambahkan di sini
        ]);

        $middleware->alias([
            'tukang.verified' => \App\Http\Middleware\CheckTukangVerification::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
