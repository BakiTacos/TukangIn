<x-app-layout>
    <div class="container mx-auto px-6 py-12" x-data="{ paymentMethod: 'bnpl' }">
        
        <nav class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-10">
            <span class="hover:text-orange-500 cursor-pointer">Secure Checkout</span> 
            <span class="mx-2 text-gray-300">></span> 
            <span class="text-orange-500">Payment</span>
        </nav>

        <div class="flex flex-col lg:flex-row gap-10">
            <div class="lg:w-2/3 space-y-8">
                <div>
                    <h2 class="text-2xl font-black text-[#0f2d50] mb-8">Select Payment Method</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div @click="paymentMethod = 'bnpl'" :class="paymentMethod === 'bnpl' ? 'border-orange-500 ring-1 ring-orange-500' : 'border-gray-100'" class="bg-white p-6 rounded-3xl border-2 cursor-pointer transition-all hover:shadow-md flex items-center gap-5">
                            <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-[#0f2d50]"><i class="fas fa-calendar-alt text-xl"></i></div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-[#0f2d50]">BNPL (Pay Later)</h4>
                                <p class="text-[10px] text-gray-400">Cicilan hingga 12 bulan</p>
                            </div>
                            <div x-show="paymentMethod === 'bnpl'" class="text-orange-500"><i class="fas fa-check-circle"></i></div>
                        </div>

                        <div @click="paymentMethod = 'gopay'" :class="paymentMethod === 'gopay' ? 'border-orange-500 ring-1 ring-orange-500' : 'border-gray-100'" class="bg-white p-6 rounded-3xl border-2 cursor-pointer transition-all hover:shadow-md flex items-center gap-5">
                            <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-[#0f2d50]"><i class="fas fa-wallet text-xl"></i></div>
                            <div class="flex-1"><h4 class="text-sm font-bold text-[#0f2d50]">GoPay</h4><p class="text-[10px] text-gray-400">Pembayaran instan & aman</p></div>
                            <div x-show="paymentMethod === 'gopay'" class="text-orange-500"><i class="fas fa-check-circle"></i></div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Lokasi Pengerjaan</h4>
                    <div class="flex items-center gap-4">
                        <i class="fas fa-map-marker-alt text-orange-500"></i>
                        <p class="text-xs text-gray-600 font-medium">
                            {{ $address->full_address ?? 'Alamat belum diatur' }}, {{ $address->city ?? '' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="lg:w-1/3">
                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-50 sticky top-10">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-8">Service Summary</h4>
                    
                    <div class="flex items-center gap-5 mb-6">
                        <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center overflow-hidden">
                            <img src="{{ $service->image_url ?? 'https://ui-avatars.com/api/?name=Service' }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h3 class="font-bold text-[#0f2d50] text-lg">{{ $service->title }}</h3>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider">{{ $service->category->name ?? 'Maintenance' }}</p>
                        </div>
                    </div>

                    <div class="bg-orange-50/50 p-5 rounded-[1.5rem] border border-orange-100 mb-8">
                        <p class="text-[9px] font-bold text-orange-500 uppercase tracking-widest mb-3">Teknisi Terpilih</p>
                        <div class="flex items-center gap-4">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($tukang->name) }}&background=FFEDD5&color=F97316" class="w-12 h-12 rounded-xl border border-orange-200">
                            <div>
                                <h5 class="text-sm font-bold text-[#0f2d50]">{{ $tukang->name }}</h5>
                                <p class="text-[10px] text-gray-400">Spesialis {{ $service->title }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 border-t border-gray-50 pt-8 mb-8 text-sm">
                        <div class="flex justify-between">
                            <p class="text-gray-400">Biaya Layanan</p>
                            <p class="font-bold text-[#0f2d50]">Rp {{ number_format($serviceFee, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex justify-between">
                            <p class="text-gray-400">Biaya Teknisi</p>
                            <p class="font-bold text-[#0f2d50]">Rp {{ number_format($technicianFee, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex justify-between">
                            <p class="text-gray-400">Pajak Platform (2%)</p>
                            <p class="font-bold text-[#0f2d50]">Rp {{ number_format($taxAmount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center border-t border-gray-100 pt-8 mb-10">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Payment</p>
                            <p class="text-2xl font-black text-[#0f2d50]">Rp {{ number_format($totalPayment, 0, ',', '.') }}</p>
                        </div>
                        <span class="bg-green-50 text-green-600 text-[9px] font-bold px-3 py-1.5 rounded-lg border border-green-100 uppercase">Promo Applied</span>
                    </div>

                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                        <input type="hidden" name="tukang_id" value="{{ $tukang->id }}">
                        <input type="hidden" name="address_id" value="{{ $address->id ?? '' }}">
                        <input type="hidden" name="payment_method" :value="paymentMethod">
                        <input type="hidden" name="total_payment" value="{{ $totalPayment }}">
                        
                        <button type="submit" class="w-full bg-[#e67e22] hover:bg-[#d35400] text-white py-5 rounded-2xl font-bold text-sm uppercase tracking-widest transition shadow-lg shadow-orange-500/20 transform hover:-translate-y-1">
                            Confirm Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    [x-cloak] { display: none !important; }
</style>