<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'amount',
        'delivery_days',
        'proposal',
        'status',
    ];
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
