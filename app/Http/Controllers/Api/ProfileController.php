<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdateSkillsRequest;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $this->profileService->updateProfile(auth()->user(), $request->validated());

        return response()->json(['message' => 'Profile updated successfully']);
    }

    public function updateSkills(UpdateSkillsRequest $request): JsonResponse
    {
        $this->profileService->syncSkills(auth()->user(), $request->getFormattedSkills());

        return response()->json(['message' => 'Skills updated successfully']);
    }
    public function show(): JsonResponse
    {
        $user = $this->profileService->getProfile(auth()->user());

        return response()->json([
            'user' => new \App\Http\Resources\UserResource($user)
        ]);
    }
}
