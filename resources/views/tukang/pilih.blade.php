<x-app-layout>
    <div class="bg-[#0f2d50] pb-32 pt-12">
        <div class="container mx-auto px-6 text-white">
            <nav class="text-xs text-gray-400 mb-4 uppercase tracking-widest">
                Home > Layanan > {{ $service->title }} > Pilih Teknisi
            </nav>
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold mb-2">Pilih Teknisi Terbaik</h1>
                    <p class="text-gray-300">Kami menemukan ahli profesional untuk layanan <strong>{{ $service->title }}</strong> Anda.</p>
                </div>
                <div class="hidden md:flex bg-white/10 p-4 rounded-2xl flex items-center space-x-6">
                    <div class="text-xs text-right">
                        <p class="text-gray-400 uppercase">Kategori Layanan</p>
                        <p class="font-bold text-white">{{ $service->category }}</p>
                    </div>
                    <div class="w-px h-8 bg-white/20"></div>
                    <div class="bg-orange-500 p-2 rounded-xl">
                        <i class="fas {{ $service->icon }} text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 -mt-16">
        <div class="flex flex-col lg:flex-row gap-8">
            <aside class="w-full lg:w-1/4 space-y-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-4 border-b pb-2 text-sm uppercase">Detail Pesanan</h4>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Layanan</p>
                            <p class="text-sm font-bold text-[#0f2d50]">{{ $service->title }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Estimasi Biaya</p>
                            <p class="text-lg font-extrabold text-orange-500">
                                Rp {{ number_format((int) str_replace('.', '', $service->price), 0, ',', '.') }}
                            </p>
                        </div>
                        <a href="{{ route('services.show', $service->slug) }}" class="block text-center text-[10px] font-bold text-gray-400 hover:text-[#0f2d50] transition">
                            <i class="fas fa-chevron-left mr-1"></i> Ubah Detail Layanan
                        </a>
                    </div>
                </div>

                <div class="bg-blue-50 p-6 rounded-[2rem] border border-blue-100 relative overflow-hidden">
                    <i class="fas fa-shield-alt absolute -right-2 -bottom-2 text-blue-200/50 text-6xl"></i>
                    <h4 class="font-bold text-[#0f2d50] text-sm mb-2 italic">Jaminan Keamanan</h4>
                    <p class="text-[10px] text-blue-700 leading-relaxed">
                        Semua teknisi kami telah melewati verifikasi ketat dan sertifikasi keahlian untuk menjamin kualitas pengerjaan.
                    </p>
                </div>
            </aside>

            <main class="w-full lg:w-3/4">
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <p class="text-gray-500 font-bold text-sm">
                        Menampilkan {{ $tukangs->total() }} Teknisi Spesialis {{ $service->category }}
                    </p>
                    <div class="flex bg-white rounded-lg shadow-sm p-1 border border-gray-100">
                        <button class="p-2 text-orange-500 bg-orange-50 rounded-md"><i class="fas fa-th-large"></i></button>
                    </div>
                </div>

                <script>
    window.citiesMap = {!! $citiesJson !!};
</script>

                <div class="relative w-full mb-8" 
                     x-data="{ 
                        open: false,
                        selectedProvince: '{{ request('province', '') }}',
                        selectedCity: '{{ request('city', '') }}',
                        hoveredProvince: '{{ request('province', '') }}',
                        cities: [],
                        loading: false,

                        // Fungsi fetch data kota dari Supabase via API Laravel secara real-time
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
                            
                            // Masukkan parameter ke URL dan muat ulang halaman untuk mem-filter database
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
                     @click.away="open = false"
                     x-init="if (selectedProvince) fetchCities(selectedProvince)">

                    <button type="button" 
                            @click="open = !open"
                            class="w-full bg-white border border-gray-150 rounded-[2rem] py-4 px-6 text-xs font-bold text-gray-700 flex justify-between items-center hover:bg-gray-50 transition shadow-sm">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-orange-500 text-sm"></i>
                            <span x-text="selectedCity ? `${selectedCity}, ${selectedProvince}` : 'Filter Berdasarkan Wilayah Terdekat Anda'"></span>
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
                         class="absolute left-0 mt-2 w-full md:w-[550px] bg-white border border-gray-100 rounded-[2.5rem] shadow-2xl z-50 overflow-hidden flex flex-col md:flex-row h-80"
                         x-cloak>
                        
                        <div class="w-full md:w-1/2 border-r border-gray-50 overflow-y-auto p-5 space-y-1 bg-gray-50/50">
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

                        <div class="w-full md:w-1/2 overflow-y-auto p-5 space-y-1 bg-white">
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
                                    
                                    <p class="text-[11px] font-bold text-yellow-500 flex items-center gap-1 mt-1">
                                        <i class="fas fa-star text-[10px]"></i> 
                                        {{ $tukang->reviews_avg_rating ? number_format($tukang->reviews_avg_rating, 1) : '5.0' }} 
                                        
                                        <span class="text-gray-400 font-normal">
                                            ({{ $tukang->completed_orders_count }} Order Selesai)
                                        </span>
                                    </p>
                                    
                                    @if($tukang->city && $tukang->province)
                                        <p class="text-[9px] text-gray-400 font-bold mt-1.5 flex items-center gap-1">
                                            <i class="fas fa-map-marker-alt text-orange-500 text-[8px]"></i>
                                            {{ $tukang->city }}, {{ $tukang->province }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <p class="text-xs text-gray-500 italic">"{{ $tukang->specialty }}"</p>
                        </div>

                        <div class="mt-8 flex justify-between items-end border-t border-dashed border-gray-100 pt-4">
                            <div>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Ketersediaan</p>
                                <div class="flex items-center gap-1">
                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                    <p class="text-xs font-bold text-gray-700">Aktif & Siap Datang</p>
                                </div>
                            </div>
                            
                            <a href="{{ route('tukang.show', $tukang->id) }}?service_id={{ $service->id }}" 
                            class="block text-center bg-[#0f2d50] text-white py-3 px-6 rounded-2xl font-bold text-sm hover:bg-orange-500 transition shadow-lg shadow-blue-100">
                                Lihat Profil & Pilih
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                            <i class="fas fa-map-marked-alt text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Teknisi Belum Tersedia</h3>
                        <p class="text-xs text-gray-400">
                            Maaf, belum ada teknisi yang tersedia di wilayah 
                            <strong>{{ request('city', 'tersebut') }}</strong> untuk kategori {{ $service->category }}.
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
</x-app-layout>

<style>
    [x-cloak] { display: none !important; }
</style>