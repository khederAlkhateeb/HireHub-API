<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProfileService
{
    /**
     * Update the user's basic information and freelancer profile (if applicable).
     *
     * This method updates the user's main fields (like name, phone, city)
     * and, if the user is a freelancer, updates or creates their profile data
     * (bio, experience years).
     *
     * The entire operation runs inside a database transaction to ensure consistency.
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function updateProfile(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            // Extract only allowed user fields
            $userData = array_intersect_key($data, array_flip(['first_name', 'last_name', 'phone', 'city_id']));
            $user->update($userData);

            // If user is a freelancer, update profile data
            if ($user->type === 'freelancer') {
                $profileData = array_intersect_key($data, array_flip(['bio', 'experience_years']));
                
                if (!empty($profileData)) {
                    $user->profile()->updateOrCreate(
                        ['user_id' => $user->id],
                        $profileData
                    );
                }
            }

            return $user->load('profile');
        });
    }

    /**
     * Sync user's skills with experience levels.
     *
     * This method updates the many-to-many relationship between the user
     * and skills, including pivot data (e.g., experience level per skill).
     * It replaces existing skills with the provided ones.
     *
     * Runs inside a transaction for data integrity.
     *
     * @param User $user
     * @param array $skillsWithExperience
     * @return array
     */
    public function syncSkills(User $user, array $skillsWithExperience)
    {
        return DB::transaction(function () use ($user, $skillsWithExperience) {
            return $user->skills()->sync($skillsWithExperience);
        });
    }

    /**
     * Retrieve full user profile with related data.
     *
     * This method loads the user's profile along with:
     * - city and its related country
     * - skills
     *
     * @param User $user
     * @return User
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