<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TukangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Rute untuk Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rute untuk Register
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// HANYA ADA SATU RUTE UNTUK '/'
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/tukang', [TukangController::class, 'index'])->name('tukang.index');

Route::get('/layanan/{slug}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/layanan', [ServiceController::class, 'index'])->name('services.index');

Route::get('/tentang-kami', [HomeController::class, 'about'])->name('about');

Route::get('/pusat-bantuan', [HomeController::class, 'help'])->name('help');

Route::get('/layanan/{slug}/pilih-tukang', [TukangController::class, 'pilihTukang'])
    ->name('services.pilih-tukang');

Route::get('/tukang/{id}', [App\Http\Controllers\TukangController::class, 'show'])->name('tukang.show');

Route::get('/my-profile', [ProfileController::class, 'index'])->name('profile.index');

// routes/web.php

Route::get('/syarat-ketentuan', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/kebijakan-privasi', function () {
    return view('pages.privacy');
})->name('privacy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';