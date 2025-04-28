<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\CustomAlert;
use App\Models\Sensor;
use Illuminate\Http\Request;
use App\Events\SensorAlertEvent;

class AlertController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::where('group', 'alerts')->pluck('value', 'key')->toArray();
        $sensors = Sensor::where('status', 'active')->get();
        return view('admin.alerts', compact('settings', 'sensors'));
    }

    public function saveSettings(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'default_threshold' => 'required|numeric|min:0',
            'alert_frequency' => 'required|numeric|min:1',
            'critical_threshold' => 'required|numeric|min:100',
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'notification_recipients' => 'required_if:email_notifications,true|string',
        ]);

        // Update system settings
        foreach ($validated as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key, 'group' => 'alerts'],
                ['value' => $value, 'type' => is_bool($value) ? 'boolean' : 'string']
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Alert settings updated successfully'
        ]);
    }

    public function sendCustomAlert(Request $request)
    {
        $validated = $request->validate([
            'alert_type' => 'required|in:warning,critical',
            'message' => 'required|string',
            'target_type' => 'required|in:all,area',
            'area' => 'required_if:target_type,area|exists:sensors,id'
        ]);

        $alert = CustomAlert::create([
            'type' => $validated['alert_type'],
            'message' => $validated['message'],
            'target_type' => $validated['target_type'],
            'area_id' => $validated['target_type'] === 'area' ? $validated['area'] : null,
            'is_active' => true
        ]);

        // Broadcast the alert
        broadcast(new SensorAlertEvent([
            'id' => $alert->id,
            'type' => $alert->type,
            'message' => $alert->message,
            'location' => $alert->sensor ? $alert->sensor->location : null
        ], null))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Alert sent successfully'
        ]);
    }

    public function getActiveAlerts()
    {
        $alerts = CustomAlert::where('is_active', true)
            ->with('sensor')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($alert) {
                return [
                    'id' => $alert->id,
                    'type' => $alert->type,
                    'message' => $alert->message,
                    'location' => $alert->sensor ? $alert->sensor->location : null,
                    'created_at' => $alert->created_at->diffForHumans()
                ];
            });

        return response()->json($alerts);
    }

    public function dismissAlert($id)
    {
        $alert = CustomAlert::findOrFail($id);
        $alert->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Alert dismissed successfully'
        ]);
    }
}