<x-app-layout>
    <!-- CSS for Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        .card {
            @apply bg-white rounded-lg shadow border border-gray-200;
        }
        .card-header {
            @apply p-4 border-b border-gray-200 flex justify-between items-center;
        }
        .title {
            @apply text-lg font-medium text-gray-900;
        }
        .btn {
            @apply px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200;
        }
        .btn-primary {
            @apply bg-blue-600 text-white hover:bg-blue-700;
        }
        .btn-outline {
            @apply border border-gray-300 bg-white text-gray-700 hover:bg-gray-50;
        }
        .btn-secondary {
            @apply bg-gray-100 text-gray-700 hover:bg-gray-200;
        }
        .alert-item {
            @apply mb-3 p-3 rounded-md border-l-4;
        }
        .select-custom {
            @apply rounded-md border-gray-300 text-sm;
        }
        .alert-badge {
            @apply px-2 py-0.5 rounded text-xs font-medium;
        }
        .alert-badge-critical {
            @apply bg-red-100 text-red-800;
        }
        .alert-badge-warning {
            @apply bg-yellow-100 text-yellow-800;
        }
        .alert-strip {
            @apply rounded-md border border-gray-200;
        }
    </style>

    <!-- Hero Section -->
    <div class="bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-12 gap-6 py-12">
                <!-- Hero Content -->
                <div class="col-span-12 lg:col-span-6 flex flex-col justify-center">
                    <h1 class="text-3xl font-semibold mb-4">Real-Time Air Quality Monitoring</h1>
                    <p class="text-lg text-blue-100 mb-6">Stay informed about the air quality in your area with our advanced sensor network and real-time monitoring system.</p>
                    <div class="flex gap-3">
                        <button class="btn btn-outline">
                            View Sensors
                        </button>
                        <button class="btn btn-primary">
                            Download Report
                        </button>
                    </div>
                </div>
                <!-- Stats Cards -->
                <div class="col-span-12 lg:col-span-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-700 rounded-lg p-4">
                            <div class="text-2xl font-semibold" data-stat="active-sensors">3</div>
                            <div class="text-blue-200">Active Sensors</div>
                        </div>
                        <div class="bg-blue-700 rounded-lg p-4">
                            <div class="text-2xl font-semibold" data-stat="avg-aqi">93</div>
                            <div class="text-blue-200">Average AQI</div>
                        </div>
                        <div class="bg-blue-700 rounded-lg p-4">
                            <div class="text-2xl font-semibold" data-stat="alerts">2</div>
                            <div class="text-blue-200">Active Alerts</div>
                        </div>
                        <div class="bg-blue-700 rounded-lg p-4">
                            <div class="text-2xl font-semibold">2.4K</div>
                            <div class="text-blue-200">Daily Readings</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Bar -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex justify-between items-center">
                <div id="alerts-container" class="flex items-center gap-4">
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-red-50 rounded-md">
                        <span class="alert-badge alert-badge-critical">Critical</span>
                        <span class="text-red-700 text-sm">High PM2.5 levels at Maradana (SNR-012)</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-yellow-50 rounded-md">
                        <span class="alert-badge alert-badge-warning">Warning</span>
                        <span class="text-yellow-700 text-sm">Moderate AQI at Pettah (SNR-002)</span>
                    </div>
                </div>
                <button onclick="viewAllAlerts()" class="btn btn-secondary">
                    View All Alerts
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Map and Legend Section -->
            <div class="grid grid-cols-12 gap-6">
                <!-- Map Section -->
                <div class="col-span-12 lg:col-span-8">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="title">Sensor Map</h2>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-500">Auto-refresh in</span>
                                <span id="refresh-countdown" class="text-sm font-medium text-blue-600">30s</span>
                            </div>
                        </div>
                        <div id="map" class="h-[400px] rounded-b-lg"></div>
                    </div>
                </div>

                <!-- AQI Legend -->
                <div class="col-span-12 lg:col-span-4">
                    <div class="card h-fit sticky top-4">
                        <div class="card-header">
                            <h2 class="title">AQI Legend</h2>
                        </div>
                        <div class="p-4">
                            <div class="space-y-3">
                                <div class="flex items-center p-3 bg-green-50 rounded-md transition-all duration-200 hover:bg-green-100">
                                    <div class="w-4 h-4 rounded-full bg-green-500 mr-3"></div>
                                    <div>
                                        <p class="font-medium text-green-800">Good</p>
                                        <p class="text-sm text-green-600">0-50 AQI</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-yellow-50 rounded-md transition-all duration-200 hover:bg-yellow-100">
                                    <div class="w-4 h-4 rounded-full bg-yellow-500 mr-3"></div>
                                    <div>
                                        <p class="font-medium text-yellow-800">Moderate</p>
                                        <p class="text-sm text-yellow-600">51-100 AQI</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-orange-50 rounded-md transition-all duration-200 hover:bg-orange-100">
                                    <div class="w-4 h-4 rounded-full bg-orange-500 mr-3"></div>
                                    <div>
                                        <p class="font-medium text-orange-800">Unhealthy for Sensitive Groups</p>
                                        <p class="text-sm text-orange-600">101-150 AQI</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-red-50 rounded-md transition-all duration-200 hover:bg-red-100">
                                    <div class="w-4 h-4 rounded-full bg-red-500 mr-3"></div>
                                    <div>
                                        <p class="font-medium text-red-800">Unhealthy</p>
                                        <p class="text-sm text-red-600">151-200 AQI</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historical Trends Section -->
            <div class="mt-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="title">Historical Trends</h2>
                        <div class="flex items-center gap-4">
                            <select id="sensor-select" class="select-custom">
                                <!-- Will be populated dynamically -->
                            </select>
                            <select id="time-range" class="select-custom">
                                <option value="day">Last 24 Hours</option>
                                <option value="week">Last Week</option>
                                <option value="month">Last Month</option>
                            </select>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="relative h-[300px]">
                            <canvas id="trends-chart"></canvas>
                            <div id="chart-loading" class="absolute inset-0 items-center justify-center bg-white bg-opacity-80 hidden">
                                <div class="text-gray-500">Loading data...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Simulation History Section -->
            <div class="mt-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="title">Simulation History</h2>
                        <select id="simulation-select" class="select-custom">
                            <option value="">Select Simulation</option>
                        </select>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Simulation Info -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div id="simulation-info" class="space-y-2">
                                    <p><span class="font-medium">Sensor:</span> <span id="sim-sensor">-</span></p>
                                    <p><span class="font-medium">Pattern:</span> <span id="sim-pattern">-</span></p>
                                    <p><span class="font-medium">Duration:</span> <span id="sim-duration">-</span></p>
                                    <p><span class="font-medium">Date:</span> <span id="sim-date">-</span></p>
                                </div>
                            </div>
                            <!-- Simulation Chart -->
                            <div class="bg-white rounded-lg p-4 col-span-1">
                                <canvas id="simulation-chart" class="w-full h-[300px]"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Quick Access Section -->
            <div class="mt-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="title">Quick Access</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <a href="{{ route('guidelines') }}" class="group">
                                <div class="border border-gray-200 rounded-lg p-6 hover:border-blue-500 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3 bg-blue-50 rounded-lg group-hover:bg-blue-100">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-medium text-gray-900">Guidelines</h3>
                                            <p class="text-sm text-gray-500">Learn about air quality standards</p>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('about') }}" class="group">
                                <div class="border border-gray-200 rounded-lg p-6 hover:border-blue-500 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3 bg-blue-50 rounded-lg group-hover:bg-blue-100">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-medium text-gray-900">About ClearSky</h3>
                                            <p class="text-sm text-gray-500">Learn more about our mission</p>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <a href="#" onclick="event.preventDefault(); showReportsModal()" class="group">
                                <div class="border border-gray-200 rounded-lg p-6 hover:border-blue-500 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3 bg-blue-50 rounded-lg group-hover:bg-blue-100">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-medium text-gray-900">Reports & Data</h3>
                                            <p class="text-sm text-gray-500">Download air quality reports</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Modal -->
    <div id="reportsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-6 w-[600px] shadow-xl rounded-lg bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-[#212121]">Download Reports</h3>
                <button onclick="closeReportsModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="space-y-6">
                <!-- Quick Reports -->
                <div>
                    <h4 class="font-medium text-gray-900 mb-4">Quick Reports</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <button onclick="downloadReport('daily')" class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:border-blue-500 transition-colors">
                            <div class="p-2 bg-blue-50 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3 3m0 0l-3-3m3 3v-7"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <div class="font-medium">Daily Report</div>
                                <div class="text-sm text-gray-500">Last 24 hours data</div>
                            </div>
                        </button>

                        <button onclick="downloadReport('weekly')" class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:border-blue-500 transition-colors">
                            <div class="p-2 bg-blue-50 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <div class="font-medium">Weekly Report</div>
                                <div class="text-sm text-gray-500">Last 7 days summary</div>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Custom Report -->
                <div class="border-t pt-6">
                    <h4 class="font-medium text-gray-900 mb-4">Custom Report</h4>
                    <form id="customReportForm" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Start Date</label>
                                <input type="date" name="start_date" class="block w-full rounded-lg border border-gray-200 px-4 py-2">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">End Date</label>
                                <input type="date" name="end_date" class="block w-full rounded-lg border border-gray-200 px-4 py-2">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Select Sensors</label>
                            <div class="max-h-40 overflow-y-auto p-2 border rounded-lg space-y-2">
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="all_sensors" class="rounded border-gray-300" checked>
                                    <span class="text-sm">All Sensors</span>
                                </label>
                                <div class="border-t my-2"></div>
                                <div id="sensor-checkboxes" class="space-y-2">
                                    <!-- Will be populated dynamically -->
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Report Format</label>
                            <select name="format" class="block w-full rounded-lg border border-gray-200 px-4 py-2">
                                <option value="csv">CSV</option>
                                <option value="excel">Excel</option>
                                <option value="pdf">PDF</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="flex justify-end pt-6 border-t">
                    <button type="button" onclick="closeReportsModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 mr-3">
                        Cancel
                    </button>
                    <button type="button" onclick="generateCustomReport()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                        Generate Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">ClearSky</h3>
                    <p class="text-gray-600 text-sm">Your trusted partner in air quality monitoring and environmental awareness.</p>
                </div>
                
                <div>
                    <h4 class="font-medium text-gray-900 mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('about') }}" class="text-gray-600 hover:text-gray-900 text-sm">About Us</a>
                        </li>
                        <li>
                            <a href="{{ route('guidelines') }}" class="text-gray-600 hover:text-gray-900 text-sm">Guidelines</a>
                        </li>
                        <li>
                            <a href="{{ route('alerts') }}" class="text-gray-600 hover:text-gray-900 text-sm">Alert Settings</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-medium text-gray-900 mb-4">Resources</h4>
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="text-gray-600 hover:text-gray-900 text-sm">API Documentation</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Mobile App</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-600 hover:text-gray-900 text-sm">FAQ</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-medium text-gray-900 mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            support@clearsky.com
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            +1 (555) 123-4567
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t mt-8 pt-8 flex justify-between items-center">
                <p class="text-sm text-gray-600">&copy; {{ date('Y') }} ClearSky. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-400 hover:text-gray-600">
                        <span class="sr-only">Privacy Policy</span>
                        Privacy Policy
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-600">
                        <span class="sr-only">Terms of Service</span>
                        Terms of Service
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map, trendChart, simulationChart;
        let currentSensors = [];

        // Initialize map
        function initializeMap() {
            map = L.map('map').setView([6.9271, 79.8612], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
        }

        // Fetch and update sensor data
        async function fetchSensors() {
            try {
                const response = await fetch('/api/sensors');
                const sensors = await response.json();
                currentSensors = sensors;
                updateMap(sensors);
                updateSensorSelect(sensors);
                updateStats(sensors);
            } catch (error) {
                console.error('Error fetching sensors:', error);
            }
        }

        // Update map markers
        function updateMap(sensors) {
            // Clear existing markers
            map.eachLayer((layer) => {
                if (layer instanceof L.Marker || layer instanceof L.CircleMarker) {
                    map.removeLayer(layer);
                }
            });

            // Keep the base tile layer
            if (map.getZoom() < 12) {
                map.setView([6.9271, 79.8612], 12);
            }

            // Add new markers
            sensors.forEach(sensor => {
                if (!sensor.lat || !sensor.lng) {
                    console.warn(`Missing coordinates for sensor: ${sensor.id}`);
                    return;
                }

                const color = sensor.aqi <= 50 ? '#10B981' : 
                            sensor.aqi <= 100 ? '#FBBF24' : 
                            sensor.aqi <= 150 ? '#FB923C' : '#EF4444';
                
                const marker = L.circleMarker([sensor.lat, sensor.lng], {
                    radius: 8,
                    fillColor: color,
                    color: '#fff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.8
                }).addTo(map);

                const trendIcon = sensor.trend === 'increasing' ? '↑' :
                                sensor.trend === 'decreasing' ? '↓' : '→';

                const trendColor = sensor.trend === 'increasing' ? 'text-red-500' :
                                 sensor.trend === 'decreasing' ? 'text-green-500' : 'text-gray-500';

                marker.bindPopup(`
                    <div class="p-2">
                        <h3 class="font-bold text-lg mb-1">${sensor.name}</h3>
                        <p class="text-sm text-gray-600 mb-2">${sensor.location}</p>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="font-bold text-2xl" style="color: ${color}">${sensor.aqi}</span>
                            <span class="text-sm ${trendColor}">${trendIcon}</span>
                        </div>
                        <div class="mt-2">
                            <canvas id="mini-chart-${sensor.id}" width="200" height="100"></canvas>
                        </div>
                    </div>
                `);

                marker.on('popupopen', () => {
                    setTimeout(() => createMiniChart(sensor.id), 100);
                });
            });
        }

        // Update sensor dropdown
        function updateSensorSelect(sensors) {
            const select = document.getElementById('sensor-select');
            select.innerHTML = '<option value="">Select Sensor</option>' + 
                sensors.map(sensor => 
                    `<option value="${sensor.id}">${sensor.name} - ${sensor.location}</option>`
                ).join('');
            
            if (sensors.length > 0) {
                select.value = sensors[0].id;
                updateChart();
            }
        }

        // Update stats cards
        function updateStats(sensors) {
            const activeSensors = sensors.length;
            const avgAqi = Math.round(sensors.reduce((sum, s) => sum + s.aqi, 0) / sensors.length);
            const criticalSensors = sensors.filter(s => s.aqi > 150).length;

            document.querySelector('[data-stat="active-sensors"]').textContent = activeSensors;
            document.querySelector('[data-stat="avg-aqi"]').textContent = avgAqi;
            document.querySelector('[data-stat="alerts"]').textContent = criticalSensors;
        }

        // Fetch and update alerts
        async function fetchAlerts() {
            try {
                const response = await fetch('/api/alerts');
                const alerts = await response.json();
                updateAlerts(alerts);
            } catch (error) {
                console.error('Error fetching alerts:', error);
            }
        }

        // Update alerts display
        function updateAlerts(alerts) {
            const container = document.getElementById('alerts-container');
            
            if (alerts.length === 0) {
                container.innerHTML = '<div class="text-gray-500 text-center py-4">No active alerts</div>';
                return;
            }

            container.innerHTML = alerts.slice(0, 2).map(alert => {
                if (alert.message) {
                    // Custom alert
                    return `
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-${alert.type === 'critical' ? 'red' : 'yellow'}-50 rounded-md">
                            <span class="alert-badge alert-badge-${alert.type}">${alert.type === 'critical' ? 'Critical' : 'Warning'}</span>
                            <span class="text-${alert.type === 'critical' ? 'red' : 'yellow'}-700 text-sm">
                                ${alert.message}
                                ${alert.location ? `(${alert.location})` : ''}
                            </span>
                        </div>
                    `;
                } else {
                    // Sensor alert
                    return `
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-${alert.type === 'critical' ? 'red' : 'yellow'}-50 rounded-md">
                            <span class="alert-badge alert-badge-${alert.type}">${alert.type === 'critical' ? 'Critical' : 'Warning'}</span>
                            <span class="text-${alert.type === 'critical' ? 'red' : 'yellow'}-700 text-sm">
                                ${alert.sensor_name}: ${alert.value} (threshold: ${alert.threshold})
                            </span>
                        </div>
                    `;
                }
            }).join('');
        }

        // Initialize trends chart
        function initializeTrendChart() {
            const ctx = document.getElementById('trends-chart').getContext('2d');
            trendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Sensor Readings',
                        data: [],
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(156, 163, 175, 0.1)',
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                maxRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 12
                            }
                        }
                    }
                }
            });
        }

        // Fetch sensor readings and update chart
        async function fetchSensorReadings(sensorId, timeRange) {
            document.getElementById('chart-loading').classList.remove('hidden');
            
            try {
                const response = await fetch(`/api/sensors/${sensorId}/readings`);
                const readings = await response.json();
                
                const now = new Date();
                const filtered = readings.filter(reading => {
                    const readingDate = new Date(reading.timestamp);
                    const diffHours = (now - readingDate) / (1000 * 60 * 60);
                    
                    return timeRange === 'day' ? diffHours <= 24 :
                           timeRange === 'week' ? diffHours <= 168 :
                           diffHours <= 720;
                });

                trendChart.data.labels = filtered.map(r => {
                    const date = new Date(r.timestamp);
                    return timeRange === 'day' ? date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) :
                           timeRange === 'week' ? date.toLocaleDateString([], { weekday: 'short' }) :
                           date.toLocaleDateString([], { month: 'short', day: 'numeric' });
                });
                trendChart.data.datasets[0].data = filtered.map(r => r.value);
                trendChart.update();
            } catch (error) {
                console.error('Error fetching readings:', error);
            } finally {
                document.getElementById('chart-loading').classList.add('hidden');
            }
        }

        // Initialize simulation chart
        function initializeSimulationChart() {
            const ctx = document.getElementById('simulation-chart').getContext('2d');
            simulationChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Simulation Data',
                        data: [],
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(156, 163, 175, 0.1)',
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                maxRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 12
                            }
                        }
                    }
                }
            });
        }

        // Format duration in HH:MM:SS
        function formatDuration(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        }

        // Load simulation history
        async function loadSimulationHistory() {
            try {
                const response = await fetch('/api/simulation-history');
                const data = await response.json();
                
                if (data.success) {
                    const select = document.getElementById('simulation-select');
                    select.innerHTML = '<option value="">Select Simulation</option>' + 
                        data.history.map(record => `
                            <option value="${record.id}">
                                ${record.sensor_name} - ${new Date(record.created_at).toLocaleDateString()}
                            </option>
                        `).join('');
                }
            } catch (error) {
                console.error('Error loading simulation history:', error);
            }
        }

        // Update simulation chart and info
        function updateSimulationDisplay(record) {
            // Update info
            document.getElementById('sim-sensor').textContent = record.sensor_name;
            document.getElementById('sim-pattern').textContent = record.pattern_type;
            document.getElementById('sim-duration').textContent = formatDuration(record.duration);
            document.getElementById('sim-date').textContent = new Date(record.created_at).toLocaleString();

            // Update chart
            simulationChart.data.labels = record.readings.map((r, i) => 
                new Date(r.timestamp).toLocaleTimeString()
            );
            simulationChart.data.datasets[0].data = record.readings.map(r => r.value);
            simulationChart.update();
        }

        // Initialize everything
        function initialize() {
            initializeMap();
            initializeTrendChart();
            initializeSimulationChart();
            fetchSensors();
            fetchAlerts();
            loadSimulationHistory();
            
            // Set up event listeners
            document.getElementById('sensor-select').addEventListener('change', updateChart);
            document.getElementById('time-range').addEventListener('change', updateChart);

            // Add simulation select event listener
            document.getElementById('simulation-select').addEventListener('change', async (e) => {
                if (!e.target.value) return;
                
                try {
                    const response = await fetch(`/api/simulation-history/${e.target.value}`);
                    const data = await response.json();
                    if (data.success) {
                        updateSimulationDisplay(data.record);
                    }
                } catch (error) {
                    console.error('Error loading simulation data:', error);
                }
            });
            
            // Set up auto-refresh
            let countdown = 30;
            const countdownEl = document.getElementById('refresh-countdown');
            
            setInterval(() => {
                countdown--;
                countdownEl.textContent = countdown + 's';
                
                if (countdown <= 0) {
                    countdown = 30;
                    fetchSensors();
                    fetchAlerts();
                }
            }, 1000);
        }

        // Update chart when sensor or time range changes
        function updateChart() {
            const sensorId = document.getElementById('sensor-select').value;
            const timeRange = document.getElementById('time-range').value;
            if (sensorId) {
                fetchSensorReadings(sensorId, timeRange);
            }
        }

        // Initialize on page load
        initialize();

        // Handle "View All Alerts" button click
        function viewAllAlerts() {
            // You can implement a modal or redirect to a full alerts page
            alert('Viewing all alerts...');
        }

        // Add these functions to your existing script section
        function showReportsModal() {
            document.getElementById('reportsModal').classList.remove('hidden');
            populateSensorCheckboxes();
        }

        function closeReportsModal() {
            document.getElementById('reportsModal').classList.add('hidden');
        }

        function populateSensorCheckboxes() {
            const container = document.getElementById('sensor-checkboxes');
            container.innerHTML = currentSensors.map(sensor => `
                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="sensors[]" value="${sensor.id}" class="rounded border-gray-300" checked>
                    <span class="text-sm">${sensor.name} (${sensor.location})</span>
                </label>
            `).join('');
        }

        function downloadReport(type) {
            window.location.href = `/reports/${type}`;
        }

        function generateCustomReport() {
            const formData = new FormData(document.getElementById('customReportForm'));
            
            // Get selected sensors
            const selectedSensors = [];
            document.querySelectorAll('input[name="sensors[]"]:checked').forEach(checkbox => {
                selectedSensors.push(checkbox.value);
            });

            // Add to form data
            if (formData.get('all_sensors')) {
                formData.delete('sensors[]');
            } else {
                formData.delete('all_sensors');
                selectedSensors.forEach(id => formData.append('sensors[]', id));
            }

            fetch('/reports/custom', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Report generation failed');
                return response.blob();
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `air_quality_report_custom_${new Date().toISOString().split('T')[0]}.${formData.get('format')}`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                a.remove();
                closeReportsModal();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to generate report. Please try again.');
            });
        }

        // Handle "all sensors" checkbox
        document.querySelector('input[name="all_sensors"]').addEventListener('change', function(e) {
            const sensorCheckboxes = document.querySelectorAll('input[name="sensors[]"]');
            sensorCheckboxes.forEach(checkbox => checkbox.checked = e.target.checked);
        });
    </script>
</x-app-layout>
