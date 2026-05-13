<x-app-layout>
    <div x-data="{ 
            showCompleteModal: false, 
            completeAction: '', 
            completePhoto: '',
            showCancelModal: false,
            cancelReason: '',
            cancelDescription: ''
         }"
         class="min-h-screen bg-gray-50 pb-20">

        <div class="bg-[#0f2d50] pb-32 pt-12">
            <div class="container mx-auto px-6 text-white">
                <nav class="text-xs text-gray-400 mb-4 uppercase tracking-widest">Home > Teknisi > Semua</nav>
                
                <div class="flex items-center gap-2.5 mb-6 bg-white/10 border border-white/10 p-4 rounded-2xl w-fit backdrop-blur-sm">
                    <input type="checkbox" id="show_inactive_toggle" 
                           onchange="let url = new URL(window.location.href); if(this.checked) { url.searchParams.set('show_inactive', '1'); } else { url.searchParams.delete('show_inactive'); } window.location.href = url.toString();"
                           {{ request('show_inactive') ? 'checked' : '' }}
                           class="rounded-lg text-orange-500 focus:ring-orange-500 border-white/20 bg-white/5 w-4 h-4 cursor-pointer">
                    <label for="show_inactive_toggle" class="text-xs font-bold text-gray-200 cursor-pointer select-none">
                        Tampilkan Mitra yang Sedang Istirahat / Di Luar Jam Kerja
                    </label>
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6">
                    <div>
                        <h1 class="text-4xl font-bold mb-2">Mitra Teknisi</h1>
                        <p class="text-gray-300">Temukan tenaga ahli profesional yang siap membantu perbaikan rumah Anda.</p>
                    </div>
                    
                    <div class="w-full md:w-[350px]">
                        <x-location-picker :provinces="$provinces" :citiesMap="$citiesMap" />
                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-6 -mt-16">
            <div class="flex flex-col lg:flex-row gap-8">
                
                <aside class="w-full lg:w-1/4 space-y-6">
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                        <form action="{{ route('tukang.index') }}" method="GET">
                            @if(request('province')) <input type="hidden" name="province" value="{{ request('province') }}"> @endif
                            @if(request('city')) <input type="hidden" name="city" value="{{ request('city') }}"> @endif
                            @if(request('show_inactive')) <input type="hidden" name="show_inactive" value="{{ request('show_inactive') }}"> @endif

                            <h4 class="font-bold text-gray-800 mb-4 border-b pb-2 text-sm uppercase tracking-wider">Cari Nama</h4>
                            <div class="relative mb-6">
                                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400 text-xs"></i>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama teknisi..." 
                                       class="w-full bg-gray-50 border-none rounded-xl py-3 pl-10 text-xs focus:ring-2 focus:ring-orange-500">
                            </div>

                            <h4 class="font-bold text-gray-800 mb-4 border-b pb-2 text-sm uppercase tracking-wider">Kategori Ahli</h4>
                            <div class="space-y-3 text-xs text-gray-600">
                                @php
                                    $categories = ['AC & Pendingin', 'Plumbing', 'Listrik', 'Konstruksi', 'Finishing', 'Interior'];
                                @endphp
                                
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="radio" name="category" value="" onchange="this.form.submit()" {{ !request('category') ? 'checked' : '' }} class="text-orange-500 focus:ring-orange-500 border-gray-300">
                                    <span class="group-hover:text-orange-500 transition font-medium">Semua Keahlian</span>
                                </label>

                                @foreach($categories as $cat)
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="radio" name="category" value="{{ $cat }}" onchange="this.form.submit()" {{ request('category') == $cat ? 'checked' : '' }} class="text-orange-500 focus:ring-orange-500 border-gray-300">
                                    <span class="group-hover:text-orange-500 transition font-medium">{{ $cat }}</span>
                                </label>
                                @endforeach
                            </div>
                        </form>
                    </div>

                    <div class="bg-[#0f2d50] p-6 rounded-[2rem] text-white relative overflow-hidden shadow-xl">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-500/10 rounded-full blur-2xl"></div>
                        <span class="inline-block bg-orange-500 text-[9px] font-bold px-3 py-1 rounded-full uppercase mb-4 tracking-wider">Top Partner</span>
                        <div class="flex items-center gap-4 mb-4">
                            <img src="https://i.pravatar.cc/150?u=kevin" class="w-12 h-12 rounded-xl border-2 border-white/20 object-cover">
                            <div>
                                <h4 class="font-bold text-sm">Kevin Setiawan</h4>
                                <p class="text-[10px] text-orange-400 font-bold">★ 5.0 (Smart Home)</p>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 mb-4 leading-relaxed">Spesialis instalasi teknologi pintar untuk rumah modern di Tangerang.</p>
                        <button class="w-full bg-orange-500 hover:bg-orange-600 py-3 rounded-xl font-bold text-[10px] transition shadow-lg shadow-orange-500/20 uppercase tracking-wider">Lihat Portofolio</button>
                    </div>
                </aside>

                <main class="w-full lg:w-3/4">
                    <div class="flex justify-between items-center mb-6">
                        <p class="text-gray-500 font-bold text-sm">Menampilkan {{ $tukangs->total() }} Teknisi Tersedia</p>
                        <div class="flex bg-white rounded-lg shadow-sm p-1 border border-gray-100">
                            <button class="p-2 text-orange-500 bg-orange-50 rounded-md"><i class="fas fa-th-large"></i></button>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
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

                            <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 {{ !$isAvailable ? 'opacity-65 grayscale-[30%] bg-gray-50/30' : '' }}">
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
                                    <button class="text-gray-200 hover:text-red-500 transition"><i class="fas fa-heart"></i></button>
                                </div>

                                <div class="mt-4">
                                    <p class="text-xs text-gray-500 italic">"{{ $tukang->specialty }}"</p>
                                </div>

                                <div class="mt-8 flex justify-between items-end border-t border-dashed border-gray-100 pt-4">
                                    @if($isAvailable)
                                        <div>
                                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Status Mitra</p>
                                            <div class="flex items-center gap-1">
                                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                                <p class="text-xs font-bold text-gray-700">Tersedia Sekarang</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('tukang.show', $tukang->id) }}" 
                                           class="bg-[#0f2d50] text-white px-6 py-2.5 rounded-2xl font-bold text-xs hover:bg-orange-500 transition shadow-lg shadow-blue-100 whitespace-nowrap">
                                                Lihat Profil
                                        </a>
                                    @else
                                        <div>
                                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Status Mitra</p>
                                            <div class="flex items-center gap-1">
                                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                                <p class="text-xs font-bold text-red-500">Sedang Istirahat / Tutup</p>
                                            </div>
                                        </div>
                                        <button disabled 
                                                class="bg-gray-150 text-gray-400 px-6 py-2.5 rounded-2xl font-bold text-xs cursor-not-allowed whitespace-nowrap">
                                                Tidak Tersedia
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-20 text-center">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                                    <i class="fas fa-user-slash text-2xl"></i>
                                </div>
                                <h3 class="font-bold text-gray-800">Teknisi Tidak Ditemukan</h3>
                                <p class="text-xs text-gray-400">
                                    Maaf, tidak ada teknisi yang cocok dengan filter atau kriteria wilayah 
                                    <strong>{{ request('city', 'tersebut') }}</strong>.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-12 flex justify-center">
                        {{ $tukangs->links() }}
                    </div>
                </main>
            </div>
        </div>

        @include('order.partials.modals')

    </div> 
</x-app-layout>

<style>
    [x-cloak] { display: none !important; }
</style>