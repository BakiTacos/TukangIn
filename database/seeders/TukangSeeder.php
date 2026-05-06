<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TukangSeeder extends Seeder
{
    public function run(): void 
    {
        $tukangs = [
            [
                'name' => 'Budi Santoso', 'email' => 'budi@tukangin.com',
                'category' => 'AC & Pendingin', 'specialty' => 'Spesialis AC & Pendingin',
                'rating' => 4.9, 'total_order' => 128, 'avatar' => 'budi.jpg'
            ],
            [
                'name' => 'Agus Kurniawan', 'email' => 'agus@tukangin.com',
                'category' => 'Listrik', 'specialty' => 'Instalasi Listrik & Panel',
                'rating' => 4.8, 'total_order' => 94, 'avatar' => 'agus.jpg'
            ],
            [
                'name' => 'Dedi Pratama', 'email' => 'dedi@tukangin.com',
                'category' => 'Konstruksi', 'specialty' => 'Perbaikan Atap & Genteng',
                'rating' => 4.7, 'total_order' => 76, 'avatar' => 'dedi.jpg'
            ],
            [
                'name' => 'Rudi Hartono', 'email' => 'rudi@tukangin.com',
                'category' => 'Finishing', 'specialty' => 'Pengecatan Rumah',
                'rating' => 4.6, 'total_order' => 65, 'avatar' => 'rudi.jpg'
            ],
            [
                'name' => 'Andi Wijaya', 'email' => 'andi@tukangin.com',
                'category' => 'Interior', 'specialty' => 'Renovasi Interior',
                'rating' => 4.9, 'total_order' => 140, 'avatar' => 'andi.jpg'
            ],
            [
                'name' => 'Hendra Gunawan', 'email' => 'hendra@tukangin.com',
                'category' => 'Interior', 'specialty' => 'Pemasangan Keramik & Lantai',
                'rating' => 4.5, 'total_order' => 58, 'avatar' => 'hendra.jpg'
            ],
            [
                'name' => 'Joko Susilo', 'email' => 'joko@tukangin.com',
                'category' => 'Plumbing', 'specialty' => 'Perbaikan Pipa & Plumbing',
                'rating' => 4.8, 'total_order' => 102, 'avatar' => 'joko.jpg'
            ],
            [
                'name' => 'Fajar Nugroho', 'email' => 'fajar@tukangin.com',
                'category' => 'Listrik', 'specialty' => 'Servis Kelistrikan Elektronik',
                'rating' => 4.6, 'total_order' => 73, 'avatar' => 'fajar.jpg'
            ],
            [
                'name' => 'Bayu Saputra', 'email' => 'bayu@tukangin.com',
                'category' => 'Konstruksi', 'specialty' => 'Pemasangan Kanopi',
                'rating' => 4.7, 'total_order' => 81, 'avatar' => 'bayu.jpg'
            ],
            [
                'name' => 'Rizky Maulana', 'email' => 'rizky@tukangin.com',
                'category' => 'AC & Pendingin', 'specialty' => 'Servis Kulkas & Chiller',
                'rating' => 4.8, 'total_order' => 90, 'avatar' => 'rizky.jpg'
            ],
            [
                'name' => 'Eko Prasetyo', 'email' => 'eko@tukangin.com',
                'category' => 'Interior', 'specialty' => 'Pemasangan Gypsum',
                'rating' => 4.5, 'total_order' => 60, 'avatar' => 'eko.jpg'
            ],
            [
                'name' => 'Tono Suharto', 'email' => 'tono@tukangin.com',
                'category' => 'Konstruksi', 'specialty' => 'Perbaikan Pintu & Jendela',
                'rating' => 4.6, 'total_order' => 55, 'avatar' => 'tono.jpg'
            ],
            [
                'name' => 'Arif Rahman', 'email' => 'arif@tukangin.com',
                'category' => 'Listrik', 'specialty' => 'Instalasi CCTV & Keamanan',
                'rating' => 4.9, 'total_order' => 110, 'avatar' => 'arif.jpg'
            ],
            [
                'name' => 'Yoga Pradana', 'email' => 'yoga@tukangin.com',
                'category' => 'Interior', 'specialty' => 'Pemasangan Wallpaper',
                'rating' => 4.4, 'total_order' => 45, 'avatar' => 'yoga.jpg'
            ],
            [
                'name' => 'Slamet Riyadi', 'email' => 'slamet@tukangin.com',
                'category' => 'Plumbing', 'specialty' => 'Servis Pompa Air',
                'rating' => 4.7, 'total_order' => 88, 'avatar' => 'slamet.jpg'
            ],
            [
                'name' => 'Wawan Setiawan', 'email' => 'wawan@tukangin.com',
                'category' => 'Interior', 'specialty' => 'Pembuatan Furniture Custom',
                'rating' => 4.8, 'total_order' => 97, 'avatar' => 'wawan.jpg'
            ],
            [
                'name' => 'Yusuf Hidayat', 'email' => 'yusuf@tukangin.com',
                'category' => 'Interior', 'specialty' => 'Perbaikan & Upholstery Sofa',
                'rating' => 4.5, 'total_order' => 52, 'avatar' => 'yusuf.jpg'
            ],
            [
                'name' => 'Imam Santoso', 'email' => 'imam@tukangin.com',
                'category' => 'Plumbing', 'specialty' => 'Instalasi Water Heater',
                'rating' => 4.7, 'total_order' => 69, 'avatar' => 'imam.jpg'
            ],
            [
                'name' => 'Dian Permana', 'email' => 'dian@tukangin.com',
                'category' => 'AC & Pendingin', 'specialty' => 'Servis AC Mobil',
                'rating' => 4.6, 'total_order' => 64, 'avatar' => 'dian.jpg'
            ],
            [
                'name' => 'Rangga Saputra', 'email' => 'rangga@tukangin.com',
                'category' => 'Konstruksi', 'specialty' => 'Pemasangan Baja Ringan',
                'rating' => 4.8, 'total_order' => 92, 'avatar' => 'rangga.jpg'
            ],
            [
                'name' => 'Kevin Setiawan', 'email' => 'kevin@tukangin.com',
                'category' => 'Listrik', 'specialty' => 'Smart Home Installation',
                'rating' => 4.9, 'total_order' => 120, 'avatar' => 'kevin.jpg'
            ],
            [
                'name' => 'Surya Dharma', 'email' => 'surya@tukangin.com',
                'category' => 'Plumbing', 'specialty' => 'Perbaikan Talang Air',
                'rating' => 4.5, 'total_order' => 50, 'avatar' => 'surya.jpg'
            ],
            // 3 Teknisi Tambahan
            [
                'name' => 'Lukman Hakim', 'email' => 'lukman@tukangin.com',
                'category' => 'Konstruksi', 'specialty' => 'Tukang Las & Teralis Besi',
                'rating' => 4.7, 'total_order' => 42, 'avatar' => 'lukman.jpg'
            ],
            [
                'name' => 'Reza Pahlevi', 'email' => 'reza@tukangin.com',
                'category' => 'Plumbing', 'specialty' => 'Cuci Toren & Sterilisasi',
                'rating' => 4.8, 'total_order' => 115, 'avatar' => 'reza.jpg'
            ],
            [
                'name' => 'Gilang Ramadhan', 'email' => 'gilang@tukangin.com',
                'category' => 'Sewa Alat', 'specialty' => 'Instruktur & Operator Alat',
                'rating' => 4.6, 'total_order' => 38, 'avatar' => 'gilang.jpg'
            ]
        ];

        foreach ($tukangs as $t) {
            User::updateOrCreate(
                ['email' => $t['email']],
                array_merge($t, [
                    'password' => Hash::make('password123'),
                    'role' => 'tukang'
                ])
            );
        }
    }
}