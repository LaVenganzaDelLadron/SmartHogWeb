<?php

namespace App\Http\Controllers\Feeding;

use App\Http\Controllers\Controller;
use App\Models\FeedingRecord;
use App\Models\GrowthStage;
use App\Models\PigBatch;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FeedingController extends Controller
{
    public function showFeedingManagement(): View
    {
        $feedingBatches = PigBatch::query()
            ->select(['batch_id', 'no_of_pigs'])
            ->orderBy('batch_id')
            ->get();

        $feedingTypes = GrowthStage::query()
            ->select(['growth_id', 'growth_name'])
            ->orderBy('growth_id')
            ->get();

        return view('feeding.index', [
            'feedingBatches' => $feedingBatches,
            'feedingTypes' => $feedingTypes,
            'feedingCards' => collect(),
        ]);
    }

    public function addSchedule(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'batch_id' => 'required|string|exists:pig_batches,batch_id',
            'feeding_quantity_kg' => 'required|numeric|min:0.1',
            'feeding_time' => 'required|date_format:H:i',
            'feeding_date' => 'required|date|after_or_equal:today',
            'feeding_type' => 'required|string|exists:growth_stages,growth_name',
        ]);

        $feedingRecord = FeedingRecord::query()->create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Feeding schedule saved successfully.',
                'feeding_id' => $feedingRecord->feeding_id,
            ]);
        }

        return redirect()
            ->route('show.feeding')
            ->with('success', 'Feeding schedule saved successfully.');
    }

    public function addScheduleFromWeb(Request $request): RedirectResponse
    {
        $request->merge([
            'batch_id' => $request->input('feed_batch_id'),
            'feeding_quantity_kg' => $request->input('feed_quantity'),
            'feeding_time' => $request->input('feed_time'),
            'feeding_date' => $request->input('feed_date'),
            'feeding_type' => $request->input('feed_type'),
        ]);

        $this->addSchedule($request);

        return redirect()
            ->route('show.feeding')
            ->with('success', 'Feeding schedule saved successfully.');
    }

    public function listSchedules(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'message' => 'Feeding schedules retrieved successfully.',
            'data' => $this->buildFeedingCards()->values(),
        ]);
    }

    private function buildFeedingCards(): Collection
    {
        $feedingRecords = FeedingRecord::query()
            ->orderByDesc('feeding_date')
            ->orderByDesc('feeding_time')
            ->get();

        if ($feedingRecords->isEmpty()) {
            return collect();
        }

        $batchMetadata = PigBatch::query()
            ->whereIn('batch_id', $feedingRecords->pluck('batch_id')->unique()->all())
            ->select(['batch_id', 'batch_name', 'pen_id'])
            ->get()
            ->keyBy('batch_id');

        return $feedingRecords->map(function (FeedingRecord $feedingRecord) use ($batchMetadata): array {
            $batch = $batchMetadata->get($feedingRecord->batch_id);

            return [
                'feeding_id' => $feedingRecord->feeding_id,
                'batch_id' => $feedingRecord->batch_id,
                'batch_name' => $batch?->batch_name,
                'pen_id' => $batch?->pen_id,
                'feeding_type' => $feedingRecord->feeding_type,
                'feeding_date' => Carbon::parse($feedingRecord->feeding_date)->format('Y-m-d'),
                'feeding_time' => Carbon::parse($feedingRecord->feeding_time)->format('h:i A'),
                'feeding_quantity_kg' => number_format((float) $feedingRecord->feeding_quantity_kg, 1).' kg',
                'status' => $this->resolveFeedingStatus($feedingRecord),
            ];
        });
    }

    private function resolveFeedingStatus(FeedingRecord $feedingRecord): string
    {
        $feedingAt = Carbon::parse($feedingRecord->feeding_date.' '.$feedingRecord->feeding_time);

        if ($feedingAt->isFuture()) {
            return 'Pending';
        }

        if (Carbon::parse($feedingRecord->feeding_date)->isBefore(today())) {
            return 'Delayed';
        }

        return 'Completed';
    }
}
