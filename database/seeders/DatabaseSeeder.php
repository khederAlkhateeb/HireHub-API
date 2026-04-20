<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            CountrySeeder::class,
            CitySeeder::class,
            UserSeeder::class,
            FreelancerProfileSeeder::class,
            SkillSeeder::class,
            TagSeeder::class,
            ProjectSeeder::class,
            ProposalSeeder::class,
            ReviewSeeder::class,
            AttachmentSeeder::class,
            PortfolioSeeder::class,
        ]);
    }
}
