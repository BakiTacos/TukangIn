<x-app-layout>
    <div class="container mx-auto px-6 py-12 max-w-5xl"
         x-data="{ 
            showComplainModal: false,
            showCancelModal: false,
            complaintReason: '',
            complaintDescription: '',
            cancelReason: '',
            cancelDescription: ''
         }">
        
        <nav class="flex justify-between items-center mb-10">
            <a href="{{ route('dashboard') }}" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-orange-500 transition flex items-center gap-2">
                <i class="fas fa-arrow-left text-xs"></i> Kembali ke Dashboard
            </a>
            <span class="bg-gray-150 text-gray-500 text-[9px] font-black px-4 py-2 rounded-xl uppercase tracking-widest border border-gray-200">
                Invoice Resmi
            </span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-8 pb-6 border-b border-gray-50">
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nomor Pemesanan</p>
                            <h2 class="text-xl font-black text-[#0f2d50]">{{ $order->order_number }}</h2>
                        </div>
                        
                        @if($order->status === 'pending')
                            <span class="bg-yellow-50 text-yellow-600 text-xs font-black px-4 py-2 rounded-full uppercase tracking-wider border border-yellow-100">Menunggu Pembayaran</span>
                        @elseif($order->status === 'pengerjaan')
                            <span class="bg-blue-50 text-blue-600 text-xs font-black px-4 py-2 rounded-full uppercase tracking-wider border border-blue-100">Dalam Pengerjaan</span>
                        @elseif($order->status === 'selesai')
                            <span class="bg-green-50 text-green-600 text-xs font-black px-4 py-2 rounded-full uppercase tracking-wider border border-green-100">Selesai</span>
                        @elseif($order->status === 'batal')
                            <span class="bg-red-50 text-red-600 text-xs font-black px-4 py-2 rounded-full uppercase tracking-wider border border-red-100">Dibatalkan</span>
                        @elseif($order->status === 'dikomplain')
                            <span class="bg-purple-50 text-purple-600 text-xs font-black px-4 py-2 rounded-full uppercase tracking-wider border border-purple-100">Dikomplain</span>
                        @endif
                    </div>

                    @if($order->status === 'dikomplain')
                        <div class="bg-purple-50 border border-purple-100 p-6 rounded-3xl flex items-start gap-4 text-purple-600">
                            <i class="fas fa-exclamation-circle text-2xl animate-bounce mt-1 shrink-0"></i>
                            <div>
                                <h4 class="text-sm font-black uppercase tracking-wider">Pemesanan Ditangguhkan (Dikomplain)</h4>
                                <p class="text-xs text-purple-500 leading-relaxed mt-1">
                                    Anda telah mengajukan komplain resmi untuk pengerjaan dari teknisi <strong>{{ $order->tukang->name ?? 'Teknisi' }}</strong>. Tim investigasi **AMARTA** sedang memverifikasi laporan ini. Dana transaksi Anda aman dan ditangguhkan dari pencairan sampai masalah selesai. Kami akan menghubungi Anda dalam 1x24 jam.
                                </p>
                                <div class="mt-4 pt-4 border-t border-purple-100 text-xs space-y-1 text-purple-700">
                                    <p><strong>Alasan:</strong> {{ $order->complaint_reason }}</p>
                                    <p class="italic">"{{ $order->complaint_description }}"</p>
                                </div>
                            </div>
                        </div>

                    @elseif($order->status === 'batal')
                        <div class="bg-red-50 border border-red-100 p-6 rounded-3xl flex flex-col gap-4 text-red-600">
                            <div class="flex items-start gap-4">
                                <i class="fas fa-ban text-2xl shrink-0 mt-0.5"></i>
                                <div>
                                    <h4 class="text-sm font-black uppercase tracking-wider">Pesanan Dibatalkan</h4>
                                    <p class="text-xs text-red-500 leading-relaxed mt-1">Pesanan ini telah resmi dibatalkan dan sistem telah menghentikan seluruh proses transaksi terkait.</p>
                                </div>
                            </div>
                            @if($order->cancel_reason)
                                <div class="pt-4 border-t border-red-100 text-xs space-y-1 text-red-700">
                                    <p><strong>Alasan Pembatalan:</strong> {{ $order->cancel_reason }}</p>
                                    <p class="italic">"{{ $order->cancel_description }}"</p>
                                </div>
                            @endif
                        </div>

                    @else
                        <div class="relative flex flex-col md:flex-row justify-between items-center gap-6 md:gap-0 mt-6">
                            <div class="hidden md:block absolute left-10 right-10 top-5 h-1 bg-gray-100 -z-10"></div>
                            <div class="hidden md:block absolute left-10 top-5 h-1 bg-orange-500 -z-10 transition-all duration-500"
                                 style="width: {{ $order->status === 'pending' ? '0%' : ($order->status === 'pengerjaan' ? '50%' : '100%') }}">
                            </div>

                            <div class="flex flex-col items-center text-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold transition-all border-2 border-orange-500 bg-white text-orange-500 shadow-md">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <p class="text-xs font-bold text-[#0f2d50] mt-3">Pesanan Masuk</p>
                                <p class="text-[9px] text-gray-400 mt-1">{{ $order->created_at->format('d M Y') }}</p>
                            </div>

                            <div class="flex flex-col items-center text-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold transition-all border-2 
                                            {{ in_array($order->status, ['pengerjaan', 'selesai']) ? 'border-orange-500 bg-white text-orange-500 shadow-md' : 'border-gray-200 bg-white text-gray-400' }}">
                                    <i class="fas fa-money-check-alt"></i>
                                </div>
                                <p class="text-xs font-bold mt-3 {{ in_array($order->status, ['pengerjaan', 'selesai']) ? 'text-[#0f2d50]' : 'text-gray-400' }}">Pembayaran Valid</p>
                                <p class="text-[9px] text-gray-400 mt-1">{{ in_array($order->status, ['pengerjaan', 'selesai']) ? $order->updated_at->format('d M Y') : 'Menunggu' }}</p>
                            </div>

                            <div class="flex flex-col items-center text-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold transition-all border-2 
                                            {{ $order->status === 'selesai' ? 'border-orange-500 bg-white text-orange-500 shadow-md' : ($order->status === 'pengerjaan' ? 'border-orange-500 bg-white text-orange-500 animate-pulse' : 'border-gray-200 bg-white text-gray-400') }}">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <p class="text-xs font-bold mt-3 {{ in_array($order->status, ['pengerjaan', 'selesai']) ? 'text-[#0f2d50]' : 'text-gray-400' }}">Proses Perbaikan</p>
                                <p class="text-[9px] text-gray-400 mt-1">{{ $order->status === 'selesai' ? 'Selesai' : ($order->status === 'pengerjaan' ? 'Sedang Berlangsung' : 'Menunggu') }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                @if($order->review)
                    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100 space-y-6">
                        <div class="flex justify-between items-center border-b border-gray-50 pb-4">
                            <h3 class="text-lg font-black text-[#0f2d50] uppercase tracking-wider">Ulasan Anda</h3>
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Dikirim {{ $order->review->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center gap-1.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-6 h-6 {{ $i <= $order->review->rating ? 'text-yellow-400 fill-current' : 'text-gray-200' }}" 
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                                <span class="text-xs font-black text-orange-500 ml-2 uppercase tracking-wide">
                                    {{ $order->review->rating === 5 ? 'Sangat Puas' : ($order->review->rating === 4 ? 'Puas' : ($order->review->rating === 3 ? 'Cukup Baik' : ($order->review->rating === 2 ? 'Buruk' : 'Sangat Buruk'))) }}
                                </span>
                            </div>

                            <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-150">
                                <p class="text-xs text-gray-600 leading-relaxed italic">
                                    "{{ $order->review->comment }}"
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100 space-y-8">
                    <h3 class="text-lg font-black text-[#0f2d50] uppercase tracking-wider border-b border-gray-50 pb-4">Rincian Jasa & Teknisi</h3>
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center overflow-hidden border border-gray-50">
                                @if($order->service->image)
                                    <img src="{{ asset('storage/' . $order->service->image) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-tools text-[#0f2d50] text-xl"></i>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-[#0f2d50] leading-tight text-lg">{{ $order->service->title }}</h4>
                                <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">{{ $order->service->category ?? 'Maintenance' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($order->tukang->name) }}&background=FFEDD5&color=F97316" class="w-12 h-12 rounded-xl border border-orange-200">
                            <div>
                                <h5 class="text-sm font-bold text-[#0f2d50]">{{ $order->tukang->name }}</h5>
                                <p class="text-[10px] text-gray-400">Spesialis Keahlian</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-black text-[#0f2d50] uppercase tracking-wider border-b border-gray-50 pb-4 mb-6">Lokasi Pengerjaan</h3>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center text-sm shrink-0">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-[#0f2d50] mb-1">Alamat Penerima ({{ $order->address->label ?? 'Lokasi Rumah' }})</h4>
                            <p class="text-xs text-gray-600 font-bold mb-1">{{ $order->address->receiver_name ?? Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                {{ $order->address->full_address }}, {{ $order->address->city }} ({{ $order->address->postal_code }})
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Ringkasan Invoice</h4>
                        
                        <div class="space-y-4 text-sm border-b border-gray-50 pb-6 mb-6">
                            <div class="flex justify-between items-center">
                                <p class="text-gray-400">Biaya Layanan</p>
                                <p class="font-bold text-[#0f2d50]">Rp {{ number_format($serviceFee, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-gray-400">Biaya Teknisi</p>
                                <p class="font-bold text-[#0f2d50]">Rp {{ number_format($technicianFee, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-gray-400">Pajak Platform (2%)</p>
                                <p class="font-bold text-[#0f2d50]">Rp {{ number_format($taxAmount, 0, ',', '.') }}</p>
                            </div>
                            
                            @if($order->payment_fee > 0)
                                <div class="flex justify-between items-center text-orange-600">
                                    <p class="font-medium">Biaya Admin ({{ strtoupper($order->payment_method) }})</p>
                                    <p class="font-bold">Rp {{ number_format($order->payment_fee, 0, ',', '.') }}</p>
                                </div>
                            @endif

                            @if($order->discount_amount > 0)
                                <div class="flex justify-between items-center text-green-600">
                                    <p class="font-medium">Diskon Promo ({{ $order->promo_code }})</p>
                                    <p class="font-bold">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-between items-center mb-8">
                            <div>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Biaya</p>
                                <p class="text-2xl font-black text-[#0f2d50]">Rp {{ number_format($order->total_cost, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @if($order->status === 'pending')
                                <a href="{{ route('orders.payment', $order->id) }}" 
                                   class="w-full bg-[#e67e22] hover:bg-[#d35400] text-white py-4 rounded-2xl font-bold shadow-lg shadow-orange-500/20 transition transform hover:-translate-y-1 block text-center uppercase tracking-widest text-xs">
                                    Bayar Sekarang <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                                
                                <button type="button" @click="showCancelModal = true"
                                        class="w-full bg-white border-2 border-red-150 hover:bg-red-50 text-red-500 py-4 rounded-2xl font-bold flex items-center justify-center gap-2 transition text-xs uppercase tracking-wider">
                                    <i class="fas fa-times-circle"></i> Batalkan Pesanan
                                </button>

                            @elseif($order->status === 'pengerjaan')
                                <button class="w-full bg-blue-500 text-white py-4 rounded-2xl font-bold cursor-default block text-center uppercase tracking-widest text-xs" disabled>
                                    <i class="fas fa-spinner animate-spin mr-1"></i> Sedang Diperbaiki
                                </button>
                                
                                @if($order->created_at->gt(now()->subHours(12)))
                                    <button type="button" @click="showCancelModal = true"
                                            class="w-full bg-red-500 hover:bg-red-600 text-white py-4 rounded-2xl font-bold transition transform hover:-translate-y-1 block text-center uppercase tracking-widest text-xs">
                                        <i class="fas fa-times-circle mr-1"></i> Batalkan Pesanan
                                    </button>
                                @endif

                                <button type="button" @click="showComplainModal = true"
                                        class="w-full bg-white border-2 border-red-150 hover:bg-red-50 text-red-500 py-4 rounded-2xl font-bold flex items-center justify-center gap-2 transition text-xs uppercase tracking-wider">
                                    <i class="fas fa-exclamation-triangle"></i> Ajukan Komplain Jasa
                                </button>

                            @elseif($order->status === 'selesai')
                                @if($order->review)
                                    <button class="w-full bg-gray-100 text-gray-400 py-4 rounded-2xl font-bold cursor-default block text-center uppercase tracking-widest text-xs border border-gray-150" disabled>
                                        Sudah Diulas <i class="fas fa-check-circle ml-1 text-green-500"></i>
                                    </button>
                                @else
                                    <a href="{{ route('reviews.create', $order->id) }}" 
                                       class="w-full bg-green-500 hover:bg-green-600 text-white py-4 rounded-2xl font-bold transition transform hover:-translate-y-1 block text-center uppercase tracking-widest text-xs">
                                        Beri Ulasan Teknisi <i class="far fa-star ml-1"></i>
                                    </a>
                                    
                                    @if($order->updated_at->gt(now()->subDays(7)))
                                        <button type="button" @click="showComplainModal = true"
                                                class="w-full bg-white border-2 border-red-150 hover:bg-red-50 text-red-500 py-4 rounded-2xl font-bold flex items-center justify-center gap-2 transition text-xs uppercase tracking-wider">
                                            <i class="fas fa-exclamation-triangle"></i> Ajukan Komplain Jasa
                                        </button>
                                    @else
                                        <div class="bg-gray-50 border border-gray-150 p-4 rounded-2xl text-center text-gray-400 text-[10px] font-bold uppercase tracking-wider">
                                            <i class="fas fa-info-circle mr-1"></i> Garansi Komplain 7 Hari Habis
                                        </div>
                                    @endif
                                @endif

                            @elseif($order->status === 'dikomplain')
                                <button class="w-full bg-purple-100 text-purple-600 py-4 rounded-2xl font-bold cursor-default block text-center uppercase tracking-widest text-xs" disabled>
                                    <i class="fas fa-clock mr-1 animate-pulse"></i> Komplain Ditinjau
                                </button>

                            @elseif($order->status === 'batal')
                                <button class="w-full bg-red-100 text-red-500 py-4 rounded-2xl font-bold cursor-not-allowed block text-center uppercase tracking-widest text-xs" disabled>
                                    Pemesanan Dibatalkan
                                </button>
                            @endif

                            <a href="https://wa.me/6281234567890?text=Halo%20Amarta%20Care,%20saya%20ingin%20bertanya%20mengenai%20status%20pesanan%20{{ $order->order_number }}" 
                               target="_blank"
                               class="w-full bg-white border-2 border-gray-100 text-gray-600 py-4 rounded-2xl font-bold flex items-center justify-center gap-2 hover:bg-gray-50 transition text-xs uppercase tracking-wider">
                                <i class="fab fa-whatsapp text-green-500 text-sm"></i> Hubungi Amarta Care
                            </a>
                        </div>
                    </div>

                    <div class="bg-[#0f2d50] rounded-[2.5rem] p-8 text-white shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Metode Pembayaran</p>
                            <h4 class="font-extrabold uppercase tracking-wider text-sm">
                                {{ $order->payment_method === 'bank_transfer' ? ($order->payment_bank . ' Virtual Account') : $order->payment_method }}
                            </h4>
                        </div>
                        <div class="text-3xl text-white/20">
                            <i class="fas {{ $order->payment_method === 'bank_transfer' ? 'fa-university' : 'fa-wallet' }}"></i>
                        </div>
                    </div>
                   
                </div>
            </div>

        </div>

        <div x-show="showComplainModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-[#0f2d50]/40 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
            
            <div class="bg-white rounded-[2.5rem] p-10 max-w-lg w-full mx-4 shadow-2xl border border-gray-100 transform transition-all"
                 @click.away="showComplainModal = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="scale-95 translate-y-4"
                 x-transition:enter-end="scale-100 translate-y-0">
                
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-50">
                    <h3 class="text-lg font-black text-[#0f2d50] uppercase tracking-wider">Form Pengajuan Komplain</h3>
                    <button @click="showComplainModal = false" class="text-gray-400 hover:text-gray-600 transition text-lg">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('orders.complain', $order->id) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider">Alasan Utama Komplain</label>
                        <div class="relative">
                            <select name="complaint_reason" x-model="complaintReason" required
                                    class="w-full bg-gray-50 border border-gray-150 rounded-2xl py-4 px-5 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent appearance-none cursor-pointer">
                                <option value="">-- Pilih Kategori Kendala --</option>
                                <option value="Teknisi tidak datang ke lokasi">Teknisi tidak datang ke lokasi</option>
                                <option value="Hasil pekerjaan tidak rapi / tidak berfungsi">Hasil pekerjaan tidak rapi / tidak berfungsi</option>
                                <option value="Terjadi kerusakan tambahan pada barang">Terjadi kerusakan tambahan pada barang</option>
                                <option value="Teknisi berperilaku tidak sopan">Teknisi berperilaku tidak sopan</option>
                                <option value="Lainnya">Lainnya (Tulis detail di deskripsi)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-gray-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider">Deskripsi Masalah</label>
                            <span class="text-[10px] font-bold transition-all" 
                                  :class="complaintDescription.length >= 256 ? 'text-red-500 animate-pulse' : 'text-gray-400'"
                                  x-text="complaintDescription.length + ' / 256'">
                            </span>
                        </div>
                        <textarea name="complaint_description" x-model="complaintDescription" maxlength="256" required
                                  placeholder="Jelaskan secara rinci kendala yang Anda alami dengan pengerjaan teknisi ini..."
                                  class="w-full bg-gray-50 border border-gray-150 rounded-2xl py-4 px-5 text-xs font-medium text-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent h-32 resize-none placeholder-gray-350"></textarea>
                    </div>

                    <div class="flex gap-3 pt-4 border-t border-gray-50">
                        <button type="button" @click="showComplainModal = false"
                                class="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-600 py-4 rounded-2xl font-bold text-xs uppercase tracking-widest transition border border-gray-150">
                            Batal
                        </button>
                        <button type="submit" 
                                :disabled="complaintReason === '' || complaintDescription.trim() === ''"
                                :class="(complaintReason === '' || complaintDescription.trim() === '') ? 'opacity-50 cursor-not-allowed hover:transform-none' : ''"
                                class="flex-1 bg-red-500 hover:bg-red-600 text-white py-4 rounded-2xl font-bold text-xs uppercase tracking-widest transition shadow-lg shadow-red-500/20 transform hover:-translate-y-1">
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="showCancelModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-[#0f2d50]/40 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
            
            <div class="bg-white rounded-[2.5rem] p-10 max-w-lg w-full mx-4 shadow-2xl border border-gray-100 transform transition-all"
                 @click.away="showCancelModal = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="scale-95 translate-y-4"
                 x-transition:enter-end="scale-100 translate-y-0">
                
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-50">
                    <h3 class="text-lg font-black text-[#0f2d50] uppercase tracking-wider">Form Pembatalan Pesanan</h3>
                    <button @click="showCancelModal = false" class="text-gray-400 hover:text-gray-600 transition text-lg">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider">Alasan Pembatalan</label>
                        <div class="relative">
                            <select name="cancel_reason" x-model="cancelReason" required
                                    class="w-full bg-gray-50 border border-gray-150 rounded-2xl py-4 px-5 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent appearance-none cursor-pointer">
                                <option value="">-- Pilih Alasan Pembatalan --</option>
                                <option value="Ingin mengubah detail rincian jasa">Ingin mengubah detail rincian jasa</option>
                                <option value="Ada urusan mendadak / keluar kota">Ada urusan mendadak / keluar kota</option>
                                <option value="Menemukan teknisi lain di luar platform">Menemukan teknisi lain di luar platform</option>
                                <option value="Teknisi meminta biaya tambahan tidak wajar">Teknisi meminta biaya tambahan tidak wajar</option>
                                <option value="Lainnya">Lainnya (Tulis detail di bawah)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-gray-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider">Deskripsi Pembatalan</label>
                            <span class="text-[10px] font-bold transition-all" 
                                  :class="cancelDescription.length >= 256 ? 'text-red-500 animate-pulse' : 'text-gray-400'"
                                  x-text="cancelDescription.length + ' / 256'">
                            </span>
                        </div>
                        <textarea name="cancel_description" x-model="cancelDescription" maxlength="256" required
                                  placeholder="Tuliskan umpan balik Anda mengapa memutuskan membatalkan pemesanan ini..."
                                  class="w-full bg-gray-50 border border-gray-150 rounded-2xl py-4 px-5 text-xs font-medium text-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent h-32 resize-none placeholder-gray-350"></textarea>
                    </div>

                    <div class="flex gap-3 pt-4 border-t border-gray-50">
                        <button type="button" @click="showCancelModal = false"
                                class="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-600 py-4 rounded-2xl font-bold text-xs uppercase tracking-widest transition border border-gray-150">
                            Batal
                        </button>
                        <button type="submit" 
                                :disabled="cancelReason === '' || cancelDescription.trim() === ''"
                                :class="(cancelReason === '' || cancelDescription.trim() === '') ? 'opacity-50 cursor-not-allowed hover:transform-none' : ''"
                                class="flex-1 bg-red-500 hover:bg-red-600 text-white py-4 rounded-2xl font-bold text-xs uppercase tracking-widest transition shadow-lg shadow-red-500/20 transform hover:-translate-y-1">
                            Batalkan Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>

<style>
    [x-cloak] { display: none !important; }
</style>