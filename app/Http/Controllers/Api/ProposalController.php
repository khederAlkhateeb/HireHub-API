<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProposalRequest;
use App\Http\Resources\ProposalResource;
use App\Services\ProposalService;

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
        $this->proposalService->acceptProposalById($id);

        return response()->json(['message' => 'Accepted']);
    }
}
