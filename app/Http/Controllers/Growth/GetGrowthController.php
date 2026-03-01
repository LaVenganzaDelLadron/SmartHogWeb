<?php

namespace App\Http\Controllers\Growth;

use App\Http\Controllers\Controller;
use App\Models\GrowthStage;
use App\Support\Concerns\ResolvesGatewayUrl;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class GetGrowthController extends Controller
{
    // Get Controller for Growth Records
    use ResolvesGatewayUrl;

    public function getAll(Request $request): JsonResponse
    {
        $cacheKey = 'api:growth:all';
        if ($request->query('fresh') !== '1') {
            $cachedPayload = Cache::get($cacheKey);
            if (is_array($cachedPayload)) {
                return response()->json($cachedPayload);
            }
        }

        try {
            $response = Http::acceptJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->get($this->endpointUrl('/growth/all/'));
        } catch (ConnectionException) {
            $localGrowth = $this->localGrowthPayload();

            return response()->json([
                'ok' => false,
                'message' => 'Growth service is currently unavailable. Returned local growth records.',
                'count' => $localGrowth->count(),
                'data' => $localGrowth,
            ], 503);
        }

        if (! $response->successful()) {
            $localGrowth = $this->localGrowthPayload();

            return response()->json([
                'ok' => false,
                'message' => $this->extractMessage($response->json(), 'Failed to fetch growth records.'),
                'count' => $localGrowth->count(),
                'data' => $localGrowth,
            ], $response->status());
        }

        $payload = $response->json();
        $growthStages = $this->resolveGrowthList($payload);
        try {
            $syncedGrowthCodes = [];

            foreach ($growthStages as $growthStage) {
                $growthCode = is_string($growthStage['growth_code'] ?? null)
                    ? $this->normalizeGrowthCode((string) $growthStage['growth_code'])
                    : '';

                if ($growthCode === '') {
                    continue;
                }

                $syncedGrowthCodes[] = $growthCode;

                GrowthStage::query()->updateOrCreate(
                    ['growth_code' => $growthCode],
                    [
                        'growth_name' => (string) ($growthStage['growth_name'] ?? 'Unnamed Growth Stage'),
                        'date' => $growthStage['date'] ?? now()->toDateTimeString(),
                    ]
                );
            }

            $syncedGrowthCodes = array_values(array_unique(array_filter($syncedGrowthCodes)));
            if (count($syncedGrowthCodes) > 0) {
                GrowthStage::query()
                    ->whereNotNull('growth_code')
                    ->whereNotIn('growth_code', $syncedGrowthCodes)
                    ->delete();
            }
        } catch (Throwable) {
            // Keep API response usable for dropdowns even if local sync fails.
        }

        $responsePayload = [
            'ok' => true,
            'message' => $this->extractMessage($payload, 'Growth Stage fetched successfully'),
            'count' => count($growthStages),
            'data' => $growthStages,
        ];

        Cache::put($cacheKey, $responsePayload, now()->addSeconds(20));

        return response()->json($responsePayload, $response->status());
    }

    private function localGrowthPayload(): Collection
    {
        return GrowthStage::query()
            ->select(['growth_code', 'growth_name', 'date'])
            ->orderByDesc('date')
            ->get()
            ->map(function (GrowthStage $growthStage): array {
                return [
                    'growth_code' => $growthStage->growth_code,
                    'growth_name' => $growthStage->growth_name,
                    'date' => $growthStage->date,
                ];
            });
    }

    private function resolveGrowthList(mixed $payload): array
    {
        if (! is_array($payload)) {
            return [];
        }

        if (isset($payload['data']) && is_array($payload['data'])) {
            return array_values(array_filter($payload['data'], 'is_array'));
        }

        if (isset($payload['growth_stages']) && is_array($payload['growth_stages'])) {
            return array_values(array_filter($payload['growth_stages'], 'is_array'));
        }

        if (isset($payload['growths']) && is_array($payload['growths'])) {
            return array_values(array_filter($payload['growths'], 'is_array'));
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

    private function normalizeGrowthCode(string $growthCode): string
    {
        return strtoupper(str_replace('-', '', trim($growthCode)));
    }
}
