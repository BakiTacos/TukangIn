<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Tukang.in</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-gradient {
            background: linear-gradient(rgba(15, 45, 80, 0.85), rgba(15, 45, 80, 0.85)), 
                        url('https://images.unsplash.com/photo-1504307651254-35680f356dfd?q=80&w=2070');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-white font-sans text-[#0f2d50]">

    <x-navbar />

    <!-- Hero Section: Terinspirasi Header Utama -->
    <section class="hero-gradient text-white py-28">
        <div class="container mx-auto px-6">
            <p class="text-orange-400 font-bold tracking-widest text-xs mb-4 uppercase">Dibalik Layar Tukang.in</p>
            <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-6 max-w-3xl">
                Menghadirkan Keandalan Teknik dalam Setiap Sentuhan.
            </h1>
            <p class="text-gray-300 text-lg mb-10 max-w-2xl leading-relaxed">
                Tukang.in (AMARTA) hadir sebagai solusi terintegrasi untuk kebutuhan konstruksi dan perawatan hunian, didukung oleh standar kepatuhan data dan profesionalisme tinggi.
            </p>
        </div>
    </section>

    <!-- Core Values: Mengikuti Style Card Atas di Service Detail_ Konstruksi (Desktop).jpg -->
    <section class="py-16 container mx-auto px-6 -mt-16 relative z-10">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-10 rounded-[2.5rem] shadow-xl border border-gray-50 group hover:shadow-2xl transition-all">
                <div class="bg-orange-100 text-orange-600 w-14 h-14 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-microchip text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-4">Inovasi Digital</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Memanfaatkan teknologi terkini untuk memastikan manajemen proyek konstruksi yang transparan dan efisien.</p>
            </div>
            <div class="bg-white p-10 rounded-[2.5rem] shadow-xl border border-gray-50 group hover:shadow-2xl transition-all">
                <div class="bg-blue-100 text-blue-600 w-14 h-14 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-user-check text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-4">Mitra Terverifikasi</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Setiap teknisi kami melewati proses kurasi ketat untuk menjamin hasil kerja yang presisi dan aman.</p>
            </div>
            <div class="bg-white p-10 rounded-[2.5rem] shadow-xl border border-gray-50 group hover:shadow-2xl transition-all">
                <div class="bg-purple-100 text-purple-600 w-14 h-14 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-boxes text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-4">Suku Cadang Tangguh</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Menjamin ketersediaan material dan suku cadang berkualitas tinggi untuk daya tahan jangka panjang.</p>
            </div>
        </div>
    </section>

    <!-- Project Gallery: Replikasi Section "Galeri Proyek Selesai" -->
    <section class="py-16 container mx-auto px-6">
        <div class="flex justify-between items-end mb-10">
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-2">Portfolio</p>
                <h2 class="text-3xl font-bold">Jejak Langkah Kami</h2>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="relative rounded-[2rem] overflow-hidden h-80 group">
                <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?q=80&w=2069" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent p-8 flex flex-col justify-end">
                    <p class="text-orange-400 text-xs font-bold mb-1 uppercase">Residential</p>
                    <h4 class="text-white font-bold text-xl">Renovasi Hunian Modern</h4>
                </div>
            </div>
            <div class="relative rounded-[2rem] overflow-hidden h-80 group">
                <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=1931" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent p-8 flex flex-col justify-end">
                    <p class="text-orange-400 text-xs font-bold mb-1 uppercase">Commercial</p>
                    <h4 class="text-white font-bold text-xl">Instalasi Listrik Industri</h4>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section: Replikasi Section "Siap Memulai Proyek Konstruksi Anda?" -->
    <section class="py-20 container mx-auto px-6">
        <div class="bg-[#0f2d50] rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl">
            <div class="relative z-10">
                <h2 class="text-white text-3xl md:text-5xl font-bold mb-6">Siap Memulai Proyek Konstruksi Anda?</h2>
                <p class="text-gray-400 mb-10 max-w-2xl mx-auto">Konsultasikan kebutuhan konstruksi Anda dengan tim ahli kami sekarang dan dapatkan penawaran terbaik untuk hunian impian Anda.</p>
                <button class="bg-[#e67e22] hover:bg-[#d35400] text-white px-10 py-4 rounded-xl font-bold transition shadow-lg text-lg">
                    Pesan Sekarang
                </button>
            </div>
            <!-- Dekoratif Background -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-orange-500/10 rounded-full -ml-24 -mb-24 blur-3xl"></div>
        </div>
    </section>

    <!-- Footer: Sesuai Referensi Gambar -->
    <footer class="bg-[#0f2d50] text-white pt-20 pb-10 border-t border-white/5">
        <div class="container mx-auto px-6 grid md:grid-cols-4 gap-12 mb-16">
            <div class="col-span-1">
                <img src="{{ asset('images/logo-white.png') }}" class="h-10 mb-6 grayscale brightness-200">
                <p class="text-gray-400 text-sm leading-relaxed mb-6">Platform terpercaya yang menghubungkan Anda dengan mitra konstruksi profesional untuk segala kebutuhan rumah.</p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-orange-500 transition"><i class="fab fa-facebook-f text-xs"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-orange-500 transition"><i class="fab fa-instagram text-xs"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-orange-500 transition"><i class="fab fa-twitter text-xs"></i></a>
                </div>
            </div>
            <div>
                <h4 class="font-bold mb-6">Layanan Utama</h4>
                <ul class="text-gray-400 text-sm space-y-4">
                    <li><a href="#" class="hover:text-orange-400 transition">Perbaikan AC & Pendingin</a></li>
                    <li><a href="#" class="hover:text-orange-400 transition">Kelistrikan & Tata Cahaya</a></li>
                    <li><a href="#" class="hover:text-orange-400 transition">Pengecatan & Dekorasi</a></li>
                    <li><a href="#" class="hover:text-orange-400 transition">Penyewaan Alat Konstruksi</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-6">Akses Cepat</h4>
                <ul class="text-gray-400 text-sm space-y-4">
                    <li><a href="/tentang-kami" class="hover:text-orange-400 transition">Tentang Tukang.in</a></li>
                    <li><a href="#" class="hover:text-orange-400 transition">Layanan</a></li>
                    <li><a href="#" class="hover:text-orange-400 transition">Pusat Bantuan</a></li>
                    <li><a href="#" class="hover:text-orange-400 transition">Promosi</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-6">Hubungi Kami</h4>
                <ul class="text-gray-400 text-sm space-y-4">
                    <li><i class="fas fa-envelope mr-3 text-orange-400"></i> support@tukang.in</li>
                    <li><i class="fas fa-phone mr-3 text-orange-400"></i> 0800-1234-5678</li>
                    <li><i class="fas fa-map-marker-alt mr-3 text-orange-400"></i> Tangerang, Banten, Indonesia</li>
                </ul>
            </div>
        </div>
        <div class="container mx-auto px-6 pt-10 border-t border-white/5 flex flex-col md:row justify-between items-center text-xs text-gray-500">
            <p>© 2026 Tukang.in. All rights reserved.</p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-white transition">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-white transition">Kebijakan Privasi</a>
            </div>
        </div>
    </footer>

</body>
</html>