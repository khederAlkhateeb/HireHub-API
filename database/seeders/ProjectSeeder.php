<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;
use App\Models\Tag;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 50; $i++) {
            $project = Project::create([
                'user_id' => 2,
                'title' => "HireHub Project #$i",
                'description' => "Detailed description for project number $i. This is a freelance opportunity.",
                'budget' => rand(100, 5000),
                'budget_type' => $i % 2 == 0 ? 'fixed' : 'hourly',
                'status' => 'open',
                'deadline' => now()->addDays(rand(5, 30)),
            ]);

            $project->tags()->attach([1, 2]); 
        }
    }
}