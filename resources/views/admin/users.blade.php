<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-20" x-data="{ showBlockModal: false, blockActionUrl: '', targetName: '' }">
        
        <div class="bg-[#0f2d50] pb-32 pt-12">
            <div class="container mx-auto px-6 text-white">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-gray-400 hover:text-white transition uppercase tracking-wider">
                    <i class="fas fa-chevron-left mr-1"></i> Kembali ke Dashboard
                </a>
                <h1 class="text-3xl font-black mt-4">🛡️ Ruang Manajemen Otoritas Akun</h1>
            </div>
        </div>

        <div class="container mx-auto px-6 -mt-16">
            <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-gray-100">
                
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-gray-50 pb-6 mb-6">
                    <div>
                        <h3 class="text-base font-black text-[#0f2d50] uppercase tracking-wider">Daftar Akun Anggota Platform</h3>
                    </div>
                    
                    <div class="flex flex-wrap bg-gray-100 p-1.5 rounded-2xl gap-1 text-[11px] font-bold">
                        <a href="{{ route('admin.users') }}?status=semua&search_user={{ request('search_user') }}" 
                           class="px-4 py-2 rounded-xl transition {{ $statusFilter === 'semua' ? 'bg-[#0f2d50] text-white' : 'text-gray-500 hover:text-gray-800' }}">Semua Akun</a>
                        <a href="{{ route('admin.users') }}?status=aktif&search_user={{ request('search_user') }}" 
                           class="px-4 py-2 rounded-xl transition {{ $statusFilter === 'aktif' ? 'bg-green-600 text-white' : 'text-gray-500 hover:text-gray-800' }}">Aktif Normal</a>
                        <a href="{{ route('admin.users') }}?status=terblokir&search_user={{ request('search_user') }}" 
                           class="px-4 py-2 rounded-xl transition {{ $statusFilter === 'terblokir' ? 'bg-red-600 text-white' : 'text-gray-500 hover:text-gray-800' }}">🚨 Terblokir</a>
                    </div>

                    <form action="{{ route('admin.users') }}" method="GET" class="w-full lg:w-72">
                        <input type="hidden" name="status" value="{{ $statusFilter }}">
                        <input type="text" name="search_user" value="{{ request('search_user') }}" placeholder="Cari nama pengguna..." class="w-full bg-gray-50 border-gray-200 rounded-xl py-2.5 px-4 text-xs font-bold focus:ring-2 focus:ring-orange-500">
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 text-gray-400 text-[10px] font-black uppercase tracking-widest bg-gray-50/50 rounded-xl">
                                <th class="py-4 px-4">Nama Akun</th>
                                <th class="py-4 px-4">Email</th>
                                <th class="py-4 px-4">Hak Akses</th>
                                <th class="py-4 px-4">Status</th>
                                <th class="py-4 px-4 text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-xs font-medium text-gray-700">
                            @forelse($users as $u)
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="py-4 px-4 font-black text-gray-800">{{ $u->name }}</td>
                                    <td class="py-4 px-4 text-gray-500">{{ $u->email }}</td>
                                    <td class="py-4 px-4 font-bold uppercase text-[10px] text-[#0f2d50]">{{ $u->role }}</td>
                                    <td class="py-4 px-4">
                                        @if($u->is_blocked)
                                            <span class="text-red-500 font-bold flex flex-col">
                                                <span>🚨 Terblokir</span>
                                                <small class="text-[9px] text-gray-400 font-normal italic mt-0.5">Ket: "{{ $u->blocked_reason }}"</small>
                                            </span>
                                        @else
                                            <span class="text-green-600 font-bold">✓ Aktif Normal</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        @if($u->is_blocked)
                                            <form action="{{ route('admin.users.unblock', $u->id) }}" method="POST" onsubmit="return confirm('Buka blokir akun ini?')">
                                                @csrf
                                                <button type="submit" class="bg-green-50 text-green-600 border border-green-200 hover:bg-green-600 hover:text-white px-4 py-2 rounded-xl font-bold text-[10px] uppercase transition duration-200">Buka Blokir</button>
                                            </form>
                                        @else
                                            <button type="button" @click="showBlockModal = true; targetName = '{{ $u->name }}'; blockActionUrl = '{{ route('admin.users.block', $u->id) }}'" class="bg-red-50 text-red-500 border border-red-200 hover:bg-red-500 hover:text-white px-4 py-2 rounded-xl font-bold text-[10px] uppercase transition duration-200">Blokir Akun</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-gray-400 font-bold uppercase tracking-wider">
                                        Tidak ada data anggota dengan status "{{ $statusFilter }}"
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $users->links() }}</div>
            </div>
        </div>

        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-show="showBlockModal" x-transition x-cloak>
            <div class="bg-white w-full max-w-md p-8 rounded-[2.5rem] shadow-2xl border border-gray-100" @click.away="showBlockModal = false">
                <h4 class="text-base font-black text-[#0f2d50] uppercase tracking-wider mb-2">🚫 Suspend Akses Akun</h4>
                <p class="text-xs text-gray-400 mb-6">Anda menangguhkan hak akses akun <strong class="text-red-500" x-text="targetName"></strong> dari sistem TUKANG.IN.</p>
                
                <form :action="blockActionUrl" method="POST">
                    @csrf
                    
                    <div class="space-y-2 mb-6">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Alasan Umum Penangguhan</label>
                        <select name="blocked_reason" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3.5 px-4 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-transparent appearance-none cursor-pointer">
                            <option value="">-- Pilih Pelanggaran Akun --</option>
                            <option value="Indikasi Fraud / Manipulasi Transaksi Finansial">💵 Indikasi Fraud / Manipulasi Transaksi Finansial</option>
                            <option value="Laporan Kasus Berat dari Pengguna Platform">🚨 Laporan Kasus Berat dari Pengguna Platform</option>
                            <option value="Pelanggaran Ketentuan & Syarat Layanan TUKANG.IN">📜 Pelanggaran Ketentuan & Syarat Layanan TUKANG.IN</option>
                            <option value="Perilaku Tidak Profesional / Intimidasi Lapangan">🤬 Perilaku Tidak Profesional / Intimidasi Lapangan</option>
                            <option value="Penyalahgunaan Akun / Indikasi Identitas Palsu">👥 Penyalahgunaan Akun / Indikasi Identitas Palsu</option>
                            <option value="Akumulasi Rating Buruk / Kinerja Di Bawah Standar">📉 Akumulasi Rating Buruk / Kinerja Di Bawah Standar</option>
                        </select>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="button" @click="showBlockModal = false" class="w-1/2 bg-gray-100 text-gray-500 py-3.5 rounded-2xl font-bold text-xs uppercase hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" class="w-1/2 bg-red-500 text-white py-3.5 rounded-2xl font-bold text-xs uppercase hover:bg-red-600 transition shadow-lg shadow-red-200">Eksekusi</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>

<style>[x-cloak] { display: none !important; }</style>