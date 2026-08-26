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
