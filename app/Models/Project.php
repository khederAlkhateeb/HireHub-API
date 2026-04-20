<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'budget',
        'budget_type',
        'status',
        'deadline',
    ];
    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function getFormattedBudgetAttribute()
    {
        return $this->budget_type === 'hourly'
            ? '$' . $this->budget . '/hr'
            : '$' . $this->budget . ' USD';
    }
    public function getDeadlineStatusAttribute()
    {
        if (now()->gt($this->deadline)) {
            return 'Expired';
        }

        return now()->diffInDays($this->deadline) . ' days left';
    }

    protected $appends = [
        'formatted_budget',
        'deadline_status',
    ];

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month);
    }

    public function scopeMinBudget($query, $amount)
    {
        return $query->where('budget', '>=', $amount);
    }

}
