<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showSignup(): View
    {
        return view('auth.signup');
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function signup(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Frontend-only signup accepted.',
            ]);
        }

        return redirect()
            ->route('show.login')
            ->with('success', 'Frontend-only signup accepted.');
    }

    public function login(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'redirect' => route('show.dashboard'),
                'message' => 'Frontend-only login accepted.',
            ]);
        }

        return redirect()
            ->route('show.dashboard')
            ->with('success', 'Frontend-only login accepted.');
    }

    public function logout(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Frontend-only logout accepted.',
            ]);
        }

        return redirect()
            ->route('show.login')
            ->with('success', 'Frontend-only logout accepted.');
    }
}
