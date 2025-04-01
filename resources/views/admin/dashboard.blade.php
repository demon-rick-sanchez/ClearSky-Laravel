<x-admin-layout>
    <!-- Success Message -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-6 lg:px-8 mb-4">
            <div class="bg-green-50 text-green-500 p-4 rounded-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Sensors</p>
                    <p class="text-xl font-semibold text-[#212121]">{{ $totalSensors }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Active Sensors</p>
                    <p class="text-xl font-semibold text-[#212121]">{{ $activeSensors }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Active Alerts</p>
                    <p class="text-xl font-semibold text-[#212121]">{{ $activeAlerts }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Admin Users</p>
                    <p class="text-xl font-semibold text-[#212121]">{{ $adminCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Recent Sensor Activity -->
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-[#212121]">Recent Sensor Activity</h3>
            </div>
            <div class="p-6">
                @forelse($recentActivity as $activity)
                    <div class="flex items-center mb-4 last:mb-0">
                        <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-[#212121]">{{ $activity->sensor->name }}</p>
                            <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="ml-auto">
                            <span class="inline-flex items-center rounded-lg bg-gray-50 px-2 py-1 text-xs text-gray-600">
                                {{ $activity->type }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center">No recent activity</p>
                @endforelse
            </div>
        </div>

        <!-- Latest Alerts -->
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-[#212121]">Latest Alerts</h3>
            </div>
            <div class="p-6">
                @forelse($latestAlerts as $alert)
                    <div class="flex items-center mb-4 last:mb-0">
                        <div class="h-8 w-8 rounded-lg {{ $alert->severity === 'high' ? 'bg-red-100' : 'bg-yellow-100' }} flex items-center justify-center">
                            <svg class="w-4 h-4 {{ $alert->severity === 'high' ? 'text-red-600' : 'text-yellow-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-[#212121]">{{ $alert->message }}</p>
                            <p class="text-xs text-gray-500">{{ $alert->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="ml-auto">
                            <span class="inline-flex items-center rounded-lg {{ $alert->severity === 'high' ? 'bg-red-50 text-red-700' : 'bg-yellow-50 text-yellow-700' }} px-2 py-1 text-xs font-medium">
                                {{ ucfirst($alert->severity) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center">No alerts found</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="bg-white rounded-lg border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-[#212121]">System Status</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">System Load</span>
                        <span class="font-medium text-[#212121]">{{ $systemStatus['cpu_load'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $systemStatus['cpu_load'] }}%"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Memory Usage</span>
                        <span class="font-medium text-[#212121]">{{ $systemStatus['memory_usage'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $systemStatus['memory_usage'] }}%"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Storage</span>
                        <span class="font-medium text-[#212121]">{{ $systemStatus['storage_usage'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $systemStatus['storage_usage'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
