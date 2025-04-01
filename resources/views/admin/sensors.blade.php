<x-admin-layout>
    <!-- Header Section with Add Button -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-[#212121]">Sensor Management</h2>
        <button onclick="showAddSensorModal()" class="px-4 py-2 bg-[#212121] text-white rounded-lg flex items-center hover:bg-opacity-90">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add New Sensor
        </button>
    </div>

    <!-- Sensors List -->
    <div class="bg-white rounded-lg border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sensor Info</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Readings</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Health Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity Period</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($sensors as $sensor)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-[#212121]">{{ $sensor->name }}</div>
                                        <div class="text-sm text-gray-500">Location: {{ $sensor->location }}</div>
                                        <span class="inline-flex mt-1 items-center rounded-lg {{ $sensor->status === 'active' ? 'bg-green-50 text-green-700 ring-green-600/20' : 'bg-red-50 text-red-700 ring-red-600/20' }} px-2 py-1 text-xs font-medium ring-1 ring-inset">
                                            {{ ucfirst($sensor->status) }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-[#212121] font-medium">--</div>
                                <div class="text-xs text-gray-500">Threshold: {{ $sensor->threshold_value }} ppm</div>
                                <div class="mt-1 flex items-center">
                                    <div class="w-24 bg-gray-200 rounded-full h-2">
                                        <div class="bg-gray-500 h-2 rounded-full" style="width: 0%"></div>
                                    </div>
                                    <span class="ml-2 text-xs text-gray-500">No data</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-3 w-3 rounded-full {{ $sensor->status === 'active' ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                    <div class="ml-2">
                                        <div class="text-sm text-[#212121]">{{ $sensor->status === 'active' ? 'Active' : 'Inactive' }}</div>
                                        <div class="text-xs text-gray-500">Type: {{ strtoupper($sensor->type) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-[#212121]">Started: {{ $sensor->start_date->format('M d, Y') }}</div>
                                <div class="text-sm text-gray-500">ID: {{ $sensor->sensor_id }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="showEditSensorModal({{ $sensor->id }})" class="text-[#212121] hover:text-opacity-70 p-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button onclick="showDeactivateModal({{ $sensor->id }})" class="text-yellow-600 hover:text-yellow-700 p-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </button>
                                <button onclick="showDeleteModal({{ $sensor->id }})" class="text-red-600 hover:text-red-700 p-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center bg-gray-50">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                    </svg>
                                    <span>No sensors found. Add your first sensor to get started.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Sensor Modal -->
    <div id="addSensorModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-1/2 -translate-y-1/2 mx-auto p-8 w-[600px] shadow-xl rounded-lg bg-white">
            <div class="text-center mb-6">
                <h3 class="text-xl font-bold text-[#212121]">Add New Gas Sensor</h3>
                <p class="mt-1 text-sm text-gray-600">Enter the details of the new sensor</p>
            </div>

            <form class="space-y-6" id="sensorForm">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium leading-6 text-[#212121]">Sensor Name</label>
                        <div class="mt-2">
                            <input type="text" name="name" required
                                class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121] focus:border-[#212121] focus:ring-[#212121]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-[#212121]">Sensor ID</label>
                        <div class="mt-2 relative">
                            <input type="text" name="sensor_id" readonly required
                                class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121] bg-gray-50">
                            <button type="button" onclick="generateSensorId()" 
                                class="absolute right-2 top-2 px-2 py-1 text-xs bg-[#212121] text-white rounded hover:bg-opacity-90">
                                Generate
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-[#212121]">Location</label>
                        <div class="mt-2">
                            <input type="text" name="location" required
                                class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121] focus:border-[#212121] focus:ring-[#212121]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-[#212121]">Sensor Type</label>
                        <div class="mt-2">
                            <select name="type" required class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121] focus:border-[#212121] focus:ring-[#212121]">
                                <option value="">Select a type...</option>
                                <option value="co2">CO2 Sensor</option>
                                <option value="no2">NO2 Sensor</option>
                                <option value="pm25">PM2.5 Sensor</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-[#212121]">Threshold Value (ppm)</label>
                        <div class="mt-2">
                            <input type="number" name="threshold_value" required
                                class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121] focus:border-[#212121] focus:ring-[#212121]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-[#212121]">Start Date</label>
                        <div class="mt-2">
                            <input type="date" name="start_date" required
                                class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121] focus:border-[#212121] focus:ring-[#212121]">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium leading-6 text-[#212121]">Notes</label>
                    <div class="mt-2">
                        <textarea name="notes" rows="3"
                            class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121] focus:border-[#212121] focus:ring-[#212121]"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6">
                    <button type="button" onclick="hideAddSensorModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-[#212121] hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-[#212121] text-white rounded-lg text-sm font-medium hover:bg-opacity-90">
                        Add Sensor
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function generateSensorId() {
            fetch('/admin/sensors/generate-id', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.querySelector('input[name="sensor_id"]').value = data.sensor_id;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to generate sensor ID');
            });
        }

        document.getElementById('sensorForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => {
                if (key !== '_token') {
                    data[key] = value;
                }
            });

            fetch('/admin/sensors/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to add sensor');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to add sensor');
            });
        });

        // Generate sensor ID when modal opens
        function showAddSensorModal() {
            document.getElementById('addSensorModal').classList.remove('hidden');
            generateSensorId();
        }

        function hideAddSensorModal() {
            document.getElementById('addSensorModal').classList.add('hidden');
        }

        // Add other modal control functions as needed
    </script>
</x-admin-layout>
