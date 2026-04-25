<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Services\HomeService;

class HomeController extends Controller
{
    public function __construct(protected HomeService $homeService)
    {
    }

    public function index()
    {
        $projects = $this->homeService->getLatestProjects();

        return response()->json([
            'latest_projects' => ProjectResource::collection($projects),
        ]);
    }
}
