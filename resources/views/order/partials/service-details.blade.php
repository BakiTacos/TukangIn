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