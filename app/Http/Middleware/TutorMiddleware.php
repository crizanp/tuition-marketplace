<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TutorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('tutor')->check()) {
            return redirect('/tutor/login');
        }

        return $next($request);
    }
}