<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function destroy(Sensor $sensor)
    {
        try {
            $sensor->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete sensor'
            ], 500);
        }
    }

    public function activate(Sensor $sensor)
    {
        try {
            $sensor->update(['status' => 'active']);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to activate sensor'
            ], 500);
        }
    }

    public function deactivate(Sensor $sensor)
    {
        try {
            $sensor->update(['status' => 'inactive']);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to deactivate sensor'
            ], 500);
        }
    }
}