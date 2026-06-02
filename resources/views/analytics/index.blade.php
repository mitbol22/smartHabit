<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8 px-4 sm:px-0">
                <h1 class="text-3xl font-bold text-gray-900">Progress Overview</h1>
                <p class="text-gray-600 mt-1">Track your habit completion over time</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10 px-4 sm:px-0">
                <!-- Total Completed -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Completed</p>
                        <p class="text-3xl font-bold text-black mt-1">{{ $totalCompletedLogs }}</p>
                        <p class="text-xs text-green-500 mt-1 font-bold">Good job!</p>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-circle-check text-green-400"></i>
                    </div>
                </div>

                <!-- Total Missed -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Missed</p>
                        <p class="text-3xl font-bold text-black mt-1">{{ $totalMissedLogs }}</p>
                        <p class="text-xs text-red-500 mt-1 font-bold">Needs focus</p>
                    </div>
                    <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-circle-xmark text-red-400"></i>
                    </div>
                </div>

                <!-- Success Rate -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Success Rate</p>
                        <p class="text-3xl font-bold text-black mt-1">{{ $successRate }}%</p>
                        <p class="text-xs text-blue-500 mt-1 font-bold">Overall completion</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-percent text-blue-400"></i>
                    </div>
                </div>

                <!-- Longest Streak -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Longest Streak</p>
                        <p class="text-3xl font-bold text-black mt-1">{{ $longestStreak }}</p>
                        <p class="text-xs text-orange-500 mt-1 font-bold">Personal best</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-trophy text-orange-400"></i>
                    </div>
                </div>
            </div>

            <!-- Trend Section -->
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm mb-10 mx-4 sm:mx-0">
                <h3 class="text-xl font-bold text-gray-900 mb-8">7-Day Habit Completion Trend</h3>
                
                <div class="flex flex-col space-y-8">
                    <div class="grid grid-cols-7 gap-4">
                        @foreach($completionTrend as $data)
                            <div class="flex flex-col items-center group">
                                <div class="w-full flex flex-col-reverse space-y-1 space-y-reverse h-40 bg-gray-50 rounded-xl overflow-hidden mb-4 relative">
                                    <div class="bg-black w-full" style="height: {{ ($data['completed'] / max(1, $data['completed'] + $data['missed'])) * 100 }}%"></div>
                                    <div class="bg-gray-200 w-full" style="height: {{ ($data['missed'] / max(1, $data['completed'] + $data['missed'])) * 100 }}%"></div>
                                    
                                    <!-- Tooltip on hover -->
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200 bg-black/5 flex-col text-[10px] font-bold">
                                        <span class="text-black">C: {{ $data['completed'] }}</span>
                                        <span class="text-gray-500">M: {{ $data['missed'] }}</span>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-tighter">{{ \Carbon\Carbon::parse($data['date'])->format('D') }}</span>
                                <span class="text-[10px] font-medium text-gray-400 mt-1">{{ \Carbon\Carbon::parse($data['date'])->format('M j') }}</span>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="flex justify-center space-x-6 pt-4 border-t border-gray-50">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-black rounded-full"></div>
                            <span class="text-xs font-bold text-gray-600">Completed</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-gray-200 rounded-full"></div>
                            <span class="text-xs font-bold text-gray-600">Missed</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Streak Tracker Section -->
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm mx-4 sm:mx-0">
                <h3 class="text-xl font-bold text-gray-900 mb-8">Streak Tracker</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="p-6 bg-orange-50 rounded-2xl flex items-center space-x-6 border border-orange-100">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-fire text-3xl text-orange-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-orange-600 uppercase tracking-wider">Current Streak</p>
                            <p class="text-4xl font-black text-orange-700">{{ $currentStreak }} <span class="text-lg">days</span></p>
                            <p class="text-xs text-orange-400 mt-1 font-medium">Start a new streak today!</p>
                        </div>
                    </div>
                    
                    <div class="p-6 bg-gray-50 rounded-2xl flex items-center space-x-6 border border-gray-100">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-crown text-3xl text-yellow-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Best Streak</p>
                            <p class="text-4xl font-black text-gray-700">{{ $longestStreak }} <span class="text-lg">days</span></p>
                            <p class="text-xs text-gray-400 mt-1 font-medium">Your all-time record</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
