<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $sensors = Sensor::where('status', 'active')->get();
        $preferences = $user->alert_preferences ?? [];

        return view('alerts', compact('preferences', 'sensors'));
    }

    public function savePreferences(Request $request)
    {
        $user = Auth::user();
        
        // Validate the request
        $validated = $request->validate([
            'receive_alerts' => 'boolean',
            'alert_sensitivity' => 'required|in:low,medium,high',
            'monitored_sensors' => 'array',
            'monitored_sensors.*' => 'exists:sensors,id',
            'email_notifications' => 'boolean',
            'notification_email' => 'required_if:email_notifications,true|email',
            'push_notifications' => 'boolean',
            'mobile_notifications' => 'boolean',
            'quiet_hours_start' => 'required|date_format:H:i',
            'quiet_hours_end' => 'required|date_format:H:i',
        ]);

        // Update user preferences
        $user->alert_preferences = $validated;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Alert preferences updated successfully'
        ]);
    }
}