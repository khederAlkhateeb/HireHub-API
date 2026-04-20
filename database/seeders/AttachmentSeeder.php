<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $project = \App\Models\Project::find(1);

        $project->attachments()->create([
            'file_path' => 'project_file.pdf',
        ]);
    }
}
