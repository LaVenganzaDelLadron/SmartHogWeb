<?php

namespace App\Http\Controllers\Pig;

use App\Http\Controllers\Controller;
use App\Models\FeedingRecord;
use App\Models\HealthRecord;
use App\Models\Pen;
use App\Models\PigBatch;
use App\Models\PigGrowthRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class PigController extends Controller
{
    // adding data
    public function addBatch(Request $request)
    {
        $validated = $request->validate([
            'batch_name' => 'required|string|max:255|unique:pig_batches,batch_name',
            'no_of_pigs' => 'required|integer|min:1',
            'current_age_days' => 'required|integer|min:0',
            'avg_weight_kg' => 'required|numeric|min:0',
            'growth_stage' => 'required|string|exists:growth_stages,growth_name',
            'notes' => 'nullable|string',
            'pen_id' => 'required|string|exists:pens,pen_code',
        ]);

        $selectedPen = Pen::query()
            ->where('pen_code', $validated['pen_id'])
            ->first();

        $currentPenLoad = PigBatch::query()
            ->where('pen_id', $validated['pen_id'])
            ->sum('no_of_pigs');

        $projectedPenLoad = $currentPenLoad + (int) $validated['no_of_pigs'];
        $penCapacity = (int) ($selectedPen?->capacity ?? 0);

        if ($projectedPenLoad > $penCapacity) {
            throw ValidationException::withMessages([
                'pen_id' => sprintf(
                    'Pen capacity exceeded. %s capacity is %d pigs. Current load is %d, adding %d would make it %d.',
                    $validated['pen_id'],
                    $penCapacity,
                    $currentPenLoad,
                    (int) $validated['no_of_pigs'],
                    $projectedPenLoad
                ),
            ]);
        }

        $batch = PigBatch::create($validated);
        $selectedPen?->update([
            'status' => $projectedPenLoad >= $penCapacity ? 'occupied' : 'available',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Batch added successfully.',
                'batch_id' => $batch->batch_id,
            ]);
        }

        return redirect()
            ->route('show.pig')
            ->with('success', 'Batch added successfully.');
    }

    public function addPen(Request $request)
    {
        $validated = $request->validate([
            'pen_name' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        Pen::create([
            ...$validated,
            'status' => 'available',
        ]);
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Pen added successfully.',
            ]);
        }

        return redirect()
            ->route('show.pig')
            ->with('success', 'Pen added successfully.');
    }

    // getting data
    public function getPens()
    {
        $pens = Pen::all();

        return response()->json($pens);
    }

    public function getBatches()
    {
        $batches = $this->buildBatchCardsData();

        return response()->json($batches);
    }

    public function getTotalPigs()
    {
        $totalPigs = PigBatch::sum('no_of_pigs');

        return response()->json(['total_pigs' => $totalPigs]);
    }

    private function buildBatchCardsData(): Collection
    {
        $batches = PigBatch::query()
            ->orderByDesc('record_date')
            ->get();

        if ($batches->isEmpty()) {
            return collect();
        }

        $batchIds = $batches->pluck('batch_id')->all();

        $latestGrowthRecords = PigGrowthRecord::query()
            ->whereIn('batch_id', $batchIds)
            ->orderByDesc('record_date')
            ->get()
            ->unique('batch_id')
            ->keyBy('batch_id');

        $latestHealthRecords = HealthRecord::query()
            ->whereIn('batch_id', $batchIds)
            ->orderByDesc('record_date')
            ->get()
            ->unique('batch_id')
            ->keyBy('batch_id');

        $latestFeedingRecords = FeedingRecord::query()
            ->whereIn('batch_id', $batchIds)
            ->orderByDesc('feeding_date')
            ->orderByDesc('feeding_time')
            ->get()
            ->unique('batch_id')
            ->keyBy('batch_id');

        return $batches->map(function (PigBatch $batch) use ($latestGrowthRecords, $latestHealthRecords, $latestFeedingRecords): array {
            $growthRecord = $latestGrowthRecords->get($batch->batch_id);
            $healthRecord = $latestHealthRecords->get($batch->batch_id);
            $feedingRecord = $latestFeedingRecords->get($batch->batch_id);

            $ageDays = (int) ($growthRecord?->pig_age_days ?? $batch->current_age_days ?? Carbon::parse($batch->record_date)->diffInDays(now()));

            return [
                'id' => $batch->batch_id,
                'date' => Carbon::parse($batch->record_date)->format('Y-m-d'),
                'age' => $ageDays.' days',
                'stage' => $growthRecord?->growth_stage ?? $batch->growth_stage,
                'weight' => number_format((float) ($growthRecord?->avg_weight_kg ?? $batch->avg_weight_kg), 1).' kg',
                'feeding' => $this->resolveFeedingStatus($feedingRecord),
                'alerts' => $this->resolveHealthAlert($healthRecord),
            ];
        });
    }

    private function resolveFeedingStatus(?FeedingRecord $feedingRecord): string
    {
        if (! $feedingRecord) {
            return 'No schedule';
        }

        $feedingAt = Carbon::parse($feedingRecord->feeding_date.' '.$feedingRecord->feeding_time);

        if ($feedingAt->isFuture()) {
            return 'Upcoming';
        }

        return 'Completed';
    }

    private function resolveHealthAlert(?HealthRecord $healthRecord): string
    {
        if (! $healthRecord) {
            return 'No health record';
        }

        if ($healthRecord->vaccine_given && $healthRecord->vitamin_given) {
            return 'None';
        }

        $alerts = [];
        if (! $healthRecord->vaccine_given) {
            $alerts[] = 'Vaccine pending';
        }
        if (! $healthRecord->vitamin_given) {
            $alerts[] = 'Vitamin pending';
        }

        return implode(' · ', $alerts);
    }
}
