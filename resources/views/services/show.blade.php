<x-app-layout>
<div class="bg-[#f8fafc] font-sans text-[#0f2d50]">

    <main class="container mx-auto px-6 py-10">
        <!-- Hero Section -->
        <div class="relative w-full h-[400px] rounded-[2.5rem] overflow-hidden mb-12 shadow-2xl">
            <img src="{{ asset('images/services/' . $service->image) }}" class="w-full h-full object-cover" alt="{{ $service->title }}">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0f2d50]/90 via-[#0f2d50]/40 to-transparent flex flex-col justify-end p-12">
                <div class="flex gap-2 mb-4">
                    <span class="bg-[#e67e22] text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">{{ $service->category }}</span>
                    <span class="bg-blue-500/30 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Perawatan Air</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ $service->title }}</h1>
                <p class="text-gray-200 max-w-xl text-sm leading-relaxed">Layanan profesional dari Tukang.in untuk memastikan setiap detail hunian Anda tetap prima, bersih, dan higienis.</p>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Konten Utama (Kiri) -->
            <div class="lg:w-2/3 space-y-12">
                <!-- Deskripsi -->
                <section>
                    <h2 class="text-xl font-bold mb-6">Deskripsi Layanan</h2>
                    <p class="text-gray-500 leading-relaxed mb-8 text-sm">
                        {{ $service->description }} Teknisi ahli kami dilengkapi dengan peralatan khusus untuk mencapai area yang sulit dijangkau, memastikan pengerjaan menyeluruh tanpa merusak struktur properti Anda.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-2xl flex items-center gap-4 shadow-sm border border-gray-100">
                            <div class="text-orange-500 bg-orange-50 p-3 rounded-xl"><i class="fas fa-certificate"></i></div>
                            <div>
                                <p class="text-xs font-bold">Teknisi Bersertifikat</p>
                                <p class="text-[10px] text-gray-400">Tim berpengalaman dan terlatih.</p>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-2xl flex items-center gap-4 shadow-sm border border-gray-100">
                            <div class="text-orange-500 bg-orange-50 p-3 rounded-xl"><i class="fas fa-bolt"></i></div>
                            <div>
                                <p class="text-xs font-bold">Pengerjaan Cepat</p>
                                <p class="text-[10px] text-gray-400">Estimasi 60-90 menit pengerjaan.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Inklusi Layanan -->
                <section>
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-4">Apa yang termasuk dalam layanan?</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @if($service->inclusions)
                        @foreach($service->inclusions as $index => $item)
                        <div class="{{ $index === 1 ? 'bg-[#0f2d50] text-white shadow-lg' : 'bg-white border border-gray-100 shadow-sm' }} p-6 rounded-[2rem]">
                            <div class="w-10 h-10 {{ $index === 1 ? 'bg-white/10 text-white' : 'bg-orange-100 text-orange-500' }} rounded-xl flex items-center justify-center mb-4">
                                <i class="fas {{ $item['icon'] }}"></i>
                            </div>
                            <h4 class="text-xs font-bold mb-2">{{ $item['title'] }}</h4>
                            <p class="text-[10px] {{ $index === 1 ? 'text-gray-300' : 'text-gray-500' }} leading-relaxed">{{ $item['desc'] }}</p>
                        </div>
                        @endforeach
                    @endif
                </div>
            </section>

                <!-- Galeri -->
                <section>
                    <h2 class="text-xl font-bold mb-6">Galeri Proses Kerja</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="col-span-1 row-span-2 rounded-3xl overflow-hidden shadow-md">
                            <img src="https://images.unsplash.com/photo-1581094794329-c8112a89af12?q=80&w=800" class="w-full h-full object-cover">
                        </div>
                        <div class="rounded-3xl overflow-hidden h-32 shadow-md">
                            <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?q=80&w=800" class="w-full h-full object-cover">
                        </div>
                        <div class="rounded-3xl overflow-hidden h-32 shadow-md">
                            <img src="https://images.unsplash.com/photo-1517646280104-a68da2e63792?q=80&w=800" class="w-full h-full object-cover">
                        </div>
                        <div class="col-span-2 rounded-3xl overflow-hidden h-40 shadow-md">
                            <img src="https://images.unsplash.com/photo-1595841696662-50634515ed0a?q=80&w=800" class="w-full h-full object-cover">
                        </div>
                    </div>
                </section>
            </div>

            <!-- Sidebar Pemesanan (Kanan) -->
            <!-- Bagian Sidebar Pemesanan (Kanan) -->
<div class="lg:w-1/3">
    <div class="sticky top-24 space-y-6">
        <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-[#e67e22] p-6 text-white">
                <p class="text-[10px] uppercase font-bold opacity-80 mb-1">Estimasi Harga</p>
                <div class="flex items-baseline gap-2">
                    {{-- FIX HARGA: Menghapus titik sebelum diformat agar tidak dianggap desimal oleh PHP --}}
                    @php
                        $cleanPrice = (int) str_replace('.', '', $service->price);
                    @endphp
                    <h3 class="text-2xl font-bold">Rp {{ number_format($cleanPrice, 0, ',', '.') }}</h3>
                    <span class="text-xs opacity-80">/ Unit</span>
                </div>
            </div>
            
            <div class="p-8 space-y-6">
                <!-- Pilihan Kapasitas -->
                @if($service->has_capacity)
                    <div>
                        <label class="text-[10px] font-bold uppercase text-gray-400 mb-2 block">Kapasitas Properti</label>
                        <select class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option>Standard (250 - 500 Liter)</option>
                            <option>Large (500 - 1000 Liter)</option>
                            <option>Extra Large (> 1000 Liter)</option>
                        </select>
                    </div>
                    @endif

                <!-- FIX PILIH TANGGAL: Menggunakan input type date agar interaktif -->
                <div>
                    <label class="text-[10px] font-bold uppercase text-gray-400 mb-2 block">Pilih Jadwal Kunjungan</label>
                    <div class="relative">
                        <input type="date" 
                               min="{{ date('Y-m-d') }}" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 cursor-pointer">
                    </div>
                </div>

                <!-- Ringkasan Biaya -->
                <div class="pt-4 border-t border-dashed border-gray-200 space-y-2">
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Biaya Layanan</span>
                        <span>Rp {{ number_format($cleanPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-sm pt-2">
                        <span>Total Pembayaran</span>
                        <span class="text-[#e67e22]">Rp {{ number_format($cleanPrice, 0, ',', '.') }}</span>
                    </div>
                </div>

                <a href="{{ route('services.pilih-tukang', $service->slug) }}" 
                    class="block text-center w-full bg-[#e67e22] hover:bg-[#d35400] text-white py-4 rounded-2xl font-bold transition shadow-lg shadow-orange-200 transform hover:-translate-y-1">
                    Lanjutkan Pilih Teknisi
                    </a>
            </div>
        </div>

        <!-- FIX LINK HUBUNGI KAMI: Mengarah ke halaman Pusat Bantuan -->
        <a href="{{ route('help') }}" class="bg-blue-50 p-6 rounded-3xl flex items-center gap-4 border border-blue-100 hover:bg-blue-100 transition group">
            <div class="w-12 h-12 bg-[#0f2d50] text-white rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition">
                <i class="fas fa-headset"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-[#0f2d50]">Butuh bantuan?</p>
                <p class="text-[10px] text-blue-600">Ke Pusat Bantuan & CS Gratis <i class="fas fa-arrow-right ml-1"></i></p>
            </div>
        </a>
    </div>
</div>
    </main>
</div>
</x-app-layout>


<style>
    [x-cloak] { display: none !important; }
</style>