<?php

namespace App\Http\Controllers\Pen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pen\PenRequest;
use App\Models\Pen;
use App\Support\Concerns\ResolvesGatewayUrl;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class UpdatePenController extends Controller
{
    use ResolvesGatewayUrl;

    public function updatePen(PenRequest $request, string $penCode): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();
        $notes = trim((string) ($validated['notes'] ?? ''));
        $existingPen = Pen::query()->where('pen_code', $penCode)->first();
        $recordDate = $existingPen?->record_date
            ? Carbon::parse($existingPen->record_date)->toIso8601String()
            : now()->toIso8601String();
        $status = 'available';

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->put($this->endpointUrl('/pen/update/'.$penCode.'/'), [
                    'pen_code' => $penCode,
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
                $this->extractMessage($response->json(), 'Failed to update pen.'),
                $response->status()
            );
        }

        $payload = $response->json();
        $returnedPenCode = (string) ($payload['pen_code'] ?? $penCode);

        Pen::query()->where('pen_code', $returnedPenCode)->update([
            'pen_name' => (string) ($payload['pen_name'] ?? $validated['pen_name']),
            'capacity' => (int) ($payload['capacity'] ?? $validated['capacity']),
            'status' => (string) ($payload['status'] ?? $status),
            'notes' => $notes !== '' ? $notes : null,
            'record_date' => $payload['date'] ?? $recordDate,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => $this->extractMessage($payload, 'Pen updated successfully.'),
                'pen_code' => $returnedPenCode,
            ], $response->status());
        }

        return redirect()
            ->route('show.pig')
            ->with('success', $this->extractMessage($payload, 'Pen updated successfully.'));
    }

    public function updatePenFromWeb(PenRequest $request, string $penCode): RedirectResponse
    {
        $response = $this->updatePen($request, $penCode);

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        return redirect()->route('show.pig');
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
