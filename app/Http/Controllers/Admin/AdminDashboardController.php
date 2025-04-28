<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Sensor;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        // Remove constructor middleware
    }

    public function index()
    {
        $data = [
            'totalSensors' => Sensor::count(),
            'activeSensors' => Sensor::where('status', 'active')->count(),
            'activeAlerts' => 0, // Implement alert counting
            'adminCount' => Admin::count(),
            'recentActivity' => collect([]), // Implement activity tracking
            'latestAlerts' => collect([]), // Implement alerts
            'systemStatus' => [
                'cpu_load' => random_int(20, 80), // Replace with actual monitoring
                'memory_usage' => random_int(30, 90),
                'storage_usage' => random_int(40, 70),
            ]
        ];

        return view('admin.dashboard', $data);
    }

    public function login()
    {
        return view('admin.login');
    }

    public function showRegister()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:admins',
                'password' => 'required|string|min:8',
                'role' => 'required|in:admin,superadmin',
            ]);

            $admin = Admin::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            Auth::guard('admin')->login($admin);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Registration successful! Welcome to the admin panel.');

        } catch (\Exception $e) {
            \Log::error('Admin registration failed: ' . $e->getMessage());
            return back()
                ->withInput($request->except('password'))
                ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    public function authenticate(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::guard('admin')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome back!');
            }

            return back()
                ->withInput($request->except('password'))
                ->withErrors(['email' => 'These credentials do not match our records.']);

        } catch (\Exception $e) {
            \Log::error('Admin authentication failed: ' . $e->getMessage());
            return back()
                ->withInput($request->except('password'))
                ->withErrors(['error' => 'Login failed: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }

    public function listAdmins()
    {
        $admins = Admin::all();
        return response()->json([
            'admins' => $admins,
            'total' => $admins->count()
        ]);
    }

    public function sensors()
    {
        $sensors = Sensor::orderBy('created_at', 'desc')->get();
        return view('admin.sensors', compact('sensors'));
    }

    public function generateSensorId()
    {
        do {
            $sensorId = 'SNR-' . strtoupper(substr(md5(uniqid()), 0, 6));
        } while (Sensor::where('sensor_id', $sensorId)->exists());

        return response()->json(['sensor_id' => $sensorId]);
    }

    public function storeSensor(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sensor_id' => 'required|string|unique:sensors',
                'location' => 'required|string|max:255',
                'type' => 'required|in:co2,no2,pm25',
                'threshold_value' => 'required|numeric|min:0',
                'start_date' => 'required|date',
                'notes' => 'nullable|string',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
            ]);

            $validated['status'] = 'active';
            $sensor = Sensor::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Sensor added successfully',
                'sensor' => $sensor
            ]);
        } catch (\Exception $e) {
            \Log::error('Sensor creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add sensor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function editSensor(Sensor $sensor)
    {
        return response()->json($sensor);
    }

    public function updateSensor(Request $request, Sensor $sensor)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'type' => 'required|in:co2,no2,pm25',
                'threshold_value' => 'required|numeric|min:0',
                'start_date' => 'required|date',
                'notes' => 'nullable|string',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
            ]);

            $sensor->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Sensor updated successfully',
                'sensor' => $sensor
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update sensor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateSensorStatus(Request $request, Sensor $sensor)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:active,inactive',
            ]);

            $sensor->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Sensor status updated successfully',
                'sensor' => $sensor
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update sensor status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteSensor(Sensor $sensor)
    {
        try {
            $sensor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sensor deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete sensor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function simulation()
    {
        return view('admin.simulation');
    }

    public function users()
    {
        $admins = Admin::all();
        return view('admin.users', compact('admins'));
    }

    public function settings()
    {
        try {
            $settings = SystemSetting::all()->pluck('value', 'key')->toArray();
        } catch (\Exception $e) {
            // If table doesn't exist or other error, return default settings
            $settings = [
                'system_name' => 'ClearSky',
                'timezone' => 'UTC',
                'data_retention' => 30,
                'enable_2fa' => false,
                'force_ssl' => true,
                'session_timeout' => 120,
            ];
        }
        
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully'
        ]);
    }

    public function clearCache()
    {
        \Artisan::call('cache:clear');
        return response()->json([
            'success' => true,
            'message' => 'Cache cleared successfully'
        ]);
    }

    public function destroy(Admin $admin)
    {
        try {
            // Check if user is superadmin and not deleting themselves
            if (auth('admin')->user()->role !== 'superadmin' || auth('admin')->id() === $admin->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this admin'
                ], 403);
            }

            $admin->delete();

            return response()->json([
                'success' => true,
                'message' => 'Admin deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete admin'
            ], 500);
        }
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $admin = auth('admin')->user();
        $admin->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password:admin',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = auth('admin')->user();
        $admin->update([
            'password' => Hash::make($validated['password'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }
}
