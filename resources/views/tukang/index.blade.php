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
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-4xl font-bold mb-2">Mitra Teknisi</h1>
                        <p class="text-gray-300">Temukan tenaga ahli profesional yang siap membantu perbaikan rumah Anda.</p>
                    </div>
                    <div class="relative w-full mb-6" 
     x-data="{ 
        open: false,
        selectedProvince: '{{ request('province', '') }}',
        selectedCity: '{{ request('city', '') }}',
        hoveredProvince: '{{ request('province', '') }}',
        cities: [],
        loading: false,

        // Fungsi fetch data kota dari Supabase via API Laravel
        async fetchCities(province) {
            if (this.hoveredProvince === province && this.cities.length > 0) return;
            this.hoveredProvince = province;
            this.loading = true;
            try {
                let response = await fetch(`/api/cities?province=${encodeURIComponent(province)}`);
                this.cities = await response.json();
            } catch (error) {
                console.error('Gagal mengambil data kota:', error);
            } finally {
                this.loading = false;
            }
        },

        // Aksi ketika Kota dipilih
        selectLocation(city) {
            this.selectedProvince = this.hoveredProvince;
            this.selectedCity = city;
            this.open = false;
            
            // Masukkan parameter ke URL dan submit halaman
            let url = new URL(window.location.href);
            url.searchParams.set('province', this.selectedProvince);
            url.searchParams.set('city', this.selectedCity);
            window.location.href = url.toString();
        },

        // Reset Filter Lokasi
        resetFilter() {
            this.selectedProvince = '';
            this.selectedCity = '';
            let url = new URL(window.location.href);
            url.searchParams.delete('province');
            url.searchParams.delete('city');
            window.location.href = url.toString();
        }
     }"
     @click.away="open = false">

    <button type="button" 
            @click="open = !open"
            class="w-full bg-gray-50 border border-gray-150 rounded-2xl py-4 px-5 text-xs font-bold text-gray-700 flex justify-between items-center hover:bg-gray-100/60 transition">
        <span class="flex items-center gap-2">
            <i class="fas fa-map-marker-alt text-orange-500"></i>
            <span x-text="selectedCity ? `${selectedCity}, ${selectedProvince}` : 'Pilih Wilayah Jangkauan'"></span>
        </span>
        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="absolute left-0 mt-2 w-full md:w-[500px] bg-white border border-gray-100 rounded-[2rem] shadow-xl z-50 overflow-hidden flex flex-col md:flex-row h-80"
         x-cloak>
        
        <div class="w-full md:w-1/2 border-r border-gray-50 overflow-y-auto p-4 space-y-1 bg-gray-50/50">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-3 mb-2">Provinsi</p>
            <button type="button" @click="resetFilter()"
                    class="w-full text-left text-xs font-bold px-3 py-2.5 rounded-xl hover:bg-orange-50 hover:text-orange-600 transition flex items-center justify-between">
                <span>Semua Wilayah</span>
            </button>
            @foreach($provinces as $prov)
                <button type="button" 
                        @mouseenter="fetchCities('{{ $prov }}')"
                        class="w-full text-left text-xs font-bold px-3 py-2.5 rounded-xl transition flex items-center justify-between"
                        :class="hoveredProvince === '{{ $prov }}' ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-100/50'">
                    <span>{{ $prov }}</span>
                    <i class="fas fa-chevron-right text-[10px] opacity-50"></i>
                </button>
            @endforeach
        </div>

        <div class="w-full md:w-1/2 overflow-y-auto p-4 space-y-1 bg-white">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-3 mb-2 flex items-center justify-between">
                <span>Kota / Kabupaten</span>
                <span x-show="loading" class="text-orange-500 animate-spin text-[10px]"><i class="fas fa-spinner"></i></span>
            </p>

            <template x-if="cities.length === 0 && !loading">
                <div class="text-center py-12 text-gray-400">
                    <i class="fas fa-map-marked text-xl mb-2 text-gray-300"></i>
                    <p class="text-[10px] font-semibold leading-relaxed">Arahkan kursor ke provinsi<br>untuk melihat daftar kota.</p>
                </div>
            </template>

            <div class="space-y-1">
                <template x-for="city in cities" :key="city">
                    <button type="button" 
                            @click="selectLocation(city)"
                            class="w-full text-left text-xs font-bold px-3 py-2.5 rounded-xl text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-orange-500 rounded-full"></span>
                        <span x-text="city"></span>
                    </button>
                </template>
            </div>
        </div>

    </div>
