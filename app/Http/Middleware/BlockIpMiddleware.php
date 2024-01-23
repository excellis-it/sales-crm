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
    public $blockIps = ['127.0.0.1:8000', '122.176.24.235'];

    public function handle(Request $request, Closure $next)
    {
        if (in_array($request->ip(), $this->blockIps)) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }

}
