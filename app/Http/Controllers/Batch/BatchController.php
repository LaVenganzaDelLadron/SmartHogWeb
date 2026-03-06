<?php

namespace App\Http\Controllers\Batch;

use App\Http\Controllers\Controller;
use App\Models\GrowthStage;
use App\Support\Concerns\ResolvesGatewayUrl;
use App\Support\Handler\HandlerFailure;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BatchController extends Controller
{
    use HandlerFailure;
    use ResolvesGatewayUrl;

    public function addBatch(Request $request): JsonResponse|RedirectResponse
    {
        $notes = $request->string('health_notes')->trim()->toString();
        if ($notes === '') {
            $notes = $request->string('notes')->trim()->toString();
        }
        if ($notes === '') {
            $notes = 'No notes';
        }

        $batchName = $request->string('batch_name')->trim()->toString();
        $batchCode = $request->string('batch_code')->trim()->toString();
        $pigCount = $request->integer('pig_count');

        $penCode = $request->string('pen_code')->trim()->toString();
        if ($penCode === '') {
            $penCode = $request->string('assigned_pen')->trim()->toString();
        }

        $growthStageCode = $request->string('growth_stage_code')->trim()->toString();
        if ($growthStageCode === '') {
            $growthStageName = $request->string('growth_stage')->trim()->toString();
            if ($growthStageName !== '') {
                $growthStageCode = (string) (GrowthStage::query()
                    ->where('growth_name', $growthStageName)
                    ->value('growth_code') ?? '');
            }
        }

        $existingBatches = $this->fetchExistingBatches();
        $existingPens = $this->fetchExistingPens();

        $batchNameExists = collect($existingBatches)
            ->contains(function ($batch) use ($batchName): bool {
                $existingName = is_array($batch) ? (string) ($batch['batch_name'] ?? '') : '';

                return $existingName !== '' && strcasecmp($existingName, $batchName) === 0;
            });
        if ($batchName !== '' && $batchNameExists) {
            return $this->handleApiFailure(
                $request,
                422,
                ['message' => 'Batch name already exists. Please use a different name.'],
                'Batch name already exists. Please use a different name.'
            );
        }

        $batchCodeExists = collect($existingBatches)
            ->contains(function ($batch) use ($batchCode): bool {
                $existingCode = is_array($batch) ? (string) ($batch['batch_code'] ?? '') : '';

                return $existingCode !== '' && strcasecmp($existingCode, $batchCode) === 0;
            });
        if ($batchCode !== '' && $batchCodeExists) {
            return $this->handleApiFailure(
                $request,
                422,
                ['message' => 'Batch code already exists. Please use a different code.'],
                'Batch code already exists. Please use a different code.'
            );
        }

        $penData = collect($existingPens)
            ->first(function ($pen) use ($penCode): bool {
                $existingPenCode = is_array($pen) ? (string) ($pen['pen_code'] ?? '') : '';

                return $existingPenCode !== '' && strcasecmp($existingPenCode, $penCode) === 0;
            });
        $selectedPenCapacity = (int) (is_array($penData) ? ($penData['capacity'] ?? 0) : 0);

        $allocatedPigsInPen = collect($existingBatches)
            ->filter(function ($batch) use ($penCode): bool {
                $batchPenCode = is_array($batch) ? (string) ($batch['pen_code_id'] ?? '') : '';

                return $batchPenCode !== '' && strcasecmp($batchPenCode, $penCode) === 0;
            })
            ->sum(function ($batch): int {
                return (int) (is_array($batch) ? ($batch['no_of_pigs'] ?? 0) : 0);
            });

        if ($selectedPenCapacity > 0) {
            $remainingCapacity = $selectedPenCapacity - $allocatedPigsInPen;

            if ($remainingCapacity <= 0) {
                return $this->handleApiFailure(
                    $request,
                    422,
                    ['message' => 'Selected pen is already full. Please choose another pen.'],
                    'Selected pen is already full. Please choose another pen.'
                );
            }

            if ($pigCount > $remainingCapacity) {
                return $this->handleApiFailure(
                    $request,
                    422,
                    ['message' => "Selected pen only has {$remainingCapacity} remaining capacity."],
                    "Selected pen only has {$remainingCapacity} remaining capacity."
                );
            }
        }

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->post($this->endpointUrl('/batch/add/'), [
                    'batch_name' => $batchName,
                    'no_of_pigs' => $pigCount,
                    'current_age' => $request->integer('current_age_days'),
                    'avg_weight' => $request->float('avg_weight'),
                    'notes' => $notes,
                    'pen_code' => $penCode,
                    'growth_stage_code' => $growthStageCode,
                    'pen_code_id' => $penCode,
                    'growth_stage_id' => $growthStageCode,
                    'date' => $request->date('date')?->toDateTimeString() ?? now()->toDateTimeString(),
                ]);
        } catch (ConnectionException) {
            return $this->handleGatewayFailure($request, 'Batch service is currently unavailable. Please try again.');
        }

        if (! $response->successful()) {
            return $this->handleApiFailure($request, $response->status(), $response->json(), 'Failed to add batch. Please try again.');
        }

        $payload = $response->json();
        $message = $this->extractMessage($payload, 'Batch added successfully');

        return response()->json([
            'ok' => true,
            'message' => $message,
            'data' => $payload,
        ]);
    }

    public function updateBatch(Request $request, string $batch_code): JsonResponse|RedirectResponse
    {
        $normalizedBatchCode = trim($batch_code);
        if ($normalizedBatchCode === '') {
            return $this->handleApiFailure($request, 422, ['message' => 'Batch code is required.'], 'Failed to update batch. Please try again.');
        }

        $notes = $request->string('health_notes')->trim()->toString();
        if ($notes === '') {
            $notes = $request->string('notes')->trim()->toString();
        }
        if ($notes === '') {
            $notes = 'No notes';
        }

        $batchName = $request->string('batch_name')->trim()->toString();
        $batchCode = $request->string('batch_code')->trim()->toString();
        if ($batchCode === '') {
            $batchCode = $normalizedBatchCode;
        }
        $pigCount = $request->integer('pig_count');

        $penCode = $request->string('pen_code')->trim()->toString();
        if ($penCode === '') {
            $penCode = $request->string('assigned_pen')->trim()->toString();
        }

        $growthStageCode = $request->string('growth_stage_code')->trim()->toString();
        if ($growthStageCode === '') {
            $growthStageName = $request->string('growth_stage')->trim()->toString();
            if ($growthStageName !== '') {
                $growthStageCode = (string) (GrowthStage::query()
                    ->where('growth_name', $growthStageName)
                    ->value('growth_code') ?? '');
            }
        }

        $existingBatches = $this->fetchExistingBatches();
        $existingPens = $this->fetchExistingPens();

        $batchNameExists = collect($existingBatches)
            ->contains(function ($batch) use ($batchName, $normalizedBatchCode): bool {
                $existingName = is_array($batch) ? (string) ($batch['batch_name'] ?? '') : '';
                $existingCode = is_array($batch) ? (string) ($batch['batch_code'] ?? '') : '';

                return $existingName !== ''
                    && strcasecmp($existingName, $batchName) === 0
                    && strcasecmp($existingCode, $normalizedBatchCode) !== 0;
            });
        if ($batchName !== '' && $batchNameExists) {
            return $this->handleApiFailure(
                $request,
                422,
                ['message' => 'Batch name already exists. Please use a different name.'],
                'Batch name already exists. Please use a different name.'
            );
        }

        $batchCodeExists = collect($existingBatches)
            ->contains(function ($batch) use ($batchCode, $normalizedBatchCode): bool {
                $existingCode = is_array($batch) ? (string) ($batch['batch_code'] ?? '') : '';

                return $existingCode !== ''
                    && strcasecmp($existingCode, $batchCode) === 0
                    && strcasecmp($existingCode, $normalizedBatchCode) !== 0;
            });
        if ($batchCode !== '' && $batchCodeExists) {
            return $this->handleApiFailure(
                $request,
                422,
                ['message' => 'Batch code already exists. Please use a different code.'],
                'Batch code already exists. Please use a different code.'
            );
        }

        $penData = collect($existingPens)
            ->first(function ($pen) use ($penCode): bool {
                $existingPenCode = is_array($pen) ? (string) ($pen['pen_code'] ?? '') : '';

                return $existingPenCode !== '' && strcasecmp($existingPenCode, $penCode) === 0;
            });
        $selectedPenCapacity = (int) (is_array($penData) ? ($penData['capacity'] ?? 0) : 0);

        $allocatedPigsInPen = collect($existingBatches)
            ->filter(function ($batch) use ($penCode, $normalizedBatchCode): bool {
                $batchPenCode = is_array($batch) ? (string) ($batch['pen_code_id'] ?? '') : '';
                $currentCode = is_array($batch) ? (string) ($batch['batch_code'] ?? '') : '';

                return $batchPenCode !== ''
                    && strcasecmp($batchPenCode, $penCode) === 0
                    && strcasecmp($currentCode, $normalizedBatchCode) !== 0;
            })
            ->sum(function ($batch): int {
                return (int) (is_array($batch) ? ($batch['no_of_pigs'] ?? 0) : 0);
            });

        if ($selectedPenCapacity > 0) {
            $remainingCapacity = $selectedPenCapacity - $allocatedPigsInPen;

            if ($remainingCapacity <= 0) {
                return $this->handleApiFailure(
                    $request,
                    422,
                    ['message' => 'Selected pen is already full. Please choose another pen.'],
                    'Selected pen is already full. Please choose another pen.'
                );
            }

            if ($pigCount > $remainingCapacity) {
                return $this->handleApiFailure(
                    $request,
                    422,
                    ['message' => "Selected pen only has {$remainingCapacity} remaining capacity."],
                    "Selected pen only has {$remainingCapacity} remaining capacity."
                );
            }
        }

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->put($this->endpointUrl('/batch/update/'.rawurlencode($normalizedBatchCode).'/'), [
                    'batch_code' => $batchCode,
                    'batch_name' => $batchName,
                    'no_of_pigs' => $pigCount,
                    'current_age' => $request->integer('current_age_days'),
                    'avg_weight' => $request->float('avg_weight'),
                    'notes' => $notes,
                    'pen_code' => $penCode,
                    'growth_stage_code' => $growthStageCode,
                    'pen_code_id' => $penCode,
                    'growth_stage_id' => $growthStageCode,
                    'date' => $request->date('date')?->toDateTimeString() ?? now()->toDateTimeString(),
                ]);
        } catch (ConnectionException) {
            return $this->handleGatewayFailure($request, 'Batch service is currently unavailable. Please try again.');
        }

        if (! $response->successful()) {
            return $this->handleApiFailure($request, $response->status(), $response->json(), 'Failed to update batch. Please try again.');
        }

        $payload = $response->json();
        $message = $this->extractMessage($payload, 'Batch updated successfully');

        return response()->json([
            'ok' => true,
            'message' => $message,
            'data' => $payload,
        ]);
    }

    public function getAllBatch(Request $request): JsonResponse|RedirectResponse
    {
        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->get($this->endpointUrl('/batch/all/'));
        } catch (ConnectionException) {
            return $this->handleGatewayFailure($request, 'Batch service is currently unavailable. Please try again.');
        }

        if (! $response->successful()) {
            return $this->handleApiFailure($request, $response->status(), $response->json(), 'Failed to fetch batches. Please try again.');
        }

        $payload = $response->json();
        $message = $this->extractMessage($payload, 'Batches fetched successfully');
        $data = is_array($payload['data'] ?? null) ? $payload['data'] : [];

        $growthCodes = collect($data)
            ->map(function ($item): string {
                return is_array($item) ? (string) ($item['growth_stage_id'] ?? '') : '';
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        $growthNamesByCode = GrowthStage::query()
            ->select(['growth_code', 'growth_name'])
            ->whereIn('growth_code', $growthCodes)
            ->pluck('growth_name', 'growth_code');

        $data = collect($data)
            ->map(function ($item) use ($growthNamesByCode): array {
                if (! is_array($item)) {
                    return [];
                }

                $growthCode = (string) ($item['growth_stage_id'] ?? '');
                $item['growth_name'] = (string) ($growthNamesByCode[$growthCode] ?? $growthCode);

                return $item;
            })
            ->all();

        return response()->json([
            'ok' => true,
            'message' => $message,
            'count' => count($data),
            'total_pigs' => self::sumTotalPigs($data),
            'data' => $data,
        ]);
    }

    public function fetchBatchStatsFromGateway(): array
    {
        $totalPigs = 0;
        $activeBatches = 0;

        try {
            $totalResponse = Http::acceptJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->get($this->endpointUrl('/batch/total-pigs/'));

            if ($totalResponse->successful()) {
                $totalPayload = $totalResponse->json();
                if (is_array($totalPayload)) {
                    if (isset($totalPayload['total_pigs'])) {
                        $totalPigs = (int) $totalPayload['total_pigs'];
                    } elseif (isset($totalPayload['data']) && is_array($totalPayload['data']) && isset($totalPayload['data']['total_pigs'])) {
                        $totalPigs = (int) $totalPayload['data']['total_pigs'];
                    }
                }
            }
        } catch (ConnectionException) {
        }

        try {
            $activeResponse = Http::acceptJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->get($this->endpointUrl('/batch/active/'));

            if ($activeResponse->successful()) {
                $activePayload = $activeResponse->json();
                if (is_array($activePayload)) {
                    if (isset($activePayload['count'])) {
                        $activeBatches = (int) $activePayload['count'];
                    } elseif (isset($activePayload['data']) && is_array($activePayload['data'])) {
                        $activeBatches = count($activePayload['data']);
                    }
                }
            }
        } catch (ConnectionException) {
        }

        return [
            'totalPigs' => $totalPigs,
            'activeBatches' => $activeBatches,
        ];
    }

    public function getActiveBatch(Request $request): JsonResponse|RedirectResponse
    {
        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->get($this->endpointUrl('/batch/active/'));
        } catch (ConnectionException) {
            return $this->handleGatewayFailure($request, 'Batch service is currently unavailable. Please try again.');
        }

        if (! $response->successful()) {
            return $this->handleApiFailure(
                $request,
                $response->status(),
                $response->json(),
                'Failed to fetch total pigs. Please try again.'
            );
        }

        $payload = $response->json();
        $message = $this->extractMessage($payload, 'Active batch fetched successfully');

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $payload['data'] ?? $payload
        ]);
    }

    public static function sumTotalPigs(array $batches): int
    {
        return collect($batches)->sum(function ($batch): int {
            return (int) ((is_array($batch) ? ($batch['no_of_pigs'] ?? 0) : 0));
        });
    }

    public function deleteBatch(Request $request, string $batch_code): JsonResponse|RedirectResponse
    {
        $normalizedBatchCode = trim($batch_code);
        if ($normalizedBatchCode === '') {
            return $this->handleApiFailure($request, 422, ['message' => 'Batch code is required.'], 'Failed to remove batch, PLease try again.');
        }

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->delete($this->endpointUrl('/batch/delete/'.rawurlencode($normalizedBatchCode).'/'));
        } catch (ConnectionException) {
            return $this->handleGatewayFailure($request, 'Batch service is currently unavailable. Please try again.');
        }
        if (! $response->successful()) {
            return $this->handleApiFailure($request, $response->status(), $response->json(), 'Failed to remove batch. Please try again.');
        }

        $payload = $response->json();
        $message = $this->extractMessage($payload, 'Batch removed successfully');

        return response()->json([
            'ok' => true,
            'message' => $message,
            'data' => $payload,
        ]);
    }

    private function fetchExistingBatches(): array
    {
        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->get($this->endpointUrl('/batch/all/'));
        } catch (ConnectionException) {
            return [];
        }

        if (! $response->successful()) {
            return [];
        }

        $payload = $response->json();

        return is_array($payload['data'] ?? null) ? $payload['data'] : [];
    }

    private function fetchExistingPens(): array
    {
        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->get($this->endpointUrl('/pen/all/'));
        } catch (ConnectionException) {
            return [];
        }

        if (! $response->successful()) {
            return [];
        }

        $payload = $response->json();

        return is_array($payload['data'] ?? null) ? $payload['data'] : [];
    }




}


