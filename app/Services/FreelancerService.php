<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class FreelancerService
{
    /**
     * Retrieve a paginated list of freelancers with optional filters.
     *
     * This method builds a query to fetch users of type "freelancer",
     * including their related skills, average rating, and completed projects count.
     * It supports filtering by:
     * - verified status (email verified)
     * - specific skill
     * - sorting by latest or rating
     *
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllFreelancers($filters = [])
    {
        $query = User::query()
            ->where('type', 'freelancer') 
            ->with(['skills'])
            ->withAvg('receivedReviews as average_rating', 'rating')
            ->withCount('completedProjects'); 

        // Filter: only verified freelancers
        if (isset($filters['verified']) && $filters['verified'] == true) {
            $query->whereNotNull('email_verified_at'); 
        }

        // Filter: freelancers having a specific skill
        if (isset($filters['skill_id'])) {
            $query->whereHas('skills', function (Builder $q) use ($filters) {
                $q->where('id', $filters['skill_id']);
            });
        }

        // Sorting logic: by rating or latest
        $sortBy = $filters['sort_by'] ?? 'latest';
        if ($sortBy === 'rating') {
            $query->orderByDesc('average_rating');
        } else {
            $query->latest();
        }

        return $query->paginate(10);
    }

    /**
     * Retrieve a single freelancer profile by ID.
     *
     * This method fetches a freelancer with full details including:
     * - skills
     * - portfolio items
     * - average rating
     * - completed projects count
     * - total reviews count
     *
     * Throws an exception if the freelancer is not found.
     *
     * @param int $id
     * @return User
     */
    public function getFreelancerProfile($id)
    {
        return User::query()
            ->where('type', 'freelancer')
            ->with(['skills', 'portfolio']) 
            ->withAvg('receivedReviews as average_rating', 'rating')
            ->withCount(['completedProjects', 'receivedReviews'])
            ->findOrFail($id);
    }
}