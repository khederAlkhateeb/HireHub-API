<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'title' => $this->title,
            'formatted_budget' => $this->formatted_budget,
            'deadline_status' => round(now()->diffInDays($this->deadline)) . ' days left',
            'proposals_count' => $this->proposals_count,

            'client' => [
                'id' => $this->client->id,
                'name' => $this->client->first_name . ' ' . $this->client->last_name,
                'average_rating' => $this->client->average_rating,
            ],

            'tags' => $this->tags->map(fn($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
            ]),
        ];
    }
}
