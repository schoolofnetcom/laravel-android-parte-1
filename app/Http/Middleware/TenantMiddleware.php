<?php

namespace SON\Http\Middleware;

use Closure;

class TenantMiddleware
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
        $user = \JWTAuth::parseToken()->toUser();
        \Landlord::addTenant($user);
        return $next($request);
    }
}
