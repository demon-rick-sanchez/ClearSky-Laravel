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
                            <div class="text-2xl font-semibold">3</div>
                            <div class="text-blue-200">Active Sensors</div>
                        </div>
                        <div class="bg-blue-700 rounded-lg p-4">
                            <div class="text-2xl font-semibold">93</div>
                            <div class="text-blue-200">Average AQI</div>
                        </div>
                        <div class="bg-blue-700 rounded-lg p-4">
                            <div class="text-2xl font-semibold">2</div>
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
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-red-50 rounded-md">
                        <span class="alert-badge alert-badge-critical">Critical</span>
                        <span class="text-red-700 text-sm">High PM2.5 levels at Downtown (SNR-002)</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-yellow-50 rounded-md">
                        <span class="alert-badge alert-badge-warning">Warning</span>
                        <span class="text-yellow-700 text-sm">Moderate AQI at Central Park (SNR-001)</span>
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
                                <option value="SNR-001">Sensor SNR-001</option>
                                <option value="SNR-002">Sensor SNR-002</option>
                                <option value="SNR-003">Sensor SNR-003</option>
                            </select>
                            <select id="time-range" class="select-custom">
                                <option value="day">Last 24 Hours</option>
                                <option value="week">Last Week</option>
                                <option value="month">Last Month</option>
                            </select>
                        </div>
                    </div>
                    <div class="p-4">
                        <canvas id="trends-chart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Add view all alerts function
        function viewAllAlerts() {
            // You can implement the view all alerts functionality here
            alert('Viewing all alerts...');
        }

        // Initialize map with custom styling
        const map = L.map('map').setView([1.3521, 103.8198], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Enhanced sensor data
        const sensors = [
            { id: 'SNR-001', name: 'Central Park', lat: 1.3521, lng: 103.8198, aqi: 45, status: 'good', trend: 'stable' },
            { id: 'SNR-002', name: 'Downtown', lat: 1.3423, lng: 103.8353, aqi: 75, status: 'moderate', trend: 'increasing' },
            { id: 'SNR-003', name: 'Industrial Zone', lat: 1.3644, lng: 103.8277, aqi: 160, status: 'unhealthy', trend: 'decreasing' }
        ];

        // Add markers to map
        sensors.forEach(sensor => {
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
                    <p class="text-sm text-gray-600 mb-2">${sensor.id}</p>
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

        // Initialize trends chart with reduced height
        const ctx = document.getElementById('trends-chart').getContext('2d');
        const trendChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: Array.from({length: 24}, (_, i) => `${i}:00`),
                datasets: [{
                    label: 'AQI',
                    data: Array.from({length: 24}, () => Math.floor(Math.random() * 100) + 20),
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 3,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#1F2937',
                        bodyColor: '#1F2937',
                        borderColor: '#E5E7EB',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(156, 163, 175, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            padding: 10
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            padding: 10,
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: 12
                        }
                    }
                }
            }
        });

        // Mini chart creation function
        function createMiniChart(sensorId) {
            const miniCtx = document.getElementById(`mini-chart-${sensorId}`);
            if (!miniCtx) return;

            new Chart(miniCtx, {
                type: 'line',
                data: {
                    labels: ['6h ago', '5h ago', '4h ago', '3h ago', '2h ago', '1h ago', 'Now'],
                    datasets: [{
                        label: 'AQI',
                        data: Array.from({length: 7}, () => Math.floor(Math.random() * 100) + 20),
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
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            display: false
                        },
                        x: {
                            display: false
                        }
                    },
                    elements: {
                        point: {
                            radius: 0
                        }
                    }
                }
            });
        }

        // Chart update function
        function updateChart() {
            const sensor = document.getElementById('sensor-select').value;
            const timeRange = document.getElementById('time-range').value;
            
            const dataPoints = timeRange === 'day' ? 24 : 
                             timeRange === 'week' ? 7 : 30;
            
            trendChart.data.labels = Array.from({length: dataPoints}, (_, i) => 
                timeRange === 'day' ? `${i}:00` :
                timeRange === 'week' ? `Day ${i + 1}` : `Day ${i + 1}`
            );
            
            trendChart.data.datasets[0].data = Array.from(
                {length: dataPoints}, 
                () => Math.floor(Math.random() * 100) + 20
            );
            
            trendChart.update('active');
        }

        // Event listeners
        document.getElementById('sensor-select').addEventListener('change', updateChart);
        document.getElementById('time-range').addEventListener('change', updateChart);

        // Auto refresh countdown
        let countdown = 30;
        const countdownEl = document.getElementById('refresh-countdown');
        
        setInterval(() => {
            countdown--;
            countdownEl.textContent = countdown + 's';
            
            if (countdown <= 0) {
                countdown = 30;
                // Refresh data here
            }
        }, 1000);
    </script>
</x-app-layout>
