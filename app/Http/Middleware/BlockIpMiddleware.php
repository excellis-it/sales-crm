<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public $not_block_ips = ['127.0.0.1', '122.176.24.235','136.232.78.74','136.232.78.75','136.232.78.76'];

    public function handle(Request $request, Closure $next)
    {
        // Get real client IP if behind a proxy
        $requestIp = $request->header('X-Forwarded-For') ?? $request->ip();

        // Check if the IP is allowed
        if (in_array($requestIp, $this->not_block_ips)) {
            return $next($request); // Allow access
        }

        // Deny access for all other IPs
        abort(403, 'Access denied.');
    }

}
