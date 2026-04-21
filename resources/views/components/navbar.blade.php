<nav class="bg-[#0f2d50] text-white py-4 shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-6 flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('images/logo.png') }}" class="h-10 w-auto object-contain" alt="Logo">
        </div>
        <div class="hidden md:flex space-x-8 text-sm font-medium">
            <a href="/" class="hover:text-orange-400">Beranda</a>
            <a href="#" class="hover:text-orange-400">Layanan</a>
            <a href="#" class="hover:text-orange-400">Tentang Kami</a>
            <a href="#" class="hover:text-orange-400">Pusat Bantuan</a>
        </div>
        <a href="{{ route('login') }}" class="bg-[#e67e22] hover:bg-[#d35400] px-6 py-2 rounded text-sm font-bold transition">Masuk</a>
    </div>
</nav>