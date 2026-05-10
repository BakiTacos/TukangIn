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