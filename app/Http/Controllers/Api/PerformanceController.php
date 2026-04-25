<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PerformanceService;
use Illuminate\Http\JsonResponse;

class PerformanceController extends Controller
{
    public function __construct(protected PerformanceService $performanceService)
    {
    }

    public function index(): JsonResponse
    {
        $results = $this->performanceService->getPerformanceComparison();

        return response()->json([
            'message' => 'HireHub performance comparison',
            'stats' => $results,
        ]);
    }
}
