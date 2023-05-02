<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
            if (Auth::guard('web')->check()) {
                return redirect()->route('student.dashboard');
            }
            if (Auth::guard('admin')->check()) {
                return redirect()->route('admin.dashboard');
            }
            if (Auth::guard('lecturer')->check()) {
                return redirect()->route('lecturer.dashboard');
            }

        return $next($request);  
    }
}
