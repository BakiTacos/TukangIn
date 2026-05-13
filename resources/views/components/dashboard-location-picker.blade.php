@props(['allCities', 'user'])

<div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
    
    <div class="border-b border-gray-50 pb-4 mb-6">
        <h4 class="text-base font-black text-[#0f2d50] uppercase tracking-wider flex items-center gap-2">
            <i class="fas fa-map-marked-alt text-orange-500 text-lg"></i> Wilayah Pangkalan
        </h4>
        <p class="text-[10px] text-gray-400 mt-1">Pilih kota domisili operasional Anda untuk mencocokkan orderan terdekat.</p>
    </div>

    <form action="{{ route('tukang.profile.update-location') }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="space-y-1.5">
            <label class="block text-[9px] font-bold uppercase text-gray-400 tracking-wider">Kota / Kabupaten Operasional</label>
            <div class="relative">
                <select name="city_data" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3.5 px-4 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent appearance-none cursor-pointer">
                    <option value="">-- Pilih Kota / Kabupaten Anda --</option>
                    
                    @foreach($allCities as $city)
                        @php
                            // Format value gabungan untuk dikirim ke backend
                            $valueString = $city['name'] . '|' . $city['province'];
                            // Cek apakah kota ini yang sedang dipilih oleh tukang saat ini
                            $isSelected = old('city', $user->city ?? '') === $city['name'];
                        @endphp
                        <option value="{{ $valueString }}" {{ $isSelected ? 'selected' : '' }}>
                            {{ $city['name'] }} ({{ $city['province'] }})
                        </option>
                    @endforeach
                    
                </select>
                <div class="absolute right-4 top-4 text-gray-400 pointer-events-none text-xs">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </div>

        <button type="submit" 
                class="w-full bg-[#0f2d50] hover:bg-orange-500 text-white py-3.5 rounded-2xl font-bold text-xs uppercase tracking-wider transition shadow-lg shadow-blue-100/50 mt-2 flex items-center justify-center gap-2">
            Simpan Wilayah Kota <i class="fas fa-save"></i>
        </button>
    </form>
</div>