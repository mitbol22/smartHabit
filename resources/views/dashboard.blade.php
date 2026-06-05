<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8 px-4 sm:px-0">
                <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }} 👋</h1>
                <p class="text-gray-600 mt-1">Track your habits and maintain your streak</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10 px-4 sm:px-0">
                <!-- Total Habits -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Habits</p>
                        <p class="text-3xl font-bold text-black mt-1">{{ $totalHabits }}</p>
                        <p class="text-xs text-gray-400 mt-1">Active habits</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-layer-group text-gray-400"></i>
                    </div>
                </div>

                <!-- Current Streak -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Current Streak</p>
                        <p class="text-3xl font-bold text-black mt-1">{{ $currentStreak }} <span class="text-lg font-medium">🔥</span></p>
                        <p class="text-xs text-gray-400 mt-1">Days in a row</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-fire text-orange-400"></i>
                    </div>
                </div>

                <!-- Points Balance -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Points Balance</p>
                        <p class="text-3xl font-bold text-black mt-1">{{ $pointsBalance }}</p>
                        <p class="text-xs text-gray-400 mt-1">Total points</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-star text-yellow-400"></i>
                    </div>
                </div>

                <!-- Weekly Penalties -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Weekly Penalties</p>
                        <p class="text-3xl font-bold text-black mt-1">{{ $weeklyPenalties }}</p>
                        <p class="text-xs text-gray-400 mt-1">Points deducted</p>
                    </div>
                    <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-triangle-exclamation text-red-400"></i>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 sm:px-0">
                <!-- Today's Habits -->
                <div class="lg:col-span-2">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Today's Habits</h2>
                    </div>

                    @forelse ($todayHabits as $habit)
                        <div class="p-5 rounded-2xl border {{ $habit->today_status === 'completed' ? 'border-green-500 bg-green-50' : ($habit->today_status === 'missed' ? 'border-red-500 bg-red-50' : 'bg-white border-gray-100') }} shadow-sm mb-4 flex items-center justify-between hover:border-black transition duration-200 group">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 {{ $habit->today_status === 'completed' ? 'bg-green-500 text-white' : ($habit->today_status === 'missed' ? 'bg-red-500 text-white' : 'bg-gray-50') }} rounded-xl flex items-center justify-center group-hover:bg-black group-hover:text-white transition duration-200">
                                    <i class="fa-solid {{ $habit->today_status === 'completed' ? 'fa-check' : ($habit->today_status === 'missed' ? 'fa-xmark' : 'fa-check') }} text-xs"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $habit->title }}</h3>
                                    <p class="text-sm text-gray-500">{{ $habit->description }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                @if ($habit->today_status)
                                    <form action="{{ route('habits.undo', $habit) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-black transition">Undo</button>
                                    </form>
                                @else
                                    <form action="{{ route('habits.check-in', $habit) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="bg-black text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-gray-800 transition">Complete</button>
                                    </form>
                                    <form action="{{ route('habits.check-in', $habit) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="missed">
                                        <button type="submit" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-gray-200 transition">Miss</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-12 rounded-3xl border border-dashed border-gray-200 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-calendar-day text-gray-300 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">No habits yet. Create one to get started!</p>
                            <a href="{{ route('habits.create') }}" class="inline-block mt-4 bg-black text-white px-6 py-2 rounded-xl font-bold hover:bg-gray-800 transition">Create Habit</a>
                        </div>
                    @endforelse
                </div>

                <!-- Progress Overview -->
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Progress Overview</h2>
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <p class="text-sm font-bold text-gray-900 mb-2">Weekly Progress</p>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-gray-500">Completion Rate</span>
                            <span class="text-xs font-bold text-black">{{ $todayHabits->count() > 0 ? round(($todayHabits->where('today_status', 'completed')->count() / $todayHabits->count()) * 100) : 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 mb-6">
                            <div class="bg-black h-2 rounded-full" style="width: {{ $todayHabits->count() > 0 ? ($todayHabits->where('today_status', 'completed')->count() / $todayHabits->count()) * 100 : 0 }}%"></div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                                        <i class="fa-solid fa-circle-check text-xs"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Completed</span>
                                </div>
                                <span class="text-sm font-bold text-gray-900">{{ $todayHabits->where('today_status', 'completed')->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                                        <i class="fa-solid fa-circle-xmark text-xs"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Missed</span>
                                </div>
                                <span class="text-sm font-bold text-gray-900">{{ $todayHabits->where('today_status', 'missed')->count() }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Tips / Motivational Quote -->
                    <div class="mt-6 bg-black p-6 rounded-3xl text-white">
                        <i class="fa-solid fa-quote-left text-gray-500 text-2xl mb-2"></i>
                        <p class="text-sm font-medium leading-relaxed italic">
                            "{{ $randomQuote['text'] }}"
                        </p>
                        <p class="text-xs text-gray-400 mt-4">— {{ $randomQuote['author'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
