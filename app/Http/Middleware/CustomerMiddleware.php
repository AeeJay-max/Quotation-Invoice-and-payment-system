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

        // For clients registered via admin dashboard: enforce email verification link first, then password update
        if ($user->created_by_admin) {
            // Step 1: Force email verification first via email link
            if (is_null($user->email_verified_at)) {
                if (!$request->is('customer/verify-email*') && !$request->is('customer/verify/*') && !$request->is('logout')) {
                    return redirect()->route('customer.verify-email')
                        ->with('warning', 'Your account was created by an administrator. A verification email link was sent to your email. You must verify your email address before setting your password.');
                }
            } else {
                // Email is verified. Now Step 2: Force password change if default password is still active
                if ($user->must_change_password) {
                    if (!$request->is('customer/must-change-password*') && !$request->is('logout')) {
                        return redirect()->route('customer.must-change-password')
                            ->with('warning', 'Email verified successfully! Please change your default password to activate your account.');
                    }
                } else {
                    // If both email is verified & password updated, prevent lingering on onboarding screens
                    if ($request->is('customer/verify-email*') || $request->is('customer/must-change-password*')) {
                        return redirect()->route('customer.dashboard');
                    }
                }
            }
        }

        return $next($request);
    }
}
