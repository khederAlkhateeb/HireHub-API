<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
        'name',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('years_of_experience');
    }
}
