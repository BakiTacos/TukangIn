<x-app-layout>
    <div class="container mx-auto px-6 py-12">
        <div class="text-center mb-12">
            <p class="text-orange-500 font-bold text-xs uppercase tracking-widest mb-2">Pilih Teknisi Anda</p>
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#0f2d50] mb-4">Mitra Terbaik untuk {{ $service->title }}</h1>
            <p class="text-gray-500 max-w-xl mx-auto">Kami menemukan {{ $tukangs->total() }} teknisi profesional di bidang {{ $service->category }} yang siap membantu Anda hari ini.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($tukangs as $tukang)
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl transition-all">
                    <div class="flex items-center gap-4 mb-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($tukang->name) }}&background=0f2d50&color=fff" 
                             alt="{{ $tukang->name }}" 
                             class="w-16 h-16 rounded-full object-cover">
                        <div>
                            <h3 class="font-bold text-lg text-gray-800">{{ $tukang->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $tukang->specialty }}</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center bg-gray-50 p-3 rounded-2xl mb-6">
                        <div class="text-center">
                            <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Rating</p>
                            <p class="text-sm font-bold text-yellow-500"><i class="fas fa-star mr-1"></i>{{ $tukang->rating }}</p>
                        </div>
                        <div class="w-px h-8 bg-gray-200"></div>
                        <div class="text-center">
                            <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Order Selesai</p>
                            <p class="text-sm font-bold text-gray-700">{{ $tukang->total_order }}</p>
                        </div>
                    </div>

                    {{-- Tombol ini nantinya mengarah ke halaman Checkout / Konfirmasi --}}
                    <a href="#" class="block text-center w-full bg-[#0f2d50] text-white py-3 rounded-2xl font-bold text-sm hover:bg-[#e67e22] transition">
                        Pilih Teknisi Ini
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 text-3xl">
                        <i class="fas fa-hard-hat"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Teknisi Belum Tersedia</h3>
                    <p class="text-gray-500">Maaf, saat ini belum ada teknisi yang aktif untuk kategori {{ $service->category }}.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10 flex justify-center">
            {{ $tukangs->links() }}
        </div>
    </div>
</x-app-layout>