<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedingRecord extends Model
{
    protected $table = 'feeding_records';

    protected $primaryKey = 'feeding_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'feeding_id',
        'batch_id',
        'feeding_quantity_kg',
        'feeding_time',
        'feeding_date',
        'feeding_type',
    ];

    protected static function booted(): void
    {
        static::creating(function (FeedingRecord $feedingRecord): void {
            if (! empty($feedingRecord->feeding_id)) {
                return;
            }

            $prefix = 'FEED-';
            $latestFeedingNumber = static::query()
                ->where('feeding_id', 'like', $prefix.'%')
                ->pluck('feeding_id')
                ->map(function (string $feedingId) use ($prefix): int {
                    return (int) str_replace($prefix, '', $feedingId);
                })
                ->max() ?? 0;

            $feedingRecord->feeding_id = sprintf('%s%03d', $prefix, $latestFeedingNumber + 1);
        });
    }
}
