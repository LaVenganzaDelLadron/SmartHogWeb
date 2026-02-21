<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PigBatch extends Model
{
    protected $table = 'pig_batches';

    protected $primaryKey = 'batch_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'batch_id',
        'batch_name',
        'no_of_pigs',
        'current_age_days',
        'avg_weight_kg',
        'notes',
        'pen_id',
        'growth_stage',
    ];

    protected static function booted(): void
    {
        static::creating(function (PigBatch $batch): void {
            if (! empty($batch->batch_id)) {
                return;
            }

            $prefix = 'BATCH-';
            $latestBatchNumber = static::query()
                ->where('batch_id', 'like', $prefix.'%')
                ->pluck('batch_id')
                ->map(function (string $batchId) use ($prefix): int {
                    return (int) str_replace($prefix, '', $batchId);
                })
                ->max() ?? 0;

            $batch->batch_id = sprintf('%s%03d', $prefix, $latestBatchNumber + 1);
        });
    }
}
