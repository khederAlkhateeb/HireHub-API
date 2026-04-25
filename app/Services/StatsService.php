<?php

namespace App\Services;

use App\Models\User;
use App\Models\Project;
use App\Models\Proposal;

class StatsService
{
    public function getGlobalStats(): array
    {
        return [
            'total_users' => User::count(),
            'total_projects' => Project::count(),
            'total_proposals' => Proposal::count(),
            'total_proposals_value' => Proposal::sum('amount'),
            'active_projects' => Project::where('status', 'open')->count(),
        ];
    }
}
