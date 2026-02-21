<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'status',
        'type',
        'description',
        'recorded_date',
    ];

    protected function casts(): array
    {
        return [
            'recorded_date' => 'datetime',
        ];
    }
}
