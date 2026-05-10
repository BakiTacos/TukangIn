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

        <form action="{{ Auth::user()->role === 'tukang' ? route('tukang.orders.cancel', $order->id) : route('orders.cancel', $order->id) }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider">Alasan Pembatalan</label>
                <div class="relative">
                    <select name="cancel_reason" x-model="cancelReason" required
                            class="w-full bg-gray-50 border border-gray-150 rounded-2xl py-4 px-5 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent appearance-none cursor-pointer">
                        <option value="">-- Pilih Alasan Pembatalan --</option>
                        
                        @if(Auth::user()->role === 'tukang')
                            <option value="Kendala cuaca ekstrem / Hujan badai">Kendala cuaca ekstrem / Hujan badai</option>
                            <option value="Peralatan kerja mengalami kerusakan mendadak">Peralatan kerja mengalami kerusakan mendadak</option>
                            <option value="Kendala transportasi / Ban bocor di perjalanan">Kendala transportasi / Ban bocor di jalan</option>
                            <option value="Kondisi kesehatan mendadak tidak fit / Sakit">Kondisi kesehatan mendadak tidak fit / Sakit</option>
                            <option value="Lokasi pengerjaan tidak kondusif / Tidak dapat diakses">Lokasi pengerjaan tidak dapat diakses</option>
                            <option value="Lainnya">Lainnya (Tulis detail di bawah)</option>
                        @else
                            <option value="Ingin mengubah detail rincian jasa">Ingin mengubah detail rincian jasa</option>
                            <option value="Ada urusan mendadak / keluar kota">Ada urusan mendadak / keluar kota</option>
                            <option value="Menemukan teknisi lain di luar platform">Menemukan teknisi lain di luar platform</option>
                            <option value="Teknisi meminta biaya tambahan tidak wajar">Teknisi meminta biaya tambahan tidak wajar</option>
                            <option value="Lainnya">Lainnya (Tulis detail di bawah)</option>
                        @endif
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
                          placeholder="Tuliskan umpan balik jujur mengapa pesanan ini harus dibatalkan..."
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
                    Batalkan Pekerjaan
                </button>
            </div>
        </form>
    </div>
</div>

@if(Auth::user()->role === 'tukang')
<div x-show="showCompleteModal" 
     class="fixed inset-0 z-50 flex items-center justify-center bg-[#0f2d50]/40 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     x-cloak>
    
    <div class="bg-white rounded-[2.5rem] p-10 max-w-lg w-full mx-4 shadow-2xl border border-gray-100 transform transition-all"
         @click.away="showCompleteModal = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="scale-95 translate-y-4"
         x-transition:enter-end="scale-100 translate-y-0">
        
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-50">
            <h3 class="text-lg font-black text-[#0f2d50] uppercase tracking-wider">Konfirmasi Penyelesaian</h3>
            <button @click="showCompleteModal = false" class="text-gray-400 hover:text-gray-600 transition text-lg">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('tukang.orders.complete', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="bg-green-50 border border-green-100 p-4 rounded-2xl text-green-700 text-xs flex gap-3">
                <i class="fas fa-info-circle text-lg shrink-0 mt-0.5 animate-bounce"></i>
                <p class="leading-relaxed">
                    Pastikan perbaikan alat/jasa selesai dengan sempurna. Unggah foto bukti pengerjaan akhir Anda di bawah untuk merampungkan transaksi.
                </p>
            </div>

            <div class="space-y-3">
                <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider">Foto Hasil Perbaikan (Wajib)</label>
                <div class="relative border-2 border-dashed border-gray-200 hover:border-orange-400 transition rounded-2xl p-6 flex flex-col items-center justify-center text-center cursor-pointer bg-gray-50">
                    <input type="file" name="completion_photo" required accept="image/*" 
                           x-on:change="completePhoto = $event.target.files[0] ? $event.target.files[0].name : ''" 
                           class="absolute inset-0 opacity-0 cursor-pointer z-10">
                    
                    <div class="text-gray-400 flex flex-col items-center justify-center gap-2">
                        <i class="fas fa-camera text-3xl text-orange-500"></i>
                        <p class="text-xs font-bold text-gray-600" x-text="completePhoto ? 'Terpilih: ' + completePhoto : 'Ambil Foto / Upload Dokumen'"></p>
                        <p class="text-[10px] text-gray-400">Format: JPG, PNG, JPEG (Maksimal 2MB)</p>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-50">
                <button type="button" @click="showCompleteModal = false"
                        class="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-600 py-4 rounded-2xl font-bold text-xs uppercase tracking-widest transition border border-gray-150">
                    Batal
                </button>
                <button type="submit" 
                        :disabled="!completePhoto"
                        :class="!completePhoto ? 'opacity-50 cursor-not-allowed hover:transform-none' : ''"
                        class="flex-1 bg-green-500 hover:bg-green-600 text-white py-4 rounded-2xl font-bold text-xs uppercase tracking-widest transition shadow-lg shadow-green-500/20 transform hover:-translate-y-1">
                    Konfirmasi Selesai
                </button>
            </div>
        </form>
    </div>
</div>
@endif

@if(Auth::user()->role !== 'tukang')
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
@endif