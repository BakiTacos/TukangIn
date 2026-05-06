<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['title' => 'Perbaikan Kebocoran', 'category' => 'Plumbing', 'description' => 'Solusi pipa bocor dan wastafel.', 'price' => '50.000', 'image' => 'leakage.jpg'],
            ['title' => 'Instalasi Listrik', 'category' => 'Listrik', 'description' => 'Perbaikan panel dan kabel.', 'price' => '75.000', 'image' => 'electrical.jpg'],
            ['title' => 'Konstruksi & Renovasi', 'category' => 'Konstruksi', 'description' => 'Renovasi dinding dan keramik.', 'price' => '500.000', 'image' => 'construction.jpg'],
            ['title' => 'Pengecatan', 'category' => 'Pengecatan', 'description' => 'Cat interior dan eksterior.', 'price' => '100.000', 'image' => 'painting.jpg'],
            ['title' => 'AC Service', 'category' => 'AC & Pendingin', 'description' => 'Cuci dan tambah freon.', 'price' => '65.000', 'image' => 'ac.jpg'],
            ['title' => 'Sewa Peralatan', 'category' => 'Sewa Alat', 'description' => 'Alat pertukangan modern.', 'price' => '120.000', 'image' => 'tools.jpg'],
            ['title' => 'Perbaikan Toilet', 'category' => 'Plumbing', 'description' => 'Service kloset dan saluran.', 'price' => '80.000', 'image' => 'toilet.jpg'],
            ['title' => 'Dekor Rumah', 'category' => 'Interior', 'description' => 'Wallpaper dan gorden.', 'price' => '150.000', 'image' => 'decor.jpg'],
            ['title' => 'Roofing', 'category' => 'Konstruksi', 'description' => 'Perbaikan atap dan genteng.', 'price' => '200.000', 'image' => 'roofing.jpg'],
            ['title' => 'Flooring', 'category' => 'Interior', 'description' => 'Pasang vinyl dan parket.', 'price' => '300.000', 'image' => 'flooring.jpg'],
            ['title' => 'Cuci Toren', 'category' => 'Plumbing', 'description' => 'Kuras lumut tandon air.', 'price' => '90.000', 'image' => 'toren.jpg'],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}