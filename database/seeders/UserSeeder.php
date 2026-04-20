<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\FreelancerProfile;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'first_name' => 'Founder',
            'last_name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'password',
            'phone'      => "963900000" . str_pad(1, 2, '0', STR_PAD_LEFT),
            'type' => 'admin',
            'city_id'    => rand(1, 2),
        ]);
        // 1. Create 24 Clients
        for ($i = 2; $i <= 25; $i++) {
            User::create([
                'first_name' => "Client",
                'last_name'  => "Number $i",
                'email'      => "client$i@hirehub.com",
                'password'   => 'password',
                'type'       => 'client',
                'phone'      => "963900000" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'city_id'    => rand(1, 2),
            ]);
        }

        // 2. Create 25 Freelancers + Their Profiles
        for ($i = 1; $i <= 25; $i++) {
            $freelancer = User::create([
                'first_name' => "Freelancer",
                'last_name'  => "Expert $i",
                'email'      => "free$i@hirehub.com",
                'password'   => 'password',
                'type'       => 'freelancer',
                'phone'      => "963911111" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'city_id'    => rand(1, 2),
            ]);

            // Phase 3 optimization test requires a profile to trigger N+1
            FreelancerProfile::create([
                'user_id' => $freelancer->id,
                'bio' => "This is the professional bio for freelancer number $i.",
                'hourly_rate' => rand(15, 100),
                'status' => 'available',
            ]);
        }
    }
}
