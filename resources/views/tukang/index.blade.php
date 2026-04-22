<x-app-layout>
    <div class="bg-[#0f2d50] pb-32 pt-12">
        <div class="container mx-auto px-6 text-white">
            <nav class="text-xs text-gray-400 mb-4 uppercase tracking-widest">Home > Services > Semua</nav>
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold mb-2">Semua Layanan</h1>
                    <p class="text-gray-300">Temukan teknisi pendingin ruangan terbaik di Jakarta Selatan</p>
                </div>
                <div class="bg-white/10 p-4 rounded-2xl flex items-center space-x-6">
                    <div class="text-xs">
                        <p class="text-gray-400">LOKASI ANDA</p>
                        <p class="font-bold text-white">Jakarta Selatan</p>
                    </div>
                    <button class="bg-yellow-500 text-gray-900 px-4 py-2 rounded-xl font-bold text-sm">Ganti Lokasi</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 -mt-16">
        <div class="flex flex-col md:flex-row gap-8">
            <aside class="w-full md:w-1/4 space-y-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-4 border-b pb-2">FILTER LAYANAN</h4>
                    <div class="space-y-3 text-sm text-gray-600">
                        <label class="flex items-center space-x-3"><input type="checkbox" checked class="rounded text-blue-600"> <span>Semua Layanan</span></label>
                        <label class="flex items-center space-x-3"><input type="checkbox" class="rounded text-blue-600"> <span>Cuci AC</span></label>
                        <label class="flex items-center space-x-3"><input type="checkbox" class="rounded text-blue-600"> <span>Perbaikan Freon</span></label>
                    </div>
                    
                    <h4 class="font-bold text-gray-800 mt-8 mb-4 border-b pb-2">URUTKAN</h4>
                    <div class="space-y-3 text-sm text-gray-600">
                        <label class="flex items-center space-x-3"><input type="radio" name="sort" class="text-blue-600"> <span>Rating Tertinggi</span></label>
                        <label class="flex items-center space-x-3"><input type="radio" name="sort" class="text-blue-600"> <span>Harga Terendah</span></label>
                    </div>
                </div>

                <div class="bg-[#0f2d50] p-6 rounded-3xl text-white relative overflow-hidden">
                    <span class="absolute top-4 right-4 bg-yellow-500 text-[10px] font-bold px-2 py-1 rounded text-gray-900 uppercase">Top Rated</span>
                    <img src="https://i.pravatar.cc/150?u=rian" class="w-16 h-16 rounded-full mb-4 border-2 border-white">
                    <h4 class="font-bold text-lg">Rian Ardiansyah</h4>
                    <p class="text-yellow-500 text-xs font-bold mb-2">★ 5.0 (450+ Selesai)</p>
                    <p class="text-xs text-gray-400 mb-4 leading-relaxed">Spesialis VRV & Inverter. Pengerjaan cepat, rapi, dan bergaransi.</p>
                    <button class="w-full bg-yellow-500 text-gray-900 py-3 rounded-2xl font-bold text-sm">Booking Sekarang</button>
                </div>
            </aside>

            <main class="w-full md:w-3/4">
                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-500 font-bold">Menampilkan {{ $tukangs->total() }} Teknisi</p>
                    <div class="flex space-x-2">
                        <button class="p-2 bg-gray-200 rounded-lg text-gray-600"><i class="fas fa-th-large"></i></button>
                        <button class="p-2 bg-gray-100 rounded-lg text-gray-400"><i class="fas fa-list"></i></button>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    @foreach($tukangs as $tukang)
                    <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $tukang->photo_url ?? 'https://i.pravatar.cc/150?u='.$tukang->id }}" class="w-16 h-16 rounded-2xl object-cover">
                                <div>
                                    <h4 class="font-bold text-gray-800 text-lg">{{ $tukang->name }}</h4>
                                    <p class="text-xs font-bold text-yellow-500">★ {{ $tukang->rating ?? '4.8' }} <span class="text-gray-400 font-normal">({{ $tukang->total_order ?? '0' }} Order)</span></p>
                                </div>
                            </div>
                            <button class="text-gray-300 hover:text-red-500"><i class="far fa-heart"></i></button>
                        </div>

                        <div class="flex flex-wrap gap-2 mt-4">
                            <span class="text-[10px] bg-gray-100 px-3 py-1 rounded-full font-bold text-gray-500 uppercase">Cuci AC</span>
                            <span class="text-[10px] bg-gray-100 px-3 py-1 rounded-full font-bold text-gray-500 uppercase">Isi Freon</span>
                        </div>

                        <div class="mt-8 flex justify-between items-end border-t pt-4">
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Mulai Dari</p>
                                <p class="text-xl font-extrabold text-[#0f2d50]">Rp 75.000</p>
                            </div>
                            <a href="#" class="border-2 border-[#0f2d50] text-[#0f2d50] px-6 py-2 rounded-2xl font-bold text-sm hover:bg-[#0f2d50] hover:text-white transition">Lihat Profil</a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-12 flex justify-center">
                    {{ $tukangs->links() }}
                </div>
            </main>
        </div>
    </div>
</x-app-layout>