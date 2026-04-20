<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FreelancerProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\FreelancerProfile::create([
            'user_id' => 2,
            'bio' => 'Backend Developer',
            'hourly_rate' => 50,
            'status' => 'available',
            'avatar' => null,
            'is_verified'=>false
        ]);
    }
}