</div>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-6 -mt-16">
            <div class="flex flex-col lg:flex-row gap-8">
                <aside class="w-full lg:w-1/4 space-y-6">
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                        <form action="{{ route('tukang.index') }}" method="GET">
                            <h4 class="font-bold text-gray-800 mb-4 border-b pb-2 text-sm">CARI NAMA</h4>
                            <div class="relative mb-6">
                                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400 text-xs"></i>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari teknisi..." 
                                       class="w-full bg-gray-50 border-none rounded-xl py-3 pl-10 text-xs focus:ring-2 focus:ring-orange-500">
                            </div>

                            <h4 class="font-bold text-gray-800 mb-4 border-b pb-2 text-sm">KATEGORI AHLI</h4>
                            <div class="space-y-3 text-xs text-gray-600">
                                @php
                                    $categories = ['AC & Pendingin', 'Plumbing', 'Listrik', 'Konstruksi', 'Finishing', 'Interior'];
                                @endphp
                                
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="radio" name="category" value="" onchange="this.form.submit()" {{ !request('category') ? 'checked' : '' }} class="text-orange-500 focus:ring-orange-500">
                                    <span class="group-hover:text-orange-500 transition">Semua Keahlian</span>
                                </label>

                                @foreach($categories as $cat)
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="radio" name="category" value="{{ $cat }}" onchange="this.form.submit()" {{ request('category') == $cat ? 'checked' : '' }} class="text-orange-500 focus:ring-orange-500">
                                    <span class="group-hover:text-orange-500 transition">{{ $cat }}</span>
                                </label>
                                @endforeach
                            </div>
                        </form>
                    </div>

                    <div class="bg-[#0f2d50] p-6 rounded-[2rem] text-white relative overflow-hidden shadow-xl">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-500/10 rounded-full blur-2xl"></div>
                        <span class="inline-block bg-orange-500 text-[9px] font-bold px-3 py-1 rounded-full uppercase mb-4">Top Partner</span>
                        <div class="flex items-center gap-4 mb-4">
                            <img src="https://i.pravatar.cc/150?u=kevin" class="w-12 h-12 rounded-xl border-2 border-white/20">
                            <div>
                                <h4 class="font-bold text-sm">Kevin Setiawan</h4>
                                <p class="text-[10px] text-orange-400 font-bold">★ 5.0 (Smart Home)</p>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 mb-4 leading-relaxed">Spesialis instalasi teknologi pintar untuk rumah modern di Tangerang.</p>
                        <button class="w-full bg-orange-500 hover:bg-orange-600 py-3 rounded-xl font-bold text-[10px] transition shadow-lg shadow-orange-500/20">Lihat Portofolio</button>
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
                        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center space-x-4">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($tukang->name) }}&background=0f2d50&color=fff" 
                                         class="w-16 h-16 rounded-2xl object-cover shadow-inner">
                                    <div>
                                        <span class="text-[9px] font-bold text-orange-500 uppercase tracking-widest">{{ $tukang->category }}</span>
                                        <h4 class="font-bold text-gray-800 text-lg">{{ $tukang->name }}</h4>
                                        <p class="text-[11px] font-bold text-yellow-500">
    <i class="fas fa-star mr-1"></i> 
    {{ $tukang->reviews_avg_rating ? number_format($tukang->reviews_avg_rating, 1) : '5.0' }} 
    
    <span class="text-gray-400 font-normal ml-1">
        ({{ $tukang->completed_orders_count }} Order Selesai)
    </span>
</p>
                                    </div>
                                </div>
                                <button class="text-gray-200 hover:text-red-500 transition"><i class="fas fa-heart"></i></button>
                            </div>

                            <div class="mt-4">
                                <p class="text-xs text-gray-500 italic">"{{ $tukang->specialty }}"</p>
                            </div>

                            <div class="mt-8 flex justify-between items-end border-t border-dashed border-gray-100 pt-4">
                                <div>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Status Mitra</p>
                                    <div class="flex items-center gap-1">
                                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                        <p class="text-xs font-bold text-gray-700">Tersedia Sekarang</p>
                                    </div>
                                </div>
                                <a href="{{ route('tukang.show', $tukang->id) }}" 
                                    class="bg-[#0f2d50] text-white px-6 py-2.5 rounded-2xl font-bold text-xs hover:bg-orange-500 transition shadow-lg shadow-blue-100">
                                        Lihat Profil
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full py-20 text-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                                <i class="fas fa-user-slash text-2xl"></i>
                            </div>
                            <h3 class="font-bold text-gray-800">Teknisi Tidak Ditemukan</h3>
                            <p class="text-xs text-gray-400">Coba ubah filter atau kata kunci pencarian Anda.</p>
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

    </div> </x-app-layout>