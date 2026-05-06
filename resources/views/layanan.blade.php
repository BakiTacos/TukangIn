<x-app-layout>
    {{-- Header Section --}}
    <div class="bg-[#0f2d50] pb-32 pt-12">
        <div class="container mx-auto px-6 text-white">
            <nav class="text-xs text-gray-400 mb-4 uppercase tracking-widest">Home > Layanan > Semua</nav>
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold mb-2">Semua Layanan</h1>
                    <p class="text-gray-300">Solusi profesional untuk perawatan dan perbaikan hunian Anda.</p>
                </div>
                
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 -mt-16">
        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Sidebar Filter --}}
            <aside class="w-full lg:w-1/4 space-y-6">
                <form action="{{ route('services.index') }}" method="GET" id="filterForm">
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                        {{-- SEKSI SEARCH (Ganti Kategori) --}}
                        <h4 class="font-bold text-gray-800 mb-4 border-b pb-2 text-sm uppercase">Cari Layanan</h4>
                        <div class="relative mb-6">
                            <i class="fas fa-search absolute left-4 top-3.5 text-gray-400 text-xs"></i>
                            <input type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Contoh: AC, Toren..." 
                                class="w-full bg-gray-50 border-none rounded-xl py-3 pl-10 pr-4 text-xs focus:ring-2 focus:ring-[#e67e22] transition-all"
                                onchange="this.form.submit()">
                        </div>
                        
                        {{-- SEKSI URUTKAN HARGA --}}
                        <h4 class="font-bold text-gray-800 mb-4 border-b pb-2 text-sm uppercase">Urutkan Harga</h4>
                        <div class="space-y-3 text-xs text-gray-600">
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="radio" name="sort" value="murah" 
                                    class="text-[#e67e22] focus:ring-[#e67e22]" 
                                    {{ request('sort') == 'murah' ? 'checked' : '' }}
                                    onchange="this.form.submit()"> 
                                <span class="group-hover:text-[#e67e22] transition">Harga Terendah</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="radio" name="sort" value="mahal" 
                                    class="text-[#e67e22] focus:ring-[#e67e22]" 
                                    {{ request('sort') == 'mahal' ? 'checked' : '' }}
                                    onchange="this.form.submit()"> 
                                <span class="group-hover:text-[#e67e22] transition">Harga Tertinggi</span>
                            </label>
                            
                            @if(request('search') || request('sort'))
                                <a href="{{ route('services.index') }}" class="block text-center text-[10px] font-bold text-red-500 mt-4 hover:underline">
                                    <i class="fas fa-undo mr-1"></i> Reset Filter
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                {{-- Promo Card --}}
                <div class="bg-[#e67e22] p-6 rounded-3xl text-white relative overflow-hidden shadow-lg shadow-orange-100">
                    <h4 class="font-bold text-lg mb-2">Butuh Bantuan?</h4>
                    <p class="text-xs text-orange-100 mb-4 leading-relaxed">Hubungi teknisi kami jika Anda bingung memilih layanan yang tepat.</p>
                    <a href="{{ route('help') }}" class="block text-center w-full bg-[#0f2d50] text-white py-3 rounded-2xl font-bold text-xs">Hubungi CS</a>
                </div>
            </aside>

            {{-- Main Content (Grid Layanan) --}}
            <main class="w-full lg:w-3/4">
                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-500 font-bold text-sm">Menampilkan {{ $services->total() }} Jenis Layanan</p>
                    <div class="flex space-x-2">
                        <button class="p-2 bg-white border border-gray-200 rounded-lg text-[#0f2d50] shadow-sm"><i class="fas fa-th-large"></i></button>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    @foreach($services as $s)
                    <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition duration-300">
                        <div>
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-4">
                                    <div class="{{ $s->color }} w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm">
                                        <i class="fas {{ $s->icon }} text-xl"></i>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">{{ $s->category }}</span>
                                        <h4 class="font-bold text-gray-800 text-lg">{{ $s->title }}</h4>
                                    </div>
                                </div>
                            </div>

                            <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">
                                {{ $s->description }}
                            </p>

                            <div class="flex flex-wrap gap-2 mt-4">
                                @if($s->inclusions)
                                    @foreach(array_slice($s->inclusions, 0, 2) as $inc)
                                        <span class="text-[9px] bg-gray-50 border border-gray-100 px-3 py-1 rounded-full font-bold text-gray-400 uppercase">
                                            <i class="fas fa-check text-[8px] mr-1 text-green-500"></i> {{ $inc['title'] }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="mt-8 flex justify-between items-end border-t border-dashed border-gray-100 pt-4">
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Estimasi Biaya</p>
                                <p class="text-xl font-extrabold text-[#0f2d50]">
                                    Rp {{ number_format((int) str_replace('.', '', $s->price), 0, ',', '.') }}
                                </p>
                            </div>
                            {{-- LOGIKA UTAMA: Mengarah ke halaman detail layanan berdasarkan SLUG --}}
                            <a href="{{ route('services.show', $s->slug) }}" 
                               class="bg-[#0f2d50] text-white px-6 py-3 rounded-2xl font-bold text-xs hover:bg-[#e67e22] transition shadow-lg shadow-blue-100">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12 flex justify-center">
                    {{ $services->links() }}
                </div>
            </main>
        </div>
    </div>
</x-app-layout>