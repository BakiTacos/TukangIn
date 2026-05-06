<x-app-layout>
    <div class="container mx-auto px-6 py-8">
        
        <!-- Header Profile Banner -->
        <div class="bg-gradient-to-r from-[#0f2d50] to-[#1e4b82] rounded-[2.5rem] p-8 md:p-12 mb-8 text-white relative overflow-hidden shadow-xl">
            <div class="flex flex-col md:flex-row items-center justify-between relative z-10">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=e67e22&color=fff&size=150" 
                             class="w-28 h-28 md:w-32 md:h-32 rounded-full border-4 border-white/20 shadow-2xl object-cover">
                        <div class="absolute bottom-1 right-1 bg-orange-500 p-1.5 rounded-full border-2 border-[#0f2d50]">
                            <i class="fas fa-check text-[10px]"></i>
                        </div>
                    </div>
                    <div class="text-center md:text-left">
                        <h1 class="text-3xl font-extrabold tracking-tight">{{ strtoupper($user->name) }}</h1>
                        <div class="flex flex-wrap justify-center md:justify-start items-center gap-3 mt-2">
                            <span class="bg-orange-500/20 text-orange-400 text-[10px] font-bold px-3 py-1 rounded-full border border-orange-500/30 flex items-center gap-1 uppercase tracking-widest">
                                <i class="fas fa-crown text-[8px]"></i> Regular User
                            </span>
                            <p class="text-xs text-gray-300 font-medium italic">Joined {{ $user->created_at->format('F Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 mt-8 md:mt-0">
                    <div class="bg-white/10 backdrop-blur-md p-4 px-6 rounded-2xl border border-white/10 text-center min-w-[120px]">
                        <p class="text-[10px] text-gray-300 font-bold uppercase tracking-widest mb-1">Total Pesanan</p>
                        <p class="text-2xl font-black">0</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-4 px-6 rounded-2xl border border-white/10 text-center min-w-[120px]">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Poin Loyalitas</p>
                        <p class="text-2xl font-black text-orange-400">0</p>
                    </div>
                </div>
            </div>
            <!-- Dekorasi Background -->
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Kiri: Pengaturan -->
            <aside class="w-full lg:w-1/3 space-y-6">
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6 px-2">Pengaturan Akun</h4>
                    <div class="space-y-2">
                        <a href="/profile" class="flex items-center justify-between p-4 rounded-2xl hover:bg-gray-50 transition group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 group-hover:text-orange-500 transition">
                                    <i class="far fa-user text-lg"></i>
                                </div>
                                <span class="text-sm font-bold text-gray-700">Edit Profil</span>
                            </div>
                            <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
                        </a>
                        <a href="#" class="flex items-center justify-between p-4 rounded-2xl hover:bg-gray-50 transition group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 group-hover:text-orange-500 transition">
                                    <i class="fas fa-map-marker-alt text-lg"></i>
                                </div>
                                <span class="text-sm font-bold text-gray-700">Alamat Saya</span>
                            </div>
                            <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
                        </a>
                        <a href="#" class="flex items-center justify-between p-4 rounded-2xl hover:bg-gray-50 transition group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 group-hover:text-orange-500 transition">
                                    <i class="far fa-credit-card text-lg"></i>
                                </div>
                                <span class="text-sm font-bold text-gray-700">Metode Pembayaran</span>
                            </div>
                            <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
                        </a>
                    </div>
                </div>

                
            </aside>

            <!-- Konten Kanan -->
            <main class="w-full lg:w-2/3 space-y-6">
                
                <!-- Pesanan Terakhir -->
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-8">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pesanan Terakhir</h4>
                        <a href="#" class="text-[10px] font-bold text-orange-500 uppercase tracking-widest hover:underline">Lihat Semua</a>
                    </div>
                    
                    <div class="bg-gray-50/50 p-6 rounded-[2rem] border border-gray-100 flex items-center justify-between group hover:border-orange-200 transition">
                        <div class="flex items-center gap-6">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-[#0f2d50] shadow-sm">
                                <i class="fas fa-tools text-xl"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-gray-800">Servis AC Split</h5>
                                <p class="text-[10px] text-gray-400 font-medium uppercase mt-1">12 Okt 2023 • <span class="text-green-500">Selesai</span></p>
                            </div>
                        </div>
                        <p class="text-lg font-black text-[#0f2d50]">Rp 150.000</p>
                    </div>
                </div>

                <!-- Bantuan & Legalitas -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Bantuan & Dukungan</h4>
                        <div class="space-y-6">
                            <a href="/pusat-bantuan" class="flex items-center gap-4 group">
                                <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 text-sm"><i class="far fa-question-circle"></i></div>
                                <div>
                                    <p class="text-sm font-bold text-gray-700 group-hover:text-orange-500 transition">Pusat Bantuan</p>
                                    <p class="text-[10px] text-gray-400">FAQ & Panduan Layanan</p>
                                </div>
                            </a>
                            
                        </div>
                    </div>

                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Legalitas</h4>
                        <div class="space-y-5">
                            <a href="/syarat-ketentuan" class="flex justify-between items-center text-sm font-bold text-gray-700 hover:text-orange-500 transition">
                                Syarat & Ketentuan <i class="fas fa-external-link-alt text-[10px] text-gray-300"></i>
                            </a>
                            <a href="/kebijakan-privasi" class="flex justify-between items-center text-sm font-bold text-gray-700 hover:text-orange-500 transition">
                                Kebijakan Privasi <i class="fas fa-external-link-alt text-[10px] text-gray-300"></i>
                            </a>
                            <div class="flex justify-between items-center text-sm font-bold text-gray-700">
                                Versi Aplikasi <span class="bg-gray-100 px-2 py-1 rounded text-[10px] text-gray-400">v1.0.0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-500 py-5 rounded-[2rem] font-bold text-sm flex items-center justify-center gap-3 transition border border-red-100 mt-4">
                        <i class="fas fa-sign-out-alt"></i> Keluar dari Akun
                    </button>
                </form>
            </main>
        </div>
    </div>
</x-app-layout>