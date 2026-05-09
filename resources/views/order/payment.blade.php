@php
    // 1. Ambil batas akhir pembayaran (created_at + 24 jam)
    $expiryTime = $order->created_at->addHours(24);
    
    // 2. Hitung selisih sisa detik, paksa casting ke (int) bulat murni di PHP
    $remainingSeconds = (int) max(0, now()->diffInSeconds($expiryTime, false));
@endphp

<x-app-layout>
    <div class="container mx-auto px-6 py-12 max-w-2xl" 
     x-data="{ 
            copied: false,
            showCancelModal: false,
            // Paksa konversi ke integer murni saat mendarat di JavaScript
            remainingSeconds: Math.floor({{ $remainingSeconds }}), 
            countdown: '00:00:00',
            
            copyToClipboard(text) {
                navigator.clipboard.writeText(text);
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            },

            // Fungsi format dibikin antipeluru dari angka desimal / koma float
            formatTime() {
                // Saring kembali agar totalSeconds benar-benar bilangan bulat murni
                let totalSeconds = Math.floor(this.remainingSeconds);
                if (totalSeconds <= 0) {
                    return '00:00:00';
                }
                let h = Math.floor(totalSeconds / 3600);
                let m = Math.floor((totalSeconds % 3600) / 60);
                let s = totalSeconds % 60; // Sekarang s dijamin bulat murni!
                return [h, m, s].map(v => v < 10 ? '0' + v : v).join(':');
            },

            init() {
                this.countdown = this.formatTime();

                let timer = setInterval(() => {
                    if (this.remainingSeconds <= 0) {
                        clearInterval(timer);
                        this.countdown = '00:00:00';
                        window.location.reload();
                        return;
                    }
                    // Kurangi sisa detik dan pastikan nilainya tetap integer
                    this.remainingSeconds = Math.floor(this.remainingSeconds) - 1;
                    this.countdown = this.formatTime();
                }, 1000);

                window.history.pushState(null, null, window.location.href);
                window.addEventListener('popstate', () => {
                    window.history.pushState(null, null, window.location.href);
                    this.showCancelModal = true;
                });
            }
     }">
        
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-4 text-orange-500 text-2xl">
                <i class="fas fa-wallet animate-pulse"></i>
            </div>
            <h1 class="text-2xl font-black text-[#0f2d50]">Selesaikan Pembayaran Anda</h1>
            <p class="text-xs text-gray-400 mt-2 uppercase tracking-widest font-bold">Order ID: {{ $order->order_number }}</p>
        </div>

        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-150/80 space-y-8">
            
            <div class="bg-red-50/50 border border-red-100 p-5 rounded-2xl flex justify-between items-center text-red-600">
                <p class="text-xs font-bold uppercase tracking-wider">Batas Waktu Transfer</p>
                <p class="text-lg font-black tracking-widest" x-text="countdown"></p>
            </div>

            <div class="text-center py-6 border-b border-gray-100">
                <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">Total Nominal Pembayaran</p>
                <h2 class="text-4xl font-black text-[#0f2d50] mt-2">Rp {{ number_format($order->total_cost, 0, ',', '.') }}</h2>
            </div>

            @if($order->payment_method === 'qris')
                <div class="text-center space-y-6">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Scan Kode QRIS di Bawah Ini</p>
                    <div class="w-56 h-56 bg-white border border-gray-200 rounded-3xl mx-auto flex items-center justify-center overflow-hidden p-4 shadow-sm">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TUKANGIN_QRIS_PAYMENT_{{ $order->total_cost }}" class="w-full h-full object-contain">
                    </div>
                    <div class="flex justify-center gap-4 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                        <span><i class="fas fa-qrcode mr-1 text-orange-500"></i> QRIS Standar</span>
                        <span>|</span>
                        <span><i class="fas fa-lock mr-1 text-green-500"></i> Secure Transaction</span>
                    </div>
                </div>

            @elseif($order->payment_method === 'bank_transfer')
                <div class="space-y-6">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider text-center">Nomor Virtual Account (<span class="uppercase text-orange-500">{{ $order->payment_bank }}</span>)</p>
                    
                    <div class="bg-gray-50 p-6 rounded-3xl border border-gray-150 flex justify-between items-center">
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nomor Rekening VA</p>
                            <h3 class="text-2xl font-black text-[#0f2d50] tracking-widest">{{ $vaNumber }}</h3>
                        </div>
                        <button @click="copyToClipboard('{{ $vaNumber }}')" 
                                class="bg-white hover:bg-orange-50 border border-gray-150 p-3.5 rounded-2xl text-xs font-bold transition flex items-center gap-2">
                            <i class="far" :class="copied ? 'fa-check-circle text-green-500' : 'fa-copy text-gray-500'"></i>
                            <span x-text="copied ? 'Tersalin' : 'Salin'"></span>
                        </button>
                    </div>
                </div>

            @elseif(in_array($order->payment_method, ['gopay', 'dana']))
                <div class="text-center space-y-6 py-4">
                    <div class="w-20 h-20 bg-blue-50/50 rounded-full flex items-center justify-center mx-auto text-[#0f2d50] text-3xl">
                        <i class="fas {{ $order->payment_method === 'gopay' ? 'fa-wallet' : 'fa-mobile-alt' }}"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-[#0f2d50] uppercase tracking-wider">Bayar Instan via {{ $order->payment_method }}</h4>
                        <p class="text-xs text-gray-400 mt-2 px-8 leading-relaxed">Silakan klik tombol di bawah untuk membuka aplikasi dompet digital di smartphone Anda.</p>
                    </div>
                    <button class="bg-[#0f2d50] hover:bg-orange-500 text-white px-8 py-4 rounded-2xl font-bold text-xs uppercase tracking-widest transition shadow-lg flex items-center justify-center gap-2 mx-auto">
                        Buka Aplikasi {{ $order->payment_method }} <i class="fas fa-external-link-alt"></i>
                    </button>
                </div>
            @endif

            <div class="border-t border-gray-100 pt-8 space-y-4">
                <h4 class="text-xs font-bold text-[#0f2d50] uppercase tracking-wider">Petunjuk Pembayaran</h4>
                <div class="space-y-3 text-xs text-gray-500 leading-relaxed">
                    <div class="flex gap-3">
                        <span class="w-5 h-5 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center font-bold shrink-0 text-[10px]">1</span>
                        <p>Buka aplikasi perbankan atau e-wallet pilihan Anda.</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="w-5 h-5 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center font-bold shrink-0 text-[10px]">2</span>
                        <p>Lakukan pembayaran sesuai detail nominal yang tertera di atas (tidak boleh kurang atau lebih).</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="w-5 h-5 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center font-bold shrink-0 text-[10px]">3</span>
                        <p>Simpan bukti transfer dan transaksi Anda akan otomatis dikonfirmasi oleh sistem **AMARTA**.</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-dashed border-gray-150 pt-8 text-center space-y-4">
                <div class="bg-blue-50 border border-blue-100 p-4 rounded-2xl">
                    <p class="text-[10px] text-blue-700 font-bold uppercase tracking-wider">Verifikasi Real-time</p>
                    <p class="text-[10px] text-gray-500 mt-1">Gunakan tombol verifikasi di bawah jika Anda telah menyelesaikan pengiriman dana agar sistem langsung memproses pesanan Anda.</p>
                </div>

                <form action="{{ route('orders.simulate_payment', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-4 rounded-2xl font-bold text-xs uppercase tracking-widest transition shadow-lg shadow-green-500/20 transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i> Saya Sudah Menyelesaikan Pembayaran
                    </button>
                </form>

                <button type="button" @click="showCancelModal = true"
                        class="w-full bg-white hover:bg-red-50 border border-gray-150 text-red-500 py-4 rounded-2xl font-bold text-xs uppercase tracking-widest transition">
                    Batalkan Pemesanan Jasa
                </button>

                <button type="button" @click="showCancelModal = true" class="block w-full text-center text-xs font-bold text-[#0f2d50] hover:underline uppercase tracking-wider">
                    Kembali ke Dashboard
                </button>
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
            
            <div class="bg-white rounded-[2.5rem] p-8 max-w-sm w-full mx-4 shadow-2xl border border-gray-100 space-y-6 text-center transform transition-all"
                 @click.away="showCancelModal = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="scale-95 translate-y-4"
                 x-transition:enter-end="scale-100 translate-y-0">
                
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto text-red-500 text-2xl">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                
                <div>
                    <h3 class="text-lg font-black text-[#0f2d50]">Batalkan Pembayaran?</h3>
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                        Apakah Anda yakin mau kembali dan membatalkan pembayaran ini? Pemesanan jasa teknisi Anda akan otomatis dibatalkan oleh sistem.
                    </p>
                </div>
                
                <div class="flex flex-col gap-2">
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-red-500 hover:bg-red-600 text-white py-3.5 rounded-xl font-bold text-xs uppercase tracking-widest transition">
                            Ya, Batalkan Pesanan
                        </button>
                    </form>
                    
                    <button @click="showCancelModal = false" 
                            class="w-full bg-gray-50 hover:bg-gray-100 text-[#0f2d50] py-3.5 rounded-xl font-bold text-xs uppercase tracking-widest transition border border-gray-150">
                        Tidak, Lanjutkan
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>

<style>
    [x-cloak] { display: none !important; }
</style>