
<style>
    [x-cloak] { display: none !important; }
</style><x-app-layout>
    <div class="container mx-auto px-6 py-12" x-data="{ tab: 'semua' }">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-6">
            <div>
                <p class="text-[10px] font-bold text-orange-500 uppercase tracking-widest mb-1">Management Dashboard</p>
                <h1 class="text-4xl font-black text-[#0f2d50] mb-2">Ada Pesanan</h1>
                <p class="text-sm text-gray-400">Lacak dan kelola semua permintaan layanan aktif Anda di satu tempat.</p>
            </div>
            
            <div class="flex bg-white p-1.5 rounded-2xl shadow-sm border border-gray-100">
                <button @click="tab = 'semua'" :class="tab === 'semua' ? 'bg-[#fdf6f0] text-orange-500' : 'text-gray-400 hover:text-gray-600'" class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all duration-200">Semua</button>
                <button @click="tab = 'pengerjaan'" :class="tab === 'pengerjaan' ? 'bg-[#fdf6f0] text-orange-500' : 'text-gray-400 hover:text-gray-600'" class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all duration-200">Pengerjaan</button>
                <button @click="tab = 'selesai'" :class="tab === 'selesai' ? 'bg-[#fdf6f0] text-orange-500' : 'text-gray-400 hover:text-gray-600'" class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all duration-200">Selesai</button>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <div class="lg:w-2/3 space-y-6">
                @forelse($orders as $order)
                    <div x-show="tab === 'semua' || (tab === 'pengerjaan' && '{{ $order->status }}' !== 'selesai' && '{{ $order->status }}' !== 'batal') || (tab === 'selesai' && '{{ $order->status }}' === 'selesai')" 
                        x-cloak
                        :class="'{{ $order->status }}' === 'batal' ? 'opacity-60' : ''"
                        class="bg-white rounded-[2.5rem] p-8 shadow-sm border {{ in_array($order->status, ['selesai', 'batal']) ? 'border-gray-100' : 'border-l-8 border-l-orange-500 border-gray-100' }} relative overflow-hidden transition-all">
                        
                        <div class="flex flex-col md:flex-row justify-between gap-6">
                            <div class="flex gap-6">
                                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-[#0f2d50] flex-shrink-0 overflow-hidden">
                                    <i class="fas fa-tools text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-[#0f2d50] mb-1">{{ $order->service->title ?? 'Layanan Umum' }}</h3>
                                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">
                                        <i class="far fa-calendar-alt mr-1"></i> {{ $order->schedule_date->translatedFormat('l, d M Y • H:i') }} WIB
                                    </p>
                                </div>
                            </div>
                            
                            @if($order->status === 'pending')
                                <span class="bg-yellow-50 text-yellow-600 text-[9px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest h-fit self-start border border-yellow-100">● Menunggu Pembayaran</span>
                            @elseif($order->status === 'pengerjaan')
                                <span class="bg-blue-50 text-blue-600 text-[9px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest h-fit self-start border border-blue-100">● Dalam Pengerjaan</span>
                            @elseif($order->status === 'selesai')
                                <span class="bg-green-50 text-green-600 text-[9px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest h-fit self-start border border-green-100">Selesai</span>
                            @elseif($order->status === 'batal')
                                <span class="bg-red-50 text-red-600 text-[9px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest h-fit self-start border border-red-100">Dibatalkan</span>
                            @else
                                <span class="bg-gray-50 text-gray-400 text-[9px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest h-fit self-start border border-gray-100">● {{ ucfirst($order->status) }}</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-3 gap-4 mt-8 pt-8 border-t border-gray-50">
                            <div>
                                <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">Tukang</p>
                                <p class="text-sm font-bold text-gray-700">{{ $order->tukang->name ?? 'Mencari Teknisi...' }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">Lokasi</p>
                                <p class="text-sm font-bold text-gray-700 truncate" title="{{ $order->address->full_address ?? '' }}">
                                    {{ $order->address->label ?? 'Alamat Dihapus' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] text-gray-400 font-bold uppercase mb-1">Total Biaya</p>
                                <p class="text-lg font-black text-[#0f2d50]">Rp {{ number_format($order->total_cost, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 mt-8">
                            @if($order->status === 'pending')
                                <a href="{{ route('orders.payment', $order->id) }}" 
                                   class="flex-1 text-center bg-[#e67e22] hover:bg-[#d35400] text-white py-4 rounded-2xl font-bold text-xs uppercase tracking-widest transition flex items-center justify-center gap-2 shadow-lg shadow-orange-500/15">
                                    Bayar Sekarang <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            @else
                                <a href="{{ route('orders.show', $order->id) }}" 
                                   class="flex-1 text-center bg-gray-50 hover:bg-gray-100 text-gray-500 py-4 rounded-2xl font-bold text-xs uppercase tracking-widest transition flex items-center justify-center">
                                    Detail Pesanan
                                </a>
                            @endif

                            @if($order->status === 'selesai')
                                <button class="px-8 bg-white border-2 border-[#fdf6f0] text-orange-500 py-4 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-orange-500 hover:text-white transition">Beri Ulasan</button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-gray-100">
                        <h3 class="text-lg font-bold text-gray-400">Belum Ada Pesanan</h3>
                        <p class="text-xs text-gray-300 mt-2">Anda belum memiliki riwayat pesanan layanan.</p>
                    </div>
                @endforelse
            </div>

            <div class="lg:w-1/3 space-y-6">
                <div class="bg-gradient-to-br from-[#0f2d50] to-[#1e4b82] rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between items-center">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Pesanan Bulan Ini</p>
                                <p class="text-2xl font-black">{{ $totalPesananBulanIni }}</p>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Pengeluaran</p>
                                <p class="text-xl font-black text-orange-400">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        
                        <div class="pt-6 border-t border-white/10 flex items-center gap-4">
                            <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center text-white shadow-lg">
                                <i class="fas fa-crown text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter leading-none">Status Akun</p>
                                <p class="text-sm font-black uppercase tracking-tight">PELANGGAN REGULER</p>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                    <h4 class="text-lg font-bold text-[#0f2d50] mb-6 px-2">Butuh Layanan Lain?</h4>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-4 bg-gray-50/50 rounded-2xl border border-gray-100 hover:border-orange-200 transition group cursor-pointer">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-orange-500 group-hover:scale-110 transition">
                                <i class="fas fa-broom"></i>
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-gray-800">Pembersihan Rumah</h5>
                                <p class="text-[9px] text-gray-400">Diskon 10% khusus Member Gold</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-4 bg-gray-50/50 rounded-2xl border border-gray-100 hover:border-orange-200 transition group cursor-pointer">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-orange-500 group-hover:scale-110 transition">
                                <i class="fas fa-bug text-lg"></i>
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-gray-800">AC & Pendingin</h5>
                                <p class="text-[9px] text-gray-400">Layanan profesional & bergaransi</p>
                            </div>
                        </div>
                    </div>
                    <a href="/layanan">
                        <button class="w-full mt-8 py-4 border-2 border-orange-500 rounded-2xl text-[10px] font-black text-orange-500 uppercase tracking-widest hover:bg-orange-500 hover:text-white transition">Lihat Semua Kategori</button>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

<style>
    [x-cloak] { display: none !important; }
</style>