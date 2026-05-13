@props(['schedules'])

@php
    // Urutkan jadwal secara urutan kalender (Senin -> Minggu) dan filter hanya yang aktif
    $dayOrder = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7];
    $activeSchedules = $schedules->where('is_active', true)->sortBy(function($sch) use ($dayOrder) {
        return $dayOrder[$sch->day] ?? 8;
    });
@endphp

<div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm mb-6">
    <h4 class="text-sm font-black text-[#0f2d50] uppercase tracking-wider mb-4 flex items-center gap-2">
        <i class="fas fa-clock text-orange-500"></i> Jadwal Kerja Aktif
    </h4>

    <div class="space-y-3">
        @forelse($activeSchedules as $schedule)
            <div class="flex justify-between items-center py-2.5 px-4 bg-gray-50/50 rounded-2xl border border-gray-100">
                <span class="text-xs font-bold text-gray-750">{{ $schedule->day }}</span>
                <span class="text-xs font-extrabold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg">
                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                </span>
            </div>
        @empty
            <div class="text-center py-6">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Sedang Istirahat Panjang</p>
                <p class="text-[9px] text-gray-450 mt-1 leading-relaxed">Mitra saat ini sedang tidak mengambil jadwal operasional aktif.</p>
            </div>
        @endforelse
    </div>
</div>