<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Services\StatsService;

class AdminStatsController extends Controller
{

    protected $statsService;

    public function __construct(StatsService $statsService)
    {
        $this->statsService = $statsService;
    }
    public function index()
    {
        $stats = $this->statsService->getGlobalStats();
        return response()->json(['data' => $stats]);
    }
}
