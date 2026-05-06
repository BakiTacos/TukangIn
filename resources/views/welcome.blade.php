<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tukang.in - Solusi Tukang Terpercaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-bg {
            background: linear-gradient(rgba(15, 45, 80, 0.7), rgba(15, 45, 80, 0.7)), 
                        url("{{ asset('images/banner-hero.jpg') }}");
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">

    <x-navbar />

    <section class="hero-bg text-white py-24 md:py-32">
        <div class="container mx-auto px-6">
            <p class="text-orange-400 font-bold tracking-widest text-xs mb-4 uppercase">Pilihan No. 1 di Indonesia</p>
            <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-6 max-w-2xl">
                Solusi Tukang Terpercaya untuk Rumah Anda
            </h1>
            <p class="text-gray-200 text-lg mb-10 max-w-xl leading-relaxed">
                Temukan teknisi profesional untuk segala kebutuhan perbaikan rumah Anda dengan jaminan hasil terbaik.
            </p>
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <button class="bg-[#e67e22] hover:bg-[#d35400] text-white px-8 py-3 rounded font-bold transition shadow-lg">Booking Sekarang</button>
                <a href="/layanan"><button class="bg-white/20 hover:bg-white/30 backdrop-blur-md text-white px-8 py-3 rounded font-bold border border-white/50 transition">Lihat Layanan</button></a>
            </div>
        </div>
    </section>

    <section class="py-16 container mx-auto px-6 text-center">
        <div class="flex justify-between items-end mb-10">
            <div class="text-left">
                <h2 class="text-2xl font-bold text-gray-800">Services</h2>
                <p class="text-gray-500">Apa yang kamu butuhkan hari ini?</p>
            </div>
            <a href="/layanan" class="text-[#0f2d50] font-bold text-sm hover:underline">View All <i class="fas fa-chevron-right ml-1"></i></a>
        </div>
        <div class="grid grid-cols-4 md:grid-cols-8 gap-6">
            @php
                $services = [
                    ['icon' => 'fa-faucet', 'name' => 'Kebocoran', 'color' => 'bg-blue-100 text-blue-600'],
                    ['icon' => 'fa-bolt', 'name' => 'Listrik', 'color' => 'bg-yellow-100 text-yellow-600'],
                    ['icon' => 'fa-hammer', 'name' => 'Konstruksi', 'color' => 'bg-red-100 text-red-600'],
                    ['icon' => 'fa-paint-roller', 'name' => 'Cat', 'color' => 'bg-purple-100 text-purple-600'],
                    ['icon' => 'fa-snowflake', 'name' => 'Perawatan AC', 'color' => 'bg-cyan-100 text-cyan-600'],
                    ['icon' => 'fa-toolbox', 'name' => 'Sewa Alat', 'color' => 'bg-green-100 text-green-600'],
                    ['icon' => 'fa-toilet', 'name' => 'Toilet', 'color' => 'bg-pink-100 text-pink-600'],
                    ['icon' => 'fa-th-large', 'name' => 'More', 'color' => 'bg-gray-100 text-gray-600'],
                ];
            @endphp
            @foreach($services as $s)
            <div class="flex flex-col items-center group cursor-pointer">
                <div class="{{ $s['color'] }} w-14 h-14 rounded-2xl flex items-center justify-center mb-3 transition-transform group-hover:-translate-y-2">
                    <i class="fas {{ $s['icon'] }} text-xl"></i>
                </div>
                <span class="text-xs font-bold text-gray-700">{{ $s['name'] }}</span>
            </div>
            @endforeach
        </div>
    </section>

    <section class="py-12 container mx-auto px-6">
        <h2 class="text-xl font-bold mb-8 flex items-center">Special Offers <span class="ml-2 bg-red-500 text-white text-[10px] px-2 py-0.5 rounded italic">NEW</span></h2>
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-gradient-to-r from-blue-900 to-blue-700 rounded-3xl p-8 relative overflow-hidden text-white group">
                <div class="relative z-10">
                    <h3 class="text-3xl font-bold mb-2">50% OFF</h3>
                    <p class="text-sm opacity-80 mb-6">Your first AC cleaning service<br>this month!</p>
                    <button class="bg-[#e67e22] px-6 py-2 rounded-lg text-sm font-bold">Claim Now</button>
                </div>
                <img src="https://illustrations.popsy.co/white/home-repair.svg" class="absolute right-0 bottom-0 w-48 opacity-20 group-hover:scale-110 transition">
            </div>
            <div class="bg-gradient-to-r from-gray-800 to-black rounded-3xl p-8 relative overflow-hidden text-white group">
                <div class="relative z-10">
                    <h3 class="text-3xl font-bold mb-2">BUNDLE</h3>
                    <p class="text-sm opacity-80 mb-6">Save up to 30% on home<br>maintenance bundles</p>
                    <button class="bg-white text-gray-900 px-6 py-2 rounded-lg text-sm font-bold">See Deals</button>
                </div>
                <img src="https://illustrations.popsy.co/white/construction-worker.svg" class="absolute right-0 bottom-0 w-48 opacity-20 group-hover:scale-110 transition">
            </div>
        </div>
    </section>

    <section class="py-16 container mx-auto px-6">
        <div class="flex justify-between items-end mb-10">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Top Rated Partners</h2>
                <p class="text-gray-500">Tukang pilihan dengan rating tertinggi di kota Anda</p>
            </div>
            <a href="/tukang" class="text-[#0f2d50] font-bold text-sm hover:underline">View All Partners</a>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach($tukangs as $t)
    <div class="bg-white p-4 rounded-[1.5rem] shadow-sm border border-gray-100 flex items-center space-x-4 hover:shadow-md transition">
        <!-- Foto Profil dari Supabase Storage atau Placeholder -->
        <img src="{{ $t->avatar ? asset('storage/' . $t->avatar) : 'https://i.pravatar.cc/150?u=' . $t->id }}" 
             class="w-16 h-16 rounded-2xl object-cover border border-gray-50">
        
        <div class="flex-1">
            <div class="flex justify-between items-start">
                <h4 class="font-bold text-[#0f2d50] text-sm">{{ $t->name }}</h4>
                <span class="text-xs font-bold text-yellow-500">
                    <i class="fas fa-star text-[10px]"></i> {{ number_format($t->rating, 1) }}
                </span>
            </div>
            
            <!-- Mengambil kolom specialty yang sudah kita buat di migrasi -->
            <p class="text-[10px] text-gray-500 mb-2 font-medium">
                {{ $t->specialty ?? 'Teknisi Terverifikasi' }}
            </p>
            
            <div class="flex space-x-2">
                <span class="text-[8px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-lg font-bold uppercase tracking-wider">Verified Partner</span>
            </div>
        </div>
    </div>
    @endforeach
        </div>
    </section>

    <x-footer />


</body>
</html>