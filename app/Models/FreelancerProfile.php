<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreelancerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'hourly_rate',
        'status',
        'avatar',
        'is_verified',
        'average_rating',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // CAST...
    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'is_verified' => 'boolean',
        'average_rating' => 'decimal:2',
    ];

    //ACCESSORS...
    public function getAvatarUrlAttribute()
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : asset('default-avatar.png');
    }
    public function getMemberSinceAttribute()
    {
        return 'Member since ' . $this->created_at->format('F Y');
    }
    public function getRatingAttribute()
    {
        $avg = $this->average_rating > 0
            ? $this->average_rating
            : $this->user->receivedReviews()->avg('rating');

        return $avg ? '⭐ ' . number_format($avg, 1) : 'No rating';
    }

    //APPENDS...
    protected $appends = [
        'avatar_url',
        'member_since',
        'rating',
    ];

    //SCOP...
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }
    public function scopeOrderByRating($query, $direction = 'desc')
    {
        return $query->withAvg('user as average_rating', 'received_reviews.rating')
            ->orderBy('average_rating', $direction);
    }
}
