<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\AuthLoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(AuthRegisterRequest $request): JsonResponse
    {
        [$user, $token] = $this->authService->register($request->validated());

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user
        ], 201);
    }

    public function login(AuthLoginRequest $request): JsonResponse
    {
        [$user, $token] = $this->authService->login($request->validated());

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'id'   => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'type' => $user->type,
            ]
        ]);
    }
}