<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomAlert extends Model
{
    protected $fillable = [
        'type',
        'message',
        'target_type',
        'area_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class, 'area_id');
    }
}