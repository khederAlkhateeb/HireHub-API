<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| PerformanceService
|--------------------------------------------------------------------------
| This service is responsible for benchmarking database performance.
| It compares "Lazy Loading" (N+1 problem) vs "Eager Loading" (Optimized).
*/

class PerformanceService
{
    /**
     * Main method to get comparison results for both Projects and Freelancers.
     */
    public function getPerformanceComparison(): array
    {
        return [
            'projects' => $this->getProjectPerformance(),
            'freelancers' => $this->getFreelancerPerformance(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | 1. Project Performance Benchmarking
    |--------------------------------------------------------------------------
    | Comparing standard retrieval vs Eager Loading with relations and counts.
    */
    protected function getProjectPerformance(): array
    {
        // Reset and start logging queries
        DB::flushQueryLog();
        DB::enableQueryLog();

        // A. Old Way: Causes N+1 problem (1 query for projects + N queries for each client)
        $start = microtime(true);
        $projectsOld = Project::all();

        foreach ($projectsOld as $project) {
            $project->client->name; // Each iteration triggers a new DB query
        }

        $oldQueries = count(DB::getQueryLog());
        $oldTime = round((microtime(true) - $start) * 1000, 2);

        DB::flushQueryLog();

        // B. New Way: Optimized using 'with' (Eager Loading) and 'withCount'
        $start = microtime(true);
        $projectsNew = Project::with(['client'])->withCount('proposals')->get();

        $newQueries = count(DB::getQueryLog());
        $newTime = round((microtime(true) - $start) * 1000, 2);

        DB::disableQueryLog();

        return [
            'old_way' => [
                'total_queries' => $oldQueries,
                'execution_time' => "{$oldTime} ms",
            ],
            'new_way' => [
                'total_queries' => $newQueries,
                'execution_time' => "{$newTime} ms",
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Freelancer Performance Benchmarking
    |--------------------------------------------------------------------------
    | Comparing standard filtering vs using Scopes and Aggregate functions.
    */
    protected function getFreelancerPerformance(): array
    {
        DB::flushQueryLog();
        DB::enableQueryLog();

        // A. Old Way: Manual filtering and lazy loading profiles
        $start = microtime(true);
        $freelancersOld = User::where('type', 'freelancer')->get();

        foreach ($freelancersOld as $freelancer) {
            $freelancer->profile->bio ?? ''; // Triggers a query for every profile
        }

        $oldQueries = count(DB::getQueryLog());
        $oldTime = round((microtime(true) - $start) * 1000, 2);

        DB::flushQueryLog();

        // B. New Way: Using Model Scope, Eager Loading, and Database Aggregates (Avg)
        $start = microtime(true);
        $freelancersNew = User::freelancers()
            ->with(['profile'])
            ->withAvg('receivedReviews', 'rating') // Calculates average directly in SQL
            ->get();

        $newQueries = count(DB::getQueryLog());
        $newTime = round((microtime(true) - $start) * 1000, 2);

        DB::disableQueryLog();

        return [
            'old_way' => [
                'total_queries' => $oldQueries,
                'execution_time' => "{$oldTime} ms",
            ],
            'new_way' => [
                'total_queries' => $newQueries,
                'execution_time' => "{$newTime} ms",
            ],
        ];
    }
}