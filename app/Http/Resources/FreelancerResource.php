<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FreelancerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'bio' => $this->bio,
            'verified' => $this->email_verified_at !== null,
            'skills' => $this->skills->pluck('name'),
            'experience_years' => $this->experience_years,
            'rating' => round($this->average_rating, 1) ?? 0,
            'projects_count' => $this->completed_projects_count ?? 0,
        ];
    }
}
