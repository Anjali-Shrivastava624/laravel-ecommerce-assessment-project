<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(array_merge($credentials, ['role' => 'admin']))) {
            $request->session()->regenerate();
            Auth::user()->setOnlineStatus(true);

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records or you are not an admin.',
        ])->onlyInput('email');
    }

    public function showRegistrationForm()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        Auth::login($user);
        $user->setOnlineStatus(true);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::user()->setOnlineStatus(false);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
