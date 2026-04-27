<?php

namespace App\Services;

use App\Jobs\SendProposalStatusNotification;
use App\Models\Proposal;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ProposalService
{
    /**
     * Submit a proposal to a project (only if project is open).
     */
    public function submitProposal(array $data, $projectId)
    {
        return DB::transaction(function () use ($data, $projectId) {
            $project = Project::findOrFail($projectId);

            // Ensure project is open
            if ($project->status !== 'open') {
                throw new \Exception("Cannot submit proposal to a non-open project.");
            }

            return $project->proposals()->create($data);
        });
    }

    /**
     * Get proposal details with freelancer and project client.
     */
    public function getProposalDetails($id)
    {
        return Proposal::with(['freelancer', 'project.client'])->findOrFail($id);
    }

    /**
     * Accept a proposal and reject all others for the same project.
     */
    public function acceptProposal($proposal)
    {
        // Mark selected proposal as accepted
        $proposal->update(['status' => 'accepted']);

        // Reject all other proposals for the same project
        $proposal->project->proposals()
            ->where('id', '!=', $proposal->id)
            ->update(['status' => 'rejected']);

        // Notify selected freelancer in the background.
        SendProposalStatusNotification::dispatch(
            $proposal->freelancer,
            'accepted',
            $proposal->project->title
        )->afterCommit();

        // Notify rejected freelancers in the background.
        $proposal->project->proposals()
            ->where('id', '!=', $proposal->id)
            ->get()
            ->each(function ($rejectedProposal) {
                SendProposalStatusNotification::dispatch(
                    $rejectedProposal->freelancer,
                    'rejected',
                    $rejectedProposal->project->title
                )->afterCommit();
            });

        return $proposal;
    }

    public function acceptProposalById($id)
    {
        return DB::transaction(function () use ($id) {
            $proposal = Proposal::with('project')->findOrFail($id);

            return $this->acceptProposal($proposal);
        });
    }
}