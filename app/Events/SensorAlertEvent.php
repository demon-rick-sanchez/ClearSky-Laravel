<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SensorAlertEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sensor;
    public $reading;

    public function __construct($sensor, $reading)
    {
        $this->sensor = $sensor;
        $this->reading = $reading;
    }

    public function broadcastOn()
    {
        return new Channel('sensors');
    }
}
