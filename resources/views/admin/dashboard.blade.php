<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-20">
        
        <div class="bg-[#0f2d50] pb-32 pt-12">
            <div class="container mx-auto px-6 text-white">
                <span class="bg-orange-500 text-white text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest">Dashboard Admin</span>
                <h1 class="text-4xl font-black mt-3">Metrik Kontrol HQ</h1>
                <p class="text-xs text-gray-300 mt-1">Ringkasan pertumbuhan pendapatan platform dan konversi aktivitas jaringan kerja TUKANG.IN.</p>
            </div>
        </div>

        <div class="container mx-auto px-6 -mt-16">
            
            <!-- 📊 KOTAK METRIK ATAS (Tetap Sama) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                    <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-lg"><i class="fas fa-chart-line"></i></div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Gross Merchandise Value</p>
                        <h3 class="text-xl font-black text-[#0f2d50] mt-1">Rp {{ number_format($totalGmv ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-lg"><i class="fas fa-shopping-basket"></i></div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Volume Transaksi</p>
                        <h3 class="text-xl font-black text-[#0f2d50] mt-1">{{ $totalTransactions ?? 0 }} Orderan</h3>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                    <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center text-lg"><i class="fas fa-calculator"></i></div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Tingkat Penggunaan (Avg)</p>
                        <h3 class="text-sm font-black text-[#0f2d50] mt-1">Rp {{ number_format($avgPurchaseValue ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                    <div class="w-12 h-12 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center text-lg"><i class="fas fa-exclamation-triangle"></i></div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Kasus Sengketa Aktif</p>
                        <h3 class="text-xl font-black text-[#0f2d50] mt-1">{{ $criticalOrdersCount ?? 0 }} Kasus</h3>
                    </div>
                </div>
            </div>

            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 px-2">Akses Cepat Pengelolaan Modul</h3>
            
            <!-- ⚡ UBAH JADI GRID 2 KOLOM BIAYA RAPI (2x2) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- 1️⃣ MODUL BARU: VERIFIKASI MITRA TUKANG -->
                <a href="{{ route('admin.verifications') }}" class="relative bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:border-orange-500 transition group flex flex-col justify-between overflow-hidden">
                    @if($pendingMitraCount > 0)
                        <!-- Badge Notifikasi Real-time -->
                        <div class="absolute top-6 right-6 flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-white text-xs font-bold animate-bounce shadow-lg shadow-red-200">
                            {{ $pendingMitraCount }}
                        </div>
                    @endif
                    <div>
                        <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center text-xl mb-6 group-hover:bg-orange-500 group-hover:text-white transition shadow-sm"><i class="fas fa-id-card-clip"></i></div>
                        <h4 class="font-black text-lg text-[#0f2d50] uppercase tracking-wide">Audit & Verifikasi Mitra Baru</h4>
                        <p class="text-xs text-gray-400 mt-2 leading-relaxed">Tinjau antrean pendaftaran teknisi. Validasi dokumen NIK KTP, periksa kesesuaian keahlian, lalu setujui atau tolak akses mereka.</p>
                    </div>
                    <span class="inline-flex items-center gap-2 text-xs font-bold text-orange-500 mt-8 group-hover:translate-x-2 transition-transform">
                        Buka Ruang Audit Mitra <i class="fas fa-arrow-right text-[10px]"></i>
                    </span>
                </a>

                <!-- 2️⃣ MODUL OTORITAS AKUN -->
                <a href="{{ route('admin.users') }}" class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:border-blue-500 transition group flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl mb-6 group-hover:bg-[#0f2d50] group-hover:text-white transition shadow-sm"><i class="fas fa-user-shield"></i></div>
                        <h4 class="font-black text-lg text-[#0f2d50] uppercase tracking-wide">Otoritas Database Akun</h4>
                        <p class="text-xs text-gray-400 mt-2 leading-relaxed">Kelola batasan hak akses, lakukan pencarian data pengguna aktif, serta eksekusi moderasi blokir & unblock akun pelanggar tata tertib.</p>
                    </div>
                    <span class="inline-flex items-center gap-2 text-xs font-bold text-blue-600 mt-8 group-hover:translate-x-2 transition-transform">
                        Masuk Manajemen Akun <i class="fas fa-arrow-right text-[10px]"></i>
                    </span>
                </a>

                <!-- 3️⃣ MODUL LOG TRANSAKSI & SENGKETA -->
                <a href="{{ route('admin.orders.index') }}" class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:border-purple-500 transition group flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-xl mb-6 group-hover:bg-[#0f2d50] group-hover:text-white transition shadow-sm"><i class="fas fa-file-invoice-dollar"></i></div>
                        <h4 class="font-black text-lg text-[#0f2d50] uppercase tracking-wide">Arus Log Transaksi & Sengketa</h4>
                        <p class="text-xs text-gray-400 mt-2 leading-relaxed">Audit menyeluruh alur perputaran kontrak kerja finansial, penanganan berkas sengketa komplain, dan keputusan penahanan dana.</p>
                    </div>
                    <span class="inline-flex items-center gap-2 text-xs font-bold text-purple-600 mt-8 group-hover:translate-x-2 transition-transform">
                        Buka Log Transaksi <i class="fas fa-arrow-right text-[10px]"></i>
                    </span>
                </a>

                <!-- 4️⃣ KOTAK STATISTIK STATIS SUPABASE -->
                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-gray-50 text-gray-600 rounded-2xl flex items-center justify-center text-xl mb-6 shadow-sm"><i class="fas fa-server"></i></div>
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-3 mb-4">Sensus Database Supabase</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center"><span class="text-xs text-gray-500">Pelanggan Terdaftar:</span><strong class="text-sm font-black text-[#0f2d50]">{{ $totalPelanggan ?? 0 }} Akun</strong></div>
                            <div class="flex justify-between items-center"><span class="text-xs text-gray-500">Mitra Teknisi Kerja:</span><strong class="text-sm font-black text-[#0f2d50]">{{ $totalMitraTeknisi ?? 0 }} Teknisi</strong></div>
                        </div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-2xl border border-green-100 text-center mt-8">
                        <p class="text-[10px] font-black text-green-600 uppercase tracking-wider">● Database Node: Connected Postgres</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>