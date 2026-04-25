<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'total_users' => $this->total_users,
            'total_projects' => $this->total_projects,
            'total_proposals' => $this->total_proposals,
            'total_proposals_value' => $this->total_proposals_value,
            'active_projects' => $this->active_projects,
        ];
    }
}
