<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Sensor;
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
        return view('admin.dashboard');
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

    public function simulation()
    {
        return view('admin.simulation');
    }

    public function alerts()
    {
        return view('admin.alerts');
    }

    public function users()
    {
        $admins = Admin::all();
        return view('admin.users', compact('admins'));
    }

    public function settings()
    {
        return view('admin.settings');
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
}
