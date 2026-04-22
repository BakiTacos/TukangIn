<nav class="bg-[#0f2d50] text-white py-4 shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-6 flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <a href="/">
                <img src="{{ asset('images/logo.png') }}" class="h-10 w-auto object-contain" alt="Logo Tukang.in">
            </a>
        </div>

        <div class="hidden md:flex space-x-8 text-sm font-medium">
            <a href="/" class="hover:text-orange-400 transition">Beranda</a>
            <a href="#" class="hover:text-orange-400 transition">Layanan</a>
            <a href="#" class="hover:text-orange-400 transition">Tentang Kami</a>
            <a href="#" class="hover:text-orange-400 transition">Pusat Bantuan</a>
        </div>

        <div class="flex items-center space-x-4">
            @if (Route::has('login'))
                @auth
                    {{-- Tampilan jika User sudah Login --}}
                    <a href="{{ url('/dashboard') }}" class="text-sm font-bold hover:text-orange-400 transition">Dashboard</a>
                @else
                    {{-- Tampilan jika User belum Login --}}
                    <a href="{{ route('login') }}" class="text-sm font-bold hover:text-orange-400 transition">Masuk</a>
                    
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-[#e67e22] hover:bg-[#d35400] px-6 py-2 rounded text-sm font-bold transition shadow-md">
                            Daftar
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</nav>