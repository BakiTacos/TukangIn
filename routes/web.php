<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;

use App\Http\Controllers\TukangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TukangOrderController;

// Rute untuk Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rute untuk Register
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/api/cities', [TukangController::class, 'getCitiesApi'])->name('api.cities');

// Rute Sementara untuk Login Instan sebagai Dian (Hapus jika sudah masuk tahap production!)

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
    Route::get('/my-addresses', [App\Http\Controllers\ProfileController::class, 'address'])
        ->name('profile.address');

    // Pintu POST: Untuk proses simpan data dari form modal
    Route::post('/my-addresses', [App\Http\Controllers\ProfileController::class, 'storeAddress'])
        ->name('profile.address.store');

    Route::put('/my-addresses/{id}', [ProfileController::class, 'updateAddress'])->name('profile.address.update');
    Route::delete('/my-addresses/{id}', [ProfileController::class, 'destroyAddress'])->name('profile.address.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    // Jalur Checkout: /checkout/{service_id}/{tukang_id}
    Route::get('/checkout/{service}/{tukang}', [OrderController::class, 'checkout'])->name('checkout');
    
    // Jalur Simpan Pesanan
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // routes/web.php (Di dalam Route::middleware(['auth'])->group(...))

// 1. Rute Inbox Utama (Membuka halaman chat terpadu kosong)
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');

    // 2. Rute Buka Chat Spesifik (Membuka halaman chat terpadu langsung dengan chat teknisi terbuka)
    // Kita arahkan ke method 'index' agar tetap menggunakan satu halaman split-pane terpadu
    Route::get('/chats/{tukang}', [ChatController::class, 'index'])->name('chats.show');

    // 3. Rute Aksi Kirim Pesan & Polling Real-time
    Route::post('/chats/{tukang}', [ChatController::class, 'store'])->name('chats.store');
    Route::get('/chats/{tukang}/messages', [ChatController::class, 'getMessages'])->name('chats.messages');

    Route::post('/tukang/orders/{id}/accept', [TukangOrderController::class, 'accept'])->name('tukang.orders.accept');
    Route::post('/tukang/orders/{id}/complete', [TukangOrderController::class, 'complete'])->name('tukang.orders.complete');
    Route::post('/tukang/orders/{id}/cancel', [TukangOrderController::class, 'cancel'])->name('tukang.orders.cancel');

    Route::post('/tukang/toggle-availability', [DashboardController::class, 'toggleAvailability'])
         ->name('tukang.toggle-availability');

    Route::put('/tukang/schedule', [DashboardController::class, 'updateSchedule'])->name('tukang.schedule.update');

    Route::put('/dashboard/update-location', [DashboardController::class, 'updateLocation'])
         ->name('tukang.profile.update-location');
    
    Route::post('/tukang/{id}/favorite', [App\Http\Controllers\TukangController::class, 'toggleFavorite'])
         ->name('tukang.favorite');
});


Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. Menampilkan Halaman Checkout
    // Kita kirim ID Layanan (service) agar ringkasan harga bisa dinamis
    Route::get('/checkout/{service}', [OrderController::class, 'checkout'])
        ->name('checkout');

    // 2. Memproses Booking (Simpan ke Tabel Orders)
    Route::post('/checkout', [OrderController::class, 'store'])
        ->name('orders.store');

    // 3. Halaman Konfirmasi Sukses (Opsional tapi Bagus buat UX)
    Route::get('/order/success/{order}', [OrderController::class, 'success'])
        ->name('orders.success');

    Route::get('/order/{order}/payment', [OrderController::class, 'payment'])->name('orders.payment');
    Route::post('/order/{order}/simulate-payment', [OrderController::class, 'simulatePayment'])->name('orders.simulate_payment');

    Route::get('/checkout/{service}/{tukang}', [OrderController::class, 'checkout'])->name('checkout');

    // Rute untuk membatalkan pesanan (Mengubah status menjadi 'cancelled')
    Route::post('/order/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/order/{order}/complain', [OrderController::class, 'complain'])->name('orders.complain');

    // routes/web.php (Di dalam Route::middleware(['auth'])->group(...))
    Route::get('/orders/{order}/review', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/orders/{order}/review', [ReviewController::class, 'store'])->name('reviews.store');
});

require __DIR__.'/auth.php';