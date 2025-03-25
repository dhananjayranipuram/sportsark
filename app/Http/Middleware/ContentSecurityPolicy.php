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

        // $csp = "
        //         default-src 'self'; 
        //         script-src 'self' 'unsafe-inline' 'unsafe-eval' https://connectark.com/dev.sportsark/public/; 
        //         style-src 'self' 'unsafe-inline' https://connectark.com/dev.sportsark/public/; 
        //         img-src 'self' data:; 
        //         object-src 'none'; 
        //         base-uri 'self'; 
        //         frame-ancestors 'none'; 
        //         form-action 'self'; 
        //         upgrade-insecure-requests; 
        //         block-all-mixed-content;
        //     ";

        $csp = "";
            $csp = trim(preg_replace('/\s+/', ' ', $csp));

            $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
