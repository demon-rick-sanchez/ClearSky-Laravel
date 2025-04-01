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
                                    <div class="text-sm font-medium text-[#212121]">CO2 Sensor #1</div>
                                    <div class="text-sm text-gray-500">Location: Lab Room 101</div>
                                    <span class="inline-flex mt-1 items-center rounded-lg bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                        Active
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-[#212121] font-medium">450 ppm</div>
                            <div class="text-xs text-gray-500">Threshold: 1000 ppm</div>
                            <div class="mt-1 flex items-center">
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: 45%"></div>
                                </div>
                                <span class="ml-2 text-xs text-gray-500">45%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-3 w-3 rounded-full bg-green-400"></div>
                                <div class="ml-2">
                                    <div class="text-sm text-[#212121]">Optimal</div>
                                    <div class="text-xs text-gray-500">Last check: 5 mins ago</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-[#212121]">Started: Jan 15, 2024</div>
                            <div class="text-sm text-gray-500">No end date</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="showEditSensorModal(1)" class="text-[#212121] hover:text-opacity-70 p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button onclick="showDeactivateModal(1)" class="text-yellow-600 hover:text-yellow-700 p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </button>
                            <button onclick="showDeleteModal(1)" class="text-red-600 hover:text-red-700 p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
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

            <form class="space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium leading-6 text-[#212121]">Sensor Name</label>
                        <div class="mt-2">
                            <input type="text" required
                                class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121] focus:border-[#212121] focus:ring-[#212121]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-[#212121]">Sensor ID</label>
                        <div class="mt-2">
                            <input type="text" required
                                class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121] focus:border-[#212121] focus:ring-[#212121]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-[#212121]">Location</label>
                        <div class="mt-2">
                            <input type="text" required
                                class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121] focus:border-[#212121] focus:ring-[#212121]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-[#212121]">Sensor Type</label>
                        <div class="mt-2">
                            <select required class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121] focus:border-[#212121] focus:ring-[#212121]">
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
                            <input type="number" required
                                class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121] focus:border-[#212121] focus:ring-[#212121]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-[#212121]">Start Date</label>
                        <div class="mt-2">
                            <input type="date" required
                                class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121] focus:border-[#212121] focus:ring-[#212121]">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium leading-6 text-[#212121]">Notes</label>
                    <div class="mt-2">
                        <textarea rows="3"
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
        function showAddSensorModal() {
            document.getElementById('addSensorModal').classList.remove('hidden');
        }

        function hideAddSensorModal() {
            document.getElementById('addSensorModal').classList.add('hidden');
        }

        // Add other modal control functions as needed
    </script>
</x-admin-layout>
