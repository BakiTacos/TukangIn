<script>
    window.dashboardCitiesMap = @json($citiesMap);
</script>

<div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-6 mt-6"
     x-data="{
        provinces: @json($provinces),
        citiesMap: window.dashboardCitiesMap || {},
        selectedProvince: '{{ old('province', $user->province ?? '') }}',
        selectedCity: '{{ old('city', $user->city ?? '') }}',
        cities: [],

        // Fungsi otomatis untuk memperbarui list kota ketika provinsi berubah
        updateCities() {
            this.cities = this.citiesMap[this.selectedProvince] || [];
            // Jika provinsi diganti dan kota lama tidak ada di provinsi baru, reset kota pilihan
            if (!this.cities.includes(this.selectedCity)) {
                this.selectedCity = '';
            }
        },

        init() {
            // Jalankan mapping kota pertama kali saat halaman dimuat
            if (this.selectedProvince) {
                this.cities = this.citiesMap[this.selectedProvince] || [];
            }
        }
     }">
    
    <div class="border-b border-gray-100 pb-3">
        <h3 class="text-sm font-black text-[#0f2d50] uppercase tracking-wider flex items-center gap-2">
            <i class="fas fa-map-marked-alt text-orange-500"></i> Wilayah Operasional Kerja
        </h3>
        <p class="text-[10px] text-gray-400 mt-1">Tentukan lokasi Anda agar sistem dapat mencocokkan pesanan pelanggan terdekat dengan presisi.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-2">
            <label class="block text-[10px] font-bold uppercase text-gray-450 tracking-wider">Provinsi Tempat Anda Standby</label>
            <div class="relative">
                <select name="province" 
                        x-model="selectedProvince" 
                        @change="updateCities()" 
                        required
                        class="w-full bg-gray-50 border border-gray-150 rounded-2xl py-4 px-5 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent appearance-none cursor-pointer">
                    <option value="">-- Pilih Provinsi --</option>
                    <template x-for="prov in provinces" :key="prov">
                        <option :value="prov" x-text="prov" :selected="selectedProvince === prov"></option>
                    </template>
                </select>
                <div class="absolute right-5 top-4.5 text-gray-400 pointer-events-none text-xs">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            @error('province') <p class="text-[10px] font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="space-y-2">
            <label class="block text-[10px] font-bold uppercase text-gray-450 tracking-wider">Kota / Kabupaten</label>
            <div class="relative">
                <select name="city" 
                        x-model="selectedCity" 
                        :disabled="!selectedProvince"
                        required
                        class="w-full bg-gray-50 border border-gray-150 rounded-2xl py-4 px-5 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                    <option value="">-- Pilih Kota / Kabupaten --</option>
                    <template x-for="city in cities" :key="city">
                        <option :value="city" x-text="city" :selected="selectedCity === city"></option>
                    </template>
                </select>
                <div class="absolute right-5 top-4.5 text-gray-400 pointer-events-none text-xs">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            @error('city') <p class="text-[10px] font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>
</div>