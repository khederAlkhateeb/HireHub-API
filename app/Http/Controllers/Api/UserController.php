<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function me(): JsonResponse
    {
        return response()->json([
            'user' => new UserResource(auth()->user()),
        ]);
    }
}
