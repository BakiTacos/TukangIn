<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ServiceController,
    TukangController,
    DashboardController,
    ProfileController,
    OrderController,
    TukangOrderController,
    AdminController,
    ChatController,
    ReviewController,
    Auth\LoginController,
    Auth\RegisterController
};

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-kami', [HomeController::class, 'about'])->name('about');
Route::get('/pusat-bantuan', [HomeController::class, 'help'])->name('help');
Route::get('/syarat-ketentuan', fn() => view('pages.terms'))->name('terms');
Route::get('/kebijakan-privasi', fn() => view('pages.privacy'))->name('privacy');

// Layanan & Tukang
Route::get('/layanan', [ServiceController::class, 'index'])->name('services.index');
Route::get('/layanan/{slug}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/layanan/{slug}/pilih-tukang', [TukangController::class, 'pilihTukang'])->name('services.pilih-tukang');
Route::get('/tukang', [TukangController::class, 'index'])->name('tukang.index');
Route::get('/tukang/{id}', [TukangController::class, 'show'])->name('tukang.show');

// API Lokasi
Route::get('/api/cities', [TukangController::class, 'getCitiesApi'])->name('api.cities');

/*
|--------------------------------------------------------------------------
| 2. GUEST ROUTES (Hanya Diakses Jika Belum Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    // Pendaftaran Mitra (Tukang)
    Route::get('/jadi-mitra', [RegisterController::class, 'showTukangRegisterForm'])->name('register.tukang');
    Route::post('/jadi-mitra', [RegisterController::class, 'registerTukang'])->name('register.tukang.post');
});

/*
|--------------------------------------------------------------------------
| 3. AUTHENTICATED ROUTES (Umum untuk Semua Role)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard Utama (Universal)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['verified'])
        ->name('dashboard');

    // Manajemen Profil & Alamat
    Route::prefix('my-profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::prefix('my-addresses')->group(function () {
        Route::get('/', [ProfileController::class, 'address'])->name('profile.address');
        Route::post('/', [ProfileController::class, 'storeAddress'])->name('profile.address.store');
        Route::put('/{id}', [ProfileController::class, 'updateAddress'])->name('profile.address.update');
        Route::delete('/{id}', [ProfileController::class, 'destroyAddress'])->name('profile.address.destroy');
    });

    // Chat System (Terpadu)
    Route::prefix('chats')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('chats.index');
        Route::get('/{tukang}', [ChatController::class, 'index'])->name('chats.show');
        Route::post('/{tukang}', [ChatController::class, 'store'])->name('chats.store');
        Route::get('/{tukang}/messages', [ChatController::class, 'getMessages'])->name('chats.messages');
    });

    // Favorit Tukang
    Route::get('/favorites', [TukangController::class, 'favorites'])->name('tukang.favorites');
    Route::post('/tukang/{id}/favorite', [TukangController::class, 'toggleFavorite'])->name('tukang.favorite');

    /*
    |--------------------------------------------------------------------------
    | 3.A. KHUSUS ROLE: TUKANG
    |--------------------------------------------------------------------------
    */
    // Halaman Tunggu (Bisa diakses meski belum diverifikasi)
    Route::get('/pendaftaran/menunggu', function () {
        $user = auth()->user();
        // ⚡ Izinkan 'menunggu' DAN 'ditolak'
        if ($user->role !== 'tukang' || !in_array($user->status_verifikasi, ['menunggu', 'ditolak'])) {
            return redirect()->route('dashboard');
        }
        return view('auth.register-pending');
    })->name('register.pending');

    // Rute Operasional Tukang (Hanya jika sudah Verified)
    Route::middleware('tukang.verified')->group(function () {
        Route::prefix('tukang')->group(function () {
            // Dashboard Spesifik Tukang (Jika lo menggunakan controller berbeda)
            Route::get('/home', [TukangController::class, 'index'])->name('tukang.dashboard_home'); 
            
            // Pengaturan Status & Profil Tukang
            Route::post('/toggle-availability', [DashboardController::class, 'toggleAvailability'])->name('tukang.toggle-availability');
            Route::put('/schedule', [DashboardController::class, 'updateSchedule'])->name('tukang.schedule.update');
            Route::put('/update-location', [DashboardController::class, 'updateLocation'])->name('tukang.profile.update-location');

            // Manajemen Order (Sisi Tukang)
            Route::post('/orders/{id}/accept', [TukangOrderController::class, 'accept'])->name('tukang.orders.accept');
            Route::post('/orders/{id}/complete', [TukangOrderController::class, 'complete'])->name('tukang.orders.complete');
            Route::post('/orders/{id}/cancel', [TukangOrderController::class, 'cancel'])->name('tukang.orders.cancel');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | 3.B. KHUSUS ROLE: USER (Pembeli)
    |--------------------------------------------------------------------------
    */
    Route::middleware('verified')->group(function () {
        // Alur Checkout
        Route::get('/checkout/{service}', [OrderController::class, 'checkout'])->name('checkout_simple');
        Route::get('/checkout/{service}/{tukang}', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/checkout', [OrderController::class, 'store'])->name('orders.store');

        // Manajemen Order & Pembayaran
        Route::prefix('order')->group(function () {
            Route::get('/{order}', [OrderController::class, 'show'])->name('orders.show');
            Route::get('/success/{order}', [OrderController::class, 'success'])->name('orders.success');
            Route::get('/{order}/payment', [OrderController::class, 'payment'])->name('orders.payment');
            Route::post('/{order}/simulate-payment', [OrderController::class, 'simulatePayment'])->name('orders.simulate_payment');
            Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
            Route::post('/{order}/complain', [OrderController::class, 'complain'])->name('orders.complain');
            
            // Reviews
            Route::get('/{order}/review', [ReviewController::class, 'create'])->name('reviews.create');
            Route::post('/{order}/review', [ReviewController::class, 'store'])->name('reviews.store');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | 3.C. KHUSUS ROLE: ADMIN
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Manajemen User & Blokir
        Route::get('/users', [AdminController::class, 'manageUsers'])->name('users');
        Route::post('/users/{id}/block', [AdminController::class, 'blockUser'])->name('users.block');
        Route::post('/users/{id}/unblock', [AdminController::class, 'unblockUser'])->name('users.unblock');
        
        // Manajemen Sengketa & Transaksi
        Route::get('/orders', [AdminController::class, 'manageOrders'])->name('orders.index');
        Route::get('/orders/{id}/review', [AdminController::class, 'reviewOrder'])->name('orders.review');
        Route::post('/orders/{id}/resolve', [AdminController::class, 'resolveOrder'])->name('orders.resolve');

        Route::get('/verifications', [AdminController::class, 'verificationQueue'])->name('verifications');
        Route::post('/verifications/{id}/approve', [AdminController::class, 'approveMitra'])->name('verifications.approve');
        Route::post('/verifications/{id}/reject', [AdminController::class, 'rejectMitra'])->name('verifications.reject');
    });
});

/*
|--------------------------------------------------------------------------
| 4. BREEZE / AUTH SYSTEM
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';