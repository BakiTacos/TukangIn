<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-20">
        
        <div class="bg-[#0f2d50] pb-32 pt-12">
            <div class="container mx-auto px-6 text-white">
                <span class="bg-orange-500 text-white text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest">Dashboard</span>
                <h1 class="text-4xl font-black mt-3">Metrik Kontrol</h1>
                <p class="text-xs text-gray-300 mt-1">Ringkasan pertumbuhan pendapatan platform dan konversi aktivitas jaringan kerja TUKANG.IN.</p>
            </div>
        </div>

        <div class="container mx-auto px-6 -mt-16">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                    <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-lg"><i class="fas fa-chart-line"></i></div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Gross Merchandise Value</p>
                        <h3 class="text-xl font-black text-[#0f2d50] mt-1">Rp {{ number_format($totalGmv, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-lg"><i class="fas fa-shopping-basket"></i></div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Volume Transaksi</p>
                        <h3 class="text-xl font-black text-[#0f2d50] mt-1">{{ $totalTransactions }} Orderan</h3>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                    <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-sm"><i class="fas fa-calculator"></i></div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Tingkat Penggunaan (Avg)</p>
                        <h3 class="text-sm font-black text-[#0f2d50] mt-1">Rp {{ number_format($avgPurchaseValue, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                    <div class="w-12 h-12 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center text-lg"><i class="fas fa-exclamation-triangle"></i></div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Kasus Sengketa Aktif</p>
                        <h3 class="text-xl font-black text-[#0f2d50] mt-1">{{ $criticalOrdersCount }} Kasus</h3>
                    </div>
                </div>
            </div>

            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 px-2">Akses Cepat Pengelolaan Modul</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <a href="{{ route('admin.users') }}" class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:border-orange-500 transition group flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl mb-6 group-hover:bg-[#0f2d50] group-hover:text-white transition"><i class="fas fa-user-shield"></i></div>
                        <h4 class="font-black text-lg text-[#0f2d50] uppercase tracking-wide">Otoritas Akun</h4>
                        <p class="text-xs text-gray-400 mt-2 leading-relaxed">Kelola batasan hak akses, lakukan pencarian data pengguna, serta eksekusi moderasi blokir & unblock akun pelanggar.</p>
                    </div>
                    <span class="inline-flex items-center gap-2 text-xs font-bold text-orange-500 mt-8 group-hover:translate-x-2 transition-transform">Masuk Manajemen Akun <i class="fas fa-arrow-right text-[10px]"></i></span>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:border-orange-500 transition group flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-xl mb-6 group-hover:bg-[#0f2d50] group-hover:text-white transition"><i class="fas fa-file-invoice-dollar"></i></div>
                        <h4 class="font-black text-lg text-[#0f2d50] uppercase tracking-wide">Arus Log Transaksi</h4>
                        <p class="text-xs text-gray-400 mt-2 leading-relaxed">Audit menyeluruh alur perputaran kontrak kerja finansial, penanganan berkas sengketa komplain, dan keputusan penahanan dana.</p>
                    </div>
                    <span class="inline-flex items-center gap-2 text-xs font-bold text-orange-500 mt-8 group-hover:translate-x-2 transition-transform">Buka Log Transaksi <i class="fas fa-arrow-right text-[10px]"></i></span>
                </a>

                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex flex-col justify-between">
                    <div>
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-3 mb-4">Sensus Database Supabase</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center"><span class="text-xs text-gray-500">Pelanggan Terdaftar:</span><strong class="text-sm font-black text-[#0f2d50]">{{ $totalPelanggan }} Akun</strong></div>
                            <div class="flex justify-between items-center"><span class="text-xs text-gray-500">Mitra Teknisi Kerja:</span><strong class="text-sm font-black text-[#0f2d50]">{{ $totalMitraTeknisi }} Teknisi</strong></div>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 text-center"><p class="text-[10px] font-bold text-green-600 uppercase tracking-wider">● Database Node: Connected Postgres</p></div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>