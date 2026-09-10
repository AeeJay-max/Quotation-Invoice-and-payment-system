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

        // For clients registered via admin dashboard: enforce password change and email verification
        if ($user->created_by_admin) {
            // Step 1: Force password change if default password is still active
            if ($user->must_change_password) {
                if (!$request->is('customer/must-change-password*') && !$request->is('logout')) {
                    return redirect()->route('customer.must-change-password')
                        ->with('warning', 'Your account was registered by an administrator with a default password. You must change your password to continue.');
                }
            } else {
                // If they are on must-change-password page but already changed it, redirect onward
                if ($request->is('customer/must-change-password*')) {
                    if (is_null($user->email_verified_at)) {
                        return redirect()->route('customer.verify-email');
                    }
                    return redirect()->route('customer.dashboard');
                }

                // Step 2: Force email verification if not yet verified
                if (is_null($user->email_verified_at)) {
                    if (!$request->is('customer/verify-email*') && !$request->is('customer/verify/*') && !$request->is('logout')) {
                        return redirect()->route('customer.verify-email')
                            ->with('warning', 'Please verify your email address to complete your account activation.');
                    }
                } else {
                    // If they are on verify-email page but already verified, redirect to dashboard
                    if ($request->is('customer/verify-email*')) {
                        return redirect()->route('customer.dashboard');
                    }
                }
            }
        }

        return $next($request);
    }
}
