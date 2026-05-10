<x-app-layout>
    <main class="min-h-screen flex flex-col lg:flex-row">
        <!-- Sisi Kiri: Ilustrasi & Teks -->
        <div class="lg:w-1/2 bg-slate-100 flex flex-col items-center justify-center p-12 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-64 h-64 bg-blue-200/30 rounded-full -ml-32 -mt-32 blur-3xl"></div>
            <div class="relative z-10 w-full max-w-md">
                <div class="bg-white p-4 rounded-[2.5rem] shadow-2xl mb-12 transform -rotate-2 hover:rotate-0 transition-transform duration-500">
                    <img src="https://images.unsplash.com/photo-1621905251918-48416bd8575a?q=80&w=2069" 
                         alt="Teknisi Perbaikan Rumah" class="rounded-[2rem] w-full h-80 object-cover">
                </div>
                <div class="text-center">
                    <h2 class="text-2xl font-bold mb-2">Solusi Praktis</h2>
                    <h3 class="text-4xl font-extrabold text-[#0f2d50] mb-6">Perbaikan Rumah</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Daftar sekarang untuk mulai memesan dan temukan tukang profesional bersertifikat di sekitar Anda.
                    </p>
                </div>
            </div>
        </div>

        <!-- Sisi Kanan: Form Register -->
        <div class="lg:w-1/2 bg-white flex items-center justify-center p-8 lg:p-24">
            <div class="w-full max-w-md">
                <div class="mb-10">
                    <h2 class="text-3xl font-extrabold mb-3">Buat Akun Baru</h2>
                    <p class="text-gray-400 text-sm">Silakan lengkapi data untuk akses penuh layanan **AMARTA**.</p>
                </div>

                <form action="{{ route('register') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Nama Lengkap</label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-4 top-4 text-gray-400"></i>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" 
                                   class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-12 pr-4 text-sm focus:ring-2 focus:ring-orange-500 focus:bg-white transition-all shadow-sm @error('name') ring-2 ring-red-500 @enderror">
                        </div>
                        @error('name') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email (Ganti dari 'login' ke 'email' agar standar Laravel) -->
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Email</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-4 top-4 text-gray-400"></i>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" 
                                   class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-12 pr-4 text-sm focus:ring-2 focus:ring-orange-500 focus:bg-white transition-all shadow-sm @error('email') ring-2 ring-red-500 @enderror">
                        </div>
                        @error('email') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kata Sandi -->
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Kata Sandi</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-4 text-gray-400"></i>
                            <input type="password" name="password" placeholder="••••••••" 
                                   class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-12 pr-4 text-sm focus:ring-2 focus:ring-orange-500 focus:bg-white transition-all shadow-sm @error('password') ring-2 ring-red-500 @enderror">
                        </div>
                        @error('password') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Konfirmasi Kata Sandi (Penting untuk Laravel Auth) -->
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Konfirmasi Kata Sandi</label>
                        <div class="relative">
                            <i class="fas fa-check-double absolute left-4 top-4 text-gray-400"></i>
                            <input type="password" name="password_confirmation" placeholder="••••••••" 
                                   class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-12 pr-4 text-sm focus:ring-2 focus:ring-orange-500 focus:bg-white transition-all shadow-sm">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#e67e22] hover:bg-[#d35400] text-white py-4 rounded-2xl font-bold text-md shadow-lg shadow-orange-200 transition-all transform hover:-translate-y-1">
                        Daftar Sekarang
                    </button>
                </form>

                <div class="my-10 flex items-center">
                    <div class="flex-grow border-t border-gray-100"></div>
                    <span class="px-4 text-[10px] uppercase font-bold text-gray-300">Atau daftar dengan</span>
                    <div class="flex-grow border-t border-gray-100"></div>
                </div>

                

                <p class="mt-12 text-center text-sm text-gray-400">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-[#e67e22] font-bold hover:underline">Masuk Sekarang</a>
                </p>
            </div>
        </div>
    </main>
</x-app-layout>