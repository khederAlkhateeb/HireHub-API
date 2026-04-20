<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FreelancerService;
use App\Http\Requests\FreelancerFilterRequest; // استدعاء الكلاس الجديد
use App\Http\Resources\FreelancerResource;

class FreelancerController extends Controller
{
    protected $freelancerService;

    public function __construct(FreelancerService $freelancerService)
    {
        $this->freelancerService = $freelancerService;
    }

    public function index(FreelancerFilterRequest $request)
    {
        $filters = $request->validated();
        
        $freelancers = $this->freelancerService->getAllFreelancers($filters);
        
        return FreelancerResource::collection($freelancers);
    }

    public function show($id)
    {
        $freelancer = $this->freelancerService->getFreelancerProfile($id);
        
        return new FreelancerResource($freelancer);
    }
}