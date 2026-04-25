<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Cache;

class HomeService
{
    public function getLatestProjects(int $limit = 5)
    {
        return Cache::tags(['projects'])->remember("home.latest_projects.{$limit}", 3600, function () use ($limit) {
            return Project::query()
                ->open()
                ->with(['client:id,first_name,last_name'])
                ->latest()
                ->take($limit)
                ->get();
        });
    }
}
