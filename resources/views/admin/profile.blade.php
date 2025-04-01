<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <h2 class="text-2xl font-bold text-[#212121]">Profile Settings</h2>
            <p class="mt-2 text-gray-600">Manage your account settings and preferences.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Information -->
            <div class="lg:col-span-2 bg-white p-6 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-[#212121] mb-4">Profile Information</h3>
                <form id="profileForm" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" value="{{ auth('admin')->user()->name }}"
                                class="block w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ auth('admin')->user()->email }}"
                                class="block w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm bg-gray-50" readonly>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <input type="text" value="{{ ucfirst(auth('admin')->user()->role) }}"
                            class="block w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm bg-gray-50" readonly>
                    </div>
                    <div>
                        <button type="submit" class="px-4 py-2 bg-[#212121] text-white rounded-lg text-sm">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white p-6 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-[#212121] mb-4">Change Password</h3>
                <form id="passwordForm" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" name="current_password" required
                            class="block w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" name="password" required
                            class="block w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" required
                            class="block w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm">
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-[#212121] text-white rounded-lg text-sm">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            updateProfile(this);
        });

        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            updatePassword(this);
        });

        function updateProfile(form) {
            const formData = new FormData(form);
            fetch('/admin/profile/update', {
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
                    alert('Profile updated successfully');
                } else {
                    alert(data.message || 'Failed to update profile');
                }
            })
            .catch(() => alert('Failed to update profile'));
        }

        function updatePassword(form) {
            const formData = new FormData(form);
            fetch('/admin/profile/password', {
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
                    alert('Password updated successfully');
                    form.reset();
                } else {
                    alert(data.message || 'Failed to update password');
                }
            })
            .catch(() => alert('Failed to update password'));
        }
    </script>
</x-admin-layout>
