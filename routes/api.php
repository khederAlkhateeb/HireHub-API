<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\User;

use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\FreelancerController;
use App\Http\Controllers\Api\ProposalController;
use App\Http\Controllers\Api\AdminStatsController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| 1. Authentication Routes
|--------------------------------------------------------------------------
| Handles user registration and login to issue Sanctum tokens.
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| 2. Public Routes
|--------------------------------------------------------------------------
| These endpoints are accessible by anyone (Guests, Freelancers, Clients, admin).
*/

Route::get('/home', [HomeController::class, 'index'])->name("home");
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/project/{id}', [ProjectController::class, 'show']);
Route::get('/freelancers', [FreelancerController::class, 'index']);
Route::get('/freelancer/{id}', [FreelancerController::class, 'show']);

/*
|--------------------------------------------------------------------------
| 3. Protected Routes (Authenticated via Sanctum)
|--------------------------------------------------------------------------
| These routes require a valid Bearer Token. Permissions are further 
| restricted based on the user 'type' (Freelancer, Client, or Admin).
*/

Route::middleware('auth:sanctum')->group(function () {

    /**
     * Common Authenticated Routes
     * Accessible by any logged-in user to manage their basic account data.
     */
    Route::get('/user', fn(Request $request) => $request->user());
    Route::get('/profile', [ProfileController::class, 'show']);

    /**
     * Freelancer Specific Routes
     * Restricted via FreelancerMiddleware. 
     * Only users with type='freelancer' can update profiles, manage skills, or bid on projects.
     */
    Route::middleware('freelancer')->group(function () {
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::post('/profile/skills', [ProfileController::class, 'updateSkills']);
        Route::post('/project/{project_id}/proposals', [ProposalController::class, 'store']);
    });

    /**
     * Client Specific Routes
     * Restricted via ClientMiddleware.
     * Only users with type='client' can post new projects or accept/hire freelancers.
     */
    Route::middleware('client')->group(function () {
        Route::post('/project', [ProjectController::class, 'store']);
        Route::post('/proposals/{id}/accept', [ProposalController::class, 'accept']);
    });

    /**
     * Shared Resource Routes
     * Routes that might be accessed by both roles involved in a transaction.
     */
    Route::get('/proposals/{id}', [ProposalController::class, 'show']);
});

// Admin & Founders Statistics
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/admin/statistics', [AdminStatsController::class, 'index']);
});
/*
|--------------------------------------------------------------------------
| 4. Phase 3: Performance Analysis & Testing
|--------------------------------------------------------------------------
| Dedicated routes to compare Eager Loading vs Lazy Loading (N+1 Solution).
*/

Route::get('/performance-test', function () {
    $results = [];
    // --- TEST 1: Projects Performance ---
    // A. Old Way (N+1 Problem)

    DB::flushQueryLog();
    DB::enableQueryLog();

    $start = microtime(true);
    $projectsOld = Project::all();

    foreach ($projectsOld as $project) {
        $name = $project->client->name; // Triggering N+1
    }
    $results['projects']['old_way'] = [
        'total_queries' => count(DB::getQueryLog()),
        'execution_time' => round((microtime(true) - $start) * 1000, 2) . ' ms',
    ];
    // B. New Way (Eager Loading - Phase 3 Solution)
    DB::flushQueryLog();

    $start = microtime(true);
    $projectsNew = Project::with(['client'])->withCount('proposals')->get();
    $results['projects']['new_way'] = [
        'total_queries' => count(DB::getQueryLog()),
        'execution_time' => round((microtime(true) - $start) * 1000, 2) . ' ms',
    ];
    // --- TEST 2: Freelancers Performance ---
    // A. Old Way (N+1 Problem)
    DB::flushQueryLog();

    $start = microtime(true);
    $freelancersOld = User::where('type', 'freelancer')->get();
    foreach ($freelancersOld as $freelancer) {
        $bio = $freelancer->profile->bio ?? ''; // Triggering N+1
    }
    $results['freelancers']['old_way'] = [
        'total_queries' => count(DB::getQueryLog()),
        'execution_time' => round((microtime(true) - $start) * 1000, 2) . ' ms',
    ];
    // B. New Way (Eager Loading + Avg Rating)
    DB::flushQueryLog();

    $start = microtime(true);
    $freelancersNew = User::freelancers() // Using Scope from Phase 2
        ->with(['profile'])
        ->withAvg('receivedReviews', 'rating')
        ->get();
    $results['freelancers']['new_way'] = [
        'total_queries' => count(DB::getQueryLog()),
        'execution_time' => round((microtime(true) - $start) * 1000, 2) . ' ms',
    ];
    return response()->json([
        'message' => 'HireHub Phase 3 Performance Comparison',
        'stats' => $results
    ]);
});
