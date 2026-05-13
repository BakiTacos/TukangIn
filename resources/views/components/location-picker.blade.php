@props(['provinces', 'citiesJson'])

<script>
    window.citiesMap = {!! $citiesJson !!};
</script>

<div class="relative w-full mb-8" 
     x-data="{ 
        open: false,
        selectedProvince: '{{ request('province', '') }}',
        selectedCity: '{{ request('city', '') }}',
        hoveredProvince: '{{ request('province', '') }}',
        
        citiesMap: window.citiesMap || {}, 
        cities: [],

        init() {
            if (this.selectedProvince) {
                this.cities = this.citiesMap[this.selectedProvince] || [];
            }
        },

        selectProvince(province) {
            this.hoveredProvince = province;
            this.cities = this.citiesMap[province] || [];
        },

        selectLocation(city) {
            this.selectedProvince = this.hoveredProvince;
            this.selectedCity = city;
            this.open = false;
            
            let url = new URL(window.location.href);
            url.searchParams.set('province', this.selectedProvince);
            url.searchParams.set('city', this.selectedCity);
            window.location.href = url.toString();
        },

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
         class="absolute left-0 mt-2 w-full md:w-[550px] bg-white border border-gray-100 rounded-[2rem] md:rounded-[2.5rem] shadow-2xl z-50 overflow-hidden flex flex-col md:flex-row h-[450px] md:h-80"
         x-cloak>
        
        <div class="w-full md:w-1/2 border-b md:border-b-0 md:border-r border-gray-100 overflow-y-auto p-4 md:p-5 space-y-1 bg-gray-50/50 h-1/2 md:h-full">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-3 mb-2">Pilih Provinsi</p>
            <button type="button" @click="resetFilter()"
                    class="w-full text-left text-xs font-bold px-3 py-2.5 rounded-xl hover:bg-orange-50 hover:text-orange-600 transition flex items-center justify-between">
                <span>Semua Wilayah</span>
            </button>
            @foreach($provinces as $prov)
                <button type="button" 
                        @mouseenter="selectProvince('{{ $prov }}')"
                        @click="selectProvince('{{ $prov }}')" 
                        class="w-full text-left text-xs font-bold px-3 py-2.5 rounded-xl transition flex items-center justify-between"
                        :class="hoveredProvince === '{{ $prov }}' ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-100/50'">
                    <span>{{ $prov }}</span>
                    <i class="fas fa-chevron-right text-[10px] opacity-50"></i>
                </button>
            @endforeach
        </div>

        <div class="w-full md:w-1/2 overflow-y-auto p-4 md:p-5 space-y-1 bg-white h-1/2 md:h-full">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-3 mb-2">Kota / Kabupaten</p>

            <div x-show="cities.length === 0" class="text-center py-8 md:py-12 text-gray-400">
                <i class="fas fa-map-marked text-xl mb-2 text-gray-300"></i>
                <p class="text-[10px] font-semibold leading-relaxed">Pilih atau arahkan kursor ke provinsi<br>untuk melihat daftar kota.</p>
            </div>

            <div class="space-y-1" x-show="cities.length > 0">
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