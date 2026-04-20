<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Resources\ProjectResource;

class HomeController extends Controller
{
    public function index()
    {
        return response()->json([
            'latest_projects' => Project::latest()->take(5)->get(),
        ]);
    }
}
