<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProfileService
{
    /**
     * Update the user's basic information and freelancer profile (if applicable).
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function updateProfile(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $userData = array_intersect_key($data, array_flip(['first_name', 'last_name', 'phone', 'city_id']));
            $user->update($userData);

            if ($user->type === 'freelancer') {
                $profileData = array_intersect_key($data, array_flip(['bio', 'experience_years', 'status']));
                
                if (!empty($profileData)) {
                    $user->profile()->updateOrCreate(
                        ['user_id' => $user->id],
                        $profileData
                    );
                }

                Cache::tags(['freelancers'])->flush();
            }

            return $user->load('profile');
        });
    }

    /**
     * Sync user's skills and invalidate cache.
     */
    public function syncSkills(User $user, array $skillsWithExperience)
    {
        return DB::transaction(function () use ($user, $skillsWithExperience) {
            $synced = $user->skills()->sync($skillsWithExperience);

            if ($user->type === 'freelancer') {
                Cache::tags(['freelancers'])->flush();
            }

            return $synced;
        });
    }

    /**
     * Retrieve full user profile.
     */
    public function getProfile(User $user): User
    {
        return $user->load([
            'profile', 
            'city.country',
            'skills'
        ]);
    }
}