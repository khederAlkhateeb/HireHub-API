<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFreelancerIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {

        $user = auth()->user();

        if ($user && $user->profile && !$user->profile->is_verified) {

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your account is not verified yet.'], 403);
            }

            return redirect()->route('home');
        }

        return $next($request);
    }
}
