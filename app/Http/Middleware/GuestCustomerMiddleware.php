<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class GuestCustomerMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard('customer')->check()) {
            return redirect('/');
        }

        return $next($request);
    }
}
