<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimulationData extends Model
{
    protected $fillable = [
        'sensor_id',
        'pattern_type',
        'min_value',
        'max_value',
        'thresholds',
        'duration',
        'readings'
    ];

    protected $casts = [
        'thresholds' => 'array',
        'readings' => 'array'
    ];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }
}
