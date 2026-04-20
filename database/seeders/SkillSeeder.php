<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\Skill::insert([
            ['name' => 'PHP'],
            ['name' => 'Laravel'],
            ['name' => 'MySQL'],
        ]);

        \App\Models\User::find(2)->skills()->attach([
            1 => ['years_of_experience' => 3],
            2 => ['years_of_experience' => 2],
        ]);
    }
}
