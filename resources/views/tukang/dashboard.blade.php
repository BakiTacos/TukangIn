<x-app-layout>
    <div class="container mx-auto px-6 py-12 max-w-6xl"
         x-data="{ 
            showCompleteModal: false, 
            completeAction: '', 
            completePhoto: '',
            showCancelModal: false,
            cancelReason: '',
            cancelDescription: ''
         }">
        
        <div class="bg-[#0f2d50] rounded-[2.5rem] p-10 text-white mb-10 relative overflow-hidden shadow-xl">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="flex items-center gap-5">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=FFEDD5&color=F97316&bold=true" 
                         class="w-20 h-20 rounded-[2rem] border-2 border-white/20 shadow-md">
                    <div>
                        <span class="bg-orange-500 text-white text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest">
                            Mitra Teknisi Terverifikasi
                        </span>
                        <h1 class="text-3xl font-black mt-3">{{ auth()->user()->name }}</h1>
                        <p class="text-xs text-gray-300 mt-1">Kelola pesanan masuk, pantau pekerjaan aktif, dan kumpulkan pendapatan Anda.</p>
                    </div>
                </div>
                
                <div x-data="{ 
                        online: {{ auth()->user()->is_available ? 'true' : 'false' }},
                        loading: false,
                        async toggleStatus() {
                            if (this.loading) return;
                            this.loading = true;
                            try {
                                let response = await fetch('{{ route('tukang.toggle-availability') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSR-TOKEN': '{{ csrf_token() }}'
                                    }
                                });
                                let data = await response.json();
                                if (data.success) {
                                    this.online = data.is_available;
                                }
                            } catch (error) {
                                console.error('Gagal:', error);
                            } finally {
                                this.loading = false;
                            }
                        }
                     }" 
                     @click="toggleStatus()"
                     :class="online ? 'border-green-500 bg-green-500/10 hover:bg-green-500/20 text-green-400' : 'border-red-500 bg-red-500/10 hover:bg-red-500/20 text-red-400'"
                     class="flex items-center gap-3 px-6 py-3 rounded-2xl border cursor-pointer transition-all">
                    <span class="relative flex h-3 w-3">
                        <span x-show="online" class="absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75 animate-ping"></span>
                        <span :class="online ? 'bg-green-500' : 'bg-red-500'" class="relative inline-flex rounded-full h-3 w-3"></span>
                    </span>
                    <span class="text-xs font-black uppercase tracking-wider select-none" x-text="loading ? 'Memproses...' : (online ? 'Menerima Order' : 'Sedang Istirahat')"></span>
                </div>
            </div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    
    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-lg shrink-0">
            <i class="fas fa-wallet"></i>
        </div>
        <div>
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Total Pendapatan</p>
            <h3 class="text-xl font-black text-[#0f2d50] mt-1">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-lg shrink-0">
            <i class="fas fa-spinner animate-spin"></i>
        </div>
        <div>
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Aktif Berjalan</p>
            <h3 class="text-xl font-black text-[#0f2d50] mt-1">{{ $activeJobsCount }} Pekerjaan</h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
        <div class="w-12 h-12 bg-yellow-50 text-yellow-500 rounded-2xl flex items-center justify-center text-lg shrink-0">
            <i class="fas fa-star"></i>
        </div>
        <div>
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Rating Anda</p>
            <h3 class="text-xl font-black text-[#0f2d50] mt-1">
                ★ {{ auth()->user()->reviews_avg_rating ? number_format(auth()->user()->reviews_avg_rating, 1) : '5.0' }}
                <span class="text-[10px] text-gray-400 font-bold">/ 5.0</span>
            </h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
        <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center text-lg shrink-0">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Proyek Selesai</p>
            <h3 class="text-xl font-black text-[#0f2d50] mt-1">
                {{ auth()->user()->completed_orders_count ?? 0 }} <span class="text-[10px] text-gray-400 font-bold">Transaksi</span>
            </h3>
        </div>
    </div>

