<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;
use App\Http\Requests\StoreProjectRequest;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(
        ProjectService $projectService,
    ) {
        $this->projectService = $projectService;
    }

    public function index()
    {
        $projects = $this->projectService->listProjects(1);
        return ProjectResource::collection($projects);
    }

    public function show($id)
    {
        $project = $this->projectService->getProjectDetails($id);
        return new ProjectResource($project);
    }

    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $project = $this->projectService->createProject($data);

        return new ProjectResource($project);
    }
}
