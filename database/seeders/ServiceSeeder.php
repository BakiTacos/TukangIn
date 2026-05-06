<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Perbaikan Kebocoran',
                'category' => 'Plumbing',
                'description' => 'Solusi pipa bocor, wastafel tersumbat, dan perbaikan tandon air.',
                'price' => '50.000',
                'image' => 'leakage.jpg',
                'icon' => 'fa-faucet',
                'color' => 'bg-blue-100 text-blue-600',
                'has_capacity' => false,
                'inclusions' => [
                    ['icon' => 'fa-wrench', 'title' => 'Perbaikan Pipa', 'desc' => 'Mengatasi kebocoran pada pipa.'],
                    ['icon' => 'fa-water', 'title' => 'Saluran Lancar', 'desc' => 'Membersihkan sumbatan air.'],
                    ['icon' => 'fa-search', 'title' => 'Cek Sistem', 'desc' => 'Pemeriksaan jalur pipa.']
                ]
            ],
            [
                'title' => 'Instalasi Listrik',
                'category' => 'Listrik',
                'description' => 'Perbaikan panel, kabel, dan instalasi titik lampu baru.',
                'price' => '75.000',
                'image' => 'electrical.jpg',
                'icon' => 'fa-bolt',
                'color' => 'bg-yellow-100 text-yellow-600',
                'has_capacity' => false,
                'inclusions' => [
                    ['icon' => 'fa-bolt', 'title' => 'Pasang Kabel', 'desc' => 'Instalasi kabel listrik aman.'],
                    ['icon' => 'fa-lightbulb', 'title' => 'Titik Lampu', 'desc' => 'Penambahan titik lampu baru.'],
                    ['icon' => 'fa-tools', 'title' => 'Perbaikan Panel', 'desc' => 'Service panel listrik.']
                ]
            ],
            [
                'title' => 'Konstruksi & Renovasi',
                'category' => 'Konstruksi',
                'description' => 'Renovasi dinding, pasang keramik, hingga pembangunan skala kecil.',
                'price' => '500.000',
                'image' => 'construction.jpg',
                'icon' => 'fa-hammer',
                'color' => 'bg-red-100 text-red-600',
                'has_capacity' => true,
                'inclusions' => [
                    ['icon' => 'fa-hammer', 'title' => 'Renovasi', 'desc' => 'Perbaikan dan pembangunan.'],
                    ['icon' => 'fa-border-all', 'title' => 'Pasang Keramik', 'desc' => 'Pemasangan rapi dan presisi.'],
                    ['icon' => 'fa-drafting-compass', 'title' => 'Konsultasi', 'desc' => 'Diskusi desain sederhana.']
                ]
            ],
            [
                'title' => 'Pengecatan',
                'category' => 'Pengecatan',
                'description' => 'Layanan cat interior dan eksterior dengan hasil presisi.',
                'price' => '100.000',
                'image' => 'painting.jpg',
                'icon' => 'fa-paint-roller',
                'color' => 'bg-purple-100 text-purple-600',
                'has_capacity' => true,
                'inclusions' => [
                    ['icon' => 'fa-fill-drip', 'title' => 'Cat Interior', 'desc' => 'Pengecatan dalam ruangan.'],
                    ['icon' => 'fa-home', 'title' => 'Cat Eksterior', 'desc' => 'Pengecatan luar rumah.'],
                    ['icon' => 'fa-layer-group', 'title' => 'Finishing', 'desc' => 'Hasil halus dan rapi.']
                ]
            ],
            [
                'title' => 'AC Service',
                'category' => 'AC & Pendingin',
                'description' => 'Cuci AC rutin, tambah freon, dan perbaikan unit outdoor.',
                'price' => '65.000',
                'image' => 'ac.jpg',
                'icon' => 'fa-snowflake',
                'color' => 'bg-cyan-100 text-cyan-600',
                'has_capacity' => true,
                'inclusions' => [
                    ['icon' => 'fa-wind', 'title' => 'Cuci AC', 'desc' => 'Membersihkan unit indoor.'],
                    ['icon' => 'fa-gas-pump', 'title' => 'Isi Freon', 'desc' => 'Pengisian freon sesuai kebutuhan.'],
                    ['icon' => 'fa-tools', 'title' => 'Perbaikan', 'desc' => 'Service unit bermasalah.']
                ]
            ],
            [
                'title' => 'Sewa Peralatan',
                'category' => 'Sewa Alat',
                'description' => 'Penyewaan alat pertukangan modern untuk proyek mandiri Anda.',
                'price' => '120.000',
                'image' => 'tools.jpg',
                'icon' => 'fa-toolbox',
                'color' => 'bg-green-100 text-green-600',
                'has_capacity' => false,
                'inclusions' => [
                    ['icon' => 'fa-toolbox', 'title' => 'Alat Lengkap', 'desc' => 'Berbagai alat tersedia.'],
                    ['icon' => 'fa-clock', 'title' => 'Sewa Fleksibel', 'desc' => 'Durasi harian/mingguan.'],
                    ['icon' => 'fa-check', 'title' => 'Siap Pakai', 'desc' => 'Alat dalam kondisi prima.']
                ]
            ],
            [
                'title' => 'Perbaikan Toilet',
                'category' => 'Plumbing',
                'description' => 'Service kloset, instalasi shower, dan saluran pembuangan.',
                'price' => '80.000',
                'image' => 'toilet.jpg',
                'icon' => 'fa-toilet',
                'color' => 'bg-pink-100 text-pink-600',
                'has_capacity' => false,
                'inclusions' => [
                    ['icon' => 'fa-toilet', 'title' => 'Service Kloset', 'desc' => 'Perbaikan kloset rusak.'],
                    ['icon' => 'fa-shower', 'title' => 'Pasang Shower', 'desc' => 'Instalasi shower baru.'],
                    ['icon' => 'fa-water', 'title' => 'Saluran Air', 'desc' => 'Perbaikan pembuangan.']
                ]
            ],
            [
                'title' => 'Dekor Rumah',
                'category' => 'Interior',
                'description' => 'Pemasangan wallpaper, gorden, dan penataan ruang estetis.',
                'price' => '150.000',
                'image' => 'decor.jpg',
                'icon' => 'fa-couch',
                'color' => 'bg-indigo-100 text-indigo-600',
                'has_capacity' => true,
                'inclusions' => [
                    ['icon' => 'fa-image', 'title' => 'Wallpaper', 'desc' => 'Pemasangan wallpaper rapi.'],
                    ['icon' => 'fa-window-maximize', 'title' => 'Gorden', 'desc' => 'Pasang gorden sesuai ukuran.'],
                    ['icon' => 'fa-couch', 'title' => 'Styling', 'desc' => 'Penataan ruang estetis.']
                ]
            ],
            [
                'title' => 'Pemasangan Lantai / Flooring',
                'category' => 'Interior',
                'description' => 'Pemasangan keramik, granit, vinyl, atau parket untuk lantai yang lebih mewah.',
                'price' => '300.000',
                'image' => 'flooring.jpg',
                'icon' => 'fa-th',
                'color' => 'bg-orange-100 text-orange-600',
                'has_capacity' => true,
                'inclusions' => [
                    ['icon' => 'fa-border-all', 'title' => 'Pasang Keramik', 'desc' => 'Keramik rapi dan kuat.'],
                    ['icon' => 'fa-layer-group', 'title' => 'Vinyl/Parket', 'desc' => 'Lantai modern elegan.'],
                    ['icon' => 'fa-ruler-combined', 'title' => 'Pengukuran', 'desc' => 'Penyesuaian presisi.']
                ]
            ],
            [
                'title' => 'Perbaikan Atap / Roofing',
                'category' => 'Konstruksi',
                'description' => 'Perbaikan atap bocor, penggantian genteng, dan pembersihan talang air.',
                'price' => '200.000',
                'image' => 'roofing.jpg',
                'icon' => 'fa-campground',
                'color' => 'bg-red-100 text-red-600',
                'has_capacity' => true,
                'inclusions' => [
                    ['icon' => 'fa-home', 'title' => 'Perbaikan Atap', 'desc' => 'Mengatasi kebocoran.'],
                    ['icon' => 'fa-th-large', 'title' => 'Ganti Genteng', 'desc' => 'Penggantian genteng rusak.'],
                    ['icon' => 'fa-water', 'title' => 'Talang Air', 'desc' => 'Pembersihan saluran air.']
                ]
            ],
            [
                'title' => 'Cuci Toren & Tandon',
                'category' => 'Plumbing',
                'description' => 'Pembersihan lumut dan kotoran pada tandon air agar air tetap higienis.',
                'price' => '90.000',
                'image' => 'toren.jpg',
                'icon' => 'fa-fill-drip',
                'color' => 'bg-teal-100 text-teal-600',
                'has_capacity' => true,
                'inclusions' => [
                    ['icon' => 'fa-hand-sparkles', 'title' => 'Kuras Lumut', 'desc' => 'Pembersihan kerak secara total.'],
                    ['icon' => 'fa-vial', 'title' => 'Sterilisasi', 'desc' => 'Cairan pembersih aman food-grade.'],
                    ['icon' => 'fa-search', 'title' => 'Cek Otomatis', 'desc' => 'Pemeriksaan filter dan pelampung.']
                ]
            ],
        ];

        foreach ($services as $service) {
            Service::create([
                'title'       => $service['title'],
                'slug'        => Str::slug($service['title']),
                'category'    => $service['category'],
                'description' => $service['description'],
                'price'       => $service['price'],
                'image'       => $service['image'],
                'icon'        => $service['icon'],
                'color'       => $service['color'],
                'has_capacity' => $service['has_capacity'],
                'inclusions' => $service['inclusions'],
            ]);
        }
    }
}