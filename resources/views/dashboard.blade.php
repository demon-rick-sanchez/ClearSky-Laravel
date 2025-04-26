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
                                <option value="SNR-001">Fort</option>
                                <option value="SNR-002">Pettah</option>
                                <option value="SNR-003">Slave Island</option>
                                <option value="SNR-004">Kollupitiya</option>
                                <option value="SNR-005">Bambalapitiya</option>
                                <option value="SNR-006">Wellawatte</option>
                                <option value="SNR-007">Dehiwala</option>
                                <option value="SNR-008">Mount Lavinia</option>
                                <option value="SNR-009">Ratmalana</option>
                                <option value="SNR-010">Moratuwa</option>
                                <option value="SNR-011">Borella</option>
                                <option value="SNR-012">Maradana</option>
                                <option value="SNR-013">Dematagoda</option>
                                <option value="SNR-014">Mattakkuliya</option>
                                <option value="SNR-015">Kotahena</option>
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
        let map, trendChart;
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
            select.innerHTML = sensors.map(sensor => 
                `<option value="${sensor.id}">${sensor.name}</option>`
            ).join('');
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
                        label: 'AQI',
                        data: [],
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 3,
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

        // Fetch sensor readings and update chart
        async function fetchSensorReadings(sensorId, timeRange) {
            try {
                const response = await fetch(`/api/sensors/${sensorId}/readings`);
                const readings = await response.json();
                
                // Filter readings based on time range
                const now = new Date();
                const filtered = readings.filter(reading => {
                    const readingDate = new Date(reading.timestamp);
                    const diffHours = (now - readingDate) / (1000 * 60 * 60);
                    
                    return timeRange === 'day' ? diffHours <= 24 :
                           timeRange === 'week' ? diffHours <= 168 :
                           diffHours <= 720; // month (30 days)
                });

                // Update chart
                trendChart.data.labels = filtered.map(r => {
                    const date = new Date(r.timestamp);
                    return timeRange === 'day' ? date.getHours() + ':00' :
                           timeRange === 'week' ? date.toLocaleDateString('en-US', { weekday: 'short' }) :
                           date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                });
                trendChart.data.datasets[0].data = filtered.map(r => r.value);
                trendChart.update();
            } catch (error) {
                console.error('Error fetching readings:', error);
            }
        }

        // Initialize everything
        function initialize() {
            initializeMap();
            initializeTrendChart();
            fetchSensors();
            fetchAlerts();
            
            // Set up event listeners
            document.getElementById('sensor-select').addEventListener('change', updateChart);
            document.getElementById('time-range').addEventListener('change', updateChart);
            
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
            fetchSensorReadings(sensorId, timeRange);
        }

        // Initialize on page load
        initialize();

        // Handle "View All Alerts" button click
        function viewAllAlerts() {
            // You can implement a modal or redirect to a full alerts page
            alert('Viewing all alerts...');
        }
    </script>
</x-app-layout>
