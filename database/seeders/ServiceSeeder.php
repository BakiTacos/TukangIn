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
                'color' => 'bg-blue-100 text-blue-600'
            ],
            [
                'title' => 'Instalasi Listrik',
                'category' => 'Listrik',
                'description' => 'Perbaikan panel, kabel, dan instalasi titik lampu baru.',
                'price' => '75.000',
                'image' => 'electrical.jpg',
                'icon' => 'fa-bolt',
                'color' => 'bg-yellow-100 text-yellow-600'
            ],
            [
                'title' => 'Konstruksi & Renovasi',
                'category' => 'Konstruksi',
                'description' => 'Renovasi dinding, pasang keramik, hingga pembangunan skala kecil.',
                'price' => '500.000',
                'image' => 'construction.jpg',
                'icon' => 'fa-hammer',
                'color' => 'bg-red-100 text-red-600'
            ],
            [
                'title' => 'Pengecatan',
                'category' => 'Pengecatan',
                'description' => 'Layanan cat interior dan eksterior dengan hasil presisi.',
                'price' => '100.000',
                'image' => 'painting.jpg',
                'icon' => 'fa-paint-roller',
                'color' => 'bg-purple-100 text-purple-600'
            ],
            [
                'title' => 'AC Service',
                'category' => 'AC & Pendingin',
                'description' => 'Cuci AC rutin, tambah freon, dan perbaikan unit outdoor.',
                'price' => '65.000',
                'image' => 'ac.jpg',
                'icon' => 'fa-snowflake',
                'color' => 'bg-cyan-100 text-cyan-600'
            ],
            [
                'title' => 'Sewa Peralatan',
                'category' => 'Sewa Alat',
                'description' => 'Penyewaan alat pertukangan modern untuk proyek mandiri Anda.',
                'price' => '120.000',
                'image' => 'tools.jpg',
                'icon' => 'fa-toolbox',
                'color' => 'bg-green-100 text-green-600'
            ],
            [
                'title' => 'Perbaikan Toilet',
                'category' => 'Plumbing',
                'description' => 'Service kloset, instalasi shower, dan saluran pembuangan.',
                'price' => '80.000',
                'image' => 'toilet.jpg',
                'icon' => 'fa-toilet',
                'color' => 'bg-pink-100 text-pink-600'
            ],
            [
                'title' => 'Dekor Rumah',
                'category' => 'Interior',
                'description' => 'Pemasangan wallpaper, gorden, dan penataan ruang estetis.',
                'price' => '150.000',
                'image' => 'decor.jpg',
                'icon' => 'fa-couch',
                'color' => 'bg-indigo-100 text-indigo-600'
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
            ]);
        }
    }
}