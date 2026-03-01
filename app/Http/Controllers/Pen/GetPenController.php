<?php

namespace App\Http\Controllers\Pen;

use App\Http\Controllers\Controller;
use App\Models\Pen;
use App\Support\Concerns\ResolvesGatewayUrl;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class GetPenController extends Controller
{
    use ResolvesGatewayUrl;

    public function getAll(Request $request): JsonResponse
    {
        try {
            $response = Http::acceptJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->get($this->endpointUrl('/pen/all/'));
        } catch (ConnectionException) {
            return response()->json([
                'ok' => false,
                'message' => 'Pen service is currently unavailable. Returned local pen records.',
                'data' => $this->localPenPayload(),
            ], 503);
        }

        if (! $response->successful()) {
            return response()->json([
                'ok' => false,
                'message' => $this->extractMessage($response->json(), 'Failed to fetch pen records.'),
                'data' => $this->localPenPayload(),
            ], $response->status());
        }

        $payload = $response->json();
        $pens = $this->resolvePenList($payload);
        $syncedPenCodes = [];

        foreach ($pens as $pen) {
            $penCode = is_string($pen['pen_code'] ?? null) ? $this->normalizePenCode((string) $pen['pen_code']) : '';
            if ($penCode === '') {
                continue;
            }

            $syncedPenCodes[] = $penCode;

            Pen::query()->updateOrCreate(
                ['pen_code' => $penCode],
                [
                    'pen_name' => (string) ($pen['pen_name'] ?? 'Unnamed Pen'),
                    'capacity' => (int) ($pen['capacity'] ?? 0),
                    'status' => (string) ($pen['status'] ?? 'available'),
                    'notes' => isset($pen['notes']) ? (string) $pen['notes'] : null,
                    'record_date' => $pen['date'] ?? $pen['record_date'] ?? now()->toDateTimeString(),
                ]
            );
        }

        $syncedPenCodes = array_values(array_unique(array_filter($syncedPenCodes)));
        if (count($syncedPenCodes) > 0) {
            Pen::query()->whereNotIn('pen_code', $syncedPenCodes)->delete();
        } else {
            Pen::query()->delete();
        }

        return response()->json([
            'ok' => true,
            'message' => $this->extractMessage($payload, 'Pen records fetched successfully.'),
            'data' => $this->localPenPayload(),
        ], $response->status());
    }

    private function localPenPayload(): Collection
    {
        return Pen::query()
            ->select(['pen_code', 'pen_name', 'capacity', 'status', 'notes', 'record_date'])
            ->orderByDesc('record_date')
            ->get()
            ->map(function (Pen $pen): array {
                return [
                    'pen_code' => $pen->pen_code,
                    'pen_name' => $pen->pen_name,
                    'capacity' => (int) $pen->capacity,
                    'status' => (string) $pen->status,
                    'notes' => $pen->notes,
                    'record_date' => $pen->record_date,
                ];
            });
    }

    private function resolvePenList(mixed $payload): array
    {
        if (! is_array($payload)) {
            return [];
        }

        if (isset($payload['data']) && is_array($payload['data'])) {
            return array_values(array_filter($payload['data'], 'is_array'));
        }

        if (isset($payload['pens']) && is_array($payload['pens'])) {
            return array_values(array_filter($payload['pens'], 'is_array'));
        }

        $isList = array_is_list($payload) && count(array_filter($payload, 'is_array')) > 0;

        return $isList ? array_values(array_filter($payload, 'is_array')) : [];
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

    private function normalizePenCode(string $penCode): string
    {
        return strtoupper(str_replace('-', '', trim($penCode)));
    }
}
