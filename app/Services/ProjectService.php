<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use App\Notifications\ProjectCreatedNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class ProjectService
{
    /**
     * Get paginated list of open projects with client, tags, and proposals count.
     */
    public function listProjects($perPage = 10)
    {
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
    }

    /**
     * Get full project details by ID with related data.
     */
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

    /**
     * Create a new project, attach tags, and notify freelancers.
     */
    public function createProject(array $data)
    {
        return DB::transaction(function () use ($data) {
            $project = Project::create($data);

            // Attach tags if provided
            if (isset($data['tags'])) {
                $project->tags()->attach($data['tags']);
            }

            // Notify all freelancers about the new project
            $freelancers = User::where('type', 'freelancer')->get();
            Notification::send(
                $freelancers,
                new ProjectCreatedNotification($project->title)
            );

            return $project->load('tags');
        });
    }
}