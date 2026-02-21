<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showSignup()
    {
        if (Auth::check()) {
            return redirect()->route('show.dashboard');
        }

        return view('auth.signup');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('show.dashboard');
        }

        return view('auth.login');
    }

    public function Signup(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Account created successfully.',
            ]);
        }

        return redirect()
            ->route('show.login')
            ->with('success', 'Account created successfully. Please login.');
    }

    public function Login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($validated, $remember)) {
            $request->session()->regenerate();

            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => true,
                    'redirect' => route('show.dashboard'),
                    'message' => 'Welcome Back!',
                ]);
            }

            return redirect()
                ->route('show.dashboard')
                ->with('success', 'Welcome Back!');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Invalid username or password',
                'errors' => [
                    'email' => ['Invalid username or password'],
                ],
            ], 422);
        }

        return back()
            ->withErrors(['email' => 'Invalid username or password'])
            ->onlyInput('email');
    }

    public function Logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Logout Successfully',
            ]);
        }

        return redirect()
            ->route('show.login')
            ->with('success', 'Logout Successfully');
    }
}
