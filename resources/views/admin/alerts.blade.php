<x-admin-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-[#212121]">Alert Configuration</h2>
        <p class="text-gray-600 mt-1">Configure system-wide alert settings and notifications</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Global Alert Settings -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold mb-4">Global Alert Settings</h3>
            <form id="globalAlertForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-2">Default Threshold Value (ppm)</label>
                    <input type="number" name="default_threshold" class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121]" 
                           value="{{ $settings['default_threshold'] ?? '100' }}">
                    <p class="text-sm text-gray-500 mt-1">Default threshold for new sensors</p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Alert Frequency (minutes)</label>
                    <input type="number" name="alert_frequency" class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121]"
                           value="{{ $settings['alert_frequency'] ?? '15' }}">
                    <p class="text-sm text-gray-500 mt-1">Minimum time between repeated alerts</p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Critical Alert Threshold (%)</label>
                    <input type="number" name="critical_threshold" class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121]"
                           value="{{ $settings['critical_threshold'] ?? '150' }}">
                    <p class="text-sm text-gray-500 mt-1">Percentage above sensor threshold to trigger critical alert</p>
                </div>
            </form>
        </div>

        <!-- Notification Settings -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold mb-4">Notification Settings</h3>
            <form id="notificationForm" class="space-y-4">
                @csrf
                <div>
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="email_notifications" class="rounded border-gray-300"
                               {{ $settings['email_notifications'] ?? false ? 'checked' : '' }}>
                        <span class="text-sm font-medium">Email Notifications</span>
                    </label>
                    <p class="text-sm text-gray-500 mt-1 ml-6">Send email notifications for critical alerts</p>
                </div>

                <div>
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="push_notifications" class="rounded border-gray-300"
                               {{ $settings['push_notifications'] ?? false ? 'checked' : '' }}>
                        <span class="text-sm font-medium">Push Notifications</span>
                    </label>
                    <p class="text-sm text-gray-500 mt-1 ml-6">Enable browser push notifications</p>
                </div>

                <div>
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="sms_notifications" class="rounded border-gray-300"
                               {{ $settings['sms_notifications'] ?? false ? 'checked' : '' }}>
                        <span class="text-sm font-medium">SMS Notifications</span>
                    </label>
                    <p class="text-sm text-gray-500 mt-1 ml-6">Send SMS for critical alerts (additional charges may apply)</p>
                </div>

                <div class="pt-4">
                    <label class="block text-sm font-medium mb-2">Notification Recipients</label>
                    <textarea name="notification_recipients" rows="3" 
                              class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121]"
                              placeholder="Enter email addresses separated by commas">{{ $settings['notification_recipients'] ?? '' }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Recipients for email notifications</p>
                </div>
            </form>
        </div>
    </div>

    <!-- Custom Alert Section -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold mb-4">Send Custom Alert</h3>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <form id="customAlertForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-2">Alert Type</label>
                    <select name="alert_type" class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121]">
                        <option value="warning">Warning</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Alert Message</label>
                    <textarea name="message" rows="3" 
                            class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121]"
                            placeholder="Enter alert message"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Target Users</label>
                    <select name="target_type" class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121] mb-2">
                        <option value="all">All Users</option>
                        <option value="area">Users in Specific Area</option>
                    </select>
                    
                    <div id="area-selection" class="hidden">
                        <select name="area" class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121]">
                            @foreach($sensors as $sensor)
                                <option value="{{ $sensor->id }}">{{ $sensor->location }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="button" onclick="sendCustomAlert()" 
                            class="px-4 py-2 bg-[#212121] text-white rounded-lg text-sm font-medium hover:bg-opacity-90">
                        Send Alert
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Active Alerts Section -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold mb-4">Active Alerts</h3>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div id="active-alerts" class="space-y-3">
                <!-- Active alerts will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div class="mt-6 flex justify-end">
        <button onclick="saveSettings()" class="px-4 py-2 bg-[#212121] text-white rounded-lg text-sm font-medium hover:bg-opacity-90">
            Save Settings
        </button>
    </div>

    <script>
        function saveSettings() {
            const globalSettings = Object.fromEntries(new FormData(document.getElementById('globalAlertForm')));
            const notificationSettings = Object.fromEntries(new FormData(document.getElementById('notificationForm')));
            
            const settings = {
                ...globalSettings,
                ...notificationSettings
            };

            fetch('/admin/alerts/settings', {
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
                    alert('Settings saved successfully');
                } else {
                    alert('Failed to save settings');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to save settings');
            });
        }

        // Show/hide area selection based on target type
        document.querySelector('select[name="target_type"]').addEventListener('change', function(e) {
            const areaSelection = document.getElementById('area-selection');
            areaSelection.classList.toggle('hidden', e.target.value !== 'area');
        });

        function sendCustomAlert() {
            const formData = new FormData(document.getElementById('customAlertForm'));
            const data = Object.fromEntries(formData);

            fetch('/admin/alerts/custom', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Alert sent successfully');
                    document.getElementById('customAlertForm').reset();
                    loadActiveAlerts();
                } else {
                    alert(data.message || 'Failed to send alert');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to send alert');
            });
        }

        function loadActiveAlerts() {
            fetch('/admin/alerts/active')
                .then(response => response.json())
                .then(alerts => {
                    const container = document.getElementById('active-alerts');
                    if (alerts.length === 0) {
                        container.innerHTML = '<p class="text-gray-500 text-center py-4">No active alerts</p>';
                        return;
                    }

                    container.innerHTML = alerts.map(alert => `
                        <div class="flex items-center justify-between p-3 bg-${alert.type === 'critical' ? 'red' : 'yellow'}-50 rounded-md">
                            <div class="flex items-center gap-3">
                                <span class="px-2 py-0.5 text-xs font-medium rounded ${alert.type === 'critical' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'}">
                                    ${alert.type === 'critical' ? 'Critical' : 'Warning'}
                                </span>
                                <span class="text-sm">${alert.message}</span>
                            </div>
                            <button onclick="dismissAlert('${alert.id}')" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    `).join('');
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function dismissAlert(alertId) {
            fetch(`/admin/alerts/${alertId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadActiveAlerts();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Load active alerts on page load
        loadActiveAlerts();
    </script>
</x-admin-layout>
