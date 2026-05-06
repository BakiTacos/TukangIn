<x-app-layout>
    <!-- HEADER -->
    <section class="bg-[#0f2d50] pt-20 pb-40 relative">
        <div class="container mx-auto px-6">
            <h1 class="text-5xl font-extrabold text-white mb-4 text-center">Solusi Perbaikan Rumah</h1>
        </div>
    </section>

    <!-- SEARCH & FILTER -->
    <div class="container mx-auto px-6 -mt-16 relative z-20">
        <form action="{{ route('layanan.index') }}" method="GET" class="bg-white p-6 rounded-[2.5rem] shadow-2xl flex flex-col md:flex-row gap-4 items-center border border-gray-100">
            <div class="flex-grow flex items-center px-6 py-3 bg-gray-50 rounded-full w-full">
                <i class="fas fa-search text-gray-400 mr-3"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari jasa..." class="bg-transparent w-full outline-none text-sm">
            </div>
            
            <div class="flex gap-2 overflow-x-auto no-scrollbar w-full md:w-auto">
                @php $categories = ['Semua', 'Plumbing', 'Listrik', 'Pengecatan', 'AC & Pendingin', 'Sewa Alat', 'Konstruksi', 'Interior']; @endphp
                @foreach($categories as $cat)
                    <button type="submit" name="category" value="{{ $cat }}" 
                        class="px-6 py-3 rounded-full text-xs font-bold whitespace-nowrap transition {{ request('category', 'Semua') == $cat ? 'bg-[#0f2d50] text-white shadow-lg' : 'bg-white border border-gray-100 text-gray-500 hover:bg-gray-50' }}">
                        {{ $cat }}
                    </button>
                @endforeach
            </div>
        </form>
    </div>

    <!-- GRID LAYANAN -->
    <section class="py-24">
        <div class="container mx-auto px-6">
            @if($services->isEmpty())
                <p class="text-center text-gray-500 py-10">Layanan tidak ditemukan.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($services as $service)
                    <div class="group flex flex-col">
                        <div class="relative h-60 rounded-[2.5rem] overflow-hidden shadow-lg mb-6">
                            <img src="{{ asset('images/services/' . $service->image) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110" onerror="this.src='https://via.placeholder.com/400x300'">
                        </div>
                        <div class="px-2">
                            <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-2 block">{{ $service->category }}</span>
                            <h4 class="text-xl font-extrabold text-[#0f2d50] mb-2">{{ $service->title }}</h4>
                            <p class="text-gray-500 text-sm mb-6 line-clamp-2">{{ $service->description }}</p>
                            <div class="flex justify-between items-center border-t pt-4">
                                <p class="text-lg font-black text-[#0f2d50]">Rp {{ $service->price }}</p>
                                <a href="{{ route('tukang.index') }}" class="w-10 h-10 bg-[#0f2d50] text-white rounded-xl flex items-center justify-center hover:bg-orange-500 transition">
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

            <!-- PAGINATION LARAVEL -->
            <div class="mt-20 flex justify-center">
                {{ $services->links() }}
            </div>
        </div>
    </section>
</x-app-layout>