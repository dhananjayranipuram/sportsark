<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('Content-Security-Policy', 
            "default-src 'self'; 
            script-src 'self' 'unsafe-inline' 'unsafe-eval' https://trusted-scripts.com;
            style-src 'self' 'unsafe-inline' https://trusted-styles.com;
            img-src 'self' data:;
            object-src 'none';
            base-uri 'self';
            frame-ancestors 'none';
            form-action 'self';
            upgrade-insecure-requests;
            block-all-mixed-content;"
        );

        return $response;
    }
}
