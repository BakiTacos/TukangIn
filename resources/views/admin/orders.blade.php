<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-20">
        <div class="bg-[#0f2d50] pb-32 pt-12">
            <div class="container mx-auto px-6 text-white">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-gray-400 hover:text-white transition uppercase tracking-wider"><i class="fas fa-chevron-left mr-1"></i> Kembali ke HQ Visualisasi</a>
                <h1 class="text-3xl font-black mt-4">📋 Pusat Log Audit Transaksi & Sengketa</h1>
            </div>
        </div>

        <div class="container mx-auto px-6 -mt-16">
            <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-gray-100">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b border-gray-50 pb-6 mb-6">
                    <div>
                        <h3 class="text-base font-black text-[#0f2d50] uppercase tracking-wider">Alur Transaksi Finansial</h3>
                    </div>
                    
                    <div class="flex flex-wrap bg-gray-100 p-1.5 rounded-2xl gap-1 text-[11px] font-bold">
                        <a href="{{ route('admin.orders.index') }}?status=semua" class="px-4 py-2 rounded-xl transition {{ $statusFilter === 'semua' ? 'bg-[#0f2d50] text-white' : 'text-gray-500 hover:text-gray-800' }}">Semua Log</a>
                        <a href="{{ route('admin.orders.index') }}?status=pengerjaan" class="px-4 py-2 rounded-xl transition {{ $statusFilter === 'pengerjaan' ? 'bg-blue-600 text-white' : 'text-gray-500 hover:text-gray-800' }}">Pengerjaan</a>
                        <a href="{{ route('admin.orders.index') }}?status=selesai" class="px-4 py-2 rounded-xl transition {{ $statusFilter === 'selesai' ? 'bg-green-600 text-white' : 'text-gray-500 hover:text-gray-800' }}">Selesai</a>
                        <a href="{{ route('admin.orders.index') }}?status=batal" class="px-4 py-2 rounded-xl transition {{ $statusFilter === 'batal' ? 'bg-red-600 text-white' : 'text-gray-500 hover:text-gray-800' }}">Batal</a>
                        <a href="{{ route('admin.orders.index') }}?status=dikomplain" class="px-4 py-2 rounded-xl transition {{ $statusFilter === 'dikomplain' ? 'bg-purple-600 text-white' : 'text-gray-500 hover:text-gray-800' }}">⚠️ Sengketa (Dikomplain)</a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 text-gray-400 text-[10px] font-black uppercase tracking-widest bg-gray-50/50 rounded-xl">
                                <th class="py-4 px-4">Invoice ID</th>
                                <th class="py-4 px-4">Pelanggan</th>
                                <th class="py-4 px-4">Teknisi Ahli</th>
                                <th class="py-4 px-4">Nilai Kontrak</th>
                                <th class="py-4 px-4 text-center">Status Global</th>
                                <th class="py-4 px-4 text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-xs font-medium text-gray-700">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="py-4 px-4 font-black text-blue-600">#{{ $order->order_number }}</td>
                                    <td class="py-4 px-4 font-bold text-gray-800">{{ $order->user->name ?? 'User Terhapus' }}</td>
                                    <td class="py-4 px-4 font-bold text-gray-800">{{ $order->tukang->name ?? 'Belum Ditunjuk' }}</td>
                                    <td class="py-4 px-4 font-extrabold text-gray-900">Rp {{ number_format($order->total_cost, 0, ',', '.') }}</td>
                                    <td class="py-4 px-4 text-center">
                                        <span class="inline-block text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-wide
                                            {{ $order->status === 'selesai' ? 'bg-green-50 text-green-600 border border-green-100' : '' }}
                                            {{ $order->status === 'dikomplain' ? 'bg-purple-50 text-purple-600 border border-purple-100' : '' }}
                                            {{ $order->status === 'proses' || $order->status === 'pengerjaan' ? 'bg-blue-50 text-blue-600 border border-blue-100' : '' }}
                                            {{ $order->status === 'batal' ? 'bg-gray-100 text-gray-400' : '' }}
                                            {{ $order->status === 'menunggu' || $order->status === 'pending' ? 'bg-amber-50 text-amber-600 border border-amber-100' : '' }}
                                        ">{{ $order->status }}</span>
                                    </td>
                                    
                                    <td class="py-4 px-4 text-center">
                                        @if($order->status === 'dikomplain')
                                            <a href="{{ route('admin.orders.review', $order->id) }}" 
                                               class="inline-block bg-purple-50 border border-purple-200 text-purple-600 hover:bg-purple-600 hover:text-white px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-wider transition duration-200 shadow-sm">
                                                ⚖️ Resolusi Sengketa
                                            </a>
                                        @else
                                            <a href="{{ route('admin.orders.review', $order->id) }}" 
                                               class="inline-block bg-gray-50 border border-gray-200 text-gray-400 hover:bg-[#0f2d50] hover:text-white px-4 py-2 rounded-xl font-bold text-[10px] uppercase tracking-wider transition duration-200">
                                                <i class="far fa-eye mr-1"></i> Lihat Detail
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-12 text-gray-400 font-bold uppercase">Tidak Ada Log Riwayat Transaksi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $recentOrders->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    [x-cloak] { display: none !important; }
</style>