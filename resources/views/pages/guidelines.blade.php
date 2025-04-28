<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold text-[#212121] mb-6">ClearSky Guidelines</h1>
                    
                    <div class="prose max-w-none">
                        <div class="mb-8">
                            <h2 class="text-2xl font-semibold text-[#212121] mb-4">Understanding Air Quality Index (AQI)</h2>
                            <div class="space-y-4">
                                <div class="flex items-center p-3 bg-green-50 rounded-md">
                                    <div class="w-4 h-4 rounded-full bg-green-500 mr-3"></div>
                                    <div>
                                        <p class="font-medium text-green-800">Good (0-50)</p>
                                        <p class="text-sm text-green-600">Air quality is satisfactory; air pollution poses little or no risk.</p>
                                    </div>
                                </div>

                                <div class="flex items-center p-3 bg-yellow-50 rounded-md">
                                    <div class="w-4 h-4 rounded-full bg-yellow-500 mr-3"></div>
                                    <div>
                                        <p class="font-medium text-yellow-800">Moderate (51-100)</p>
                                        <p class="text-sm text-yellow-600">Acceptable air quality, but some pollutants may be a concern for very sensitive individuals.</p>
                                    </div>
                                </div>

                                <div class="flex items-center p-3 bg-orange-50 rounded-md">
                                    <div class="w-4 h-4 rounded-full bg-orange-500 mr-3"></div>
                                    <div>
                                        <p class="font-medium text-orange-800">Unhealthy for Sensitive Groups (101-150)</p>
                                        <p class="text-sm text-orange-600">Members of sensitive groups may experience health effects.</p>
                                    </div>
                                </div>

                                <div class="flex items-center p-3 bg-red-50 rounded-md">
                                    <div class="w-4 h-4 rounded-full bg-red-500 mr-3"></div>
                                    <div>
                                        <p class="font-medium text-red-800">Unhealthy (151+)</p>
                                        <p class="text-sm text-red-600">Everyone may begin to experience health effects.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h2 class="text-2xl font-semibold text-[#212121] mb-4">Health Recommendations</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h3 class="font-semibold text-lg mb-2">When AQI is Good to Moderate</h3>
                                    <ul class="list-disc pl-5 text-gray-600 space-y-2">
                                        <li>It's safe to be active outdoors</li>
                                        <li>Perfect conditions for outdoor activities</li>
                                        <li>Keep monitoring for any changes</li>
                                    </ul>
                                </div>
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h3 class="font-semibold text-lg mb-2">When AQI is Unhealthy</h3>
                                    <ul class="list-disc pl-5 text-gray-600 space-y-2">
                                        <li>Reduce prolonged outdoor activities</li>
                                        <li>Keep windows closed</li>
                                        <li>Use air purifiers indoors</li>
                                        <li>Wear masks when outdoors</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h2 class="text-2xl font-semibold text-[#212121] mb-4">Using the Alert System</h2>
                            <div class="space-y-4">
                                <p class="text-gray-600">Our alert system is designed to keep you informed about air quality changes:</p>
                                <ul class="list-disc pl-5 text-gray-600 space-y-2">
                                    <li>Set up your preferred alert methods (email, push notifications, or SMS)</li>
                                    <li>Customize alert thresholds based on your sensitivity</li>
                                    <li>Choose specific areas to monitor</li>
                                    <li>Enable quiet hours to prevent disturbances during sleep</li>
                                </ul>
                            </div>
                        </div>

                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h2 class="text-2xl font-semibold text-[#212121] mb-4">Need Help?</h2>
                            <p class="text-gray-600">
                                If you have questions about using ClearSky or need technical support, our team is here to help.
                                Contact support at <a href="mailto:support@clearsky.com" class="text-blue-600 hover:text-blue-800">support@clearsky.com</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>