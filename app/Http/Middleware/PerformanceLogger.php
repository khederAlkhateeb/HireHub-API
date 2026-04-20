<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Auth;

class PerformanceLogger
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->set('start_time', microtime(true));

        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $startTime = $request->attributes->get('start_time');

        if ($startTime) {
            $endTime = microtime(true);
            $duration = round(($endTime - $startTime) * 1000);

            $userId = $request->user('sanctum') ? $request->user('sanctum')->id : null;
            ApiLog::create([
                'endpoint'      => $request->fullUrl(),
                'method'        => $request->method(),
                'user_id'       => $userId,
                'response_time' => $duration,
            ]);
        }
    }
}
