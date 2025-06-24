<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlaceSeeder extends Seeder
{
    public function run()
{
    \App\Models\Place::insert([
        [
            'name' => 'River Park',
            'description' => 'A quiet riverside escape.',
            'province' => 'Siem Reap',
            'price' => 350,
            'rating' => 5,
            'images' => json_encode(['/images/i11.png']),
            'created_at' => now(),
            'updated_at' => now(),
        ],

    ]);
}


}