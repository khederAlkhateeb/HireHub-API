<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'full_name'  => $this->full_name,
            'email'      => $this->email,
            'type'       => $this->type,
            'phone'      => $this->phone,
            'location'   => [
                'city'    => $this->city->name ?? null,
                'country' => $this->city->country->name ?? null,
            ],
            'profile'    => [
                'bio'          => $this->profile->bio ?? null,
                'hourly_rate'  => $this->profile->hourly_rate ?? 0,
                'status'       => $this->profile->status ?? 'unknown',
                'avatar_url'   => $this->profile->avatar_url ?? null,
                'rating'       => $this->profile->rating ?? 'No rating',
            ],
            'skills'     => $this->skills->map(function($skill) {
                return [
                    'name'  => $skill->name,
                    'years' => $skill->pivot->years_of_experience ?? 0
                ];
            }),
        ];
    }
}
