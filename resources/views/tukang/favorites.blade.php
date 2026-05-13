<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-20">
        
        <div class="bg-[#0f2d50] pb-32 pt-12">
            <div class="container mx-auto px-6 text-white">
                <nav class="text-xs text-gray-400 mb-4 uppercase tracking-widest">Home > Teknisi > Favorit Saya</nav>
                <div>
                    <h1 class="text-4xl font-bold mb-2">Teknisi Favorit Anda</h1>
                    <p class="text-gray-300">Daftar tenaga ahli andalan yang telah Anda simpan untuk kemudahan perbaikan berkala.</p>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-6 -mt-16">
            <div class="max-w-5xl mx-auto">
                
                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-500 font-bold text-sm">
                        Menyimpan {{ $tukangs->total() }} Teknisi Pilihan
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($tukangs as $tukang)
                        
                        @php
                            $todaySchedule = $tukang->schedules->first();
                            $currentTime = \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s');
                            
                            $isOperating = $todaySchedule && 
                                           $todaySchedule->is_active && 
                                           $todaySchedule->start_time <= $currentTime && 
                                           $todaySchedule->end_time >= $currentTime;
                            
                            $isAvailable = $tukang->is_available && $isOperating;
                        @endphp

                        <div x-data="{ visible: true }" 
                             x-show="visible" 
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-xl transition-all duration-300 {{ !$isAvailable ? 'opacity-75 bg-gray-50/60' : '' }}">
                            
                            <div class="flex items-start justify-between">
                                <div class="flex items-center space-x-4">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($tukang->name) }}&background=0f2d50&color=fff" 
                                         class="w-16 h-16 rounded-2xl object-cover shadow-inner">
                                    <div>
                                        <span class="text-[9px] font-bold text-orange-500 uppercase tracking-widest">{{ $tukang->category }}</span>
                                        <h4 class="font-bold text-gray-800 text-lg">{{ $tukang->name }}</h4>
                                        
                                        <p class="text-[11px] font-bold text-yellow-500 flex items-center gap-1 mt-0.5">
                                            <i class="fas fa-star text-[10px]"></i> 
                                            {{ $tukang->reviews_avg_rating ? number_format($tukang->reviews_avg_rating, 1) : '5.0' }} 
                                            <span class="text-gray-400 font-normal ml-1">
                                                ({{ $tukang->completed_orders_count }} Order Selesai)
                                            </span>
                                        </p>

                                        @if($tukang->city && $tukang->province)
                                            <p class="text-[9px] text-gray-400 font-bold mt-1 flex items-center gap-1">
                                                <i class="fas fa-map-marker-alt text-orange-500 text-[8px]"></i>
                                                {{ $tukang->city }}, {{ $tukang->province }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <button type="button" 
                                        @click="
                                            fetch('{{ route('tukang.favorite', $tukang->id) }}', {
                                                method: 'POST',
                                                headers: { 
                                                    'Content-Type': 'application/json', 
                                                    'X-CSR-TOKEN': '{{ csrf_token() }}' 
                                                },
                                                body: JSON.stringify({})
                                            })
                                            .then(res => res.json())
                                            .then(data => { 
                                                if(data.success && data.status === 'removed') { 
                                                    visible = false; // Memicu efek hilangnya kartu dari grid
                                                } 
                                            })
                                            .catch(err => console.error(err));
                                        "
                                        class="text-red-500 bg-red-50 p-2 rounded-xl hover:bg-gray-150 hover:text-gray-300 transition-all duration-200">
                                    <i class="fas fa-heart text-sm scale-110"></i>
                                </button>
                            </div>

                            <div class="mt-4">
                                <p class="text-xs text-gray-500 italic">"{{ $tukang->specialty }}"</p>
                            </div>

                            <div class="mt-8 flex justify-between items-end border-t border-dashed border-gray-100 pt-4">
                                @if($isAvailable)
                                    <div>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Status Mitra</p>
                                        <div class="flex items-center gap-1 mt-0.5">
                                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                            <p class="text-xs font-bold text-green-600">Tersedia Sekarang</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('tukang.show', $tukang->id) }}" 
                                       class="bg-[#0f2d50] text-white px-6 py-2.5 rounded-2xl font-bold text-xs hover:bg-orange-500 transition shadow-lg shadow-blue-100 whitespace-nowrap">
                                            Lihat Profil
                                    </a>
                                @elseif($tukang->is_available && !$isOperating)
                                    <div>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Status Mitra</p>
                                        <div class="flex items-center gap-1 mt-0.5">
                                            <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                                            <p class="text-xs font-bold text-amber-600">Di Luar Jam Kerja</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('tukang.show', $tukang->id) }}" 
                                       class="bg-gray-150 border border-gray-200 text-gray-500 px-6 py-2.5 rounded-2xl font-bold text-xs hover:bg-gray-200 transition whitespace-nowrap">
                                            Lihat Jadwal
                                    </a>
                                @else
                                    <div>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Status Mitra</p>
                                        <div class="flex items-center gap-1 mt-0.5">
                                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                            <p class="text-xs font-bold text-red-500">Sedang Istirahat</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('tukang.show', $tukang->id) }}" 
                                       class="bg-gray-150 border border-gray-200 text-gray-500 px-6 py-2.5 rounded-2xl font-bold text-xs hover:bg-gray-200 transition whitespace-nowrap">
                                            Lihat Profil
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 text-center bg-white rounded-[2.5rem] border border-dashed border-gray-200">
                            <div class="w-20 h-20 bg-red-50 text-red-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl animate-pulse">
                                <i class="far fa-heart"></i>
                            </div>
                            <h3 class="font-bold text-gray-800 text-base">Belum Ada Teknisi Favorit</h3>
                            <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto leading-relaxed">
                                Jelajahi mitra kami dan klik ikon hati pada teknisi pilihan Anda untuk menyimpannya di sini.
                            </p>
                            <a href="{{ route('tukang.index') }}" 
                               class="inline-block mt-6 bg-[#0f2d50] hover:bg-orange-500 text-white font-bold text-xs uppercase tracking-wider py-3 px-6 rounded-xl transition shadow-md">
                                Cari Teknisi Sekarang
                            </a>
                        </div>
                    @endforelse
                </div>

                <div class="mt-12 flex justify-center">
                    {{ $tukangs->links() }}
                </div>
            </div>
        </div>

    </div>
</x-app-layout>

<style>
    [x-cloak] { display: none !important; }
</style>