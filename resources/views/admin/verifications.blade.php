<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="container mx-auto px-6 max-w-6xl">
            
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-[#0f2d50] transition mb-6 uppercase tracking-wider">
                <i class="fas fa-chevron-left"></i> Kembali ke Dashboard
            </a>

            <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-8 md:p-10 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-orange-50/50">
                    <div>
                        <h2 class="text-2xl font-black text-[#0f2d50] uppercase tracking-wide">Audit Aplikasi Mitra Baru</h2>
                        <p class="text-xs text-gray-500 mt-1">Tinjau antrean verifikasi NIK dan spesialisasi teknisi sebelum memberikan izin akses ke jaringan TUKANG.IN.</p>
                    </div>
                    <div class="bg-white px-5 py-3 rounded-2xl border border-orange-100 shadow-sm flex items-center gap-3">
                        <div class="w-8 h-8 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center animate-pulse"><i class="fas fa-clock"></i></div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Antrean</p>
                            <p class="text-sm font-bold text-[#0f2d50]">{{ $pendingMitras->total() }} Menunggu</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                <th class="py-5 px-8">Data Pribadi & NIK Sesuai KTP</th>
                                <th class="py-5 px-6">Domisili Operasional</th>
                                <th class="py-5 px-6">Kategori & Spesialisasi</th>
                                <th class="py-5 px-8 text-center">Keputusan Audit</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs text-gray-700 divide-y divide-gray-50">
                            @forelse($pendingMitras as $mitra)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="py-5 px-8">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center font-bold text-sm border border-blue-100">
                                                {{ substr($mitra->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-black text-[#0f2d50] text-sm">{{ $mitra->name }}</p>
                                                <p class="text-orange-600 font-bold mt-0.5 tracking-wider"><i class="fas fa-id-card mr-1 text-gray-300"></i>{{ $mitra->nik ?? 'TIDAK ADA NIK' }}</p>
                                                <p class="text-[10px] text-gray-400 mt-0.5">{{ $mitra->phone }} • {{ $mitra->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-5 px-6">
                                        <p class="font-bold text-gray-800">{{ $mitra->city }}, {{ $mitra->province }}</p>
                                        <p class="text-[10px] text-gray-400 mt-1 truncate max-w-[200px]" title="{{ $mitra->address }}">{{ $mitra->address }}</p>
                                    </td>
                                    <td class="py-5 px-6">
                                        <span class="inline-block bg-blue-50 text-blue-600 text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest border border-blue-100 mb-1">
                                            {{ $mitra->category }}
                                        </span>
                                        <p class="text-[10px] font-medium text-gray-500">{{ Str::limit($mitra->specialty ?? '-', 40) }}</p>
                                    </td>
                                    <td class="py-5 px-8">
                                        <div class="flex justify-center gap-2">
                                            <!-- Tombol Terima -->
                                            <form action="{{ route('admin.verifications.approve', $mitra->id) }}" method="POST" onsubmit="return confirm('Yakin menyetujui dan mengaktifkan akun {{ $mitra->name }}?');">
                                                @csrf
                                                <button type="submit" class="bg-green-50 text-green-600 hover:bg-green-600 hover:text-white px-4 py-2.5 rounded-xl font-bold text-[10px] uppercase tracking-wider transition border border-green-100 shadow-sm flex items-center gap-1.5">
                                                    <i class="fas fa-check-circle"></i> Setujui
                                                </button>
                                            </form>

                                            <!-- Tombol Tolak -->
                                            <form action="{{ route('admin.verifications.reject', $mitra->id) }}" method="POST" onsubmit="return confirm('Tolak pendaftaran mitra ini? Akun akan diblokir.');">
                                                @csrf
                                                <button type="submit" class="bg-red-50 text-red-500 hover:bg-red-600 hover:text-white px-4 py-2.5 rounded-xl font-bold text-[10px] uppercase tracking-wider transition border border-red-100 shadow-sm flex items-center gap-1.5">
                                                    <i class="fas fa-times-circle"></i> Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-16 text-center">
                                        <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-4 border border-gray-100"><i class="fas fa-clipboard-check"></i></div>
                                        <h4 class="text-sm font-black text-[#0f2d50] uppercase tracking-widest">Semua Bersih</h4>
                                        <p class="text-xs text-gray-400 mt-2">Saat ini tidak ada antrean pendaftaran mitra teknisi baru yang perlu diaudit.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($pendingMitras->hasPages())
                    <div class="p-6 border-t border-gray-100 bg-gray-50/50">
                        {{ $pendingMitras->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>