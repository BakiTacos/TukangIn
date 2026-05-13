<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-20" x-data="{ showBlockModal: false, blockActionUrl: '', targetName: '' }">
        <div class="bg-[#0f2d50] pb-32 pt-12">
            <div class="container mx-auto px-6 text-white">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-gray-400 hover:text-white transition uppercase tracking-wider"><i class="fas fa-chevron-left mr-1"></i> Kembali ke HQ Visualisasi</a>
                <h1 class="text-3xl font-black mt-4">🛡️ Ruang Manajemen Otoritas Akun</h1>
            </div>
        </div>

        <div class="container mx-auto px-6 -mt-16">
            <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-gray-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-50 pb-6 mb-6">
                    <h3 class="text-base font-black text-[#0f2d50] uppercase tracking-wider">Daftar Akun Anggota Platform</h3>
                    <form action="{{ route('admin.users') }}" method="GET" class="w-full sm:w-72">
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
                            @foreach($users as $u)
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="py-4 px-4 font-black text-gray-800">{{ $u->name }}</td>
                                    <td class="py-4 px-4 text-gray-500">{{ $u->email }}</td>
                                    <td class="py-4 px-4 font-bold uppercase text-[10px] text-[#0f2d50]">{{ $u->role }}</td>
                                    <td class="py-4 px-4">
                                        @if($u->is_blocked)
                                            <span class="text-red-500 font-bold flex flex-col"><span>🚨 Terblokir</span><small class="text-[9px] text-gray-400 font-normal italic">Ket: "{{ $u->blocked_reason }}"</small></span>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $users->links() }}</div>
            </div>
        </div>

        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-show="showBlockModal" x-transition x-cloak>
            <div class="bg-white w-full max-w-md p-8 rounded-[2.5rem] shadow-2xl border border-gray-100" @click.away="showBlockModal = false">
                <h4 class="text-base font-black text-[#0f2d50] uppercase tracking-wider mb-2">🚫 Suspend Akses Akun</h4>
                <p class="text-xs text-gray-400 mb-6">Anda menangguhkan hak akses akun <strong class="text-red-500" x-text="targetName"></strong> dari TUKANG.IN.</p>
                <form :action="blockActionUrl" method="POST">
                    @csrf
                    <div class="space-y-2 mb-6"><label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Alasan Penangguhan</label><textarea name="blocked_reason" required rows="3" placeholder="Sebutkan pelanggaran..." class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 px-4 text-xs font-medium focus:ring-2 focus:ring-red-500"></textarea></div>
                    <div class="flex gap-3"><button type="button" @click="showBlockModal = false" class="w-1/2 bg-gray-100 text-gray-500 py-3.5 rounded-2xl font-bold text-xs uppercase hover:bg-gray-200 transition">Batal</button><button type="submit" class="w-1/2 bg-red-500 text-white py-3.5 rounded-2xl font-bold text-xs uppercase hover:bg-red-600 transition shadow-lg">Eksekusi</button></div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<style>[x-cloak] { display: none !important; }</style>