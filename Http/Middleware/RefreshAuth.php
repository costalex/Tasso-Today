<?php

namespace App\Http\Middleware;

use function app;
use Closure;
use Illuminate\Support\Facades\Auth;

class RefreshAuth
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
	    if (!Auth::check())
		    app('App\Http\Controllers\UserInformationsController')->refreshUserSession();
        return $next($request);
    }
}
