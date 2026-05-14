<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12" x-data="{ 
        selectedProvince: '', 
        citiesMap: {{ json_encode($citiesMap) }},
        get filteredCities() {
            return this.selectedProvince ? this.citiesMap[this.selectedProvince] : [];
        }
    }">
        <div class="container mx-auto px-6 max-w-4xl">
            <div class="bg-white rounded-[3rem] shadow-xl overflow-hidden border border-gray-100">
                <div class="bg-[#0f2d50] p-12 text-white">
                    <h2 class="text-3xl font-black uppercase tracking-tighter italic">Join Mitra TUKANG.IN</h2>
                    <p class="text-blue-200 text-xs mt-2 font-bold tracking-widest uppercase">Pendaftaran Tenaga Ahli Profesional</p>
                </div>

                <form method="POST" action="{{ route('register.tukang.post') }}" class="p-10 md:p-14 space-y-10">
                    @csrf

                    <!-- SEKSI 1: IDENTITAS -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-2 border-b border-gray-100 pb-2">
                            <i class="fas fa-id-card text-orange-500"></i>
                            <h4 class="text-[10px] font-black text-[#0f2d50] uppercase tracking-widest">Informasi Identitas Kependudukan</h4>
                        </div>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase">Nama Lengkap (Sesuai KTP)</label>
                                <input type="text" name="name" required class="w-full bg-gray-50 border-gray-200 rounded-2xl py-3.5 px-4 text-xs font-bold focus:ring-2 focus:ring-orange-500">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase">NIK (16 Digit)</label>
                                <input type="text" name="nik" maxlength="16" required class="w-full bg-gray-50 border-gray-200 rounded-2xl py-3.5 px-4 text-xs font-bold focus:ring-2 focus:ring-orange-500">
                            </div>
                        </div>
                    </div>

                    <!-- SEKSI 2: KATEGORI KERJA -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-2 border-b border-gray-100 pb-2">
                            <i class="fas fa-tools text-orange-500"></i>
                            <h4 class="text-[10px] font-black text-[#0f2d50] uppercase tracking-widest">Bidang Keahlian Utama</h4>
                        </div>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase">Kategori Layanan</label>
                                <select name="category" required class="w-full bg-gray-50 border-gray-200 rounded-2xl py-3.5 px-4 text-xs font-bold focus:ring-2 focus:ring-orange-500">
                                    <option value="">-- Pilih Kategori Matching --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}">{{ strtoupper($cat) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase">Nomor WhatsApp Aktif</label>
                                <input type="text" name="phone" required class="w-full bg-gray-50 border-gray-200 rounded-2xl py-3.5 px-4 text-xs font-bold focus:ring-2 focus:ring-orange-500">
                            </div>
                        </div>
                    </div>

                    <!-- SEKSI 3: ALAMAT OPERASIONAL -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-2 border-b border-gray-100 pb-2">
                            <i class="fas fa-map-marker-alt text-orange-500"></i>
                            <h4 class="text-[10px] font-black text-[#0f2d50] uppercase tracking-widest">Domisili Operasional Mitra</h4>
                        </div>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase">Provinsi</label>
                                <select name="province" x-model="selectedProvince" required class="w-full bg-gray-50 border-gray-200 rounded-2xl py-3.5 px-4 text-xs font-bold focus:ring-2 focus:ring-orange-500">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach($provinces as $p)
                                        <option value="{{ $p }}">{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase">Kota / Kabupaten</label>
                                <select name="city" required :disabled="!selectedProvince" class="w-full bg-gray-50 border-gray-200 rounded-2xl py-3.5 px-4 text-xs font-bold focus:ring-2 focus:ring-orange-500">
                                    <option value="">-- Pilih Kota --</option>
                                    <template x-for="city in filteredCities" :key="city">
                                        <option :value="city" x-text="city"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase">Alamat Lengkap Workshop / Rumah</label>
                            <textarea name="address" rows="3" required placeholder="Sebutkan Jalan, Blok, No Rumah..." class="w-full bg-gray-50 border-gray-200 rounded-2xl py-3.5 px-4 text-xs font-medium focus:ring-2 focus:ring-orange-500"></textarea>
                        </div>
                    </div>

                    <!-- SEKSI 4: LOGIN -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-2 border-b border-gray-100 pb-2">
                            <i class="fas fa-lock text-orange-500"></i>
                            <h4 class="text-[10px] font-black text-[#0f2d50] uppercase tracking-widest">Kredensial Akses Akun</h4>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase">Email (Eks: nama@tukangin.com)</label>
                            <input type="email" name="email" required class="w-full bg-gray-50 border-gray-200 rounded-2xl py-3.5 px-4 text-xs font-bold focus:ring-2 focus:ring-orange-500">
                        </div>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase">Kata Sandi</label>
                                <input type="password" name="password" required class="w-full bg-gray-50 border-gray-200 rounded-2xl py-3.5 px-4 text-xs">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase">Konfirmasi Sandi</label>
                                <input type="password" name="password_confirmation" required class="w-full bg-gray-50 border-gray-200 rounded-2xl py-3.5 px-4 text-xs">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#0f2d50] hover:bg-orange-500 text-white py-5 rounded-[2rem] font-bold text-xs uppercase tracking-[0.2em] transition shadow-2xl shadow-blue-200">
                        Daftar & Aktivasi Akun Mitra <i class="fas fa-check-double ml-1"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>