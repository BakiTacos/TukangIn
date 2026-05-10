<nav class="bg-[#0f2d50] text-white py-4 shadow-lg sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
    <div class="container mx-auto px-6 flex justify-between items-center">
        
        <div class="flex items-center space-x-2">
            <a href="/">
                <img src="{{ asset('images/logo.png') }}" class="h-10 w-auto object-contain" alt="Logo Tukang.in">
            </a>
        </div>

        <div class="hidden md:flex space-x-8 text-sm font-medium">
            <a href="/" class="hover:text-orange-400 transition {{ request()->is('/') ? 'text-orange-400 font-bold' : '' }}">Beranda</a>
            <a href="/layanan" class="hover:text-orange-400 transition {{ request()->is('layanan*') ? 'text-orange-400 font-bold' : '' }}">Layanan</a>
            <a href="/tentang-kami" class="hover:text-orange-400 transition {{ request()->is('tentang-kami') ? 'text-orange-400 font-bold' : '' }}">Tentang Kami</a>
            <a href="/pusat-bantuan" class="hover:text-orange-400 transition {{ request()->is('pusat-bantuan') ? 'text-orange-400 font-bold' : '' }}">Pusat Bantuan</a>
        </div>

        <div class="flex items-center space-x-4">
            
            <div class="hidden md:block">
                @auth
                    <div class="relative group">
                        <button class="flex items-center space-x-3 bg-white/5 hover:bg-white/10 p-1.5 pr-4 rounded-full transition border border-white/10">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=e67e22&color=fff&bold=true" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="w-8 h-8 rounded-full border border-white/20">
                            
                            <div class="text-left">
                                <p class="text-[9px] text-gray-400 font-bold leading-none uppercase tracking-tighter">Halo, Pengguna</p>
                                <p class="text-xs text-white font-extrabold truncate max-w-[100px]">{{ Auth::user()->name }}</p>
                            </div>
                            
                            <i class="fas fa-chevron-down text-[10px] text-gray-400 group-hover:text-white transition"></i>
                        </button>

                        <div class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 hidden group-hover:block animate-fade-in-down z-50 text-gray-750">
                            <div class="px-4 py-3 border-b border-gray-50 mb-1 text-left">
                                <p class="text-[10px] font-bold text-gray-400 uppercase leading-none mb-1">Status Akun</p>
                                <span class="text-[11px] font-extrabold text-[#0f2d50] uppercase">
                                    {{ Auth::user()->role === 'tukang' ? 'Mitra Teknisi' : 'Pelanggan Reguler' }}
                                </span>
                            </div>
                            
                            @if(Auth::user()->role === 'tukang')
                                <a href="/dashboard" class="flex items-center px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-[#e67e22] transition">
                                    <i class="fas fa-briefcase mr-3 w-4"></i> Dashboard Kerja
                                </a>

                                <a href="{{ route('chats.index') }}" class="flex items-center px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-[#e67e22] transition">
                                    <i class="fas fa-comments mr-3 w-4"></i> Chat Pelanggan
                                </a>
                            
                            @else
                                <a href="/my-profile" class="flex items-center px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-[#e67e22] transition">
                                    <i class="far fa-user-circle mr-3 w-4"></i> Profil Saya
                                </a>

                                <a href="/dashboard" class="flex items-center px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-[#e67e22] transition">
                                    <i class="fas fa-history mr-3 w-4"></i> Pesanan
                                </a>

                                <a href="{{ route('chats.index') }}" class="flex items-center px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-[#e67e22] transition">
                                    <i class="fas fa-comments mr-3 w-4"></i> Chat
                                </a>
                            @endif

                            <div class="border-t border-gray-50 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center px-4 py-3 text-xs font-bold text-red-500 hover:bg-red-50 transition">
                                    <i class="fas fa-sign-out-alt mr-3 w-4"></i> Keluar Aplikasi
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-sm font-bold hover:text-orange-400 transition">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-[#e67e22] hover:bg-[#d35400] px-6 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-orange-500/20 transform hover:-translate-y-0.5">
                                Daftar
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

            <button @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="md:hidden text-gray-300 hover:text-white transition p-2 focus:outline-none"
                    aria-label="Toggle Menu">
                <svg class="w-6 h-6 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     :class="mobileMenuOpen ? 'rotate-90 text-orange-400' : ''">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
                    <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" x-cloak></path>
                </svg>
            </button>

        </div>
    </div>

    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-4"
         class="md:hidden bg-[#0a223e] border-t border-white/5 px-6 py-6 space-y-6"
         x-cloak>
        
        <div class="flex flex-col space-y-4 font-bold text-sm">
            <a href="/" class="transition py-1 {{ request()->is('/') ? 'text-orange-400' : 'text-gray-300 hover:text-orange-400' }}">Beranda</a>
            <a href="/layanan" class="transition py-1 {{ request()->is('layanan*') ? 'text-orange-400' : 'text-gray-300 hover:text-orange-400' }}">Layanan</a>
            <a href="/tentang-kami" class="transition py-1 {{ request()->is('tentang-kami') ? 'text-orange-400' : 'text-gray-300 hover:text-orange-400' }}">Tentang Kami</a>
            <a href="/pusat-bantuan" class="transition py-1 {{ request()->is('pusat-bantuan') ? 'text-orange-400' : 'text-gray-300 hover:text-orange-400' }}">Pusat Bantuan</a>
        </div>

        <div class="border-t border-white/10 my-4"></div>

        @auth
            <div class="space-y-4">
                <div class="flex items-center space-x-3 bg-white/5 p-3.5 rounded-2xl border border-white/5">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=e67e22&color=fff&bold=true" 
                         alt="{{ Auth::user()->name }}" 
                         class="w-10 h-10 rounded-full border border-white/20">
                    <div>
                        <p class="text-[9px] text-gray-400 font-bold leading-none uppercase tracking-wider">Status Akun</p>
                        <p class="text-xs text-white font-extrabold mt-1">
                            {{ Auth::user()->role === 'tukang' ? 'Mitra Teknisi' : 'Pelanggan Reguler' }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-col space-y-3 pl-2">
                    @if(Auth::user()->role === 'tukang')
                        <a href="/dashboard" class="flex items-center text-xs font-bold text-gray-300 hover:text-orange-400 transition py-1">
                            <i class="fas fa-briefcase mr-3.5 w-4 text-center"></i> Dashboard Kerja
                        </a>

                        <a href="{{ route('chats.index') }}" class="flex items-center text-xs font-bold text-gray-300 hover:text-orange-400 transition py-1">
                            <i class="fas fa-comments mr-3.5 w-4 text-center"></i> Chat Pelanggan
                        </a>
                    
                    @else
                        <a href="/my-profile" class="flex items-center text-xs font-bold text-gray-300 hover:text-orange-400 transition py-1">
                            <i class="far fa-user-circle mr-3.5 w-4 text-center"></i> Profil Saya
                        </a>

                        <a href="/dashboard" class="flex items-center text-xs font-bold text-gray-300 hover:text-orange-400 transition py-1">
                            <i class="fas fa-history mr-3.5 w-4 text-center"></i> Pesanan
                        </a>

                        <a href="{{ route('chats.index') }}" class="flex items-center text-xs font-bold text-gray-300 hover:text-orange-400 transition py-1">
                            <i class="fas fa-comments mr-3.5 w-4 text-center"></i> Chat
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="pt-2">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center text-xs font-bold text-red-400 hover:text-red-500 transition py-1">
                            <i class="fas fa-sign-out-alt mr-3.5 w-4 text-center"></i> Keluar Aplikasi
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="flex flex-col space-y-3 pt-2">
                <a href="{{ route('login') }}" class="w-full text-center py-3.5 border border-white/10 rounded-xl text-sm font-bold hover:bg-white/5 transition">
                    Masuk
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="w-full text-center bg-[#e67e22] hover:bg-[#d35400] py-3.5 rounded-xl text-sm font-bold transition shadow-lg shadow-orange-500/20">
                        Daftar Akun
                    </a>
                @endif
            </div>
        @endauth

    </div>
</nav>

{{-- Animasi Sederhana Dropdown Desktop --}}
<style>
    @keyframes fade-in-down {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down { animation: fade-in-down 0.2s ease-out; }
    [x-cloak] { display: none !important; }
</style>