</div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center border-b border-gray-50 pb-5 mb-6">
                        <h3 class="text-lg font-black text-[#0f2d50] uppercase tracking-wider">
                            ⚡ Pekerjaan Sedang Aktif
                        </h3>
                        <span class="bg-blue-50 text-blue-600 text-[10px] font-black px-3 py-1.5 rounded-lg border border-blue-100 uppercase">
                            {{ $activeJobsCount }} Berjalan
                        </span>
                    </div>

                    <div class="space-y-6">
                        @forelse($activeJobs as $job)
                            <div class="relative group bg-blue-50/20 hover:bg-blue-50/40 p-6 rounded-3xl border border-blue-100/60 hover:border-blue-300 hover:shadow-md transition-all duration-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                                <a href="{{ route('orders.show', $job->id) }}" class="absolute inset-0 z-10" aria-label="Lihat Detail"></a>

                                <div class="flex items-center gap-4 relative z-20">
                                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center border border-blue-100 shrink-0 text-blue-500 shadow-sm">
                                        <i class="fas fa-tools text-lg animate-pulse"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-[#0f2d50] text-sm leading-tight group-hover:text-orange-500 transition-colors">{{ $job->service->title }}</h4>
                                        <p class="text-[10px] text-blue-600 font-extrabold mt-1.5 tracking-wider uppercase">
                                            Invoice: #{{ $job->order_number }}
                                        </p>
                                        <p class="text-[11px] text-gray-550 mt-1 font-semibold">
                                            Hubungi: <span class="font-extrabold text-blue-600">{{ $job->user->name }}</span>
                                        </p>
                                        <p class="text-[10px] text-gray-400 mt-1 flex items-center gap-1">
                                            <i class="fas fa-home"></i> {{ $job->address->full_address }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 w-full sm:w-auto shrink-0 relative z-20">
                                    <a href="{{ route('orders.show', $job->id) }}" 
                                    class="flex-1 sm:flex-none text-center bg-white border border-gray-200 hover:bg-gray-50 text-[#0f2d50] px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition shadow-sm whitespace-nowrap">
                                        Detail
                                    </a>
                                    
                                    <a href="{{ route('chats.index') }}" 
                                    class="flex-1 sm:flex-none text-center bg-white border border-gray-200 hover:bg-gray-50 text-[#0f2d50] px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-2 shadow-sm whitespace-nowrap">
                                        <i class="fas fa-comments text-blue-500"></i> Chat
                                    </a>
                                    
                                    <button type="button" 
                                            @click="completeAction = '{{ route('tukang.orders.complete', $job->id) }}'; showCompleteModal = true" 
                                            class="flex-1 sm:flex-none text-center bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition shadow-md shadow-green-500/25 transform hover:-translate-y-0.5 whitespace-nowrap">
                                        Tandai Selesai
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Tidak Ada Pekerjaan Aktif</p>
                                <p class="text-[10px] text-gray-400 mt-1">Pekerjaan yang Anda terima akan terdaftar di panel ini agar tidak terlewat.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-black text-[#0f2d50] uppercase tracking-wider border-b border-gray-50 pb-5 mb-6">
                        📜 Riwayat Penyelesaian & Masalah
                    </h3>
                    
                    <div class="divide-y divide-gray-50">
                        @forelse($jobHistory as $hist)
                            <a href="{{ route('orders.show', $hist->id) }}" 
                               class="group py-5 px-4 -mx-4 rounded-2xl hover:bg-gray-50 transition-all duration-200 flex justify-between items-center gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-[#0f2d50] shrink-0 group-hover:bg-orange-50 group-hover:text-orange-500 transition-colors">
                                        <i class="fas fa-archive"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-[#0f2d50] text-xs leading-none group-hover:text-orange-500 transition-colors">
                                            {{ $hist->service->title }}
                                        </h4>
                                        <p class="text-[9px] text-gray-400 font-bold mt-2 tracking-wider uppercase group-hover:text-orange-600 transition-colors">
                                            Invoice: #{{ $hist->order_number }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 mt-1 font-medium">Pelanggan: {{ $hist->user->name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 shrink-0">
                                    <div class="text-right">
                                        <p class="font-bold text-xs text-[#0f2d50]">Rp {{ number_format($hist->technician_fee, 0, ',', '.') }}</p>
                                        <p class="text-[8px] text-gray-400 font-bold mt-1 uppercase tracking-wider">{{ $hist->created_at->format('d M Y') }}</p>
                                    </div>
                                    
                                    @if($hist->status === 'selesai')
                                        <span class="bg-green-50 text-green-600 text-[9px] font-black px-2.5 py-1.5 rounded-lg border border-green-100 uppercase tracking-wider">Selesai</span>
                                    @elseif($hist->status === 'batal')
                                        <span class="bg-red-50 text-red-600 text-[9px] font-black px-2.5 py-1.5 rounded-lg border border-red-100 uppercase tracking-wider">Batal</span>
                                    @elseif($hist->status === 'dikomplain')
                                        <span class="bg-purple-50 text-purple-600 text-[9px] font-black px-2.5 py-1.5 rounded-lg border border-purple-100 uppercase tracking-wider">Dikomplain</span>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <p class="text-xs text-gray-400 text-center py-10 font-medium">Belum ada riwayat pengerjaan lampau.</p>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $jobHistory->links() }}
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1 space-y-8">

            <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
        <div class="border-b border-gray-50 pb-4 mb-6">
            <h4 class="text-base font-black text-[#0f2d50] uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-calendar-alt text-orange-500 text-lg"></i> Jadwal Operasional
            </h4>
            <p class="text-[10px] text-gray-400 mt-1">Centang hari aktif kerja dan atur jam operasional Anda.</p>
        </div>

        <form action="{{ route('tukang.schedule.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="space-y-3">
                @foreach($schedules as $sch)
                    <div x-data="{ active: {{ $sch->is_active ? 'true' : 'false' }} }" 
                         class="flex items-center justify-between p-3 rounded-2xl border transition-all duration-200"
                         :class="active ? 'bg-orange-50/10 border-orange-100/60' : 'bg-gray-50/50 border-gray-150'">
                        
                        <div class="flex items-center gap-3">
                            <input type="checkbox" 
                                   name="schedules[{{ $sch->day }}][is_active]" 
                                   value="1" 
                                   x-model="active"
                                   @if($sch->is_active) checked @endif
                                   class="rounded-lg text-orange-500 focus:ring-orange-500 border-gray-300 w-4 h-4 cursor-pointer">
                            <span class="text-xs font-black tracking-wide" 
                                  :class="active ? 'text-[#0f2d50]' : 'text-gray-400'">
                                {{ $sch->day }}
                            </span>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <input type="time" 
                                   name="schedules[{{ $sch->day }}][start_time]" 
                                   value="{{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }}"
                                   :disabled="!active"
                                   :class="!active ? 'opacity-40 bg-gray-100/60 border-gray-200 text-gray-400' : 'bg-white border-gray-200 text-gray-700 focus:ring-orange-500'"
                                   class="text-[10px] font-bold px-2 py-1.5 rounded-xl border focus:outline-none transition w-[64px] text-center">
                            
                            <span class="text-gray-300 text-[9px] font-bold">-</span>
                            
                            <input type="time" 
                                   name="schedules[{{ $sch->day }}][end_time]" 
                                   value="{{ \Carbon\Carbon::parse($sch->end_time)->format('H:i') }}"
                                   :disabled="!active"
                                   :class="!active ? 'opacity-40 bg-gray-100/60 border-gray-200 text-gray-400' : 'bg-white border-gray-200 text-gray-700 focus:ring-orange-500'"
                                   class="text-[10px] font-bold px-2 py-1.5 rounded-xl border focus:outline-none transition w-[64px] text-center">
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" 
                    class="w-full bg-[#0f2d50] hover:bg-orange-500 text-white py-3.5 rounded-2xl font-bold text-xs uppercase tracking-wider transition shadow-lg shadow-blue-100 transform hover:-translate-y-0.5 text-center block">
                Simpan Jadwal Kerja <i class="fas fa-save ml-1"></i>
            </button>
        </form>
    </div>
                <div class="bg-gradient-to-br from-gray-800 to-black rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-lg">
                    <div class="relative z-10">
                        <h4 class="text-lg font-bold mb-2">Pusat Bantuan Mitra</h4>
                        <p class="text-xs text-gray-300 leading-relaxed">Mengalami masalah dengan koordinasi alamat pemesan atau pencairan komisi di TUKANG.IN? Kami siap membantu 24 jam.</p>
                        <a href="/pusat-bantuan" target="_blank" class="mt-6 w-full text-center bg-[#e67e22] hover:bg-[#d35400] text-white py-3.5 rounded-xl font-bold text-xs uppercase tracking-wider transition block">
                            Hubungi CS Partner
                        </a>
                    </div>
                    <img src="https://illustrations.popsy.co/white/customer-support.svg" class="absolute right-0 bottom-0 w-32 opacity-10 pointer-events-none">
                </div>
            </div>
            
        </div>

        @include('order.partials.modals')

    </div> </x-app-layout>

<style>
    [x-cloak] { display: none !important; }
</style>