<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-[#212121]">Data Simulation</h2>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <span id="simulationStatus" class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            Simulation Inactive
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Enhanced Simulation Controls -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Basic Settings -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Sensor</label>
                        <select id="sensorSelect" class="w-full rounded-lg border border-gray-200 px-4 py-2.5">
                            <option value="">Choose a sensor...</option>
                            @foreach($sensors as $sensor)
                                <option value="{{ $sensor->id }}" data-type="{{ $sensor->type }}">
                                    {{ $sensor->name }} ({{ strtoupper($sensor->type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Simulation Pattern</label>
                        <select id="patternType" class="w-full rounded-lg border border-gray-200 px-4 py-2.5">
                            <option value="random">Random Fluctuations</option>
                            <option value="linear">Linear Trend</option>
                            <option value="cyclical">Cyclical Pattern</option>
                        </select>
                    </div>
                </div>

                <!-- Range Settings -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Value Range</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <input type="number" id="minValue" placeholder="Min" 
                                    class="w-full rounded-lg border border-gray-200 px-4 py-2.5">
                            </div>
                            <div>
                                <input type="number" id="maxValue" placeholder="Max"
                                    class="w-full rounded-lg border border-gray-200 px-4 py-2.5">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Update Frequency (minutes)</label>
                        <input type="number" id="frequency" min="1" max="60" value="5"
                            class="w-full rounded-lg border border-gray-200 px-4 py-2.5">
                    </div>
                </div>

                <!-- Simulation Controls -->
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <button id="startBtn" onclick="toggleSimulation(true)"
                                class="flex-1 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Start Simulation
                        </button>
                        <button id="stopBtn" onclick="toggleSimulation(false)"
                                class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700" disabled>
                            Stop Simulation
                        </button>
                    </div>
                    <button onclick="saveSettings()"
                            class="w-full px-4 py-2.5 bg-[#212121] text-white rounded-lg hover:bg-opacity-90">
                        Save Settings
                    </button>
                </div>
            </div>
        </div>

        <!-- Visualization Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Chart -->
            <div class="lg:col-span-2 bg-white p-6 rounded-lg border border-gray-200">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-[#212121]">Real-time Data</h3>
                    <p class="text-sm text-gray-500" id="sensorInfo">Select a sensor to begin simulation</p>
                </div>
                <div class="h-[400px]" id="chart"></div>
            </div>

            <!-- Simulation Logs -->
            <div class="bg-white p-6 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-[#212121] mb-4">Simulation Log</h3>
                <div class="space-y-2" id="simulationLog">
                    <!-- Logs will be inserted here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Include ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <script>
        let chart;
        let simulationInterval;
        const chartOptions = {
            series: [{
                name: 'Value',
                data: []
            }],
            chart: {
                type: 'line',
                height: 400,
                animations: {
                    enabled: true,
                    easing: 'linear',
                    dynamicAnimation: {
                        speed: 1000
                    }
                },
                toolbar: {
                    show: false
                }
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            markers: {
                size: 0
            },
            xaxis: {
                type: 'datetime',
                range: 60000, // 1 minute in milliseconds
            },
            yaxis: {
                labels: {
                    formatter: (val) => val.toFixed(2)
                }
            },
            grid: {
                padding: {
                    top: 20,
                    right: 20,
                    bottom: 20,
                    left: 20
                }
            }
        };

        // Initialize chart
        document.addEventListener('DOMContentLoaded', function() {
            chart = new ApexCharts(document.querySelector("#chart"), chartOptions);
            chart.render();
        });

        const logMessages = [];

        function addLogMessage(message) {
            const timestamp = new Date().toLocaleTimeString();
            logMessages.unshift(`${timestamp}: ${message}`);
            if (logMessages.length > 50) logMessages.pop();
            
            const logHtml = logMessages.map(msg => `
                <div class="text-sm text-gray-600 pb-2 border-b border-gray-100">${msg}</div>
            `).join('');
            
            document.getElementById('simulationLog').innerHTML = logHtml;
        }

        function saveSettings() {
            const sensorId = document.getElementById('sensorSelect').value;
            if (!sensorId) {
                alert('Please select a sensor');
                return;
            }

            const settings = {
                frequency: parseInt(document.getElementById('frequency').value),
                pattern_type: document.getElementById('patternType').value,
                min_value: parseFloat(document.getElementById('minValue').value),
                max_value: parseFloat(document.getElementById('maxValue').value),
                is_active: false
            };

            fetch(`/admin/simulation/${sensorId}/settings`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(settings)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    addLogMessage('Settings saved successfully');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                addLogMessage('Failed to save settings');
            });
        }

        function toggleSimulation(start) {
            const sensorId = document.getElementById('sensorSelect').value;
            if (!sensorId) {
                alert('Please select a sensor');
                return;
            }

            if (start) {
                startSimulation();
            } else {
                stopSimulation();
            }

            fetch(`/admin/simulation/${sensorId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const status = data.is_active ? 'active' : 'inactive';
                    document.getElementById('simulationStatus').textContent = 
                        `Simulation ${status}`;
                    addLogMessage(`Simulation ${status}`);
                }
            });
        }

        function startSimulation() {
            document.getElementById('startBtn').disabled = true;
            document.getElementById('stopBtn').disabled = false;
            // ... existing simulation logic ...
        }

        function stopSimulation() {
            document.getElementById('startBtn').disabled = false;
            document.getElementById('stopBtn').disabled = true;
            if (simulationInterval) {
                clearInterval(simulationInterval);
            }
        }

        function updateChart(data) {
            // Update sensor info
            document.getElementById('sensorInfo').textContent = 
                `${data.sensor.name} - Measuring ${data.sensor.type.toUpperCase()} (${data.sensor.unit})`;

            // Process data for chart
            const chartData = data.data.map(reading => ({
                x: new Date(reading.timestamp).getTime(),
                y: reading.value,
                threshold: reading.threshold,
                status: reading.status
            }));

            // Update chart
            chart.updateSeries([{
                name: data.sensor.type.toUpperCase(),
                data: chartData
            }]);

            // Update chart colors based on threshold
            chart.updateOptions({
                colors: ['#10B981'],
                yaxis: {
                    title: {
                        text: data.sensor.unit
                    },
                    min: Math.min(...chartData.map(d => d.y)) * 0.9,
                    max: Math.max(...chartData.map(d => d.y)) * 1.1
                },
                annotations: {
                    yaxis: [{
                        y: data.data[0].threshold,
                        borderColor: '#DC2626',
                        label: {
                            text: 'Threshold',
                            style: {
                                color: '#DC2626'
                            }
                        }
                    }]
                }
            });
        }
    </script>
</x-admin-layout>
