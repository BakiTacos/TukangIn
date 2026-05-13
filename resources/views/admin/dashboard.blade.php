<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-20">
        
        <div class="bg-[#0f2d50] pb-32 pt-12">
            <div class="container mx-auto px-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="bg-orange-500 text-white text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest">
                            HQ Pusat Kontrol Admin
                        </span>
                        <h1 class="text-4xl font-black mt-3">Selamat Datang, {{ auth()->user()->name }}</h1>
                        <p class="text-xs text-gray-300 mt-1">Pantau grafik transaksi, audit aktivitas mitra teknisi, dan tangani komplain pelanggan.</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 px-5 py-3 rounded-2xl backdrop-blur-sm text-right hidden sm:block">
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Sistem Node</p>
                        <p class="text-xs font-black text-green-400 mt-0.5 animate-pulse">● Production Online</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-6 -mt-16">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                
                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                    <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-lg shrink-0">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Total GMV Transaksi</p>
                        <h3 class="text-xl font-black text-[#0f2d50] mt-1">Rp {{ number_format($totalGmv, 0, ',', '.') }}</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-lg shrink-0">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Pelanggan Reguler</p>
                        <h3 class="text-xl font-black text-[#0f2d50] mt-1">{{ $totalPelanggan }} Akun</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                    <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center text-lg shrink-0">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Mitra Terdaftar</p>
                        <h3 class="text-xl font-black text-[#0f2d50] mt-1">{{ $totalMitraTeknisi }} Teknisi</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                    <div class="w-12 h-12 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center text-lg shrink-0">
                        <i class="fas fa-exclamation-triangle animate-bounce"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Pekerjaan Dipantau</p>
                        <h3 class="text-xl font-black text-[#0f2d50] mt-1">{{ $criticalOrdersCount }} Antrean</h3>
                    </div>
                </div>

            </div>

            <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-gray-100">
                <div class="flex justify-between items-center border-b border-gray-50 pb-5 mb-6">
                    <div>
                        <h3 class="text-lg font-black text-[#0f2d50] uppercase tracking-wider">📋 Arus Log Transaksi Terbaru</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">Pantau status pengerjaan yang sedang terjadi di seluruh wilayah jangkauan.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 text-gray-400 text-[10px] font-black uppercase tracking-widest bg-gray-50/50 rounded-xl">
                                <th class="py-4 px-4">Invoice ID</th>
                                <th class="py-4 px-4">Pelanggan</th>
                                <th class="py-4 px-4">Teknisi Ahli</th>
                                <th class="py-4 px-4">Layanan / Kategori</th>
                                <th class="py-4 px-4">Nilai Kontrak</th>
                                <th class="py-4 px-4 text-center">Status Global</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-xs font-medium text-gray-700">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="py-4 px-4 font-black text-blue-600">#{{ $order->order_number }}</td>
                                    <td class="py-4 px-4 font-bold text-gray-800">{{ $order->user->name ?? 'User Terhapus' }}</td>
                                    <td class="py-4 px-4">
                                        <span class="font-bold text-gray-800">{{ $order->tukang->name ?? 'Belum Ditunjuk' }}</span>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase mt-0.5">{{ $order->tukang->city ?? '' }}</p>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="font-extrabold text-[#0f2d50]">{{ $order->service->title ?? 'Custom Layanan' }}</span>
                                        <p class="text-[9px] text-orange-500 font-bold uppercase mt-0.5">{{ $order->service->category ?? '' }}</p>
                                    </td>
                                    <td class="py-4 px-4 font-extrabold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="py-4 px-4 text-center">
                                        @if($order->status === 'selesai')
                                            <span class="inline-block bg-green-50 text-green-600 text-[9px] font-black px-3 py-1.5 rounded-lg border border-green-100 uppercase tracking-wide">Selesai</span>
                                        @elseif($order->status === 'proses')
                                            <span class="inline-block bg-blue-50 text-blue-600 text-[9px] font-black px-3 py-1.5 rounded-lg border border-blue-100 uppercase tracking-wide animate-pulse">Pengerjaan</span>
                                        @elseif($order->status === 'menunggu')
                                            <span class="inline-block bg-amber-50 text-amber-600 text-[9px] font-black px-3 py-1.5 rounded-lg border border-amber-100 uppercase tracking-wide">Mencari Mitra</span>
                                        @elseif($order->status === 'batal')
                                            <span class="inline-block bg-gray-100 text-gray-400 text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-wide">Dibatalkan</span>
                                        @elseif($order->status === 'dikomplain')
                                            <span class="inline-block bg-purple-50 text-purple-600 text-[9px] font-black px-3 py-1.5 rounded-lg border border-purple-100 uppercase tracking-wide">⚠️ Sengketa</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-12 text-gray-400 font-bold uppercase tracking-wider">Belum Ada Transaksi Log Masuk di Platform</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 border-t border-gray-50 pt-6">
                    {{ $recentOrders->links() }}
                </div>
            </div>
        </div>

    </div>
</x-app-layout>