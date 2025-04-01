<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        @if(!in_array(request()->route()->getName(), ['admin.login', 'admin.register']))
            <div class="flex">
                <!-- Sidebar -->
                <div class="w-64 min-h-screen bg-white border-r flex flex-col">
                    <!-- Logo -->
                    <div class="flex items-center justify-center h-16 border-b">
                        <a href="/" class="text-xl font-bold text-[#212121]">
                            ClearSky
                        </a>
                    </div>

                    <!-- Navigation -->
                    <div class="flex-1 p-4">
                        <nav class="space-y-1">
                            @foreach([
                                ['route' => 'admin.dashboard', 'name' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                                ['route' => 'admin.sensors', 'name' => 'Sensor Management', 'icon' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z'],
                                ['route' => 'admin.simulation', 'name' => 'Data Simulation', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                                ['route' => 'admin.alerts', 'name' => 'Alert Configuration', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                                ['route' => 'admin.users', 'name' => 'Admin Management', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                                ['route' => 'admin.settings', 'name' => 'System Settings', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c-.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                                ['route' => 'admin.profile', 'name' => 'Profile Settings', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                            ] as $item)
                                <a href="{{ route($item['route']) }}" 
                                   class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs($item['route']) ? 'bg-gray-100 text-[#212121]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#212121]' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                                    </svg>
                                    <span>{{ $item['name'] }}</span>
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    <!-- Logout Button (Fixed at bottom) -->
                    <div class="p-4 border-t">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-2 rounded-lg text-white bg-[#212121] hover:bg-opacity-90 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="flex-1">
                    <main class="p-8">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        @else
            <main>
                {{ $slot }}
            </main>
        @endif
    </div>
</body>
</html>
