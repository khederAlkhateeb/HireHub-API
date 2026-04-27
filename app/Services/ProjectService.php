<?php

namespace App\Services;

use App\Jobs\SendProjectCreatedNotification;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProjectService
{

    public function listProjects($perPage = 10)
    {
        $page = request('page', 1);
        $cacheKey = "projects.open.{$page}.{$perPage}";

        return Cache::tags(['projects'])->remember($cacheKey, 3600, function () use ($perPage) {
            return Project::query()
                ->open()
                ->with([
                    'client' => function ($query) {
                        $query->select('id', 'first_name', 'last_name')
                            ->withAvg('receivedReviews as average_rating', 'rating');
                    },
                    'tags:id,name'
                ])
                ->withCount('proposals')
                ->latest()
                ->paginate($perPage);
        });
    }

    public function getProjectDetails($id)
    {
        return Project::query()
            ->with([
                'client:id,first_name,last_name',
                'tags:id,name',
                'proposals.freelancer:id,first_name,last_name',
                'attachments',
                'reviews'
            ])
            ->withCount('proposals')
            ->findOrFail($id);
    }

    public function createProject(array $data)
    {
        return DB::transaction(function () use ($data) {
            $project = Project::create($data);

            if (isset($data['tags'])) {
                $project->tags()->attach($data['tags']);
            }

            $user = auth()->user();
            if ($user) {
                SendProjectCreatedNotification::dispatch($user, $project->title)->afterCommit();
            }

            Cache::tags(['projects'])->flush();

            return $project->load('tags');
        });
    }
}