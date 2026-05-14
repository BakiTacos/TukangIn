<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="container mx-auto px-6 max-w-4xl">
            
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-[#0f2d50] transition mb-6 uppercase tracking-wider">
                <i class="fas fa-chevron-left"></i> Kembali ke Log Transaksi
            </a>

            <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-gray-100 space-y-8">
                
                <div class="border-b border-gray-50 pb-6 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                    <div>
                        <span class="inline-block text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest border
                            {{ $order->status === 'dikomplain' ? 'bg-purple-100 text-purple-600 border-purple-200 animate-pulse' : '' }}
                            {{ $order->status === 'selesai' ? 'bg-green-50 text-green-600 border-green-100' : '' }}
                            {{ $order->status === 'pengerjaan' ? 'bg-blue-50 text-blue-600 border-blue-150' : '' }}
                            {{ $order->status === 'batal' ? 'bg-red-50 text-red-500 border-red-100' : '' }}
                        ">
                            Status Utama: {{ strtoupper($order->status) }}
                        </span>
                        
                        @if($order->sub_status)
                            <span class="inline-block text-[9px] font-black bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1.5 rounded-lg uppercase tracking-widest ml-1">
                                Sub-Fase: {{ str_replace('_', ' ', $order->sub_status) }}
                            </span>
                        @endif

                        <h2 class="text-2xl font-black text-[#0f2d50] mt-3">Audit Sengketa Invoice #{{ $order->order_number }}</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Sistem Audit Log TUKANG.IN • Diperbarui pada {{ \Carbon\Carbon::parse($order->updated_at)->translatedFormat('d F Y, H:i') }} WIB</p>
                    </div>
                    <div class="text-left sm:text-right bg-gray-50 p-4 rounded-2xl border border-gray-100 min-w-[180px]">
                        <p class="text-[9px] text-gray-400 font-bold uppercase">Nilai Kontrak Ditahan</p>
                        <p class="text-xl font-black text-gray-900 mt-0.5">Rp {{ number_format($order->total_cost, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                        <h4 class="text-[10px] font-bold text-blue-600 uppercase tracking-wider mb-3">👤 Data Pelanggan (Pelapor)</h4>
                        <p class="text-sm font-black text-gray-800">{{ $order->user->name ?? 'User Terhapus' }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $order->user->email ?? '' }} • {{ $order->user->phone ?? '' }}</p>
                        <div class="mt-4 pt-4 border-t border-gray-200/60">
                            <p class="text-[9px] font-bold text-gray-400 uppercase">Alamat Lokasi Pengerjaan</p>
                            <p class="text-xs text-gray-600 font-medium mt-1 leading-relaxed">{{ $order->address->full_address ?? 'Lokasi Kustom' }}</p>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                        <h4 class="text-[10px] font-bold text-orange-500 uppercase tracking-wider mb-3">🛠️ Data Mitra Teknisi (Terlapor)</h4>
                        <p class="text-sm font-black text-gray-800">{{ $order->tukang->name ?? 'Belum Ditunjuk' }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Kategori: {{ $order->tukang->category ?? 'Spesialis' }}</p>
                        <div class="mt-4 pt-4 border-t border-gray-200/60">
                            <p class="text-[9px] font-bold text-gray-400 uppercase">Layanan yang Diorder</p>
                            <p class="text-xs text-[#0f2d50] font-black mt-1">{{ $order->service->title ?? 'Custom Item' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50/50 border border-purple-100 p-6 rounded-2xl space-y-4">
                    <div>
                        <h4 class="text-[10px] font-bold text-purple-600 uppercase tracking-wider mb-2 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> Berita Acara Keluhan Pelanggan (Awal):
                        </h4>
                        <p class="text-xs font-bold text-gray-800 mb-1">"{{ $order->cancel_reason ?? 'Tidak disebutkan judul alasan secara spesifik' }}"</p>
                        <p class="text-xs text-gray-600 leading-relaxed italic">
                            {{ $order->cancel_description ?? 'Pelanggan mengajukan sengketa tanpa menyertakan deskripsi teks tambahan.' }}
                        </p>
                    </div>

                    @if($order->complaint_image)
                        @php
                            $rawEndpoint = getenv('SUPABASE_STORAGE_ENDPOINT') ?: ($_ENV['SUPABASE_STORAGE_ENDPOINT'] ?? '');
                            if (str_contains($rawEndpoint, 'SUPABASE_STORAGE_ENDPOINT=')) {
                                $rawEndpoint = str_replace('SUPABASE_STORAGE_ENDPOINT=', '', $rawEndpoint);
                            }
                            $rawEndpoint = trim($rawEndpoint);
                            $supabaseBucket = getenv('SUPABASE_STORAGE_BUCKET') ?: 'tukangin-complain';

                            if (!empty($rawEndpoint)) {
                                $supabasePublicBase = str_replace('/storage/v1/s3', '/storage/v1/object/public/' . $supabaseBucket, $rawEndpoint);
                                if (str_contains($supabasePublicBase, 'https:/') && !str_contains($supabasePublicBase, 'https://')) {
                                    $supabasePublicBase = str_replace('https:/', 'https://', $supabasePublicBase);
                                }
                                $fullComplaintUrl = rtrim($supabasePublicBase, '/') . '/' . ltrim($order->complaint_image, '/');
                            } else {
                                $fullComplaintUrl = asset('storage/' . $order->complaint_image);
                            }
                        @endphp
                        
                        <div class="pt-2 border-t border-purple-100">
                            <span class="block text-[9px] font-black text-purple-500 uppercase tracking-widest mb-2">Lampiran Dokumen Bukti Fisik:</span>
                            <a href="{{ $fullComplaintUrl }}" target="_blank" class="inline-block group relative overflow-hidden rounded-2xl border border-purple-200 bg-white p-2 hover:shadow-lg transition duration-300">
                                <img src="{{ $fullComplaintUrl }}" 
                                     alt="Bukti Sengketa TUKANG.IN" 
                                     class="w-full max-w-sm h-48 object-cover rounded-xl group-hover:scale-[1.02] transition duration-300">
                                <div class="absolute inset-0 bg-gray-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center rounded-xl">
                                    <span class="text-white font-bold text-[10px] uppercase bg-[#0f2d50] py-2 px-4 rounded-xl shadow-md"><i class="fas fa-search-plus mr-1"></i> Perbesar Foto</span>
                                </div>
                            </a>
                        </div>
                    @endif
                </div>

                <div class="border-t border-gray-50 pt-8">
                    @if($order->status === 'dikomplain')
                        <h3 class="text-base font-black text-[#0f2d50] uppercase tracking-wider mb-2">⚖️ Ambil Keputusan Arbitrase & Sub-Status</h3>
                        <p class="text-xs text-gray-400 mb-6">Pilih putusan final untuk mencairkan/refund dana, atau pindahkan ke sub-fase banding mediasi lanjutan.</p>
                        
                        <form action="{{ route('admin.orders.resolve', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin dengan keputusan tingkat arbitrase ini?')">
                            @csrf
                            
                            <div class="space-y-2 mb-6">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tindakan / Sub-Status Putusan</label>
                                <select name="status" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3.5 px-4 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-orange-500 cursor-pointer">
                                    <option value="">-- Tentukan Opsi Resolusi Kasus --</option>
                                    
                                    <optgroup label="🔒 KEPUTUSAN ABSOLUT (INKRAH)">
                                        <option value="selesai" {{ $order->sub_status === 'komplain_ditolak' ? 'selected' : '' }}>✓ SELESAI (Tolak Komplain - Cairkan Dana ke Teknisi)</option>
                                        <option value="batal" {{ $order->sub_status === 'komplain_diterima' ? 'selected' : '' }}>✕ BATAL (Terima Komplain - Refund Dana Penuh ke User)</option>
                                    </optgroup>
                                    
                                    <optgroup label="⏳ TAHAP MEDIASI / BANDING BERJALAN">
                                        <option value="dikomplain" {{ $order->sub_status === 'proses_banding' ? 'selected' : '' }}>⚠️ PROSES BANDING (Tahan Dana - Evaluasi Bukti Tambahan)</option>
                                        <option value="pengerjaan" {{ $order->sub_status === 'garansi_perbaikan' ? 'selected' : '' }}>🛠️ KEMBALIKAN KE PENGERJAAN (Perintahkan Garansi Perbaikan Ulang)</option>
                                    </optgroup>
                                </select>
                            </div>

                            <div class="space-y-2 mb-8">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Catatan Dasar Pertimbangan & Hasil Sidang Kasus</label>
                                <textarea name="admin_note" required rows="4" 
                                    placeholder="Tulis kronologi hasil mediasi atau dasar pertimbangan di sini..." 
                                    class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3.5 px-4 text-xs font-medium focus:ring-2 focus:ring-orange-500">{{ $order->admin_note }}</textarea>
                            </div>

                            <button type="submit" class="w-full bg-[#0f2d50] hover:bg-orange-500 text-white py-4 rounded-2xl font-bold text-xs uppercase tracking-widest transition shadow-lg flex items-center justify-center gap-2">
                                Simpan Keputusan Arbitrase <i class="fas fa-gavel"></i>
                            </button>
                        </form>
                    @else
                        @if($order->sub_status)
                            <div class="bg-gray-50 border border-gray-200 p-8 rounded-[2rem] space-y-6 animate-fade-in">
                                <div class="flex items-center gap-4 border-b border-gray-200 pb-4">
                                    <div class="w-12 h-12 bg-[#0f2d50] text-white rounded-2xl flex items-center justify-center text-lg shadow-md">
                                        <i class="fas fa-archive"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-[#0f2d50] uppercase tracking-wider">Berkas Arsip Keputusan Perkara</h4>
                                        <p class="text-[11px] text-gray-400">Sengketa sengketa ini telah selesai disidangkan dan dikunci oleh Direksi TUKANG.IN.</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                    <div class="p-4 bg-white rounded-2xl border border-gray-100 shadow-sm">
                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Amar Putusan Utama</p>
                                        <p class="font-black text-gray-800 mt-1 uppercase text-sm">
                                            @if($order->status === 'selesai')
                                                🟢 Selesai (Komplain Ditolak)
                                            @elseif($order->status === 'batal')
                                                🔴 Batal (Komplain Diterima)
                                            @elseif($order->status === 'pengerjaan')
                                                🛠️ Dikembalikan (Garansi Perbaikan Kerja)
                                            @endif
                                        </p>
                                    </div>
                                    <div class="p-4 bg-white rounded-2xl border border-gray-100 shadow-sm">
                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Sub-Status Validasi</p>
                                        <p class="font-extrabold text-blue-600 mt-1 uppercase text-sm">
                                            {{ str_replace('_', ' ', $order->sub_status ?? 'Final Inkrah') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="p-6 bg-white rounded-2xl border border-gray-100 shadow-sm">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-2">Risalah & Catatan Pertimbangan Resmi Admin</p>
                                    <p class="text-xs text-gray-700 leading-relaxed italic bg-gray-50/70 p-4 rounded-xl border border-gray-100 font-semibold text-justify">
                                        "{{ $order->admin_note ?? 'Tidak ada catatan kesimpulan tertulis dari admin.' }}"
                                    </p>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

<style>
    [x-cloak] { display: none !important; }
</style>