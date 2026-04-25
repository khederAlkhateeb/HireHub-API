<?php

namespace App\Services;

use App\Models\Project;

class HomeService
{
    public function getLatestProjects(int $limit = 5)
    {
        return Project::query()
            ->open()
            ->with([ 'client:id,first_name,last_name' ])
            ->latest()
            ->take($limit)
            ->get();
    }
}
