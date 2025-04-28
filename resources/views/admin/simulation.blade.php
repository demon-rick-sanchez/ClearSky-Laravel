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
                    <div class="flex items-center gap-2">
                        <div class="flex items-center">
                            <input type="checkbox" id="auto-save" class="mr-2">
                            <label for="auto-save" class="text-sm">Auto-save (5min)</label>
                        </div>
                        <button onclick="quickStartSimulation()" 
                                class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                            Quick Start
                        </button>
                    </div>
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
                        <button id="save-simulation-btn" 
                                onclick="manualSaveSimulationData()"
                                class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 hidden">
                            Save Data
                        </button>
                        <span id="countdown-timer" class="px-2 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800 hidden">
                            00:00:00
                        </span>
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

    <!-- Simulation History -->
    <div class="mt-6">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold mb-4">Simulation History</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sensor</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pattern</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Value</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Value</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="simulation-history" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be populated dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let simulationChart;
        let isSimulationActive = false;
        let countdownInterval;
        let simulationStartTime;
        const currentSensorSettings = {};
        let autoSaveInterval;

        function updateCountdownTimer() {
            const timer = document.getElementById('countdown-timer');
            const now = new Date().getTime();
            const elapsedTime = Math.floor((now - simulationStartTime) / 1000);
            
            const hours = Math.floor(elapsedTime / 3600);
            const minutes = Math.floor((elapsedTime % 3600) / 60);
            const seconds = elapsedTime % 60;
            
            timer.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }

        function startCountdown() {
            const timer = document.getElementById('countdown-timer');
            const saveButton = document.getElementById('save-simulation-btn');
            timer.classList.remove('hidden');
            saveButton.classList.remove('hidden');
            simulationStartTime = new Date().getTime();
            countdownInterval = setInterval(updateCountdownTimer, 1000);
            startAutoSave();
        }

        function stopCountdown() {
            const timer = document.getElementById('countdown-timer');
            const saveButton = document.getElementById('save-simulation-btn');
            timer.classList.add('hidden');
            saveButton.classList.add('hidden');
            clearInterval(countdownInterval);
            stopAutoSave();
        }

        function startAutoSave() {
            if (document.getElementById('auto-save').checked) {
                autoSaveInterval = setInterval(async () => {
                    if (isSimulationActive) {
                        await manualSaveSimulationData();
                        loadSimulationHistory();
                    }
                }, 5 * 60 * 1000);
            }
        }

        function stopAutoSave() {
            if (autoSaveInterval) {
                clearInterval(autoSaveInterval);
            }
        }

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
                        startCountdown();
                        updateChart();
                    } else {
                        stopCountdown();
                        await saveSimulationData();
                    }
                } else {
                    throw new Error(data.message || 'Failed to toggle simulation');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to toggle simulation: ' + error.message);
            }
        }

        async function saveSimulationData() {
            const sensorId = document.getElementById('sensor-select').value;
            try {
                const response = await fetch(`/admin/simulation/${sensorId}/save-data`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        duration: Math.floor((new Date().getTime() - simulationStartTime) / 1000)
                    })
                });

                if (!response.ok) {
                    throw new Error('Failed to save simulation data');
                }

                const data = await response.json();
                if (data.success) {
                    console.log('Simulation data saved successfully');
                }
            } catch (error) {
                console.error('Error saving simulation data:', error);
            }
        }

        async function manualSaveSimulationData() {
            const saveButton = document.getElementById('save-simulation-btn');
            const originalText = saveButton.textContent;
            saveButton.textContent = 'Saving...';
            saveButton.disabled = true;

            try {
                const response = await fetch(`/admin/simulation/${document.getElementById('sensor-select').value}/save-data`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        duration: Math.floor((new Date().getTime() - simulationStartTime) / 1000)
                    })
                });

                if (!response.ok) {
                    throw new Error('Failed to save simulation data');
                }

                const data = await response.json();
                if (data.success) {
                    saveButton.textContent = 'Saved!';
                    setTimeout(() => {
                        saveButton.textContent = originalText;
                        saveButton.disabled = false;
                    }, 2000);
                }
            } catch (error) {
                console.error('Error saving simulation data:', error);
                saveButton.textContent = 'Save Failed';
                setTimeout(() => {
                    saveButton.textContent = originalText;
                    saveButton.disabled = false;
                }, 2000);
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

        async function loadSimulationHistory() {
            try {
                const response = await fetch('/admin/simulation/history');
                const data = await response.json();
                
                if (data.success) {
                    const tbody = document.getElementById('simulation-history');
                    tbody.innerHTML = data.history.map(record => `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${new Date(record.created_at).toLocaleString()}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${record.sensor_name}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${record.pattern_type}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${formatDuration(record.duration)}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${record.min_value}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${record.max_value}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <button onclick="viewSimulationData(${record.id})" 
                                        class="text-blue-600 hover:text-blue-800">
                                    View Data
                                </button>
                            </td>
                        </tr>
                    `).join('');
                }
            } catch (error) {
                console.error('Error loading simulation history:', error);
            }
        }

        function formatDuration(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const remainingSeconds = seconds % 60;
            
            return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
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

        // Auto-save checkbox event listener
        document.getElementById('auto-save').addEventListener('change', function(e) {
            if (e.target.checked && isSimulationActive) {
                startAutoSave();
            } else {
                stopAutoSave();
            }
        });

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
            loadSimulationHistory();
        });
    </script>
</x-admin-layout>
