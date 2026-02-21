<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PigGrowthRecord extends Model
{
    protected $table = 'pig_growth_records';

    protected $primaryKey = 'record_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'record_id',
        'batch_id',
        'pig_age_days',
        'avg_weight_kg',
        'growth_stage',
    ];

    protected static function booted(): void
    {
        static::creating(function (PigGrowthRecord $growthRecord): void {
            if (! empty($growthRecord->record_id)) {
                return;
            }

            $prefix = 'GROWTH-';
            $latestRecordNumber = static::query()
                ->where('record_id', 'like', $prefix.'%')
                ->pluck('record_id')
                ->map(function (string $recordId) use ($prefix): int {
                    return (int) str_replace($prefix, '', $recordId);
                })
                ->max() ?? 0;

            $growthRecord->record_id = sprintf('%s%03d', $prefix, $latestRecordNumber + 1);
        });
    }
}
