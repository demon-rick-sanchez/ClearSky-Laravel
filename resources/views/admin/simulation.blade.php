<x-admin-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-[#212121]">Data Simulation</h2>
        <p class="text-gray-600 mt-1">Configure and manage sensor data simulation settings</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Simulation Settings -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Simulation Settings</h3>
                    <button onclick="quickStartSimulation()" 
                            class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                        Quick Start
                    </button>
                </div>
                <form id="simulationForm" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-2">Select Sensor</label>
                        <select id="sensor-select" name="sensor_id" class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121]">
                            @foreach($sensors as $sensor)
                                <option value="{{ $sensor->id }}" data-type="{{ $sensor->type }}">{{ $sensor->name }} ({{ $sensor->location }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Pattern Type</label>
                        <select id="pattern-type" name="pattern_type" class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121]">
                            <option value="random">Random</option>
                            <option value="linear">Linear</option>
                            <option value="cyclical">Cyclical</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Update Frequency (minutes)</label>
                        <input type="number" id="frequency" name="frequency" value="5" min="1" max="60" 
                               class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Minimum Value</label>
                        <input type="number" id="min-value" name="min_value" 
                               class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Maximum Value</label>
                        <input type="number" id="max-value" name="max_value" 
                               class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Thresholds</label>
                        <div class="space-y-2">
                            <input type="number" id="warning-threshold" name="thresholds[warning]" placeholder="Warning threshold"
                                   class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121]">
                            <input type="number" id="critical-threshold" name="thresholds[critical]" placeholder="Critical threshold"
                                   class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121]">
                        </div>
                    </div>
                </form>

                <div class="mt-6 flex justify-between items-center pt-4 border-t border-gray-100">
                    <button onclick="toggleSimulation()" id="toggle-btn" 
                            class="px-4 py-2 bg-[#212121] text-white rounded-lg text-sm font-medium hover:bg-opacity-90">
                        Start Simulation
                    </button>
                    <button onclick="saveSettings()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-[#212121] hover:bg-gray-50">
                        Save Settings
                    </button>
                </div>
            </div>
        </div>

        <!-- Visualization -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Live Data</h3>
                    <div class="flex items-center gap-4">
                        <span id="status-badge" class="px-2 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-800">
                            Inactive
                        </span>
                        <select id="duration-select" class="rounded-lg border border-gray-200 px-3 py-2 text-sm">
                            <option value="60">Last 1 minute</option>
                            <option value="300">Last 5 minutes</option>
                            <option value="900">Last 15 minutes</option>
                            <option value="3600">Last 1 hour</option>
                        </select>
                    </div>
                </div>
                
                <div class="h-[400px]">
                    <canvas id="simulation-chart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let simulationChart;
        let isSimulationActive = false;
        const currentSensorSettings = {};

        // Initialize Chart.js
        function initChart() {
            const ctx = document.getElementById('simulation-chart').getContext('2d');
            simulationChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Sensor Readings',
                        data: [],
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Toggle simulation state
        async function toggleSimulation() {
            const sensorId = document.getElementById('sensor-select').value;
            const button = document.getElementById('toggle-btn');
            const badge = document.getElementById('status-badge');

            try {
                const response = await fetch(`/admin/simulation/${sensorId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                
                if (data.success) {
                    isSimulationActive = data.is_active;
                    button.textContent = isSimulationActive ? 'Stop Simulation' : 'Start Simulation';
                    badge.textContent = isSimulationActive ? 'Active' : 'Inactive';
                    badge.className = `px-2 py-1 text-sm font-medium rounded-full ${
                        isSimulationActive ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                    }`;

                    if (isSimulationActive) {
                        updateChart();
                    }
                } else {
                    throw new Error(data.message || 'Failed to toggle simulation');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to toggle simulation: ' + error.message);
            }
        }

        // Save simulation settings
        async function saveSettings() {
            const sensorId = document.getElementById('sensor-select').value;
            const form = document.getElementById('simulationForm');
            
            const settings = {
                frequency: parseInt(form.querySelector('[name="frequency"]').value),
                pattern_type: form.querySelector('[name="pattern_type"]').value,
                min_value: parseFloat(form.querySelector('[name="min_value"]').value),
                max_value: parseFloat(form.querySelector('[name="max_value"]').value),
                thresholds: {
                    warning: parseFloat(form.querySelector('[name="thresholds[warning]"]').value),
                    critical: parseFloat(form.querySelector('[name="thresholds[critical]"]').value)
                }
            };
            
            try {
                const response = await fetch(`/admin/simulation/${sensorId}/settings`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify(settings)
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const data = await response.json();
                if (data.success) {
                    currentSensorSettings[sensorId] = data.settings;
                } else {
                    throw new Error(data.message || 'Failed to save settings');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to save settings: ' + error.message);
            }
        }

        // Add Quick Start function
        async function quickStartSimulation() {
            const select = document.getElementById('sensor-select');
            const sensorId = select.value;
            const sensorType = select.options[select.selectedIndex].dataset.type;
            
            // Get default values based on sensor type
            const defaults = getDefaultValues(sensorType);
            
            // Set form values
            document.getElementById('pattern-type').value = 'random';
            document.getElementById('frequency').value = 5;
            document.getElementById('min-value').value = defaults.min;
            document.getElementById('max-value').value = defaults.max;
            document.getElementById('warning-threshold').value = Math.round(defaults.max * 0.7);
            document.getElementById('critical-threshold').value = Math.round(defaults.max * 0.9);

            // Save settings and start simulation
            await saveSettings();
            await toggleSimulation();
        }

        function getDefaultValues(sensorType) {
            switch(sensorType) {
                case 'co2':
                    return { min: 350, max: 2000 };
                case 'no2':
                    return { min: 10, max: 100 };
                case 'pm25':
                    return { min: 0, max: 500 };
                default:
                    return { min: 0, max: 100 };
            }
        }

        // Update chart with new data
        async function updateChart() {
            if (!isSimulationActive) return;

            const sensorId = document.getElementById('sensor-select').value;
            const duration = document.getElementById('duration-select').value;

            try {
                const response = await fetch(`/admin/simulation/generate?sensor_id=${sensorId}&duration=${duration}`);
                const data = await response.json();
                
                if (data.success) {
                    simulationChart.data.labels = data.data.map(d => new Date(d.timestamp).toLocaleTimeString());
                    simulationChart.data.datasets[0].data = data.data.map(d => d.value);
                    simulationChart.data.datasets[0].label = `${data.sensor.name} (${data.sensor.unit})`;
                    simulationChart.update();
                }
            } catch (error) {
                console.error('Error:', error);
            }

            if (isSimulationActive) {
                setTimeout(updateChart, 5000); // Update every 5 seconds
            }
        }

        // Load sensor settings when sensor changes
        async function loadSensorSettings(sensorId) {
            if (currentSensorSettings[sensorId]) {
                const settings = currentSensorSettings[sensorId];
                Object.keys(settings).forEach(key => {
                    const input = document.querySelector(`[name="${key}"]`);
                    if (input) input.value = settings[key];
                });
                return;
            }

            try {
                const response = await fetch(`/admin/simulation/${sensorId}/settings`);
                const data = await response.json();
                if (data.success) {
                    Object.keys(data.settings).forEach(key => {
                        const input = document.querySelector(`[name="${key}"]`);
                        if (input) input.value = data.settings[key];
                    });
                    currentSensorSettings[sensorId] = data.settings;
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Event Listeners
        document.getElementById('sensor-select').addEventListener('change', (e) => {
            loadSensorSettings(e.target.value);
        });

        document.getElementById('duration-select').addEventListener('change', () => {
            if (isSimulationActive) {
                updateChart();
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            initChart();
            const initialSensorId = document.getElementById('sensor-select').value;
            if (initialSensorId) {
                loadSensorSettings(initialSensorId);
            }
        });
    </script>
</x-admin-layout>
