@props(['title', 'value', 'icon', 'bgClass' => 'bg-blue-50', 'iconClass' => 'text-blue-600'])

<div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
    <div class="w-12 h-12 {{ $bgClass }} {{ $iconClass }} rounded-2xl flex items-center justify-center text-lg shrink-0">
        <i class="{{ $icon }}"></i>
    </div>
    <div>
        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $title }}</p>
        <h3 class="text-xl font-black text-[#0f2d50] mt-1">{!! $value !!}</h3>
    </div>
</div>