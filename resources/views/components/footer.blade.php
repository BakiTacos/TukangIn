
    <footer class="bg-[#0f2d50] text-gray-300 py-16">
        <div class="container mx-auto px-6 grid md:grid-cols-4 gap-12 text-sm">
            <div>
                <div class="flex items-center space-x-2 mb-6 text-white">
                    <img src="{{ asset('images/logo.png') }}" class="h-10 w-auto object-contain" alt="Logo">
                    <span class="text-xl font-bold">Tukang.in</span>
                </div>
                <p class="leading-relaxed mb-6 opacity-70">Platform terdepan di Indonesia yang menghubungkan Anda dengan ribuan teknisi berpengalaman untuk berbagai kebutuhan perawatan rumah.</p>
                <div class="flex space-x-4">
                    <a href="#" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-orange-500 transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-orange-500 transition"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-orange-500 transition"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6 uppercase text-xs tracking-widest">Layanan Utama</h4>
                <ul class="space-y-3 opacity-70">
                    @foreach($footerServices as $service)
                        <li>
                            <a href="{{ route('services.show', $service->slug) }}" class="hover:text-orange-400 transition">
                                {{ $service->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6 uppercase text-xs tracking-widest">Akses Cepat</h4>
                <ul class="space-y-3 opacity-70">
                    <li><a href="/tentang-kami" class="hover:text-orange-400">Tentang Tukang.in</a></li>
                    <li><a href="/pusat-bantuan" class="hover:text-orange-400">Pusat Bantuan</a></li>
                    <li><a href="/layanan" class="hover:text-orange-400">Layanan Kami</a></li>
                    <li>
                        <a href="{{ route('register.tukang') }}" class="text-gray-400 hover:text-orange-500 transition duration-200">
                            Jadi Mitra Kami
                        </a>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6 uppercase text-xs tracking-widest">Hubungi Kami</h4>
                <p class="mb-2 opacity-70">support@tukangin.in</p>
                <p class="mb-4 opacity-70">0800 1234 5678</p>
                <p class="opacity-70">Sudirman Central Business District, Jakarta</p>
            </div>
        </div>
        <div class="container mx-auto px-6 mt-16 pt-8 border-t border-white/10 flex flex-col md:row justify-between items-center text-[10px] opacity-50 uppercase tracking-widest">
            <p>&copy; 2026 Tukang.in. Powered by Handyman Network Indonesia.</p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="/syarat-ketentuan">Syarat & Ketentuan</a>
                <a href="/kebijakan-privasi">Kebijakan Privasi</a>
            </div>
        </div>
    </footer>