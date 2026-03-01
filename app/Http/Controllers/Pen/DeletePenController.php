<?php

namespace App\Http\Controllers\Pen;

use App\Http\Controllers\Controller;
use App\Models\Pen;
use App\Models\PigBatch;
use App\Support\Concerns\ResolvesGatewayUrl;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DeletePenController extends Controller
{
    use ResolvesGatewayUrl;

    public function deletePen(Request $request, string $penCode): JsonResponse|RedirectResponse
    {
        $normalizedPenCode = strtoupper(str_replace('-', '', trim($penCode)));
        $pen = Pen::query()->where('pen_code', $normalizedPenCode)->first();
        if (! $pen) {
            return $this->handleValidationFailure($request, 'Pen record was not found.');
        }

        $isPenUsedByBatches = PigBatch::query()
            ->where('pen_id', $normalizedPenCode)
            ->exists();
        if ($isPenUsedByBatches) {
            return $this->handleValidationFailure($request, 'Cannot delete this pen because it is assigned to one or more pig batches.');
        }

        try {
            $response = Http::acceptJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->delete($this->endpointUrl('/pen/delete/'.$normalizedPenCode.'/'));
        } catch (ConnectionException) {
            return $this->handleGatewayFailure($request, 'Pen service is currently unavailable. Please try again.');
        }

        if (! $response->successful()) {
            return $this->handleGatewayFailure(
                $request,
                $this->extractMessage($response->json(), 'Failed to delete pen.'),
                $response->status()
            );
        }

        Pen::query()->where('pen_code', $normalizedPenCode)->delete();
        $payload = $response->json();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => $this->extractMessage($payload, 'Pen deleted successfully.'),
                'pen_code' => $normalizedPenCode,
            ], $response->status());
        }

        return redirect()
            ->route('show.pig')
            ->with('success', $this->extractMessage($payload, 'Pen deleted successfully.'));
    }

    public function deletePenFromWeb(Request $request, string $penCode): RedirectResponse
    {
        $response = $this->deletePen($request, $penCode);

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
            ->withErrors(['pen' => $message]);
    }

    private function handleValidationFailure(Request $request, string $message): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => false,
                'message' => $message,
            ], 422);
        }

        return back()->withErrors(['pen' => $message]);
    }
}
