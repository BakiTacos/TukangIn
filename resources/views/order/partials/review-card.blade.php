@if($order->review)
    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100 space-y-6">
        <div class="flex justify-between items-center border-b border-gray-50 pb-4">
            <h3 class="text-lg font-black text-[#0f2d50] uppercase tracking-wider">Ulasan Anda</h3>
            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Dikirim {{ $order->review->created_at->translatedFormat('d M Y') }}</span>
        </div>
        
        <div class="space-y-4">
            <div class="flex items-center gap-1.5">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-6 h-6 {{ $i <= $order->review->rating ? 'text-yellow-400 fill-current' : 'text-gray-200' }}" 
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                @endfor
                <span class="text-xs font-black text-orange-500 ml-2 uppercase tracking-wide">
                    {{ $order->review->rating === 5 ? 'Sangat Puas' : ($order->review->rating === 4 ? 'Puas' : ($order->review->rating === 3 ? 'Cukup Baik' : ($order->review->rating === 2 ? 'Buruk' : 'Sangat Buruk'))) }}
                </span>
            </div>

            <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-150">
                <p class="text-xs text-gray-600 leading-relaxed italic">
                    "{{ $order->review->comment }}"
                </p>
            </div>
        </div>
    </div>
@endif