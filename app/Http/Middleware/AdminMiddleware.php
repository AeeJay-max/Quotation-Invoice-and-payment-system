<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
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

        // Assuming role_id 1 is Admin, or user has an is_admin flag
        $user = auth()->user();
        if ($user->role_id != 1 && !$user->is_admin) {
            return redirect('/customer/dashboard')->with('error', 'Access denied. Administrator privileges required.');
        }

        return $next($request);
    }
}
