<div class="sticky top-24 space-y-6" x-data="{ 
    showCancelModal: false, 
    showCompleteModal: false, 
    showComplainModal: false, 
    cancelAction: '', 
    complaintAction: '' 
}">
    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Ringkasan Invoice</h4>
        
        @if(Auth::user()->role === 'tukang')
            <div class="space-y-4 text-sm border-b border-gray-50 pb-6 mb-6">
                <div class="flex justify-between items-center">
                    <p class="text-gray-400 font-medium">Biaya Jasa Anda</p>
                    <p class="font-bold text-[#0f2d50] text-lg">Rp {{ number_format($order->technician_fee, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="flex justify-between items-center mb-8">
                <div>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Pendapatan Bersih</p>
                    <p class="text-2xl font-black text-green-600">Rp {{ number_format($order->technician_fee, 0, ',', '.') }}</p>
                </div>
            </div>
        @else
            <div class="space-y-4 text-sm border-b border-gray-50 pb-6 mb-6">
                <div class="flex justify-between items-center">
                    <p class="text-gray-400">Biaya Layanan</p>
                    <p class="font-bold text-[#0f2d50]">Rp {{ number_format($order->service_fee, 0, ',', '.') }}</p>
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-gray-400">Biaya Teknisi</p>
                    <p class="font-bold text-[#0f2d50]">Rp {{ number_format($order->technician_fee, 0, ',', '.') }}</p>
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-gray-400">Pajak Platform (5%)</p>
                    <p class="font-bold text-[#0f2d50]">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</p>
                </div>
                
                @if($order->platform_fee > 0)
                    <div class="flex justify-between items-center text-orange-600">
                        <p class="font-medium">Biaya Admin ({{ strtoupper($order->payment_method) }})</p>
                        <p class="font-bold">Rp {{ number_format($order->platform_fee, 0, ',', '.') }}</p>
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
        @endif

        <div class="space-y-3">
            @if(Auth::user()->role === 'tukang')
                @if($order->status === 'pending')
                    <form action="{{ route('tukang.orders.accept', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-[#e67e22] hover:bg-[#d35400] text-white py-4 rounded-2xl font-bold shadow-lg shadow-orange-500/20 transition transform hover:-translate-y-1 block text-center uppercase tracking-widest text-xs">
                            Terima Pekerjaan <i class="fas fa-check ml-1"></i>
                        </button>
                    </form>

                @elseif($order->status === 'pengerjaan')
                    <button type="button" @click="showCompleteModal = true"
                            class="w-full bg-green-500 hover:bg-green-600 text-white py-4 rounded-2xl font-bold shadow-lg shadow-green-500/25 transition transform hover:-translate-y-1 block text-center uppercase tracking-widest text-xs">
                        Selesaikan Pekerjaan <i class="fas fa-check-double ml-1"></i>
                    </button>
                    <button type="button" 
                            @click="cancelAction = '{{ route('tukang.orders.cancel', $order->id) }}'; showCancelModal = true"
                            class="w-full bg-white border-2 border-red-150 hover:bg-red-50 text-red-500 py-4 rounded-2xl font-bold flex items-center justify-center gap-2 transition text-xs uppercase tracking-wider">
                        <i class="fas fa-times-circle"></i> Batalkan Pekerjaan
                    </button>

                @elseif($order->status === 'selesai')
                    <button class="w-full bg-green-50 text-green-600 py-4 rounded-2xl font-bold cursor-default block text-center uppercase tracking-widest text-xs border border-green-150" disabled>
                        Pekerjaan Selesai <i class="fas fa-check-circle ml-1"></i>
                    </button>

                @elseif($order->status === 'dikomplain')
                    <button class="w-full bg-purple-100 text-purple-600 py-4 rounded-2xl font-bold cursor-default block text-center uppercase tracking-widest text-xs animate-pulse" disabled>
                        Komplain Ditinjau <i class="fas fa-clock ml-1"></i>
                    </button>

                @elseif($order->status === 'batal')
                    <button class="w-full bg-red-100 text-red-500 py-4 rounded-2xl font-bold cursor-not-allowed block text-center uppercase tracking-widest text-xs" disabled>
                        Pemesanan Dibatalkan
                    </button>
                @endif

            @else
                @if($order->status === 'pending')
                    <a href="{{ route('orders.payment', $order->id) }}" 
                       class="w-full bg-[#e67e22] hover:bg-[#d35400] text-white py-4 rounded-2xl font-bold shadow-lg shadow-orange-500/20 transition transform hover:-translate-y-1 block text-center uppercase tracking-widest text-xs">
                        Bayar Sekarang <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                    <button type="button" 
                            @click="cancelAction = '{{ route('orders.cancel', $order->id) }}'; showCancelModal = true"
                            class="w-full bg-white border-2 border-red-150 hover:bg-red-50 text-red-500 py-4 rounded-2xl font-bold flex items-center justify-center gap-2 transition text-xs uppercase tracking-wider">
                        <i class="fas fa-times-circle"></i> Batalkan Pesanan
                    </button>
                    
                @elseif($order->status === 'pengerjaan')
                    <button class="w-full bg-blue-500 text-white py-4 rounded-2xl font-bold cursor-default block text-center uppercase tracking-widest text-xs mb-2" disabled>
                        <i class="fas fa-spinner animate-spin mr-1"></i> Sedang Diperbaiki
                    </button>
                    
                    @if($order->created_at->gt(now()->subHours(12)))
                        <button type="button" @click="cancelAction = '{{ route('orders.cancel', $order->id) }}'; showCancelModal = true"
                                class="w-full bg-red-500 hover:bg-red-600 text-white py-4 rounded-2xl font-bold transition transform hover:-translate-y-1 block text-center uppercase tracking-widest text-xs mb-2">
                            <i class="fas fa-times-circle mr-1"></i> Batalkan Pesanan
                        </button>
                    @endif
                    
                    <button type="button" 
                            @click="complaintAction = '{{ route('orders.complain', $order->id) }}'; showComplainModal = true"
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
                           class="w-full bg-green-500 hover:bg-green-600 text-white py-4 rounded-2xl font-bold transition transform hover:-translate-y-1 block text-center uppercase tracking-widest text-xs mb-2">
                            Beri Ulasan Teknisi <i class="far fa-star ml-1"></i>
                        </a>
                        
                        @if($order->updated_at->gt(now()->subDays(7)))
                           <button type="button" 
                                   @click="complaintAction = '{{ route('orders.complain', $order->id) }}'; showComplainModal = true"
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

                    <div class="mt-6 p-6 bg-purple-50 rounded-3xl border border-purple-100 space-y-4">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-purple-600 text-white rounded-xl flex items-center justify-center text-xs shadow-sm">
            <i class="fas fa-gavel"></i>
        </div>
        <div>
            <h5 class="text-xs font-black text-[#0f2d50] uppercase tracking-wide">Berkas Laporan Sengketa Anda</h5>
            <p class="text-[10px] text-gray-400">Sedang dalam proses peninjauan objektivitas oleh Admin HQ TUKANG.IN</p>
        </div>
    </div>

    <div class="text-xs space-y-1">
        <p class="text-gray-500"><strong>Alasan Klaim:</strong> "{{ $order->cancel_reason }}"</p>
        <p class="text-gray-400 italic">"{{ $order->cancel_description }}"</p>
    </div>

    @if($order->complaint_image)
        <div class="pt-2 border-t border-purple-200/60">
            <p class="text-[9px] font-black text-purple-600 uppercase tracking-widest mb-2">Foto Bukti Terlampir:</p>
            <a href="{{ Storage::disk('supabase_complain')->url($order->complaint_image) }}" target="_blank" class="inline-block relative overflow-hidden rounded-xl border border-purple-200 bg-white p-1.5 shadow-sm hover:shadow-md transition">
                <img src="{{ Storage::disk('supabase_complain')->url($order->complaint_image) }}" 
                     alt="Bukti Unggahan Konsumen" 
                     class="w-32 h-24 object-cover rounded-lg">
            </a>
        </div>
    @endif
</div>
                    
                @elseif($order->status === 'batal')
                    <button class="w-full bg-red-100 text-red-500 py-4 rounded-2xl font-bold cursor-not-allowed block text-center uppercase tracking-widest text-xs" disabled>
                        Pemesanan Dibatalkan
                    </button>
                @endif
            @endif
        </div>
    </div>

    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-show="showComplainModal" x-transition x-cloak>
        <div class="bg-white w-full max-w-lg p-8 md:p-10 rounded-[2.5rem] shadow-2xl border border-gray-100 max-h-[90vh] overflow-y-auto" @click.away="showComplainModal = false">
            <h4 class="text-base font-black text-[#0f2d50] uppercase tracking-wider mb-2">⚖️ Ajukan Klaim Sengketa Jasa</h4>
            <p class="text-xs text-gray-400 mb-6">Dana kontrak kerja akan kami tangankan/tahan di sistem pusat demi keamanan proses peninjauan bukti.</p>
            
            <form :action="complaintAction" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Judul / Alasan Utama Komplain</label>
                    <select name="cancel_reason" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3.5 px-4 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-transparent appearance-none cursor-pointer">
                        <option value="">-- Pilih Indikasi Masalah --</option>
                        <option value="Hasil Pengerjaan Rusak / Tidak Berfungsi">🛠️ Hasil Pengerjaan Rusak / Tidak Berfungsi</option>
                        <option value="Teknisi Meninggalkan Lokasi Sebelum Selesai">🏃‍♂️ Teknisi Meninggalkan Lokasi Sebelum Selesai</option>
                        <option value="Kenaikan Biaya Sepihak di Luar Kontrak">💵 Kenaikan Biaya Sepihak di Luar Kontrak</option>
                        <option value="Kerusakan Properti Tambahan Rumah Tangga">🏠 Kerusakan Properti Tambahan Rumah Tangga</option>
                        <option value="Perilaku Mitra Kasar / Tidak Sopan">🤬 Perilaku Mitra Kasar / Tidak Sopan</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Kronologi Kejadian Singkat</label>
                    <textarea name="cancel_description" required rows="3" placeholder="Ceritakan detail kendala pengerjaan teknisi ahli di lapangan..." class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3.5 px-4 text-xs font-medium focus:ring-2 focus:ring-red-500"></textarea>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Lampirkan Foto Bukti Fisik Lapangan (Wajib)</label>
                    <div class="relative flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-200 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                            <div class="flex flex-col items-center justify-center pt-4 pb-4 text-center px-4">
                                <i class="fas fa-camera text-xl text-red-400 mb-1.5"></i>
                                <p class="text-xs font-bold text-gray-600">Klik untuk unggah dokumen gambar</p>
                                <p class="text-[9px] text-gray-400 mt-0.5">Format JPG, JPEG, PNG (Maksimal Ukuran 2MB)</p>
                            </div>
                            <input type="file" name="complaint_image" required accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" />
                        </label>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showComplainModal = false" class="w-1/3 bg-gray-100 text-gray-500 py-3.5 rounded-2xl font-bold text-xs uppercase hover:bg-gray-200 transition">Batal</button>
                    <button type="submit" class="w-2/3 bg-red-500 text-white py-3.5 rounded-2xl font-bold text-xs uppercase hover:bg-red-600 transition shadow-lg shadow-red-100">Kirim Berkas Sengketa</button>
                </div>
            </form>
        </div>
    </div>

    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-show="showCancelModal" x-transition x-cloak>
        <div class="bg-white w-full max-w-md p-8 rounded-[2.5rem] shadow-2xl border border-gray-100" @click.away="showCancelModal = false">
            <h4 class="text-base font-black text-[#0f2d50] uppercase tracking-wider mb-2">❌ Batalkan Pemesanan</h4>
            <p class="text-xs text-gray-400 mb-6">Tindakan ini tidak bisa dibatalkan kembali. Masukkan alasan penolakan kontrak pengerjaan.</p>
            
            <form :action="cancelAction" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Alasan Pembatalan</label>
                    <input type="text" name="cancel_reason" required placeholder="Contoh: Mengubah jadwal pengerjaan..." class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3.5 px-4 text-xs font-medium focus:ring-2 focus:ring-red-500">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showCancelModal = false" class="w-1/2 bg-gray-100 text-gray-500 py-3.5 rounded-2xl font-bold text-xs uppercase hover:bg-gray-200 transition">Batal</button>
                    <button type="submit" class="w-1/2 bg-red-500 text-white py-3.5 rounded-2xl font-bold text-xs uppercase hover:bg-red-600 transition shadow-lg shadow-red-100">Konfirmasi Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-show="showCompleteModal" x-transition x-cloak>
        <div class="bg-white w-full max-w-md p-8 rounded-[2.5rem] shadow-2xl border border-gray-100 text-center" @click.away="showCompleteModal = false">
            <div class="w-14 h-14 bg-green-50 text-green-500 border border-green-100 rounded-2xl flex items-center justify-center text-xl mx-auto mb-4">
                <i class="fas fa-check-double"></i>
            </div>
            <h4 class="text-base font-black text-[#0f2d50] uppercase tracking-wider mb-2">Pekerjaan Rampung?</h4>
            <p class="text-xs text-gray-400 mb-6">Pastikan seluruh area pengerjaan milik pelanggan sudah dibersihkan dan berfungsi normal sebelum dikonfirmasi selesai.</p>
            
            <form action="{{ route('tukang.orders.complete', $order->id) }}" method="POST" class="flex gap-3">
                @csrf
                <button type="button" @click="showCompleteModal = false" class="w-1/3 bg-gray-100 text-gray-500 py-3.5 rounded-2xl font-bold text-xs uppercase hover:bg-gray-200 transition">Belum</button>
                <button type="submit" class="w-2/3 bg-green-500 text-white py-3.5 rounded-2xl font-bold text-xs uppercase hover:bg-green-600 transition shadow-lg shadow-green-100">Ya, Selesai</button>
            </form>
        </div>
    </div>

</div>

<style>
    [x-cloak] { display: none !important; }
</style>