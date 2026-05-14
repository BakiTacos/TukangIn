<x-app-layout>
    <div class="min-h-[80vh] flex items-center justify-center bg-gray-50 py-12 px-6">
        <div class="max-w-2xl w-full text-center space-y-8 bg-white p-12 rounded-[3rem] shadow-xl border border-gray-100 relative overflow-hidden">
            
            @if(auth()->user()->status_verifikasi === 'ditolak')
                <!-- ❌ TAMPILAN JIKA PENDAFTARAN DITOLAK -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-red-50 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-orange-50 rounded-full blur-3xl"></div>

                <div class="relative">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-red-50 text-red-500 rounded-[2rem] mb-6">
                        <i class="fas fa-ban text-4xl"></i>
                    </div>

                    <h2 class="text-3xl font-black text-[#0f2d50] uppercase tracking-tighter italic">Pendaftaran Ditolak</h2>
                    
                    <div class="mt-6 space-y-4 max-w-md mx-auto">
                        <p class="text-sm text-gray-500 leading-relaxed">
                            Mohon maaf, aplikasi kemitraan Anda pada <span class="font-bold text-[#0f2d50]">TUKANG.IN</span> belum dapat kami setujui. Hal ini biasanya dikarenakan <span class="font-bold text-red-600">ketidaksesuaian data NIK: {{ auth()->user()->nik }}</span> atau standar spesialisasi yang belum memenuhi kriteria platform kami.
                        </p>
                    </div>

                    <div class="pt-8 border-t border-gray-50 mt-8 flex flex-col gap-3">
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Tindakan Selanjutnya</p>
                        <div class="flex justify-center gap-4">
                            
                            <span class="text-gray-200">|</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs font-bold text-red-400 hover:text-red-600 transition">
                                    <i class="fas fa-sign-out-alt"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            @else
                <!-- ⏳ TAMPILAN JIKA MASIH MENUNGGU VERIFIKASI -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-orange-50 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl"></div>

                <div class="relative">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-orange-50 text-orange-500 rounded-[2rem] mb-6 animate-pulse">
                        <i class="fas fa-user-shield text-4xl"></i>
                    </div>

                    <h2 class="text-3xl font-black text-[#0f2d50] uppercase tracking-tighter italic">Data Anda Telah Diterima!</h2>
                    
                    <div class="mt-6 space-y-4 max-w-md mx-auto">
                        <p class="text-sm text-gray-500 leading-relaxed">
                            Terima kasih telah mendaftar sebagai mitra di <span class="font-bold text-[#0f2d50]">TUKANG.IN</span>. Saat ini, tim audit kami sedang melakukan verifikasi terhadap <span class="font-bold text-orange-600">NIK: {{ auth()->user()->nik }}</span> dan data domisili Anda.
                        </p>
                        
                        <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl flex items-center gap-4 text-left">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-500 shadow-sm">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Estimasi Waktu Tunggu</p>
                                <p class="text-xs font-bold text-gray-700">1 x 24 Jam (Hari Kerja)</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-gray-50 mt-8 flex flex-col gap-3">
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Butuh bantuan darurat?</p>
                        <div class="flex justify-center gap-4">
                            
                            <span class="text-gray-200">|</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs font-bold text-red-400 hover:text-red-600 transition">
                                    <i class="fas fa-sign-out-alt"></i> Keluar Sementara
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>