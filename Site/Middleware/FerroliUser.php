<?php

namespace ServiceBoiler\Prf\Site\Middleware;

/**
 * @license MIT
 * @package ServiceBoiler\Prf\Site
 */


use Closure;
use Illuminate\Support\Facades\Auth;

class FerroliUser
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

        if (Auth::check() && (Auth::user()->hasRole(['teacher','admin','ferroli_user','supervisor','ferroli_staff'], false) == 1 || Auth::user()->admin == 1)) {
            return $next($request);
        }
        return app()->abort(403);
    }
}