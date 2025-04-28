<x-app-layout>
    <div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center px-4">
        <div class="text-center max-w-2xl">
            <!-- Error Illustration -->
            <div class="mb-8">
                <svg class="mx-auto h-32 w-32 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            <!-- Error Message -->
            <h1 class="text-6xl font-bold text-gray-900 mb-4">500</h1>
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Internal Server Error</h2>
            <p class="text-gray-500 mb-4">Something went wrong on our end. Our team has been notified.</p>
            
            @if(config('app.debug'))
                <div class="mt-6 text-left">
                    <div class="bg-red-50 border border-red-200 rounded-t-lg p-4">
                        <h3 class="text-red-800 font-mono font-bold">{{ $exception }}</h3>
                        <p class="text-red-600 font-mono mt-2">{{ $message }}</p>
                    </div>
                    @if(isset($trace))
                        <div class="bg-gray-800 rounded-b-lg p-4 overflow-x-auto">
                            <pre class="text-gray-200 text-sm font-mono">{{ $trace }}</pre>
                        </div>
                    @endif
                </div>
            @endif
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                <a href="{{ url('/') }}" class="btn btn-primary inline-flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Return Home
                </a>
                <button onclick="window.location.reload()" class="btn btn-outline inline-flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Try Again
                </button>
            </div>
            
            @if(!config('app.debug'))
                <p class="mt-8 text-sm text-gray-500">
                    If this problem persists, please contact support with error code: {{ time() }}
                </p>
            @endif
        </div>
    </div>
</x-app-layout>