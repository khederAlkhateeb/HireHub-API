<?php

namespace App\Jobs;

use App\Models\FreelancerProfile;
use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class RecalculateFreelancerRating implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $queue = 'ratings';
    public int $tries = 5;
    public array $backoff = [60, 120, 300];

    protected Review $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function handle(): void
    {
        $review = $this->review->fresh('reviewable');

        if (!$review || $review->reviewable_type !== \App\Models\User::class) {
            return;
        }

        $user = $review->reviewable;
        if (!$user) {
            return;
        }

        DB::transaction(function () use ($user) {
            $profile = FreelancerProfile::where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if (!$profile) {
                return;
            }

            $averageRating = $user->receivedReviews()->avg('rating') ?? 0;
            $profile->update(['average_rating' => $averageRating]);
            Cache::tags(['freelancers'])->flush();
        });
    }

    public function failed(Throwable $exception): void
    {
        Log::error('RecalculateFreelancerRating failed', [
            'review_id' => $this->review->id,
            'reviewable_type' => $this->review->reviewable_type,
            'reviewable_id' => $this->review->reviewable_id,
            'exception' => $exception->getMessage(),
        ]);
    }
}
