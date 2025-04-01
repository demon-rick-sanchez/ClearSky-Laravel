<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimulationSetting extends Model
{
    protected $fillable = [
        'sensor_id',
        'frequency',
        'pattern_type',
        'min_value',
        'max_value',
        'is_active',
        'thresholds',
        'last_run'
    ];

    protected $casts = [
        'thresholds' => 'array',
        'last_run' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }
}
