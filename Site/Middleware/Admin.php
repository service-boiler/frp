<?php

namespace ServiceBoiler\Prf\Site\Middleware;

/**
 * @license MIT
 * @package ServiceBoiler\Prf\Site
 */


use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (Auth::check() && Auth::user()->admin == 1) {
            return $next($request);
        }
        if (Auth::user()->hasRole(['teacher'], false) == 1) {
            return redirect()->route('ferroli-user.home')->with('error', trans('site::massage.no_admin_access'));
        }
        return app()->abort(403);
        
    }
}