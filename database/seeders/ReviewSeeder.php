<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $project = \App\Models\Project::find(1);

        $project->reviews()->create([
            'user_id' => 1,
            'rating' => 4.5,
            'comment' => 'Great work',
        ]);
    }
}
