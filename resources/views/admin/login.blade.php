<x-admin-layout>
    <div class="flex min-h-screen flex-col justify-center px-6 py-12 bg-white">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="text-center text-2xl font-bold leading-9 text-[#212121]">Admin Login</h2>
            <p class="mt-2 text-center text-sm text-gray-600">Welcome back to the admin panel</p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
            <div class="bg-white px-8 py-12 rounded-lg">
                <form class="space-y-6" action="{{ route('admin.login') }}" method="POST">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-[#212121]">Email address</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" required 
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
                        <button type="submit" class="w-full rounded-lg bg-[#212121] px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Sign in to Dashboard
                        </button>
                    </div>
                </form>

                <p class="mt-8 text-center text-sm text-gray-600">
                    Not registered yet? 
                    <a href="{{ route('admin.register') }}" class="font-medium text-[#212121] hover:text-gray-800">Create an account</a>
                </p>
            </div>
        </div>
    </div>
</x-admin-layout>
