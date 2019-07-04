<?php

namespace App\Http\Middleware;

use function abort;
use function app;
use Closure;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class RedirectUserTypeTableMiddleware
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
		if (Cookie::has('API-TOKEN'))
		{
			if(!Auth::check() && !app('App\Http\Controllers\UserInformationsController')->refreshUserSession())
			{
				Cookie::forget('API-TOKEN');
				$request->session()->forget('API-TOKEN');
				return Response::json(['user' => ['user_type_id' => 'Guest']]);
			}
		}
		else
			return Response::json(['user' => ['user_type_id' => 'Guest']]);


		if (Auth::check() && $request->isMethod('put'))
		{
			if (Auth::user()->isClient())
				return app('App\Http\Controllers\ClientInformationsController')->updateClient($request);
			else if (Auth::user()->isEntreprise())
			{
				$request->id = 0;
				$request->user_id = Auth::user()->id;
				return app('App\Http\Controllers\EntrepriseInformationsController')->updateEntreprise($request);
			}
			else if (Auth::user()->isAdmin())
				return app('App\Http\Controllers\EntrepriseInformationsController')->updateEntreprise($request);
		}
		else if (Auth::check() && $request->isMethod('get'))
		{
			if (Auth::user()->isClient())
				return app('App\Http\Controllers\ClientInformationsController')->showClientInformations();
			else if (Auth::user()->isEntreprise())
				return app('App\Http\Controllers\EntrepriseInformationsController')->showEntrepriseInformations();
			else if (Auth::user()->isAdmin())
				return app('App\Http\Controllers\EntrepriseInformationsController')->showDetailsEntrepriseInformations($request->userInfo);
		}
		else if (Auth::check() && $request->isMethod('delete'))
			return app('App\Http\Controllers\UserInformationsController')->destroy();

		return $next($request);
	}
}
