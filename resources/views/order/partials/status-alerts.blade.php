<div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
    <div class="flex justify-between items-center pb-6 border-b border-gray-50 mb-6">
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
        <div class="bg-purple-50 border border-purple-100 p-6 rounded-3xl flex items-start gap-4 text-purple-600 animate-fade-in">
            <i class="fas fa-exclamation-circle text-2xl animate-bounce mt-1 shrink-0"></i>
            <div>
                <h4 class="text-sm font-black uppercase tracking-wider">Pemesanan Ditangguhkan (Dikomplain)</h4>
                <p class="text-xs text-purple-500 leading-relaxed mt-1">
                    Anda telah mengajukan komplain resmi untuk pengerjaan dari teknisi <strong>{{ $order->tukang->name ?? 'Teknisi' }}</strong>. Tim investigasi TUKANG.IN sedang memverifikasi laporan ini. Dana transaksi Anda aman dan ditangguhkan dari pencairan sampai masalah selesai. Kami akan menghubungi Anda dalam 1x24 jam.
                </p>
                <div class="mt-4 pt-4 border-t border-purple-100 text-xs space-y-1 text-purple-700">
                    <p><strong>Alasan:</strong> {{ $order->complaint_reason }}</p>
                    <p class="italic">"{{ $order->complaint_description }}"</p>
                </div>
            </div>
        </div>
    @elseif($order->status === 'batal')
        <div class="bg-red-50 border border-red-100 p-6 rounded-3xl flex flex-col gap-4 text-red-600">
            <div class="flex items-start gap-4">
                <i class="fas fa-ban text-2xl shrink-0 mt-0.5"></i>
                <div>
                    <h4 class="text-sm font-black uppercase tracking-wider">Pesanan Dibatalkan</h4>
                    <p class="text-xs text-red-500 leading-relaxed mt-1">Pesanan ini telah resmi dibatalkan dan sistem telah menghentikan seluruh proses transaksi terkait.</p>
                </div>
            </div>
            @if($order->cancel_reason)
                <div class="pt-4 border-t border-red-100 text-xs space-y-1 text-red-700">
                    <p><strong>Alasan Pembatalan:</strong> {{ $order->cancel_reason }}</p>
                    <p class="italic">"{{ $order->cancel_description }}"</p>
                </div>
            @endif
        </div>
    @endif
</div>