<x-app-layout>
    <div class="container mx-auto px-6 py-12 max-w-5xl">
        
        <nav class="flex justify-between items-center mb-10">
            <a href="{{ route('dashboard') }}" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-orange-500 transition flex items-center gap-2">
                <i class="fas fa-arrow-left text-xs"></i> Kembali ke Dashboard
            </a>
            <span class="bg-gray-150 text-gray-500 text-[9px] font-black px-4 py-2 rounded-xl uppercase tracking-widest border border-gray-200">
                Invoice Resmi
            </span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-8 pb-6 border-b border-gray-50">
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nomor Pemesanan</p>
                            <h2 class="text-xl font-black text-[#0f2d50]">{{ $order->order_number }}</h2>
                        </div>
                        
                        @if($order->status === 'pending')
                            <span class="bg-yellow-50 text-yellow-600 text-xs font-black px-4 py-2 rounded-full uppercase tracking-wider border border-yellow-100">Menunggu Pembayaran</span>
                        @elseif($order->status === 'pengerjaan')
                            <span class="bg-blue-50 text-blue-600 text-xs font-black px-4 py-2 rounded-full uppercase tracking-wider border border-blue-100">Dalam Pengerjaan</span>
                        @elseif($order->status === 'selesai')
                            <span class="bg-green-50 text-green-600 text-xs font-black px-4 py-2 rounded-full uppercase tracking-wider border border-green-100">Selesai</span>
                        @elseif($order->status === 'batal')
                            <span class="bg-red-50 text-red-600 text-xs font-black px-4 py-2 rounded-full uppercase tracking-wider border border-red-100">Dibatalkan</span>
                        @elseif($order->status === 'dikomplain')
                            <span class="bg-purple-50 text-purple-600 text-xs font-black px-4 py-2 rounded-full uppercase tracking-wider border border-purple-100">Dikomplain</span>
                        @endif
                    </div>

                    @if($order->status === 'dikomplain')
                        <div class="bg-purple-50 border border-purple-100 p-6 rounded-3xl flex items-start gap-4 text-purple-600">
                            <i class="fas fa-exclamation-circle text-2xl animate-bounce mt-1 shrink-0"></i>
                            <div>
                                <h4 class="text-sm font-black uppercase tracking-wider">Pemesanan Ditangguhkan (Dikomplain)</h4>
                                <p class="text-xs text-purple-500 leading-relaxed mt-1">
                                    Anda telah mengajukan komplain resmi untuk pengerjaan dari teknisi <strong>{{ $order->tukang->name ?? 'Teknisi' }}</strong>. Tim investigasi **AMARTA** sedang memverifikasi laporan ini. Dana transaksi Anda aman dan ditangguhkan dari pencairan sampai masalah selesai. Kami akan menghubungi Anda dalam 1x24 jam.
                                </p>
                            </div>
                        </div>

                    @elseif($order->status === 'batal')
                        <div class="bg-red-50 border border-red-100 p-5 rounded-2xl flex items-center gap-4 text-red-600">
                            <i class="fas fa-ban text-2xl shrink-0"></i>
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider">Pesanan Dibatalkan</h4>
                                <p class="text-[10px] text-red-500 leading-relaxed mt-1">Pesanan ini telah dibatalkan sebelum proses transaksi dana berhasil diselesaikan.</p>
                            </div>
                        </div>

                    @else
                        <div class="relative flex flex-col md:flex-row justify-between items-center gap-6 md:gap-0 mt-6">
                            
                            <div class="hidden md:block absolute left-10 right-10 top-5 h-1 bg-gray-100 -z-10"></div>
                            <div class="hidden md:block absolute left-10 top-5 h-1 bg-orange-500 -z-10 transition-all duration-500"
                                 style="width: {{ $order->status === 'pending' ? '0%' : ($order->status === 'pengerjaan' ? '50%' : '100%') }}">
                            </div>

                            <div class="flex flex-col items-center text-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold transition-all border-2 border-orange-500 bg-white text-orange-500 shadow-md">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <p class="text-xs font-bold text-[#0f2d50] mt-3">Pesanan Masuk</p>
                                <p class="text-[9px] text-gray-400 mt-1">{{ $order->created_at->format('d M Y') }}</p>
                            </div>

                            <div class="flex flex-col items-center text-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold transition-all border-2 
                                            {{ in_array($order->status, ['pengerjaan', 'selesai']) ? 'border-orange-500 bg-white text-orange-500 shadow-md' : 'border-gray-200 bg-white text-gray-400' }}">
                                    <i class="fas fa-money-check-alt"></i>
                                </div>
                                <p class="text-xs font-bold mt-3 {{ in_array($order->status, ['pengerjaan', 'selesai']) ? 'text-[#0f2d50]' : 'text-gray-400' }}">Pembayaran Valid</p>
                                <p class="text-[9px] text-gray-400 mt-1">{{ in_array($order->status, ['pengerjaan', 'selesai']) ? $order->updated_at->format('d M Y') : 'Menunggu' }}</p>
                            </div>

                            <div class="flex flex-col items-center text-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold transition-all border-2 
                                            {{ $order->status === 'selesai' ? 'border-orange-500 bg-white text-orange-500 shadow-md' : ($order->status === 'pengerjaan' ? 'border-orange-500 bg-white text-orange-500 animate-pulse' : 'border-gray-200 bg-white text-gray-400') }}">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <p class="text-xs font-bold mt-3 {{ in_array($order->status, ['pengerjaan', 'selesai']) ? 'text-[#0f2d50]' : 'text-gray-400' }}">Proses Perbaikan</p>
                                <p class="text-[9px] text-gray-400 mt-1">{{ $order->status === 'selesai' ? 'Selesai' : ($order->status === 'pengerjaan' ? 'Sedang Berlangsung' : 'Menunggu') }}</p>
                            </div>

                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100 space-y-8">
                    <h3 class="text-lg font-black text-[#0f2d50] uppercase tracking-wider border-b border-gray-50 pb-4">Rincian Jasa & Teknisi</h3>
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center overflow-hidden border border-gray-50">
                                @if($order->service->image)
                                    <img src="{{ asset('storage/' . $order->service->image) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-tools text-[#0f2d50] text-xl"></i>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-[#0f2d50] leading-tight text-lg">{{ $order->service->title }}</h4>
                                <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">{{ $order->service->category ?? 'Maintenance' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($order->tukang->name) }}&background=FFEDD5&color=F97316" class="w-12 h-12 rounded-xl border border-orange-200">
                            <div>
                                <h5 class="text-sm font-bold text-[#0f2d50]">{{ $order->tukang->name }}</h5>
                                <p class="text-[10px] text-gray-400">Spesialis Keahlian</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-black text-[#0f2d50] uppercase tracking-wider border-b border-gray-50 pb-4 mb-6">Lokasi Pengerjaan</h3>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center text-sm shrink-0">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-[#0f2d50] mb-1">Alamat Penerima ({{ $order->address->label ?? 'Lokasi Rumah' }})</h4>
                            <p class="text-xs text-gray-600 font-bold mb-1">{{ $order->address->receiver_name ?? Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                {{ $order->address->full_address }}, {{ $order->address->city }} ({{ $order->address->postal_code }})
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Ringkasan Invoice</h4>
                        
                        <div class="space-y-4 text-sm border-b border-gray-50 pb-6 mb-6">
                            <div class="flex justify-between items-center">
                                <p class="text-gray-400">Biaya Layanan</p>
                                <p class="font-bold text-[#0f2d50]">Rp {{ number_format($serviceFee, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-gray-400">Biaya Teknisi</p>
                                <p class="font-bold text-[#0f2d50]">Rp {{ number_format($technicianFee, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-gray-400">Pajak Platform (5%)</p>
                                <p class="font-bold text-[#0f2d50]">Rp {{ number_format($taxAmount, 0, ',', '.') }}</p>
                            </div>
                            
                            @if($order->payment_fee > 0)
                                <div class="flex justify-between items-center text-orange-600">
                                    <p class="font-medium">Biaya Admin ({{ strtoupper($order->payment_method) }})</p>
                                    <p class="font-bold">Rp {{ number_format($order->payment_fee, 0, ',', '.') }}</p>
                                </div>
                            @endif

                            @if($order->discount_amount > 0)
                                <div class="flex justify-between items-center text-green-600">
                                    <p class="font-medium">Diskon Promo ({{ $order->promo_code }})</p>
                                    <p class="font-bold">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-between items-center mb-8">
                            <div>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Biaya</p>
                                <p class="text-2xl font-black text-[#0f2d50]">Rp {{ number_format($order->total_cost, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @if($order->status === 'pending')
                                <a href="{{ route('orders.payment', $order->id) }}" 
                                   class="w-full bg-[#e67e22] hover:bg-[#d35400] text-white py-4 rounded-2xl font-bold shadow-lg shadow-orange-500/20 transition transform hover:-translate-y-1 block text-center uppercase tracking-widest text-xs">
                                    Bayar Sekarang <i class="fas fa-arrow-right ml-1"></i>
                                </a>

                            @elseif($order->status === 'pengerjaan')
                                <button class="w-full bg-blue-500 text-white py-4 rounded-2xl font-bold cursor-default block text-center uppercase tracking-widest text-xs" disabled>
                                    <i class="fas fa-spinner animate-spin mr-1"></i> Sedang Diperbaiki
                                </button>
                                
                                <form action="{{ route('orders.complain', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengajukan komplain resmi saat proses perbaikan sedang berlangsung?')">
                                    @csrf
                                    <button type="submit" class="w-full bg-white border-2 border-red-150 hover:bg-red-50 text-red-500 py-4 rounded-2xl font-bold flex items-center justify-center gap-2 transition text-xs uppercase tracking-wider">
                                        <i class="fas fa-exclamation-triangle"></i> Ajukan Komplain Jasa
                                    </button>
                                </form>

                            @elseif($order->status === 'selesai')
                                <button class="w-full bg-green-500 hover:bg-green-600 text-white py-4 rounded-2xl font-bold transition transform hover:-translate-y-1 block text-center uppercase tracking-widest text-xs">
                                    Beri Ulasan Teknisi <i class="far fa-star ml-1"></i>
                                </button>
                                
                                @if($order->updated_at->gt(now()->subDays(7)))
                                    <form action="{{ route('orders.complain', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengajukan komplain resmi untuk pekerjaan ini?')">
                                        @csrf
                                        <button type="submit" class="w-full bg-white border-2 border-red-150 hover:bg-red-50 text-red-500 py-4 rounded-2xl font-bold flex items-center justify-center gap-2 transition text-xs uppercase tracking-wider">
                                            <i class="fas fa-exclamation-triangle"></i> Ajukan Komplain Jasa
                                        </button>
                                    </form>
                                @else
                                    <div class="bg-gray-50 border border-gray-150 p-4 rounded-2xl text-center text-gray-400 text-[10px] font-bold uppercase tracking-wider">
                                        <i class="fas fa-info-circle mr-1"></i> Garansi Komplain 7 Hari Habis
                                    </div>
                                @endif

                            @elseif($order->status === 'dikomplain')
                                <button class="w-full bg-purple-100 text-purple-600 py-4 rounded-2xl font-bold cursor-default block text-center uppercase tracking-widest text-xs" disabled>
                                    <i class="fas fa-clock mr-1 animate-pulse"></i> Komplain Ditinjau
                                </button>

                            @elseif($order->status === 'batal')
                                <button class="w-full bg-red-100 text-red-500 py-4 rounded-2xl font-bold cursor-not-allowed block text-center uppercase tracking-widest text-xs" disabled>
                                    Pemesanan Dibatalkan
                                </button>
                            @endif
                        </div>
                    </div>

                    
                   
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

<style>
    [x-cloak] { display: none !important; }
</style>