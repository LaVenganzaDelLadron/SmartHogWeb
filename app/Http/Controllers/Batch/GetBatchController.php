<?php

namespace App\Http\Controllers\Batch;

use App\Http\Controllers\Controller;
use App\Models\GrowthStage;
use App\Models\PigBatch;
use App\Support\Concerns\ResolvesGatewayUrl;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Throwable;

class GetBatchController extends Controller
{
    use ResolvesGatewayUrl;

    public function getTotalPigs(Request $request): JsonResponse
    {
        try {
            $response = Http::acceptJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->get($this->endpointUrl('/batch/total-pigs/'));
        } catch (ConnectionException) {
            $totalPigs = (int) PigBatch::query()->sum('no_of_pigs');

            return response()->json([
                'ok' => false,
                'message' => 'Batch service is currently unavailable. Returned local total pigs.',
                'total_pigs' => $totalPigs,
            ], 503);
        }

        if (! $response->successful()) {
            $totalPigs = (int) PigBatch::query()->sum('no_of_pigs');

            return response()->json([
                'ok' => false,
                'message' => $this->extractMessage($response->json(), 'Failed to fetch total pigs.'),
                'total_pigs' => $totalPigs,
            ], $response->status());
        }

        $payload = $response->json();
        $totalPigs = (int) ($payload['total_pigs'] ?? 0);

        return response()->json([
            'ok' => true,
            'message' => $this->extractMessage($payload, 'Total pigs fetched successfully'),
            'total_pigs' => $totalPigs,
        ], $response->status());
    }

    public function getActiveBatches(Request $request): JsonResponse
    {
        try {
            $response = Http::acceptJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->get($this->endpointUrl('/batch/active/'));
        } catch (ConnectionException) {
            $localBatches = $this->localBatchPayload()->filter(function (array $batch): bool {
                return (int) ($batch['no_of_pigs'] ?? 0) > 0;
            })->values();

            return response()->json([
                'ok' => false,
                'message' => 'Batch service is currently unavailable. Returned local active batches.',
                'count' => $localBatches->count(),
                'data' => $localBatches,
            ], 503);
        }

        if (! $response->successful()) {
            $localBatches = $this->localBatchPayload()->filter(function (array $batch): bool {
                return (int) ($batch['no_of_pigs'] ?? 0) > 0;
            })->values();

            return response()->json([
                'ok' => false,
                'message' => $this->extractMessage($response->json(), 'Failed to fetch active batches.'),
                'count' => $localBatches->count(),
                'data' => $localBatches,
            ], $response->status());
        }

        $payload = $response->json();
        $batches = $this->resolveBatchList($payload);
        $normalizedBatches = $this->normalizeGrowthStageNames($batches);

        return response()->json([
            'ok' => true,
            'message' => $this->extractMessage($payload, 'Active pig batches fetched successfully'),
            'count' => count($normalizedBatches),
            'data' => $normalizedBatches,
        ], $response->status());
    }

    public function getAll(Request $request): JsonResponse
    {
        try {
            $response = Http::acceptJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->get($this->endpointUrl('/batch/all/'));
        } catch (ConnectionException) {
            $localBatches = $this->localBatchPayload();

            return response()->json([
                'ok' => false,
                'message' => 'Batch service is currently unavailable. Returned local batch records.',
                'count' => $localBatches->count(),
                'data' => $localBatches,
            ], 503);
        }

        if (! $response->successful()) {
            $localBatches = $this->localBatchPayload();

            return response()->json([
                'ok' => false,
                'message' => $this->extractMessage($response->json(), 'Failed to fetch batch records.'),
                'count' => $localBatches->count(),
                'data' => $localBatches,
            ], $response->status());
        }

        $payload = $response->json();
        $batches = $this->resolveBatchList($payload);
        $normalizedBatches = $this->normalizeGrowthStageNames($batches);

        try {
            foreach ($normalizedBatches as $batch) {
                $batchCode = trim((string) ($batch['batch_code'] ?? ''));
                if ($batchCode === '') {
                    continue;
                }

                $recordDate = $batch['date'] ?? now()->toDateTimeString();

                PigBatch::query()->updateOrCreate(
                    ['batch_id' => $batchCode],
                    [
                        'batch_name' => (string) ($batch['batch_name'] ?? $batchCode),
                        'no_of_pigs' => (int) ($batch['no_of_pigs'] ?? 0),
                        'current_age_days' => (int) ($batch['current_age'] ?? 0),
                        'avg_weight_kg' => (float) ($batch['avg_weight'] ?? 0),
                        'notes' => isset($batch['notes']) ? (string) $batch['notes'] : null,
                        'pen_id' => (string) ($batch['pen_code_id'] ?? ''),
                        'growth_stage' => (string) ($batch['growth_stage_name'] ?? ''),
                        'record_date' => $recordDate,
                    ]
                );
            }
        } catch (Throwable) {
            // Keep response usable even if local sync fails.
        }

        return response()->json([
            'ok' => true,
            'message' => $this->extractMessage($payload, 'Pig Batches fetched successfully'),
            'count' => count($normalizedBatches),
            'data' => $normalizedBatches,
        ], $response->status());
    }

    private function normalizeGrowthStageNames(array $batches): array
    {
        $normalizedBatches = [];

        foreach ($batches as $batch) {
            $growthStageId = trim((string) ($batch['growth_stage_id'] ?? ''));
            $growthStageName = trim((string) ($batch['growth_stage_name'] ?? ''));

            if ($growthStageName === '' && $growthStageId !== '') {
                $resolvedGrowthName = $this->resolveGrowthNameByCode($growthStageId);
                $growthStageName = $resolvedGrowthName !== '' ? $resolvedGrowthName : $growthStageId;
            }

            if ($growthStageName === '') {
                $growthStageName = 'N/A';
            }

            $batch['growth_stage_name'] = $growthStageName;
            $normalizedBatches[] = $batch;
        }

        return $normalizedBatches;
    }

    private function resolveGrowthNameByCode(string $growthCode): string
    {
        if ($growthCode === '') {
            return '';
        }

        return (string) (GrowthStage::query()
            ->whereRaw('LOWER(growth_code) = ?', [mb_strtolower($growthCode)])
            ->value('growth_name') ?? '');
    }

    private function localBatchPayload(): Collection
    {
        return PigBatch::query()
            ->select(['batch_id', 'batch_name', 'no_of_pigs', 'current_age_days', 'avg_weight_kg', 'notes', 'pen_id', 'growth_stage', 'record_date'])
            ->orderByDesc('record_date')
            ->get()
            ->map(function (PigBatch $batch): array {
                return [
                    'batch_code' => $batch->batch_id,
                    'batch_name' => $batch->batch_name,
                    'no_of_pigs' => (int) $batch->no_of_pigs,
                    'current_age' => (int) $batch->current_age_days,
                    'avg_weight' => (float) $batch->avg_weight_kg,
                    'notes' => $batch->notes ?? 'No notes',
                    'pen_code_id' => $batch->pen_id,
                    'growth_stage_id' => $batch->growth_stage,
                    'growth_stage_name' => $batch->growth_stage,
                    'date' => $batch->record_date,
                ];
            });
    }

    private function resolveBatchList(mixed $payload): array
    {
        if (! is_array($payload)) {
            return [];
        }

        if (isset($payload['data']) && is_array($payload['data'])) {
            return array_values(array_filter($payload['data'], 'is_array'));
        }

        if (isset($payload['batches']) && is_array($payload['batches'])) {
            return array_values(array_filter($payload['batches'], 'is_array'));
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
}
