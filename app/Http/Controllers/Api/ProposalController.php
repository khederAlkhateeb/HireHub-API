<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProposalRequest;
use App\Http\Resources\ProposalResource;
use App\Services\ProposalService;
use App\Models\Proposal;

class ProposalController extends Controller
{
    protected $proposalService;

    public function __construct(ProposalService $proposalService)
    {
        $this->proposalService = $proposalService;
    }

    public function store(StoreProposalRequest $request, $projectId)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $proposal = $this->proposalService->submitProposal($data, $projectId);

        return new ProposalResource($proposal);
    }

    public function show($id)
    {
        $proposal = $this->proposalService->getProposalDetails($id);
        return new ProposalResource($proposal);
    }
    public function accept($id)
    {
        $proposal = Proposal::findOrFail($id);

        app(ProposalService::class)->acceptProposal($proposal);

        return response()->json(['message' => 'Accepted']);
    }
}
