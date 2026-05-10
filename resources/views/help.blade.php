<x-app-layout>
    <div class="bg-gray-50 text-[#0f2d50] min-h-screen pb-12">

        <section class="bg-[#0f2d50] text-white py-20 relative overflow-hidden">
            <div class="container mx-auto px-6 text-center relative z-10">
                <p class="text-orange-400 font-bold tracking-widest text-xs mb-4 uppercase">Ada yang bisa kami bantu?</p>
                <h1 class="text-3xl md:text-5xl font-bold mb-8">Pusat Bantuan Tukang.in</h1>
                
                <div class="max-w-2xl mx-auto relative">
                    <input type="text" placeholder="Cari masalah Anda (misal: cara bayar, garansi...)" 
                           class="w-full px-8 py-5 rounded-2xl text-gray-800 focus:outline-none shadow-2xl">
                    <button class="absolute right-3 top-3 bg-[#e67e22] p-3 rounded-xl hover:bg-[#d35400] transition">
                        <i class="fas fa-search text-white"></i>
                    </button>
                </div>
            </div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
        </section>

        <section class="py-16 container mx-auto px-6 -mt-10 relative z-20">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white p-8 rounded-[2rem] shadow-lg border border-gray-100 text-center hover:-translate-y-2 transition-all duration-200 cursor-pointer">
                    <div class="bg-blue-100 text-blue-600 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shopping-cart text-xl"></i>
                    </div>
                    <h4 class="font-bold text-sm">Pemesanan</h4>
                </div>
                <div class="bg-white p-8 rounded-[2rem] shadow-lg border border-gray-100 text-center hover:-translate-y-2 transition-all duration-200 cursor-pointer">
                    <div class="bg-green-100 text-green-600 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                    <h4 class="font-bold text-sm">Pembayaran</h4>
                </div>
                <div class="bg-white p-8 rounded-[2rem] shadow-lg border border-gray-100 text-center hover:-translate-y-2 transition-all duration-200 cursor-pointer">
                    <div class="bg-orange-100 text-orange-600 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-shield text-xl"></i>
                    </div>
                    <h4 class="font-bold text-sm">Akun & Keamanan</h4>
                </div>
                <div class="bg-white p-8 rounded-[2rem] shadow-lg border border-gray-100 text-center hover:-translate-y-2 transition-all duration-200 cursor-pointer">
                    <div class="bg-purple-100 text-purple-600 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-handshake text-xl"></i>
                    </div>
                    <h4 class="font-bold text-sm">Kemitraan</h4>
                </div>
            </div>
        </section>

        <section class="py-12 container mx-auto px-6 max-w-4xl">
            <h2 class="text-2xl font-bold mb-8 text-center text-[#0f2d50]">Pertanyaan Populer</h2>
            
            <div class="space-y-4" x-data="{ active: 1 }">
                <div class="bg-white rounded-[1.5rem] border border-gray-100 overflow-hidden shadow-sm">
                    <button @click="active = (active === 1 ? null : 1)" 
                            class="w-full px-8 py-5 text-left flex justify-between items-center hover:bg-gray-50 transition">
                        <span class="font-bold text-[#0f2d50]">Bagaimana cara memanggil tukang di Tukang.in?</span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="active === 1 ? 'rotate-180 text-orange-500' : 'text-gray-400'"></i>
                    </button>
                    <div x-show="active === 1" x-collapse class="px-8 pb-5 text-gray-500 text-sm leading-relaxed" x-cloak>
                        Anda cukup memilih kategori layanan (misal: Konstruksi atau Kelistrikan), memilih mitra tukang berdasarkan rating, dan menekan tombol "Booking Sekarang" untuk mengatur jadwal kunjungan teknisi kami.
                    </div>
                </div>

                <div class="bg-white rounded-[1.5rem] border border-gray-100 overflow-hidden shadow-sm">
                    <button @click="active = (active === 2 ? null : 2)" 
                            class="w-full px-8 py-5 text-left flex justify-between items-center hover:bg-gray-50 transition">
                        <span class="font-bold text-[#0f2d50]">Apakah layanan Tukang.in memiliki garansi?</span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="active === 2 ? 'rotate-180 text-orange-500' : 'text-gray-400'"></i>
                    </button>
                    <div x-show="active === 2" x-collapse class="px-8 pb-5 text-gray-500 text-sm leading-relaxed" x-cloak>
                        Ya, setiap pekerjaan melalui TUKANGIN mendapatkan jaminan kualitas selama 7 hari kalender setelah pekerjaan dinyatakan selesai oleh pelanggan.
                    </div>
                </div>

                <div class="bg-white rounded-[1.5rem] border border-gray-100 overflow-hidden shadow-sm">
                    <button @click="active = (active === 3 ? null : 3)" 
                            class="w-full px-8 py-5 text-left flex justify-between items-center hover:bg-gray-50 transition">
                        <span class="font-bold text-[#0f2d50]">Metode pembayaran apa saja yang tersedia?</span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="active === 3 ? 'rotate-180 text-orange-500' : 'text-gray-400'"></i>
                    </button>
                    <div x-show="active === 3" x-collapse class="px-8 pb-5 text-gray-500 text-sm leading-relaxed" x-cloak>
                        Kami mendukung berbagai metode pembayaran mulai dari transfer bank, E-Wallet (OVO, GoPay), hingga QRIS untuk memastikan kemudahan transaksi bagi Anda.
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 container mx-auto px-6">
            <div class="bg-gradient-to-r from-gray-800 to-black rounded-[3rem] p-12 text-white flex flex-col md:flex-row items-center justify-between shadow-2xl relative overflow-hidden">
                <div class="relative z-10 mb-8 md:mb-0 text-center md:text-left">
                    <h2 class="text-3xl font-bold mb-2">Masih butuh bantuan?</h2>
                    <p class="opacity-70">Tim CS TUKANGIN siap membantu Anda 24/7 melalui jalur berikut.</p>
                </div>
                <div class="relative z-10 flex space-x-4">
                    
                    <a href="mailto:support@tukangin.com" class="bg-white/10 hover:bg-white/20 backdrop-blur-md text-white px-8 py-3 rounded-xl font-bold border border-white/30 transition flex items-center justify-center">
                        Kirim Email
                    </a>
                </div>
                <img src="https://illustrations.popsy.co/white/customer-support.svg" class="absolute right-0 bottom-0 w-48 opacity-20 pointer-events-none">
            </div>
        </section>

    </div>
</x-app-layout>

<style>
    [x-cloak] { display: none !important; }
</style>