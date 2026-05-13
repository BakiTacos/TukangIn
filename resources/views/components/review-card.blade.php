@props(['review'])

<div class="border-b border-gray-50 pb-8 last:border-0">
    <div class="flex justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-[10px] font-bold text-gray-400 uppercase shadow-inner">
                {{ substr($review->user_name, 0, 2) }}
            </div>
            <div>
                <p class="text-sm font-bold text-gray-800">{{ $review->user_name }}</p>
                <p class="text-[10px] text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
            </div>
        </div>
        <div class="text-yellow-400 text-[10px]">
            @for($i = 0; $i < $review->rating; $i++) 
                <i class="fas fa-star"></i> 
            @endfor
        </div>
    </div>
    <p class="text-xs text-gray-500 leading-relaxed italic">"{{ $review->comment }}"</p>
</div>