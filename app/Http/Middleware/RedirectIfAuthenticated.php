<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if ($guard == 'admin' && Auth::guard($guard)->check()) {
                return redirect()->route('admin.dashboard');
            }
            if (Auth::guard($guard)->check()) {
                return redirect('/');
            }
        }

        return $next($request);
    }
}
