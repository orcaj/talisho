<?php

namespace App\Http\Middleware;

use Closure;

class CheckProjectAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->route('project')->full_access) {
            abort(403, 'Payment required to access this portion of the application.');
        }

        return $next($request);
    }
}
