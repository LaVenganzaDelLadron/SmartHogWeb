<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrowthStage extends Model
{
    protected $table = 'growth_stages';

    protected $primaryKey = 'growth_id';

    public $timestamps = false;

    protected $fillable = [
        'growth_name',
    ];
}
