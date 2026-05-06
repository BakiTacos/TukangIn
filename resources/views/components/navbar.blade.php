<nav class="bg-[#0f2d50] text-white py-4 shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-6 flex justify-between items-center">
        
        <!-- Bagian Kiri: Logo -->
        <div class="flex items-center space-x-2">
            <a href="/">
                {{-- Menggunakan logo dari folder public/images/logo.png sesuai kode kamu --}}
                <img src="{{ asset('images/logo.png') }}" class="h-10 w-auto object-contain" alt="Logo Tukang.in">
            </a>
        </div>

        <!-- Bagian Tengah: Menu Navigasi (Desktop) -->
        <div class="hidden md:flex space-x-8 text-sm font-medium">
            <a href="/" class="hover:text-orange-400 transition {{ request()->is('/') ? 'text-orange-400 font-bold' : '' }}">Beranda</a>
            <a href="/layanan" class="hover:text-orange-400 transition {{ request()->is('layanan*') ? 'text-orange-400 font-bold' : '' }}">Layanan</a>
            <a href="/tentang-kami" class="hover:text-orange-400 transition">Tentang Kami</a>
            <a href="/pusat-bantuan" class="hover:text-orange-400 transition">Pusat Bantuan</a>
        </div>

        <!-- Bagian Kanan: Autentikasi -->
        <div class="flex items-center space-x-4">
            @auth
                {{-- 1. TAMPILAN JIKA USER SUDAH LOGIN (DROPDOWN PROFIL) --}}
                <div class="relative group">
                    <button class="flex items-center space-x-3 bg-white/5 hover:bg-white/10 p-1.5 pr-4 rounded-full transition border border-white/10 group">
                        <!-- Avatar Dinamis Menggunakan UI Avatars -->
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=e67e22&color=fff&bold=true" 
                             alt="{{ Auth::user()->name }}" 
                             class="w-8 h-8 rounded-full border border-white/20">
                        
                        <div class="text-left hidden md:block">
                            <p class="text-[9px] text-gray-400 font-bold leading-none uppercase tracking-tighter">Halo, Pengguna</p>
                            <p class="text-xs text-white font-extrabold truncate max-w-[100px]">{{ Auth::user()->name }}</p>
                        </div>
                        
                        <i class="fas fa-chevron-down text-[10px] text-gray-400 group-hover:text-white transition"></i>
                    </button>

                    <!-- Dropdown Menu (Muncul saat Hover) -->
                    <div class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 hidden group-hover:block animate-fade-in-down z-50">
                        <div class="px-4 py-3 border-b border-gray-50 mb-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase leading-none mb-1">Status Akun</p>
                            <span class="text-[11px] font-extrabold text-[#0f2d50] uppercase">{{ Auth::user()->role ?? 'Pelanggan' }}</span>
                        </div>
                        
                        <a href="{{ url('/dashboard') }}" class="flex items-center px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-[#e67e22] transition">
                            <i class="fas fa-th-large mr-3 w-4"></i> Dashboard
                        </a>
                        
                        <a href="/my-profile" class="flex items-center px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-[#e67e22] transition">
                            <i class="far fa-user-circle mr-3 w-4"></i> Profil Saya
                        </a>

                        <a href="#" class="flex items-center px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-[#e67e22] transition">
                            <i class="fas fa-history mr-3 w-4"></i> Pesanan
                        </a>

                        <div class="border-t border-gray-50 my-1"></div>

                        <!-- Tombol Keluar (Method POST) -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center px-4 py-3 text-xs font-bold text-red-500 hover:bg-red-50 transition">
                                <i class="fas fa-sign-out-alt mr-3 w-4"></i> Keluar Aplikasi
                            </button>
                        </form>
                    </div>
                </div>
            @else
                {{-- 2. TAMPILAN JIKA USER BELUM LOGIN --}}
                <a href="{{ route('login') }}" class="text-sm font-bold hover:text-orange-400 transition">Masuk</a>
                
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="bg-[#e67e22] hover:bg-[#d35400] px-6 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-orange-500/20 transform hover:-translate-y-0.5">
                        Daftar
                    </a>
                @endif
            @endauth
        </div>
    </div>
</nav>

{{-- Animasi Sederhana untuk Dropdown --}}
<style>
    @keyframes fade-in-down {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down { animation: fade-in-down 0.2s ease-out; }
</style>