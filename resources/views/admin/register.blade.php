<x-admin-layout>
    <div class="flex min-h-screen flex-col justify-center px-6 py-12 bg-white">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="text-center text-2xl font-bold leading-9 text-[#212121]">Create Admin Account</h2>
            <p class="mt-2 text-center text-sm text-gray-600">Register to access the admin dashboard</p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
            <div class="bg-white px-8 py-12 rounded-lg">
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
                        <label class="block text-sm font-medium leading-6 text-[#212121] mb-4">Select Role</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <input type="radio" name="role" value="admin" id="admin-role" class="hidden peer" required>
                                <label for="admin-role" class="inline-flex items-center justify-between w-full p-5 bg-white border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-[#212121] hover:bg-gray-50 transition-all duration-200">
                                    <div class="w-full text-center">
                                        <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <div class="font-semibold text-[#212121]">Admin</div>
                                        <div class="text-sm text-gray-500">Standard access</div>
                                    </div>
                                </label>
                            </div>
                            <div>
                                <input type="radio" name="role" value="superadmin" id="superadmin-role" class="hidden peer">
                                <label for="superadmin-role" class="inline-flex items-center justify-between w-full p-5 bg-white border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-[#212121] hover:bg-gray-50 transition-all duration-200">
                                    <div class="w-full text-center">
                                        <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                        <div class="font-semibold text-[#212121]">Super Admin</div>
                                        <div class="text-sm text-gray-500">Full access</div>
                                    </div>
                                </label>
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
