<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'type',
        'phone',
        'city_id',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    //RELATIONS...
    public function profile()
    {
        return $this->hasOne(FreelancerProfile::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function skills()
    {
        return $this->belongsToMany(Skill::class)->withPivot('years_of_experience');
    }
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }
    public function country()
    {
        return $this->hasOneThrough(Country::class, City::class);
    }
    public function receivedReviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
    public function completedProjects()
    {
        return $this->hasMany(Project::class, 'user_id')
            ->where('status', 'closed');
    }

    public function portfolio()
    {
        return $this->hasMany(Portfolio::class);
    }

    //ACCESSOR...
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    //MUTATOR...
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
    public function setPhoneAttribute($value)
    {
        $phone = preg_replace('/[^0-9]/', '', $value);

        if (str_starts_with($phone, '0')) {
            $phone = '+963' . substr($phone, 1);
        }

        if (str_starts_with($phone, '00963')) {
            $phone = '+963' . substr($phone, 5);
        }

        $this->attributes['phone'] = $phone;
    }

    //APPENDS...
    protected $appends = ['full_name'];

    //LOCAL SCOPE...
    public function scopeFreelancers($query)
    {
        return $query->where('type', 'freelancer');
    }

    public function scopeClients($query)
    {
        return $query->where('type', 'client');
    }
}
