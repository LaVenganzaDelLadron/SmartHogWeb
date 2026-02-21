<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesRecord extends Model
{
    protected $table = 'sales_records';

    protected $primaryKey = 'sales_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'sales_id',
        'batch_id',
        'avg_weight_kg',
        'sale_date',
        'total_pigs_sold',
    ];

    protected static function booted(): void
    {
        static::creating(function (SalesRecord $salesRecord): void {
            if (! empty($salesRecord->sales_id)) {
                return;
            }

            $prefix = 'SALES-';
            $latestSalesNumber = static::query()
                ->where('sales_id', 'like', $prefix.'%')
                ->pluck('sales_id')
                ->map(function (string $salesId) use ($prefix): int {
                    return (int) str_replace($prefix, '', $salesId);
                })
                ->max() ?? 0;

            $salesRecord->sales_id = sprintf('%s%03d', $prefix, $latestSalesNumber + 1);
        });
    }
}
