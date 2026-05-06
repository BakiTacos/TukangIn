<x-app-layout>
    <!-- Header Banner -->
    <div class="bg-[#0f2d50] pb-32 pt-12">
        <div class="container mx-auto px-6 text-white">
            <nav class="text-xs text-gray-400 mb-4 uppercase tracking-widest">
                Home > Profil > Pengaturan
            </nav>
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold mb-2">Pengaturan Akun</h1>
                    <p class="text-gray-300">Perbarui informasi profil dan keamanan akun Anda.</p>
                </div>
                <a href="{{ route('profile.index') }}" class="bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-2xl font-bold text-xs transition border border-white/10">
                    <i class="fas fa-chevron-left mr-2"></i> Kembali ke Profil
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 -mt-20 pb-20">
        <div class="max-w-4xl mx-auto space-y-8">
            
            <!-- Section 1: Informasi Profil -->
            <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-xl shadow-blue-900/5 border border-gray-100 relative overflow-hidden">
                <div class="flex items-center gap-4 mb-10 border-b border-gray-50 pb-6">
                    <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500">
                        <i class="fas fa-user-edit text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-[#0f2d50]">Informasi Profil</h3>
                        <p class="text-xs text-gray-400">Perbarui nama lengkap dan alamat email akun Anda.</p>
                    </div>
                </div>
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Section 2: Keamanan / Password -->
            <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-xl shadow-blue-900/5 border border-gray-100">
                <div class="flex items-center gap-4 mb-10 border-b border-gray-50 pb-6">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500">
                        <i class="fas fa-shield-alt text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-[#0f2d50]">Keamanan Akun</h3>
                        <p class="text-xs text-gray-400">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak.</p>
                    </div>
                </div>
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Section 3: Hapus Akun -->
            <div class="bg-red-50/30 p-8 md:p-12 rounded-[2.5rem] border border-red-100">
                <div class="flex items-center gap-4 mb-10 border-b border-red-100/50 pb-6">
                    <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center text-red-500">
                        <i class="fas fa-user-slash text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-red-600">Hapus Akun</h3>
                        <p class="text-xs text-red-400">Setelah akun dihapus, semua sumber daya dan datanya akan dihapus permanen.</p>
                    </div>
                </div>
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>