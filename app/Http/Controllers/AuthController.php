<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Str;

class AuthController extends Controller
{
    // @Method GET
    // @Route /auth/login
    public function index()
    {
        return view("auth.login");
    }

    // @Method POST
    // @Route /auth/login
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email|max:100',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($credentials)) {
            // Regenerate session to prevent fixation
            $request->session()->regenerate();

            $user = Auth::user(); // Now authenticated user

            // Redirect based on role
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended(route('home'))->with('success', "You are now logged In!");
                case 'manager':
                    return redirect()->intended(route('home'))->with('success', "You are now logged In!");
                case 'employee':
                    return redirect()->intended(route('employees.showMe'))->with('success', "You are now logged In!");
                case 'student':
                    return redirect()->intended(route('students.showMe'))->with('success', "You are now logged In!");
                default:
                    Auth::logout(); // log out if role is invalid
                    return redirect()->route('login')->withErrors(['email' => 'Unauthorized role.']);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // @Method POST
    // @Route /auth/logout
    public function logout(Request $request): RedirectResponse
    {
        // Logout the user
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        // Redirect to login page (or any route you prefer)
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }

    // @Method GET
    // @Route /auth/forget-password
    public function showLinkRequestForm()
    {
        return view('auth.forgetPassword');
    }

    // @Method POST
    // @Route /auth/forget-password
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    // @Method GET
    // @Route /auth/reset-password/{token}
    public function showResetForm(Request $request, $token)
    {
        return view('auth.resetPassword', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // @Method POST
    // @Route /auth/reset-password
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
