<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddSecurityHeaders
{
    /**
     * Attach security headers to the outgoing response.
     *
     */
    public function handle(Request $request, Closure $next) : mixed
    {
        return $next($request)
            ->header('X-Frame-Options', 'SAMEORIGIN')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->header('Strict-Transport-Security', 'max-age=2592000; includeSubDomains');
    }
}
