<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignupRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

    public function signup(SignupRequest $request): JsonResponse|RedirectResponse
    {
        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->post($this->endpointUrl('/auth/signup/'), [
                    'username' => $request->string('name')->trim()->toString(),
                    'email' => $request->string('email')->trim()->toString(),
                    'password' => $request->string('password')->toString(),
                ]);
        } catch (ConnectionException) {
            return $this->handleGatewayFailure($request, 'Signup service is currently unavailable. Please try again.');
        }

        if (! $response->successful()) {
            return $this->handleApiFailure($request, $response->status(), $response->json(), 'Signup failed. Please try again.');
        }

        $payload = $response->json();
        $message = $this->extractMessage($payload, 'Signup Successfully');

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => $message,
                'data' => $payload,
            ], $response->status());
        }

        return redirect()
            ->route('show.login')
            ->with('success', $message);
    }

    public function login(LoginRequest $request): JsonResponse|RedirectResponse
    {
        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->post($this->endpointUrl('/auth/login/'), [
                    'email' => $request->string('email')->trim()->toString(),
                    'password' => $request->string('password')->toString(),
                ]);
        } catch (ConnectionException) {
            return $this->handleGatewayFailure($request, 'Login service is currently unavailable. Please try again.');
        }

        if (! $response->successful()) {
            return $this->handleApiFailure($request, $response->status(), $response->json(), 'Invalid email or password');
        }

        $payload = $response->json();
        $message = $this->extractMessage($payload, 'Login Successfully');

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'redirect' => route('show.dashboard'),
                'message' => $message,
                'data' => $payload,
            ], $response->status());
        }

        return redirect()
            ->route('show.dashboard')
            ->with('success', $message);
    }

    public function logout(Request $request): JsonResponse|RedirectResponse
    {
        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->post($this->endpointUrl('/auth/logout/'), [
                ]);
        } catch (ConnectionException) {
            return $this->handleGatewayFailure($request, 'Logout service is currently unavailable. Please try again.');
        }

        if (! $response->successful()) {
            return $this->handleApiFailure($request, $response->status(), $response->json(), 'Logout failed. Please try again.');
        }

        $payload = $response->json();
        $message = $this->extractMessage($payload, 'Logout Successfully');

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => $message,
                'data' => $payload,
            ], $response->status());
        }

        return redirect()
            ->route('show.login')
            ->with('success', $message);
    }

    private function endpointUrl(string $path): string
    {
        $baseUrl = rtrim((string) config('services.shapi_auth.base_url', 'http://shapi-qq0p.onrender.com'), '/');
        $normalizedPath = '/'.ltrim($path, '/');

        return $baseUrl.$normalizedPath;
    }

    private function handleApiFailure(Request $request, int $status, mixed $payload, string $fallbackMessage): JsonResponse|RedirectResponse
    {
        $message = $this->extractMessage($payload, $fallbackMessage);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => false,
                'message' => $message,
                'errors' => $payload,
            ], $status);
        }

        return back()
            ->withInput($request->except('password', 'password_confirmation'))
            ->withErrors(['auth' => $message]);
    }

    private function handleGatewayFailure(Request $request, string $message): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => false,
                'message' => $message,
            ], 503);
        }

        return back()
            ->withInput($request->except('password', 'password_confirmation'))
            ->withErrors(['auth' => $message]);
    }

    private function extractMessage(mixed $payload, string $fallbackMessage): string
    {
        if (is_array($payload)) {
            $message = $payload['message'] ?? null;
            if (is_string($message) && $message !== '') {
                return $message;
            }

            $firstValue = reset($payload);
            if (is_array($firstValue)) {
                $firstItem = $firstValue[0] ?? null;
                if (is_string($firstItem) && $firstItem !== '') {
                    return $firstItem;
                }
            }

            if (is_string($firstValue) && $firstValue !== '') {
                return $firstValue;
            }
        }

        return $fallbackMessage;
    }
}
