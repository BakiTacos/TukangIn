<x-app-layout>
    <!-- Header Section: Menampilkan Layanan yang Sedang Dipesan -->
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
            <!-- Sidebar: Ringkasan Pesanan -->
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

                <!-- Info Card -->
                <div class="bg-blue-50 p-6 rounded-[2rem] border border-blue-100 relative overflow-hidden">
                    <i class="fas fa-shield-alt absolute -right-2 -bottom-2 text-blue-200/50 text-6xl"></i>
                    <h4 class="font-bold text-[#0f2d50] text-sm mb-2 italic">Jaminan Keamanan</h4>
                    <p class="text-[10px] text-blue-700 leading-relaxed">
                        Semua teknisi kami telah melewati verifikasi ketat dan sertifikasi keahlian untuk menjamin kualitas pengerjaan.
                    </p>
                </div>
            </aside>

            <!-- Main Content: Daftar Tukang Berdasarkan Kategori -->
            <main class="w-full lg:w-3/4">
                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-500 font-bold text-sm">
                        Menampilkan {{ $tukangs->total() }} Teknisi Spesialis {{ $service->category }}
                    </p>
                    <div class="flex bg-white rounded-lg shadow-sm p-1 border border-gray-100">
                        <button class="p-2 text-orange-500 bg-orange-50 rounded-md"><i class="fas fa-th-large"></i></button>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    @forelse($tukangs as $tukang)
                    <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-4">
                                <!-- Avatar Dinamis dengan UI Avatars -->
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($tukang->name) }}&background=0f2d50&color=fff" 
                                     class="w-16 h-16 rounded-2xl object-cover shadow-inner">
                                <div>
                                    <span class="text-[9px] font-bold text-orange-500 uppercase tracking-widest">{{ $tukang->category }}</span>
                                    <h4 class="font-bold text-gray-800 text-lg">{{ $tukang->name }}</h4>
                                    <p class="text-[11px] font-bold text-yellow-500">
                                        <i class="fas fa-star mr-1"></i> {{ $tukang->rating }} 
                                        <span class="text-gray-400 font-normal ml-1">({{ $tukang->total_order }} Order Selesai)</span>
                                    </p>
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
                            <!-- CTA Utama: Lanjut ke Konfirmasi/Checkout -->
                            {{-- CARA BENAR --}}
                            <a href="{{ route('tukang.show', $tukang->id) }}?service_id={{ $service->id }}" 
                            class="block text-center w-full bg-[#0f2d50] text-white py-3 rounded-2xl font-bold text-sm hover:bg-orange-500 transition shadow-lg shadow-blue-100">
                                Lihat Profil & Pilih
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                            <i class="fas fa-user-slash text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Teknisi Belum Tersedia</h3>
                        <p class="text-xs text-gray-400">Maaf, saat ini belum ada teknisi yang tersedia untuk kategori {{ $service->category }}.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-12 flex justify-center">
                    {{ $tukangs->links() }}
                </div>
            </main>
        </div>
    </div>
</x-app-layout>