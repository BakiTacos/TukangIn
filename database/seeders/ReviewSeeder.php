<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/ReviewSeeder.php
public function run(): void
{
    $tukangs = \App\Models\User::where('role', 'tukang')->get();

    foreach ($tukangs as $t) {
        \App\Models\Review::create([
            'tukang_id' => $t->id,
            'user_name' => 'Agus Muliawan',
            'rating' => 5,
            'comment' => "Pak {$t->name} sangat profesional, kerjanya rapi dan cepat!",
            'created_at' => now()->subDays(rand(1, 10)),
        ]);

        \App\Models\Review::create([
            'tukang_id' => $t->id,
            'user_name' => 'Siti Nurhaliza',
            'rating' => 4,
            'comment' => "Puas dengan hasilnya, harganya juga transparan.",
            'created_at' => now()->subDays(rand(11, 20)),
        ]);
    }
}
}
