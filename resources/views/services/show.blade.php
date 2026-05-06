<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $service->title }} - Tukang.in</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans">

    <x-navbar />

    <!-- Hero Detail Layanan -->
    <section class="bg-[#0f2d50] text-white py-20">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="md:w-1/2">
                    <p class="text-orange-400 font-bold text-xs uppercase mb-4">{{ $service->category }}</p>
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ $service->title }}</h1>
                    <p class="text-gray-300 text-lg mb-8">{{ $service->description }}</p>
                    <div class="flex space-x-4">
                        <button class="bg-[#e67e22] hover:bg-[#d35400] text-white px-8 py-3 rounded-xl font-bold transition shadow-lg">Pesan Sekarang</button>
                    </div>
                </div>
                <div class="md:w-1/2">
                    <img src="{{ asset('images/services/' . $service->image) }}" 
                         class="rounded-[3rem] shadow-2xl w-full object-cover h-96 border-4 border-white/10"
                         alt="{{ $service->title }}">
                </div>
            </div>
        </div>
    </section>

    <!-- Info Harga & Detail Tambahan -->
    <section class="py-16 container mx-auto px-6">
        <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center">
            <div class="text-center md:text-left mb-6 md:mb-0">
                <h3 class="text-2xl font-bold text-gray-800">Estimasi Biaya Mulai Dari</h3>
                <p class="text-gray-500 mt-2">Harga final akan ditentukan setelah mitra teknisi melakukan survei lokasi.</p>
            </div>
            <div class="text-center md:text-right">
                <span class="text-3xl font-bold text-[#0f2d50]">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                <span class="text-gray-400 ml-2 block md:inline mt-1 text-sm">/ Perbaikan</span>
            </div>
        </div>
    </section>

    <x-footer />

</body>
</html>