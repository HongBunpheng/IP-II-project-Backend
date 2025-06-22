<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SearchPlace;

class SearchPlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

SearchPlace::create([
    'name' => 'Angkor Wat',
    'location' => 'Siem Reap',
    'description' => 'Famous temple'
]);

SearchPlace::create([
    'name' => 'Kampot River',
    'location' => 'Kampot',
    'description' => 'Peaceful river'
]);

}
}
