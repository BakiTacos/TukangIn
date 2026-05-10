<div class="sticky top-24 space-y-6">
    
    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Ringkasan Invoice</h4>
        
        <div class="space-y-4 text-sm border-b border-gray-50 pb-6 mb-6">
    <div class="flex justify-between items-center">
        <p class="text-gray-400">Biaya Layanan</p>
        <p class="font-bold text-[#0f2d50]">Rp {{ number_format($order->service_fee, 0, ',', '.') }}</p>
    </div>
    <div class="flex justify-between items-center">
        <p class="text-gray-400">Biaya Teknisi</p>
        <p class="font-bold text-[#0f2d50]">Rp {{ number_format($order->technician_fee, 0, ',', '.') }}</p>
    </div>
    <div class="flex justify-between items-center">
        <p class="text-gray-400">Pajak Platform (5%)</p>
        <p class="font-bold text-[#0f2d50]">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</p>
    </div>
    
    @if($order->platform_fee > 0)
        <div class="flex justify-between items-center text-orange-600">
            <p class="font-medium">Biaya Admin ({{ strtoupper($order->payment_method) }})</p>
            <p class="font-bold">Rp {{ number_format($order->platform_fee, 0, ',', '.') }}</p>
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
                
                <button type="button" @click="showCancelModal = true"
                        class="w-full bg-white border-2 border-red-150 hover:bg-red-50 text-red-500 py-4 rounded-2xl font-bold flex items-center justify-center gap-2 transition text-xs uppercase tracking-wider">
                    <i class="fas fa-times-circle"></i> Batalkan Pesanan
                </button>

            @elseif($order->status === 'pengerjaan')
                <button class="w-full bg-blue-500 text-white py-4 rounded-2xl font-bold cursor-default block text-center uppercase tracking-widest text-xs" disabled>
                    <i class="fas fa-spinner animate-spin mr-1"></i> Sedang Diperbaiki
                </button>
                
                @if($order->created_at->gt(now()->subHours(12)))
                    <button type="button" @click="showCancelModal = true"
                            class="w-full bg-red-500 hover:bg-red-600 text-white py-4 rounded-2xl font-bold transition transform hover:-translate-y-1 block text-center uppercase tracking-widest text-xs">
                        <i class="fas fa-times-circle mr-1"></i> Batalkan Pesanan
                    </button>
                @endif

                <button type="button" @click="showComplainModal = true"
                        class="w-full bg-white border-2 border-red-150 hover:bg-red-50 text-red-500 py-4 rounded-2xl font-bold flex items-center justify-center gap-2 transition text-xs uppercase tracking-wider">
                    <i class="fas fa-exclamation-triangle"></i> Ajukan Komplain Jasa
                </button>

            @elseif($order->status === 'selesai')
                @if($order->review)
                    <button class="w-full bg-gray-100 text-gray-400 py-4 rounded-2xl font-bold cursor-default block text-center uppercase tracking-widest text-xs border border-gray-150" disabled>
                        Sudah Diulas <i class="fas fa-check-circle ml-1 text-green-500"></i>
                    </button>
                @else
                    <a href="{{ route('reviews.create', $order->id) }}" 
                       class="w-full bg-green-500 hover:bg-green-600 text-white py-4 rounded-2xl font-bold transition transform hover:-translate-y-1 block text-center uppercase tracking-widest text-xs">
                        Beri Ulasan Teknisi <i class="far fa-star ml-1"></i>
                    </a>
                    
                    @if($order->updated_at->gt(now()->subDays(7)))
                        <button type="button" @click="showComplainModal = true"
                                class="w-full bg-white border-2 border-red-150 hover:bg-red-50 text-red-500 py-4 rounded-2xl font-bold flex items-center justify-center gap-2 transition text-xs uppercase tracking-wider">
                            <i class="fas fa-exclamation-triangle"></i> Ajukan Komplain Jasa
                        </button>
                    @else
                        <div class="bg-gray-50 border border-gray-150 p-4 rounded-2xl text-center text-gray-400 text-[10px] font-bold uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-1"></i> Garansi Komplain 7 Hari Habis
                        </div>
                    @endif
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