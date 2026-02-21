<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    protected $table = 'health_records';

    protected $primaryKey = 'health_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'health_id',
        'batch_id',
        'vaccine_given',
        'vitamin_given',
        'pig_age_days',
    ];

    protected static function booted(): void
    {
        static::creating(function (HealthRecord $healthRecord): void {
            if (! empty($healthRecord->health_id)) {
                return;
            }

            $prefix = 'HEALTH-';
            $latestHealthNumber = static::query()
                ->where('health_id', 'like', $prefix.'%')
                ->pluck('health_id')
                ->map(function (string $healthId) use ($prefix): int {
                    return (int) str_replace($prefix, '', $healthId);
                })
                ->max() ?? 0;

            $healthRecord->health_id = sprintf('%s%03d', $prefix, $latestHealthNumber + 1);
        });
    }
}
