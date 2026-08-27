<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomerMiddleware
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
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();
        
        // Admin shouldn't be in customer portal
        if ($user->role_id == 1 || $user->is_admin) {
            return redirect('/dashboard')->with('error', 'Administrators should use the Admin Dashboard.');
        }

        // Must be customer (role_id == 2)
        if ($user->role_id != 2) {
            return redirect('/login')->with('error', 'Access denied. Exhibitor portal only.');
        }

        return $next($request);
    }
}
