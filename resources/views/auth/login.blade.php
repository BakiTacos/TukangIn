<x-app-layout>
    <div class="flex flex-col md:flex-row min-h-screen">
        <div class="hidden md:flex md:w-1/2 bg-[#f0f4f8] flex-col items-center justify-center p-10">
            <div class="max-w-md w-full text-center">
                <div class="bg-white p-4 rounded-[2.5rem] shadow-xl mb-12">
                    <img src="{{ asset('images/login-hero.jpg') }}" alt="Tukang In" class="rounded-[2rem] w-full object-cover shadow-inner">
                </div>
                
                <h2 class="text-3xl font-extrabold text-[#0f2d50] mb-2 uppercase tracking-tight">Solusi Praktis</h2>
                <h3 class="text-4xl font-extrabold text-[#1e40af] mb-6 uppercase">Perbaikan Rumah</h3>
                
                <p class="text-gray-500 leading-relaxed px-4">
                    Masuk untuk melanjutkan pesanan Anda dan temukan tukang profesional bersertifikat di sekitar Anda.
                </p>
            </div>
        </div>

        <div class="w-full md:w-1/2 bg-white flex items-center justify-center p-8 md:p-20">
            <div class="w-full max-w-md">
                <div class="mb-10">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang Kembali</h2>
                    <p class="text-gray-500">Silakan masuk untuk akses penuh layanan kami.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email atau Nomor Telepon</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                <i class="far fa-envelope text-lg"></i>
                            </span>
                            <input type="email" name="email" class="w-full pl-12 pr-4 py-4 bg-[#f8fafc] border border-gray-100 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="nama@email.com">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-2">
                            <label class="text-sm font-bold text-gray-700">Kata Sandi</label>
                            <a href="#" class="text-xs font-bold text-blue-600">Lupa Password?</a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                <i class="fas fa-lock text-lg"></i>
                            </span>
                            <input type="password" name="password" class="w-full pl-12 pr-4 py-4 bg-[#f8fafc] border border-gray-100 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#ff7a30] hover:bg-[#e66a20] text-white font-extrabold py-4 rounded-2xl shadow-lg transition-all transform hover:scale-[1.02]">
                        Masuk Sekarang
                    </button>

                    <div class="relative flex items-center py-4">
                        <div class="flex-grow border-t border-gray-100"></div>
                        <span class="mx-4 text-xs text-gray-400 uppercase tracking-widest">Atau masuk dengan</span>
                        <div class="flex-grow border-t border-gray-100"></div>
                    </div>
                    <p class="text-center text-sm text-gray-500 mt-8">
                        Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Daftar Sekarang</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>