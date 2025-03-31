<x-admin-layout>
    <div class="flex min-h-screen flex-col justify-center px-6 py-12 bg-gray-50">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="text-center text-2xl font-bold leading-9 text-[#212121]">Create Admin Account</h2>
            <p class="mt-2 text-center text-sm text-gray-600">Register to access the admin dashboard</p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
            <div class="bg-white px-8 py-12 shadow-lg rounded-lg">
                <form class="space-y-6" action="{{ route('admin.register') }}" method="POST">
                    @csrf
                    
                    <div>
                        <label for="name" class="block text-sm font-medium leading-6 text-[#212121]">Full Name</label>
                        <div class="mt-2">
                            <input id="name" name="name" type="text" required 
                                class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121] focus:border-[#212121] focus:ring-[#212121]">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-[#212121]">Email address</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" required 
                                class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121] focus:border-[#212121] focus:ring-[#212121]">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-[#212121]">Password</label>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" required 
                                class="block w-full rounded-lg border border-gray-200 px-4 py-3 text-[#212121] focus:border-[#212121] focus:ring-[#212121]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-[#212121]">Admin Role</label>
                        <div class="mt-4 space-y-3">
                            <div class="flex items-center">
                                <input type="radio" name="role" value="admin" id="admin-role" required
                                    class="h-4 w-4 border-gray-300 text-[#212121] focus:ring-[#212121]">
                                <label for="admin-role" class="ml-3 block text-sm text-gray-600">Admin</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" name="role" value="superadmin" id="superadmin-role"
                                    class="h-4 w-4 border-gray-300 text-[#212121] focus:ring-[#212121]">
                                <label for="superadmin-role" class="ml-3 block text-sm text-gray-600">Super Admin</label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full rounded-lg bg-[#212121] px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Create Account
                        </button>
                    </div>
                </form>

                <p class="mt-8 text-center text-sm text-gray-600">
                    Already have an account? 
                    <a href="{{ route('admin.login') }}" class="font-medium text-[#212121] hover:text-gray-800">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</x-admin-layout>
