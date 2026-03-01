<?php

namespace App\Http\Controllers\Batch;

use App\Http\Controllers\Controller;
use App\Http\Requests\Batch\BatchRequest;
use App\Models\GrowthStage;
use App\Models\Pen;
use App\Models\PigBatch;
use App\Support\Concerns\ResolvesGatewayUrl;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class AddBatchController extends Controller
{
    use ResolvesGatewayUrl;

    public function addBatch(BatchRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();
        $assignedPenCode = (string) $validated['assigned_pen'];
        $incomingPigCount = (int) $validated['pig_count'];

        $penCapacity = $this->resolvePenCapacity($assignedPenCode);
        if ($penCapacity !== null) {
            $currentPenLoad = $this->resolveCurrentPenLoad($assignedPenCode);
            if (($currentPenLoad + $incomingPigCount) > $penCapacity) {
                $availableSlots = max($penCapacity - $currentPenLoad, 0);

                return $this->handleGatewayFailure(
                    $request,
                    "Cannot add batch to {$assignedPenCode}. Pen capacity is {$penCapacity}, currently loaded with {$currentPenLoad}, only {$availableSlots} slot(s) available.",
                    422
                );
            }
        }

        $batchCode = $this->generateNextBatchCode();
        $batchName = (string) $validated['batch_name'];
        $notes = trim((string) ($validated['notes'] ?? $validated['health_notes'] ?? ''));
        $recordDate = now()->toIso8601String();
        $growthStageInput = trim((string) $validated['growth_stage']);
        $growthCode = $growthStageInput;
        if (! $this->looksLikeGrowthCode($growthCode)) {
            $growthCode = $this->resolveGrowthCodeByName($growthStageInput);
        }

        if ($growthCode === '') {
            return $this->handleGatewayFailure(
                $request,
                "Selected growth stage \"{$growthStageInput}\" is invalid. Please refresh and choose a valid stage.",
                422
            );
        }

        $growthName = $this->resolveGrowthNameByCode($growthCode);
        if ($growthName === '') {
            $growthName = $growthStageInput;
        }

        $payload = [
            'batch_code' => $batchCode,
            'batch_name' => $batchName,
            'no_of_pigs' => (int) $validated['pig_count'],
            'current_age' => (int) $validated['current_age_days'],
            'avg_weight' => (float) $validated['avg_weight'],
            'notes' => $notes !== '' ? $notes : 'No notes',
            'pen_code_id' => $assignedPenCode,
            'growth_stage_id' => $growthCode,
            'date' => $recordDate,
        ];

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->post($this->endpointUrl('/batch/add/'), $payload);
        } catch (ConnectionException) {
            return $this->handleGatewayFailure($request, 'Batch service is currently unavailable. Please try again.');
        }

        if (! $response->successful()) {
            return $this->handleGatewayFailure(
                $request,
                $this->extractMessage($response->json(), 'Failed to create batch.'),
                $response->status()
            );
        }

        PigBatch::query()->updateOrCreate(
            ['batch_name' => $batchName],
            [
                'no_of_pigs' => (int) $validated['pig_count'],
                'current_age_days' => (int) $validated['current_age_days'],
                'avg_weight_kg' => (float) $validated['avg_weight'],
                'notes' => $notes !== '' ? $notes : null,
                'pen_id' => $assignedPenCode,
                'growth_stage' => $growthName,
                'record_date' => Carbon::parse($recordDate)->toDateTimeString(),
            ]
        );

        $responsePayload = $response->json();
        $message = $this->extractMessage($responsePayload, 'PigBatches successfully created');

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => $message,
                'batch_code' => $batchCode,
                'data' => $responsePayload,
            ], $response->status());
        }

        return redirect()
            ->route('show.pig')
            ->with('success', $message);
    }

    public function addBatchFromWeb(BatchRequest $request): RedirectResponse
    {
        $response = $this->addBatch($request);
        if ($response instanceof RedirectResponse) {
            return $response;
        }

        return redirect()->route('show.pig');
    }

    private function resolveGrowthCodeByName(string $growthName): string
    {
        $normalizedGrowthName = trim($growthName);
        if ($normalizedGrowthName === '') {
            return '';
        }

        $localCode = (string) (GrowthStage::query()
            ->whereRaw('LOWER(growth_name) = ?', [mb_strtolower($normalizedGrowthName)])
            ->value('growth_code') ?? '');
        if ($localCode !== '') {
            return $localCode;
        }

        try {
            $response = Http::acceptJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->get($this->endpointUrl('/growth/all/'));

            if (! $response->successful()) {
                return '';
            }

            $growthStages = $this->extractApiRecords($response->json(), 'data', 'growth_stages');
            foreach ($growthStages as $growthStage) {
                $currentGrowthName = trim((string) ($growthStage['growth_name'] ?? ''));
                if (mb_strtolower($currentGrowthName) !== mb_strtolower($normalizedGrowthName)) {
                    continue;
                }

                return trim((string) ($growthStage['growth_code'] ?? ''));
            }
        } catch (ConnectionException) {
            return '';
        }

        return '';
    }

    private function resolveGrowthNameByCode(string $growthCode): string
    {
        $normalizedGrowthCode = trim($growthCode);
        if ($normalizedGrowthCode === '') {
            return '';
        }

        return (string) (GrowthStage::query()
            ->whereRaw('LOWER(growth_code) = ?', [mb_strtolower($normalizedGrowthCode)])
            ->value('growth_name') ?? '');
    }

    private function looksLikeGrowthCode(string $value): bool
    {
        return preg_match('/^GROWTH-?\d+$/i', trim($value)) === 1;
    }

    private function generateNextBatchCode(): string
    {
        $max = 0;

        try {
            $response = Http::acceptJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->get($this->endpointUrl('/batch/all/'));

            if ($response->successful()) {
                $batches = $this->extractApiRecords($response->json(), 'data', 'batches');
                foreach ($batches as $batch) {
                    $max = max($max, $this->extractBatchNumber((string) ($batch['batch_code'] ?? '')));
                }
            }
        } catch (ConnectionException) {
            // Fall back to local cache when gateway is unavailable.
        }

        $existingBatchCodes = PigBatch::query()->pluck('batch_id');
        foreach ($existingBatchCodes as $existingBatchCode) {
            if (! is_string($existingBatchCode)) {
                continue;
            }

            $max = max($max, $this->extractBatchNumber($existingBatchCode));
        }

        return sprintf('BATCH%03d', $max + 1);
    }

    private function extractBatchNumber(string $batchCode): int
    {
        if (preg_match('/^BATCH-?(\d+)$/i', trim($batchCode), $matches) !== 1) {
            return 0;
        }

        return (int) $matches[1];
    }

    private function resolvePenCapacity(string $penCode): ?int
    {
        $normalizedPenCode = $this->normalizePenCode($penCode);
        if ($normalizedPenCode === '') {
            return null;
        }

        try {
            $response = Http::acceptJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->get($this->endpointUrl('/pen/all/'));

            if ($response->successful()) {
                $pens = $this->extractApiRecords($response->json(), 'data', 'pens');
                foreach ($pens as $pen) {
                    if ($this->normalizePenCode((string) ($pen['pen_code'] ?? '')) !== $normalizedPenCode) {
                        continue;
                    }

                    return (int) ($pen['capacity'] ?? 0);
                }
            }
        } catch (ConnectionException) {
            // Fall back to local cache when gateway is unavailable.
        }

        $localPen = Pen::query()
            ->select(['pen_code', 'capacity'])
            ->get()
            ->first(function (Pen $pen) use ($normalizedPenCode): bool {
                return $this->normalizePenCode((string) $pen->pen_code) === $normalizedPenCode;
            });

        if (! $localPen instanceof Pen) {
            return null;
        }

        return (int) $localPen->capacity;
    }

    private function resolveCurrentPenLoad(string $penCode): int
    {
        $normalizedPenCode = $this->normalizePenCode($penCode);
        if ($normalizedPenCode === '') {
            return 0;
        }

        try {
            $response = Http::acceptJson()
                ->timeout(15)
                ->connectTimeout(5)
                ->get($this->endpointUrl('/batch/all/'));

            if ($response->successful()) {
                $batches = $this->extractApiRecords($response->json(), 'data', 'batches');
                $total = 0;

                foreach ($batches as $batch) {
                    if ($this->normalizePenCode((string) ($batch['pen_code_id'] ?? '')) !== $normalizedPenCode) {
                        continue;
                    }

                    $total += (int) ($batch['no_of_pigs'] ?? 0);
                }

                return $total;
            }
        } catch (ConnectionException) {
            // Fall back to local cache when gateway is unavailable.
        }

        return (int) PigBatch::query()
            ->select(['pen_id', 'no_of_pigs'])
            ->get()
            ->filter(function (PigBatch $batch) use ($normalizedPenCode): bool {
                return $this->normalizePenCode((string) $batch->pen_id) === $normalizedPenCode;
            })
            ->sum('no_of_pigs');
    }

    private function extractApiRecords(mixed $payload, string $primaryKey, string $secondaryKey): array
    {
        if (! is_array($payload)) {
            return [];
        }

        if (isset($payload[$primaryKey]) && is_array($payload[$primaryKey])) {
            return array_values(array_filter($payload[$primaryKey], 'is_array'));
        }

        if (isset($payload[$secondaryKey]) && is_array($payload[$secondaryKey])) {
            return array_values(array_filter($payload[$secondaryKey], 'is_array'));
        }

        if (array_is_list($payload)) {
            return array_values(array_filter($payload, 'is_array'));
        }

        return [];
    }

    private function normalizePenCode(string $penCode): string
    {
        return strtoupper(str_replace('-', '', trim($penCode)));
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
            ->withErrors(['batch' => $message]);
    }
}
