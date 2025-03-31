<x-admin-layout>
    <div class="py-12">
        <!-- Success Message -->
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-6 lg:px-8 mb-4">
                <div class="bg-green-50 text-green-500 p-4 rounded-lg">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold">Admin Dashboard</h1>
                    <p>Welcome to the admin dashboard!</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
