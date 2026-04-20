<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\City::insert([
            ['name' => 'Amsterdam', 'country_id' => 1],
            ['name' => 'Rotterdam', 'country_id' => 1],
            ['name' => 'Berlin', 'country_id' => 2],
        ]);
    }
}
