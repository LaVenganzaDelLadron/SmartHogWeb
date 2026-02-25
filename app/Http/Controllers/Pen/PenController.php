<?php

namespace App\Http\Controllers\Pig;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pen\PenRequest;
use App\Models\Pen;
use App\Models\PigBatch;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class PenController extends Controller
{

    public function addPen(PenRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();
        $status = 'available';
        $notes = trim((string) ($validated['notes'] ?? ''));
        $recordDate = isset($validated['date'])
            ? Carbon::parse($validated['date'])->toIso8601String()
            : now()->toIso8601String();

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->post($this->endpointUrl('/pen/add/'), [
                    'pen_name' => $validated['pen_name'],
                    'capacity' => (int) $validated['capacity'],
                    'status' => $status,
                    'notes' => $notes !== '' ? $notes : 'No notes',
                    'date' => $recordDate,
                ]);
        } catch (ConnectionException) {
            return $this->handleGatewayFailure($request, 'Pen service is currently unavailable. Please try again.');
        }

        if (! $response->successful()) {
            return $this->handleGatewayFailure(
                $request,
                $this->extractMessage($response->json(), 'Failed to create pen.'),
                $response->status()
            );
        }

        $payload = $response->json();
        $penCode = (string) ($payload['pen_code'] ?? '');
        $penName = (string) ($payload['pen_name'] ?? $validated['pen_name']);

        if ($penCode !== '') {
            Pen::query()->updateOrCreate(
                ['pen_code' => $penCode],
                [
                    'pen_name' => $penName,
                    'capacity' => (int) $validated['capacity'],
                    'status' => $status,
                    'notes' => $notes !== '' ? $notes : null,
                ]
            );
        }

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => $this->extractMessage($payload, 'Pen Successfully Created'),
                'pen_code' => $penCode,
                'pen_name' => $penName,
            ], $response->status());
        }

        return redirect()
            ->route('show.pig')
            ->with('success', $this->extractMessage($payload, 'Pen Successfully Created'));
    }

    public function addPenFromWeb(PenRequest $request): RedirectResponse
    {
        $response = $this->addPen($request);

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        return redirect()->route('show.pig');
    }

    private function endpointUrl(string $path): string
    {
        $baseUrl = rtrim((string) config('services.shapi_auth.base_url', 'http://shapi-qq0p.onrender.com'), '/');
        $normalizedPath = '/'.ltrim($path, '/');

        return $baseUrl.$normalizedPath;
    }

    private function extractMessage(mixed $payload, string $fallback): string
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

        return $fallback;
    }

    private function handleGatewayFailure(Request $request, string $message, int $status = 503): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => false,
                'message' => $message,
            ], $status);
        }

        return back()
            ->withInput($request->except('password', 'password_confirmation'))
            ->withErrors(['pen' => $message]);
    }
}
