<?php

namespace App\Support\Handler;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait HandlerFailure
{
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
