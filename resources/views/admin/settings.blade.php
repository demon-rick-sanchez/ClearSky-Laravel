<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <h2 class="text-2xl font-bold text-[#212121]">System Settings</h2>
            <p class="mt-2 text-gray-600">Configure system-wide settings and parameters.</p>
        </div>

        <!-- Settings Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- General Settings -->
            <div class="bg-white p-6 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-[#212121] mb-4">General Settings</h3>
                <form id="generalSettingsForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">System Name</label>
                        <input type="text" name="system_name" value="{{ $settings['system_name'] ?? 'ClearSky' }}"
                            class="block w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Time Zone</label>
                        <select name="timezone" class="block w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm">
                            @foreach(timezone_identifiers_list() as $timezone)
                                <option value="{{ $timezone }}" {{ ($settings['timezone'] ?? 'UTC') === $timezone ? 'selected' : '' }}>
                                    {{ $timezone }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-[#212121] text-white rounded-lg text-sm">Save Changes</button>
                </form>
            </div>

            <!-- Database Settings -->
            <div class="bg-white p-6 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-[#212121] mb-4">Database Settings</h3>
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Connection Status</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Connected</span>
                        </div>
                        <div class="text-sm text-gray-600">
                            <p>Driver: {{ config('database.default') }}</p>
                            <p>Database: {{ config('database.connections.'.config('database.default').'.database') }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Data Retention Period (days)</label>
                        <input type="number" name="data_retention" value="{{ $settings['data_retention'] ?? 30 }}"
                            class="block w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm">
                    </div>
                    <button onclick="clearCache()" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm">Clear Cache</button>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="bg-white p-6 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-[#212121] mb-4">Security Settings</h3>
                <form id="securitySettingsForm" class="space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="enable_2fa" name="enable_2fa" class="rounded border-gray-300 text-[#212121]"
                                {{ ($settings['enable_2fa'] ?? false) ? 'checked' : '' }}>
                            <label for="enable_2fa" class="ml-2 block text-sm text-gray-700">Enable Two-Factor Authentication</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="force_ssl" name="force_ssl" class="rounded border-gray-300 text-[#212121]"
                                {{ ($settings['force_ssl'] ?? true) ? 'checked' : '' }}>
                            <label for="force_ssl" class="ml-2 block text-sm text-gray-700">Force SSL/HTTPS</label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Session Timeout (minutes)</label>
                            <input type="number" name="session_timeout" value="{{ $settings['session_timeout'] ?? 120 }}"
                                class="block w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm">
                        </div>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-[#212121] text-white rounded-lg text-sm">Update Security</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function saveSettings(formId) {
            const form = document.getElementById(formId);
            const formData = new FormData(form);
            
            fetch('/admin/settings/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Settings updated successfully');
                }
            })
            .catch(error => alert('Failed to update settings'));
        }

        function clearCache() {
            if (!confirm('Are you sure you want to clear the system cache?')) return;

            fetch('/admin/settings/clear-cache', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Cache cleared successfully');
                }
            })
            .catch(error => alert('Failed to clear cache'));
        }

        // Add form submit handlers
        document.getElementById('generalSettingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveSettings('generalSettingsForm');
        });

        document.getElementById('securitySettingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveSettings('securitySettingsForm');
        });
    </script>
</x-admin-layout>
