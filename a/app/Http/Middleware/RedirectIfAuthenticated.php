<?php

namespace Autovilla\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $val = Auth::user()->roles()->pluck('name')->first();
            //dd($val);
            if ($val == 'Admin')
            {
            return redirect()->route('home');
            }
            elseif($val == 'User')
            {
                if(Auth::user()->status == 1)
                    return redirect()->route('site');
                else
                    return redirect()->route('error');
            }
            //return redirect('/home');
        }

        return $next($request);
    }
}
