<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold text-gray-900">Alert Settings</h2>
            <p class="mt-1 text-sm text-gray-600">Customize your alert preferences and notifications</p>

            <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Personal Alert Preferences -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Alert Preferences</h3>
                    <form id="alertPreferencesForm" class="space-y-4">
                        @csrf
                        <div>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="receive_alerts" class="rounded border-gray-300"
                                       {{ $preferences['receive_alerts'] ?? true ? 'checked' : '' }}>
                                <span class="text-sm font-medium">Receive Air Quality Alerts</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Alert Sensitivity</label>
                            <select name="alert_sensitivity" class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-gray-900">
                                <option value="low" {{ ($preferences['alert_sensitivity'] ?? '') == 'low' ? 'selected' : '' }}>Low (Major changes only)</option>
                                <option value="medium" {{ ($preferences['alert_sensitivity'] ?? 'medium') == 'medium' ? 'selected' : '' }}>Medium (Default)</option>
                                <option value="high" {{ ($preferences['alert_sensitivity'] ?? '') == 'high' ? 'selected' : '' }}>High (All changes)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Areas of Interest</label>
                            <div class="space-y-2">
                                @foreach($sensors as $sensor)
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="monitored_sensors[]" value="{{ $sensor->id }}" 
                                           class="rounded border-gray-300"
                                           {{ in_array($sensor->id, $preferences['monitored_sensors'] ?? []) ? 'checked' : '' }}>
                                    <span class="text-sm">{{ $sensor->name }} ({{ $sensor->location }})</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Notification Methods -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Notification Methods</h3>
                    <form id="notificationMethodsForm" class="space-y-4">
                        @csrf
                        <div>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="email_notifications" class="rounded border-gray-300"
                                       {{ $preferences['email_notifications'] ?? true ? 'checked' : '' }}>
                                <span class="text-sm font-medium">Email Notifications</span>
                            </label>
                            <div class="mt-2 ml-6">
                                <input type="email" name="notification_email" 
                                       class="block w-full rounded-lg border border-gray-200 px-4 py-2 text-sm"
                                       placeholder="Your email address"
                                       value="{{ $preferences['notification_email'] ?? auth()->user()->email }}">
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="push_notifications" class="rounded border-gray-300"
                                       {{ $preferences['push_notifications'] ?? false ? 'checked' : '' }}>
                                <span class="text-sm font-medium">Browser Push Notifications</span>
                            </label>
                            <p class="text-sm text-gray-500 mt-1 ml-6">Receive alerts even when you're not on the site</p>
                        </div>

                        <div>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="mobile_notifications" class="rounded border-gray-300"
                                       {{ $preferences['mobile_notifications'] ?? false ? 'checked' : '' }}>
                                <span class="text-sm font-medium">Mobile App Notifications</span>
                            </label>
                            <p class="text-sm text-gray-500 mt-1 ml-6">Requires ClearSky mobile app installation</p>
                        </div>

                        <div class="pt-4">
                            <label class="block text-sm font-medium mb-2">Quiet Hours</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Start Time</label>
                                    <input type="time" name="quiet_hours_start" 
                                           class="block w-full rounded-lg border border-gray-200 px-4 py-2"
                                           value="{{ $preferences['quiet_hours_start'] ?? '22:00' }}">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">End Time</label>
                                    <input type="time" name="quiet_hours_end" 
                                           class="block w-full rounded-lg border border-gray-200 px-4 py-2"
                                           value="{{ $preferences['quiet_hours_end'] ?? '07:00' }}">
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">No notifications will be sent during quiet hours</p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Save Button -->
            <div class="mt-6 flex justify-end">
                <button onclick="savePreferences()" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                    Save Preferences
                </button>
            </div>
        </div>
    </div>

    <script>
        function savePreferences() {
            const alertPreferences = Object.fromEntries(new FormData(document.getElementById('alertPreferencesForm')));
            const notificationMethods = Object.fromEntries(new FormData(document.getElementById('notificationMethodsForm')));
            
            const preferences = {
                ...alertPreferences,
                ...notificationMethods
            };

            fetch('/alerts/preferences', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(preferences)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Preferences saved successfully');
                } else {
                    alert('Failed to save preferences');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to save preferences');
            });
        }

        // Handle push notification permission
        document.querySelector('input[name="push_notifications"]').addEventListener('change', function(e) {
            if (this.checked) {
                Notification.requestPermission().then(function(permission) {
                    if (permission !== 'granted') {
                        e.target.checked = false;
                        alert('Please allow notifications to enable this feature');
                    }
                });
            }
        });
    </script>
</x-app-layout>