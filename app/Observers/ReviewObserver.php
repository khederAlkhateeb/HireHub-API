<?php

namespace App\Observers;

use App\Jobs\RecalculateFreelancerRating;
use App\Models\Review;

class ReviewObserver
{
    public function created(Review $review): void
    {
        if ($review->reviewable_type === \App\Models\User::class) {
            RecalculateFreelancerRating::dispatch($review)->afterCommit();
        }
    }
}
