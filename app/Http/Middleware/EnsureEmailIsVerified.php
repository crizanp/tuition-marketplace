<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard = 'web'): Response
    {
        $user = Auth::guard($guard)->user();
        
        // Allow access to email verification pages even if not verified
        if ($request->is('*/email/verify') || $request->is('*/email/verification-notification')) {
            return $next($request);
        }
        
        if (!$user || !$user->hasVerifiedEmail()) {
            if ($guard === 'tutor') {
                return redirect('/tutor/email/verify');
            } else {
                return redirect('/student/email/verify');
            }
        }
        
        return $next($request);
    }
}
