<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Progress Overview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Key Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-blue-100 p-4 rounded-lg shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-700">Total Completed</p>
                            <p class="text-xl font-bold text-blue-900">{{ $totalCompletedLogs }}</p>
                        </div>
                        <i class="fa-solid fa-check-circle text-blue-500 text-2xl"></i>
                    </div>
                    <div class="bg-red-100 p-4 rounded-lg shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-sm text-red-700">Total Missed</p>
                            <p class="text-xl font-bold text-red-900">{{ $totalMissedLogs }}</p>
                        </div>
                        <i class="fa-solid fa-times-circle text-red-500 text-2xl"></i>
                    </div>
                    <div class="bg-green-100 p-4 rounded-lg shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-700">Success Rate</p>
                            <p class="text-xl font-bold text-green-900">{{ $successRate }}%</p>
                        </div>
                        <i class="fa-solid fa-percent text-green-500 text-2xl"></i>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-lg shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-sm text-yellow-700">Longest Streak</p>
                            <p class="text-xl font-bold text-yellow-900">{{ $longestStreak }} days</p>
                        </div>
                        <i class="fa-solid fa-trophy text-yellow-500 text-2xl"></i>
                    </div>
                </div>

                <!-- 7-Day Habit Completion Trend -->
                <h3 class="text-xl font-semibold text-gray-800 mb-4">7-Day Habit Completion Trend</h3>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-8">
                    <p class="text-gray-500 text-center">Graph placeholder (e.g., using Chart.js or similar)</p>
                    <div class="flex justify-around text-sm mt-4">
                        @foreach($completionTrend as $data)
                            <div class="text-center">
                                <p class="font-medium">{{ $data['date'] }}</p>
                                <p class="text-green-600">C: {{ $data['completed'] }}</p>
                                <p class="text-red-600">M: {{ $data['missed'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Streak Tracker -->
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Streak Tracker</h3>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex justify-around items-center">
                    <div class="text-center">
                        <p class="text-blue-700">Current Streak</p>
                        <p class="text-3xl font-bold text-blue-900">{{ $currentStreak }}</p>
                        <p class="text-sm text-gray-600">days</p>
                    </div>
                    <div class="text-center">
                        <p class="text-blue-700">Longest Streak</p>
                        <p class="text-3xl font-bold text-blue-900">{{ $longestStreak }}</p>
                        <p class="text-sm text-gray-600">days</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>