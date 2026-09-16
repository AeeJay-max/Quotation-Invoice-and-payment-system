<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){
        $settings = Settings::where('type', 'system')->pluck('description', 'label');
        return view('login')->with(['settings' => $settings]);
    }

    public function adminLogin()
    {
        $settings = Settings::where('type', 'system')->pluck('description', 'label');
        return view('admin.login')->with(['settings' => $settings]);
    }

    public function signIn(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);

        if (!auth()->attempt($loginData, $request->has('remember'))) {
            return redirect()->back()->with(['error' => 'Invalid credentials']);
        }

        $user = auth()->user();
        if ($user->role_id == 1 || $user->is_admin) {
            return redirect()->intended('dashboard');
        }

        // For admin-created clients on first login: auto-send verification email
        // so they must verify before setting their password
        if ($user->created_by_admin && is_null($user->email_verified_at)) {
            $verificationUrl = route('customer.verify-email.verify', [
                'id'   => $user->id,
                'hash' => sha1($user->email),
            ]);
            try {
                \Illuminate\Support\Facades\Mail::raw(
                    "Hello {$user->name},\n\nWelcome to the MOSRAC Exhibitor Portal!\n\nYour account was created by an administrator. Please verify your email address and set your password by clicking the link below:\n\n{$verificationUrl}\n\nThis link will log you in and prompt you to set a secure password.\n\nThank you,\nMinistry of Sport, Recreation, Arts and Culture",
                    function ($message) use ($user) {
                        $message->to($user->email)->subject('Verify Your Account & Set Password - MOSRAC Exhibitor Portal');
                    }
                );
            } catch (\Exception $e) {
                // Silently fail if email not configured
            }
            return redirect('customer/verify-email')
                ->with('info', "A verification email has been sent to {$user->email}. Please click the link in your inbox to verify your account and set your new password.");
        }

        return redirect()->intended('customer/dashboard');
    }

    public function adminSignIn(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);

        if (!auth()->attempt($loginData, $request->has('remember'))) {
            return redirect()->back()->with(['error' => 'Invalid administrator credentials']);
        }

        $user = auth()->user();
        if ($user->role_id == 1 || $user->is_admin) {
            return redirect()->intended('dashboard');
        }

        Auth::logout();
        return redirect()->back()->with(['error' => 'Access denied. You do not have administrator privileges.']);
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }

    public function store(Request $request)
    {

            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'role_id' => 'required'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'is_admin' => true,
                'role_id' => $request->role_id,
                'password' => bcrypt($request->password)
            ]);

            return redirect('users?staff')->with(['success' => 'user created successfully']);

    }
}
