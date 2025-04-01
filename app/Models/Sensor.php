<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $fillable = [
        'sensor_id',
        'name',
        'location',
        'type',
        'threshold_value',
        'start_date',
        'notes',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'threshold_value' => 'float',
    ];
}
