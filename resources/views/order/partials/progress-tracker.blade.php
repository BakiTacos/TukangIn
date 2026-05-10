@if(!in_array($order->status, ['dikomplain', 'batal']))
    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
        <div class="relative flex flex-col md:flex-row justify-between items-center gap-6 md:gap-0">
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
    </div>
@endif