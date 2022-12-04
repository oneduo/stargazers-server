<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowVercelEdge
{
    public function handle(Request $request, Closure $next)
    {
        // is implicitly disabled ?
        if (config('app.vercel_token') === null) {
            return $next($request);
        }

        $request->headers->set('accept', 'application/json');

        abort_if($request->header('x-vercel-edge') !== config('app.vercel_token'), 400);

        return $next($request);
    }
}