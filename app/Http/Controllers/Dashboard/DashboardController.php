<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\FeedingRecord;
use App\Models\GrowthStage;
use App\Models\HealthRecord;
use App\Models\Pen;
use App\Models\PigBatch;
use App\Models\PigGrowthRecord;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function showDashboard(): View
    {
        $pens = Pen::query()
            ->select(['pen_code', 'pen_name'])
            ->whereNotNull('pen_code')
            ->orderBy('pen_name')
            ->get();

        $growthStages = GrowthStage::query()
            ->select(['growth_id', 'growth_name'])
            ->orderBy('growth_id')
            ->get();

        $totalPigs = (int) PigBatch::query()->sum('no_of_pigs');
        $activeBatches = (int) PigBatch::query()->count();

        return view('home.index', [
            'pens' => $pens,
            'growthStages' => $growthStages,
            'totalPigs' => $totalPigs,
            'activeBatches' => $activeBatches,
        ]);
    }

    public function showPigManagement(): View
    {
        $pens = Pen::query()
            ->select(['pen_code', 'pen_name', 'capacity', 'status', 'notes', 'record_date'])
            ->whereNotNull('pen_code')
            ->orderByDesc('record_date')
            ->get();

        $growthStages = GrowthStage::query()
            ->select(['growth_id', 'growth_name'])
            ->orderBy('growth_id')
            ->get();

        $totalPigs = (int) PigBatch::query()->sum('no_of_pigs');
        $activeBatches = (int) PigBatch::query()->count();
        $pigBatchCards = $this->buildPigBatchCards();

        return view('pig.index', [
            'pens' => $pens,
            'growthStages' => $growthStages,
            'totalPigs' => $totalPigs,
            'activeBatches' => $activeBatches,
            'pigBatchCards' => $pigBatchCards,
            'penCards' => $this->buildPenCards($pens),
        ]);
    }

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

    public function showMonitorManagement(): View
    {
        return view('monitor.index');
    }

    public function showNotifications(): View
    {
        return view('notifications.index');
    }

    public function showReports(): View
    {
        return view('reports.index');
    }

    private function buildPigBatchCards(): Collection
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
            $feedingStatus = $this->resolveFeedingStatus($feedingRecord);
            $healthAlert = $this->resolveHealthAlert($healthRecord);

            return [
                'id' => $batch->batch_id,
                'date' => Carbon::parse($batch->record_date)->format('Y-m-d'),
                'age' => $ageDays.' days',
                'stage' => $growthRecord?->growth_stage ?? $batch->growth_stage,
                'weight' => number_format((float) ($growthRecord?->avg_weight_kg ?? $batch->avg_weight_kg), 1).' kg',
                'feeding' => $feedingStatus,
                'alerts' => $healthAlert,
            ];
        });
    }

    private function buildPenCards(Collection $pens): Collection
    {
        return $pens->map(function (Pen $pen): array {
            return [
                'pen_id' => $pen->pen_code,
                'pen_name' => $pen->pen_name,
                'capacity' => (int) $pen->capacity,
                'status' => ucfirst((string) $pen->status),
                'notes' => $pen->notes ?: 'No notes',
                'record_date' => Carbon::parse($pen->record_date)->format('Y-m-d H:i'),
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
