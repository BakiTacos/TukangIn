<x-app-layout>
    <div class="container mx-auto px-6 py-12" 
         x-data="{ 
            paymentMethod: 'gopay', 
            selectedBank: 'bca',
            selectedAddressId: '{{ $address->id ?? '' }}',
            serviceFee: {{ $serviceFee }},
            technicianFee: {{ $technicianFee }},

            // 1. Menghitung Pajak Platform murni 5% dari Subtotal (Layanan + Teknisi)
            get platformTax() {
                return Math.round((this.serviceFee + this.technicianFee) * 0.05);
            },

            // State Voucher
            promoCode: '',
            appliedPromo: '',
            promoDiscount: 0,
            promoError: '',

            // 2. Menghitung Biaya Transaksi secara Real-time berdasarkan Metode Pembayaran
            get paymentFee() {
                let base = this.serviceFee + this.technicianFee;
                if (this.paymentMethod === 'gopay') {
                    return Math.round(base * 0.02); // GoPay: 2%
                } else if (this.paymentMethod === 'dana') {
                    return Math.round(base * 0.015); // DANA: 1.5%
                } else if (this.paymentMethod === 'qris') {
                    return Math.round(base * 0.007); // QRIS: 0.7%
                } else if (this.paymentMethod === 'bank_transfer') {
                    return 4000; // Virtual Account: Flat Rp 4.000
                }
                return 0;
            },

            // 3. Fungsi Terapkan Voucher secara Instan
            applyPromo() {
                let code = this.promoCode.trim().toUpperCase();
                let base = this.serviceFee + this.technicianFee;

                if (code === 'NEWUSERDANCE') {
                    this.appliedPromo = 'NEWUSERDANCE';
                    this.promoDiscount = base + this.platformTax; // Gratis biaya dasar & tax
                    this.promoError = '';
                } else if (code === 'NEWUSERKING') {
                    this.appliedPromo = 'NEWUSERKING';
                    this.promoDiscount = Math.round(base * 0.5); // Diskon 50%
                    this.promoError = '';
                } else if (code === 'NEWUSERKANG') {
                    this.appliedPromo = 'NEWUSERKANG';
                    this.promoDiscount = Math.round(base * 0.2); // Diskon 20% (Rp 56.000)
                    this.promoError = '';
                } else {
                    this.promoError = 'Kode voucher tidak valid!';
                    this.appliedPromo = '';
                    this.promoDiscount = 0;
                }
            },

            // 4. Fungsi Batalkan Voucher
            removePromo() {
                this.appliedPromo = '';
                this.promoCode = '';
                this.promoDiscount = 0;
                this.promoError = '';
            },

            // 5. Menghitung Total Pembayaran Akhir
            get totalPayment() {
                let total = this.serviceFee + this.technicianFee + this.platformTax + this.paymentFee - this.promoDiscount;
                return total < 0 ? 0 : total;
            },

            formatRupiah(num) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
            }
         }">
        
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
                        <div @click="paymentMethod = 'gopay'" 
                             :class="paymentMethod === 'gopay' ? 'border-orange-500 ring-1 ring-orange-500' : 'border-gray-100'"
                             class="bg-white p-6 rounded-3xl border-2 cursor-pointer transition-all hover:shadow-md relative flex items-center gap-5">
                            <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-[#0f2d50]">
                                <i class="fas fa-wallet text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-[#0f2d50]">GoPay</h4>
                                <p class="text-[10px] text-gray-400">Biaya transaksi +2.0%</p>
                            </div>
                            <div x-show="paymentMethod === 'gopay'" class="text-orange-500"><i class="fas fa-check-circle"></i></div>
                            <div x-show="paymentMethod !== 'gopay'" class="w-5 h-5 border-2 border-gray-100 rounded-full"></div>
                        </div>

                        <div @click="paymentMethod = 'dana'" 
                             :class="paymentMethod === 'dana' ? 'border-orange-500 ring-1 ring-orange-500' : 'border-gray-100'"
                             class="bg-white p-6 rounded-3xl border-2 cursor-pointer transition-all hover:shadow-md relative flex items-center gap-5">
                            <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-[#0f2d50]">
                                <i class="fas fa-mobile-alt text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-[#0f2d50]">DANA</h4>
                                <p class="text-[10px] text-gray-400">Biaya transaksi +1.5%</p>
                            </div>
                            <div x-show="paymentMethod === 'dana'" class="text-orange-500"><i class="fas fa-check-circle"></i></div>
                            <div x-show="paymentMethod !== 'dana'" class="w-5 h-5 border-2 border-gray-100 rounded-full"></div>
                        </div>

                        <div @click="paymentMethod = 'bank_transfer'" 
                             :class="paymentMethod === 'bank_transfer' ? 'border-orange-500 ring-1 ring-orange-500' : 'border-gray-100'"
                             class="bg-white p-6 rounded-3xl border-2 cursor-pointer transition-all hover:shadow-md relative flex items-center gap-5">
                            <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-[#0f2d50]">
                                <i class="fas fa-university text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-[#0f2d50]">Virtual Account</h4>
                                <p class="text-[10px] text-gray-400">Biaya admin +Rp 4.000</p>
                            </div>
                            <div x-show="paymentMethod === 'bank_transfer'" class="text-orange-500"><i class="fas fa-check-circle"></i></div>
                            <div x-show="paymentMethod !== 'bank_transfer'" class="w-5 h-5 border-2 border-gray-100 rounded-full"></div>
                        </div>

                        <div @click="paymentMethod = 'qris'" 
                             :class="paymentMethod === 'qris' ? 'border-orange-500 ring-1 ring-orange-500' : 'border-gray-100'"
                             class="bg-white p-6 rounded-3xl border-2 cursor-pointer transition-all hover:shadow-md relative flex items-center gap-5">
                            <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-[#0f2d50]">
                                <i class="fas fa-qrcode text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-[#0f2d50]">QRIS</h4>
                                <p class="text-[10px] text-gray-400">Biaya transaksi +0.7%</p>
                            </div>
                            <div x-show="paymentMethod === 'qris'" class="text-orange-500"><i class="fas fa-check-circle"></i></div>
                            <div x-show="paymentMethod !== 'qris'" class="w-5 h-5 border-2 border-gray-100 rounded-full"></div>
                        </div>
                    </div>

                    <div x-show="paymentMethod === 'bank_transfer'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-6 p-6 bg-gray-50 rounded-3xl border border-gray-150 space-y-4"
                         x-cloak>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider">Pilih Bank Virtual Account</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div @click="selectedBank = 'bca'" :class="selectedBank === 'bca' ? 'border-orange-500 bg-orange-50/30' : 'border-gray-200 bg-white'" class="border-2 p-4 rounded-2xl cursor-pointer text-center transition hover:shadow-sm">
                                <span class="text-xs font-black text-[#0f2d50]">BCA</span>
                            </div>
                            <div @click="selectedBank = 'mandiri'" :class="selectedBank === 'mandiri' ? 'border-orange-500 bg-orange-50/30' : 'border-gray-200 bg-white'" class="border-2 p-4 rounded-2xl cursor-pointer text-center transition hover:shadow-sm">
                                <span class="text-xs font-black text-[#0f2d50]">MANDIRI</span>
                            </div>
                            <div @click="selectedBank = 'bni'" :class="selectedBank === 'bni' ? 'border-orange-500 bg-orange-50/30' : 'border-gray-200 bg-white'" class="border-2 p-4 rounded-2xl cursor-pointer text-center transition hover:shadow-sm">
                                <span class="text-xs font-black text-[#0f2d50]">BNI</span>
                            </div>
                            <div @click="selectedBank = 'bri'" :class="selectedBank === 'bri' ? 'border-orange-500 bg-orange-50/30' : 'border-gray-200 bg-white'" class="border-2 p-4 rounded-2xl cursor-pointer text-center transition hover:shadow-sm">
                                <span class="text-xs font-black text-[#0f2d50]">BRI</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-8 rounded-[2rem] border border-gray-100">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
                        <div>
                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Lokasi Pengerjaan</h4>
                            <p class="text-[10px] text-gray-400 mt-1">Pilih alamat pengerjaan untuk teknisi Anda</p>
                        </div>
                        <a href="{{ route('profile.address') }}" class="text-[10px] font-bold text-orange-500 uppercase tracking-widest hover:underline flex items-center gap-1">
                            <i class="fas fa-plus"></i> Tambah / Kelola Alamat
                        </a>
                    </div>

                    @if(auth()->user()->addresses->isNotEmpty())
                        <div class="relative">
                            <select x-model="selectedAddressId" class="w-full bg-white border border-gray-100 rounded-2xl py-4 px-5 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent appearance-none cursor-pointer">
                                @foreach(auth()->user()->addresses as $addr)
                                    <option value="{{ $addr->id }}">
                                        [{{ $addr->label }}] {{ $addr->receiver_name }} — {{ $addr->full_address }}, {{ $addr->city }} ({{ $addr->postal_code }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-gray-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-red-50 p-4 rounded-2xl border border-red-100">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                                <p class="text-xs font-bold text-red-600">Anda belum mendaftarkan alamat pengerjaan.</p>
                            </div>
                            <a href="{{ route('profile.address') }}" class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-xl font-bold text-[10px] uppercase tracking-wider transition">
                                Tambah Alamat Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:w-1/3">
                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-50 sticky top-10">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-8">Service Summary</h4>
                    
                    <div class="flex items-center gap-5 mb-6">
                        <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center overflow-hidden border border-gray-50">
                            @if($service->image)
                                <img src="{{ asset('storage/' . $service->image) }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-tools text-[#0f2d50] text-xl"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-bold text-[#0f2d50] leading-tight text-lg">{{ $service->title }}</h3>
                            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">{{ $service->category ?? 'Maintenance' }}</p>
                        </div>
                    </div>

                    <div class="bg-orange-50/50 p-5 rounded-[1.5rem] border border-orange-100 mb-6">
                        <p class="text-[9px] font-bold text-orange-500 uppercase tracking-widest mb-3">Teknisi Terpilih</p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm overflow-hidden border border-orange-200">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($tukang->name) }}&background=FFEDD5&color=F97316" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-[#0f2d50]">{{ $tukang->name }}</h5>
                                <p class="text-[10px] text-gray-400">Spesialis {{ $service->title }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-6 mb-6">
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2 tracking-wider">Miliki Kode Voucher?</label>
                        <div class="relative flex items-center w-full">
                            <input type="text" x-model="promoCode" placeholder="Masukkan voucher" :disabled="appliedPromo !== ''"
                                   class="w-full bg-gray-50 border border-gray-150 rounded-2xl py-4 pl-5 pr-24 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent uppercase placeholder-gray-300 disabled:bg-gray-100 disabled:text-gray-400">
                            
                            <div class="absolute right-2">
                                <button type="button" x-show="appliedPromo === ''" @click="applyPromo()"
                                        class="bg-[#0f2d50] hover:bg-orange-500 text-white px-4 py-2 rounded-xl font-extrabold text-[10px] tracking-wider transition uppercase">
                                    Apply
                                </button>
                                <button type="button" x-show="appliedPromo !== ''" @click="removePromo()"
                                        class="bg-red-50 hover:bg-red-100 text-red-500 px-4 py-2 rounded-xl font-extrabold text-[10px] tracking-wider transition uppercase" x-cloak>
                                    Batal
                                </button>
                            </div>
                        </div>
                        <p x-show="promoError" class="text-[10px] text-red-500 font-bold mt-2" x-text="promoError" x-cloak></p>
                        <p x-show="appliedPromo" class="text-[10px] text-green-600 font-bold mt-2" x-cloak>
                            <i class="fas fa-check-circle mr-1"></i> Voucher <span x-text="appliedPromo" class="underline"></span> aktif!
                        </p>
                    </div>

                    <div class="space-y-4 border-t border-gray-50 pt-6 mb-6 text-sm">
                        <div class="flex justify-between items-center">
                            <p class="text-gray-400">Biaya Layanan</p>
                            <p class="font-bold text-[#0f2d50]" x-text="formatRupiah(serviceFee)"></p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-gray-400">Biaya Teknisi</p>
                            <p class="font-bold text-[#0f2d50]" x-text="formatRupiah(technicianFee)"></p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-gray-400">Pajak Platform (5%)</p>
                            <p class="font-bold text-[#0f2d50]" x-text="formatRupiah(platformTax)"></p>
                        </div>
                        <div class="flex justify-between items-center text-orange-600 font-medium">
                            <p>Biaya Admin (<span class="uppercase font-bold" x-text="paymentMethod === 'bank_transfer' ? selectedBank + ' VA' : paymentMethod"></span>)</p>
                            <p class="font-bold" x-text="formatRupiah(paymentFee)"></p>
                        </div>
                        <div class="flex justify-between items-center text-green-600 font-medium" x-show="promoDiscount > 0" x-cloak>
                            <p>Diskon Voucher (<span x-text="appliedPromo"></span>)</p>
                            <p class="font-bold" x-text="'- ' + formatRupiah(promoDiscount)"></p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center border-t border-gray-100 pt-6 mb-8">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Payment</p>
                            <p class="text-2xl font-black text-[#0f2d50]" x-text="formatRupiah(totalPayment)"></p>
                        </div>
                        <span x-show="promoDiscount > 0" class="bg-green-50 text-green-600 text-[9px] font-bold px-3 py-1.5 rounded-lg border border-green-100 uppercase" x-cloak>
                            Promo Applied
                        </span>
                    </div>

                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                        <input type="hidden" name="tukang_id" value="{{ $tukang->id }}">
                        <input type="hidden" name="address_id" :value="selectedAddressId">
                        
                        <input type="hidden" name="payment_method" :value="paymentMethod">
                        <input type="hidden" name="payment_bank" :value="paymentMethod === 'bank_transfer' ? selectedBank : ''">
                        <input type="hidden" name="promo_code" :value="appliedPromo">
                        
                        <input type="hidden" name="service_fee" :value="serviceFee">
                        <input type="hidden" name="technician_fee" :value="technicianFee">
                        <input type="hidden" name="tax_amount" :value="platformTax">
                        <input type="hidden" name="payment_fee" :value="paymentFee">
                        <input type="hidden" name="discount_amount" :value="promoDiscount">
                        <input type="hidden" name="total_cost" :value="totalPayment">
                        
                        <button type="submit" 
                                :disabled="!selectedAddressId"
                                :class="!selectedAddressId ? 'opacity-50 cursor-not-allowed hover:transform-none' : ''"
                                class="w-full bg-[#e67e22] hover:bg-[#d35400] text-white py-5 rounded-2xl font-bold text-sm uppercase tracking-widest transition shadow-lg shadow-orange-500/20 transform hover:-translate-y-1">
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