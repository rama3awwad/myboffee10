<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class setLocal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $userLocal = 'en';
        if (Auth::user()) {
            $userLocal = Auth::user()['lang'];
        }
        if (in_array($userLocal, config('app.available_locales'))) {
            app()->setLocale($userLocal);
        }
        return $next($request);
    }
    }

