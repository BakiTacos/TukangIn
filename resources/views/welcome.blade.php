<x-app-layout>
    <style>
        .hero-bg {
            background: linear-gradient(rgba(15, 45, 80, 0.7), rgba(15, 45, 80, 0.7)), 
                        url("{{ asset('images/banner-hero.jpg') }}");
            background-size: cover;
            background-position: center;
        }
    </style>

    <div class="bg-gray-50 font-sans text-[#0f2d50] min-h-screen pb-12">

        <section class="hero-bg text-white py-24 md:py-32">
            <div class="container mx-auto px-6">
                <p class="text-orange-400 font-bold tracking-widest text-xs mb-4 uppercase">Pilihan No. 1 di Indonesia</p>
                <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-6 max-w-2xl">
                    Solusi Tukang Terpercaya untuk Rumah Anda
                </h1>
                <p class="text-gray-200 text-lg mb-10 max-w-xl leading-relaxed">
                    Temukan teknisi profesional untuk segala kebutuhan perbaikan rumah Anda dengan jaminan hasil terbaik.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="/tukang" class="inline-block">
                        <button class="w-full sm:w-auto bg-[#e67e22] hover:bg-[#d35400] text-white px-8 py-4 rounded-2xl font-bold text-xs uppercase tracking-widest transition shadow-lg shadow-orange-500/20 transform hover:-translate-y-0.5">
                            Booking Sekarang
                        </button>
                    </a>
                    <a href="/layanan" class="inline-block">
                        <button class="w-full sm:w-auto bg-white/20 hover:bg-white/30 backdrop-blur-md text-white px-8 py-4 rounded-2xl font-bold text-xs uppercase tracking-widest border border-white/55 transition transform hover:-translate-y-0.5">
                            Lihat Layanan
                        </button>
                    </a>
                </div>
            </div>
        </section>

        <section class="py-16 container mx-auto px-6 text-center">
            <div class="flex justify-between items-end mb-10">
                <div class="text-left">
                    <h2 class="text-2xl font-black text-[#0f2d50]">Services</h2>
                    <p class="text-xs text-gray-400 font-semibold mt-1">Apa yang kamu butuhkan hari ini?</p>
                </div>
                <a href="/layanan" class="text-orange-500 font-bold text-xs uppercase tracking-wider hover:underline flex items-center gap-1">
                    View All <i class="fas fa-chevron-right text-[10px]"></i>
                </a>
            </div>

            <div class="grid grid-cols-4 md:grid-cols-8 gap-6">
                @foreach($services as $s)
                    <a href="{{ route('services.show', $s->slug) }}" class="flex flex-col items-center group cursor-pointer">
                        <div class="{{ $s->color }} w-14 h-14 rounded-2xl flex items-center justify-center mb-3 transition-transform group-hover:-translate-y-2 shadow-sm">
                            <i class="fas {{ $s->icon }} text-xl"></i>
                        </div>
                        <span class="text-[10px] font-bold text-gray-700 group-hover:text-[#0f2d50] transition-colors">{{ $s->title }}</span>
                    </a>
                @endforeach

                <a href="/layanan" class="flex flex-col items-center group cursor-pointer">
                    <div class="bg-gray-100 text-gray-600 w-14 h-14 rounded-2xl flex items-center justify-center mb-3 transition-transform group-hover:-translate-y-2">
                        <i class="fas fa-th-large text-xl"></i>
                    </div>
                    <span class="text-[10px] font-bold text-gray-700">More</span>
                </a>
            </div>
        </section>

        <section class="py-16 container mx-auto px-6">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-2xl font-black text-[#0f2d50]">Top Rated Partners</h2>
                    <p class="text-xs text-gray-400 font-semibold mt-1">Tukang pilihan dengan rating tertinggi di kota Anda</p>
                </div>
                <a href="/tukang" class="text-orange-500 font-bold text-xs uppercase tracking-wider hover:underline">
                    View All Partners
                </a>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @forelse($tukangs as $t)
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex items-center space-x-5 hover:shadow-md transition duration-200">
                        <img src="{{ $t->avatar ? asset('storage/' . $t->avatar) : 'https://i.pravatar.cc/150?u=' . $t->id }}" 
                             class="w-16 h-16 rounded-2xl object-cover border border-gray-100">
                        
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <h4 class="font-black text-[#0f2d50] text-sm leading-none">{{ $t->name }}</h4>
                                <span class="text-xs font-black text-yellow-500 flex items-center gap-1 shrink-0">
                                    <i class="fas fa-star text-[10px]"></i> {{ number_format($t->rating, 1) }}
                                </span>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-2 font-bold uppercase tracking-wider">
                                {{ $t->specialty ?? 'Teknisi Terverifikasi' }}
                            </p>
                            <div class="flex space-x-2 mt-2">
                                <span class="text-[8px] bg-blue-50 text-blue-600 px-2 py-1 rounded-lg font-bold uppercase tracking-wider">
                                    Verified Partner
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-gray-100">
                        <p class="text-xs text-gray-450 font-bold">Belum ada mitra teknisi yang tersedia.</p>
                    </div>
                @endforelse
            </div>
        </section>

    </div>
</x-app-layout>

<style>
    [x-cloak] { display: none !important; }
</style>