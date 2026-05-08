<x-app-layout>
    <div class="container mx-auto px-6 py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <div class="lg:w-2/3 space-y-8">
                
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center md:items-start gap-8">
                    <div class="relative">
                        <img src="{{ $tukang->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($tukang->name).'&background=0f2d50&color=fff&size=200' }}" 
                             class="w-40 h-40 rounded-3xl object-cover shadow-lg">
                        <div class="absolute -bottom-2 -right-2 bg-yellow-500 text-white p-2 rounded-xl shadow-lg">
                            <i class="fas fa-certificate"></i>
                        </div>
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-2 text-center md:text-left">
                            <h1 class="text-3xl font-extrabold text-[#0f2d50] w-full md:w-auto">{{ $tukang->name }}</h1>
                            <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Tersedia</span>
                            <span class="bg-gray-50 text-gray-400 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Jabodetabek</span>
                        </div>
                        <p class="text-gray-400 font-medium mb-6 uppercase text-xs tracking-widest text-center md:text-left">{{ $tukang->specialty ?? 'Spesialis ' . $tukang->category }}</p>
                        
                        <div class="grid grid-cols-3 gap-4 border-t pt-6 text-center">
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Rating</p>
                                <p class="text-lg font-bold text-gray-800">
                                    <i class="fas fa-star text-yellow-500 mr-1"></i>{{ $tukang->rating ?? '5.0' }} 
                                    <span class="text-[10px] text-gray-400 font-normal block md:inline">({{ $tukang->reviews_count ?? rand(50,200) }} Ulasan)</span>
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Pengalaman</p>
                                <p class="text-lg font-bold text-gray-800">{{ $tukang->experience_years ?? rand(5,15) }}+ Tahun</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Proyek Selesai</p>
                                <p class="text-lg font-bold text-gray-800">{{ $tukang->total_order ?? '100' }}</p>
                            </div>
                        </div>

                        <div class="flex justify-center md:justify-start gap-6 mt-8">
                            <button class="text-xs font-bold text-gray-400 hover:text-[#0f2d50] transition flex items-center gap-2">
                                <i class="fas fa-share-alt"></i> Bagikan
                            </button>
                            <button class="text-xs font-bold text-gray-400 hover:text-red-500 transition flex items-center gap-2">
                                <i class="far fa-heart"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold text-[#0f2d50] mb-6">Tentang {{ explode(' ', $tukang->name)[0] }}</h3>
                    <p class="text-gray-500 leading-relaxed mb-8">
                        {{ $tukang->bio ?? "Seorang ahli di bidang $tukang->category bersertifikat dengan dedikasi lebih dari satu dekade dalam melayani kebutuhan infrastruktur rumah tangga dan industri kecil. Spesialisasi saya mencakup pengerjaan profesional, audit keamanan sistem, hingga perbaikan darurat dengan standar keselamatan tinggi." }}
                    </p>
                    
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Keahlian Utama</p>
                    <div class="flex flex-wrap gap-2">
                        @if($tukang->skills && is_array($tukang->skills))
                            @foreach($tukang->skills as $skill)
                                <span class="bg-gray-50 text-gray-600 text-[10px] font-bold px-4 py-2 rounded-xl border border-gray-100">{{ $skill }}</span>
                            @endforeach
                        @else
                            @php $default_skills = ['Sertifikasi Pro', 'Respon Cepat', 'Garansi Kerja', 'Alat Modern', 'SNI Standard']; @endphp
                            @foreach($default_skills as $skill)
                                <span class="bg-gray-50 text-gray-600 text-[10px] font-bold px-4 py-2 rounded-xl border border-gray-100">{{ $skill }}</span>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex justify-between items-center px-2">
                        <h3 class="text-xl font-bold text-[#0f2d50]">Galeri Pekerjaan Terakhir</h3>
                        <a href="#" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua Proyek</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <img src="https://images.unsplash.com/photo-1581094794329-c8112a89af12?q=80&w=2070" class="w-full h-80 object-cover rounded-[2rem] shadow-sm">
                        </div>
                        <div class="grid grid-rows-2 gap-4">
                            <img src="https://images.unsplash.com/photo-1595841696677-6489ff3f8cd1?q=80&w=1974" class="w-full h-[152px] object-cover rounded-[2rem] shadow-sm">
                            <img src="https://images.unsplash.com/photo-1621905251918-48416bd8575a?q=80&w=2069" class="w-full h-[152px] object-cover rounded-[2rem] shadow-sm">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-10">
                        <h3 class="text-xl font-bold text-[#0f2d50]">Ulasan Pelanggan</h3>
                        <div class="text-xs font-bold text-gray-400">Urutkan: <span class="text-[#0f2d50] cursor-pointer">Terbaru <i class="fas fa-chevron-down ml-1"></i></span></div>
                    </div>
                    
                    <div class="space-y-8">
                        @forelse($tukang->reviews ?? [] as $review)
                            <div class="border-b border-gray-50 pb-8 last:border-0">
                                <div class="flex justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-[10px] font-bold text-gray-400 uppercase">
                                            {{ substr($review->user_name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">{{ $review->user_name }}</p>
                                            <p class="text-[10px] text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="text-yellow-400 text-[10px]">
                                        @for($i=0; $i<$review->rating; $i++) <i class="fas fa-star"></i> @endfor
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 leading-relaxed italic">"{{ $review->comment }}"</p>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <i class="fas fa-comment-slash text-gray-200 text-4xl mb-4 block"></i>
                                <p class="text-xs text-gray-400">Belum ada ulasan untuk teknisi ini.</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <button class="w-full mt-6 py-4 border-2 border-dashed border-gray-100 rounded-2xl text-xs font-bold text-gray-400 hover:bg-gray-50 transition">Muat Ulasan Lainnya</button>
                </div>
            </div>

            <div class="lg:w-1/3">
                <div class="sticky top-24 space-y-6">
                    
                    <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden"
                         x-data="{ 
                            selectedServiceId: '{{ $service->id ?? '' }}',
                            hasService: {{ $service ? 'true' : 'false' }}
                         }">
                        <div class="h-2 bg-yellow-400"></div>
                        <div class="p-8">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Biaya Kunjungan</p>
                            <div class="flex items-baseline gap-2 mb-6">
                                <h2 class="text-3xl font-extrabold text-[#0f2d50]">Rp {{ number_format($tukang->price_kunjungan ?? 75000, 0, ',', '.') }}</h2>
                                <span class="text-xs text-gray-400">/ Kunjungan</span>
                            </div>
                            
                            <div class="space-y-4 mb-6">
                                <div class="flex items-center gap-3 text-xs font-bold text-gray-700">
                                    <i class="fas fa-check-circle text-orange-500"></i> Estimasi Transparan
                                </div>
                                <div class="flex items-center gap-3 text-xs font-bold text-gray-700">
                                    <i class="fas fa-check-circle text-orange-500"></i> Garansi 30 Hari
                                </div>
                            </div>

                            <div class="border-t border-gray-50 pt-6 mb-6">
                                <div x-show="hasService" class="space-y-2">
                                    <div class="bg-orange-50/50 p-4 rounded-2xl border border-orange-100 flex items-center gap-3">
                                        <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center text-white text-xs shrink-0">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                        <div>
                                            <h5 class="text-[11px] font-bold text-[#0f2d50] uppercase tracking-wider">Layanan Terpilih</h5>
                                            <p class="text-xs text-gray-600 font-medium mt-0.5">
                                                {{ $service->title ?? '' }} (Rp {{ number_format($service->price ?? 0, 0, ',', '.') }})
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div x-show="!hasService" class="space-y-3">
                                    <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 flex items-start gap-3 mb-2">
                                        <i class="fas fa-exclamation-circle text-blue-500 mt-0.5 text-xs shrink-0"></i>
                                        <div>
                                            <h5 class="text-[11px] font-bold text-[#0f2d50] uppercase tracking-wider">Pilih Layanan</h5>
                                            <p class="text-[10px] text-gray-500 leading-relaxed mt-0.5">
                                                Teknisi ini adalah spesialis <strong class="text-orange-500">{{ $tukang->category }}</strong>. Silakan pilih jenis perbaikan:
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="relative">
                                        <select x-model="selectedServiceId" 
                                                class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-4 px-5 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent appearance-none cursor-pointer">
                                            <option value="">-- Pilih Layanan {{ $tukang->category }} --</option>
                                            @foreach($availableServices as $as)
                                                <option value="{{ $as->id }}">
                                                    {{ $as->title }} (Rp {{ number_format($as->price, 0, ',', '.') }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-gray-400">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <a :href="'/checkout/' + selectedServiceId + '/{{ $tukang->id }}'" 
                                   x-show="selectedServiceId !== ''"
                                   class="w-full bg-[#e67e22] hover:bg-[#d35400] text-white py-4 rounded-2xl font-bold shadow-lg shadow-orange-200 transition transform hover:-translate-y-1 block text-center uppercase tracking-widest text-sm">
                                    PESAN SEKARANG
                                </a>

                                <button x-show="selectedServiceId === ''"
                                        class="w-full bg-gray-100 text-gray-400 py-4 rounded-2xl font-bold block text-center uppercase tracking-widest text-sm cursor-not-allowed" 
                                        disabled>
                                    PILIH LAYANAN DULU
                                </button>
                                
                                <button class="w-full bg-white border-2 border-gray-100 text-gray-600 py-4 rounded-2xl font-bold flex items-center justify-center gap-2 hover:bg-gray-50 transition text-sm">
                                    <i class="far fa-comment-dots"></i> Tanya Dulu
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#0f2d50] rounded-[2.5rem] p-8 text-white shadow-lg">
                        <div class="flex items-center gap-3 mb-6">
                            <i class="far fa-calendar-alt text-yellow-500 text-xl"></i>
                            <h4 class="font-bold text-sm">Jadwal Tersedia</h4>
                        </div>
                        <div class="space-y-4 text-[11px]">
                            @if($tukang->schedule && is_array($tukang->schedule))
                                @foreach($tukang->schedule as $hari => $jam)
                                    <div class="flex justify-between border-b border-white/10 pb-2">
                                        <span class="text-gray-400">{{ $hari }}</span>
                                        <span class="font-bold {{ strtolower($jam) == 'libur' ? 'text-red-400' : '' }}">{{ $jam }}</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex justify-between border-b border-white/10 pb-2">
                                    <span class="text-gray-400">Senin - Jumat</span>
                                    <span class="font-bold">08:00 - 18:00</span>
                                </div>
                                <div class="flex justify-between border-b border-white/10 pb-2">
                                    <span class="text-gray-400">Sabtu</span>
                                    <span class="font-bold">09:00 - 15:00</span>
                                </div>
                                <div class="flex justify-between text-yellow-500">
                                    <span>Minggu</span>
                                    <span class="font-bold uppercase">Libur</span>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>