<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('admin')->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Please login first.');
        }

        // Allow both admin and superadmin roles
        if (in_array(auth('admin')->user()->role, ['admin', 'superadmin'])) {
            return $next($request);
        }

        return redirect()->route('admin.login')
            ->with('error', 'Unauthorized access.');
    }
}
