<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pen extends Model
{
    protected $table = 'pens';

    protected $primaryKey = 'pen_id';

    public $timestamps = false;

    protected $fillable = [
        'pen_code',
        'pen_name',
        'capacity',
        'status',
        'notes',
    ];

    protected static function booted(): void
    {
        static::created(function (Pen $pen): void {
            if (! empty($pen->pen_code)) {
                return;
            }

            $pen->pen_code = sprintf('PEN-%03d', (int) $pen->pen_id);
            $pen->saveQuietly();
        });
    }
}
