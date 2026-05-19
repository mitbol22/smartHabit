<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-2xl font-semibold text-gray-800 mb-6">Welcome back, {{ Auth::user()->name }} 👋</h3>

                <!-- Key Stats Section -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-blue-100 p-4 rounded-lg shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-700">Total Habits</p>
                            <p class="text-xl font-bold text-blue-900">{{ $totalHabits }}</p>
                        </div>
                        <i class="fa-solid fa-list-check text-blue-500 text-2xl"></i>
                    </div>
                    <div class="bg-green-100 p-4 rounded-lg shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-700">Current Streak</p>
                            <p class="text-xl font-bold text-green-900">{{ $currentStreak }} days</p>
                        </div>
                        <i class="fa-solid fa-fire text-green-500 text-2xl"></i>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-lg shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-sm text-yellow-700">Points Balance</p>
                            <p class="text-xl font-bold text-yellow-900">{{ $pointsBalance }}</p>
                        </div>
                        <i class="fa-solid fa-star text-yellow-500 text-2xl"></i>
                    </div>
                    <div class="bg-red-100 p-4 rounded-lg shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-sm text-red-700">Weekly Penalties</p>
                            <p class="text-xl font-bold text-red-900">{{ $weeklyPenalties }}</p>
                        </div>
                        <i class="fa-solid fa-hand-fist text-red-500 text-2xl"></i>
                    </div>
                </div>

                <!-- Today's Habits Section -->
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Today's Habits</h3>
                @forelse ($todayHabits as $habit)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4 flex items-center justify-between">
                        <div>
                            <p class="text-lg font-medium text-gray-900">{{ $habit->title }}</p>
                            <p class="text-sm text-gray-600">{{ $habit->description }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <form action="{{ route('habits.check-in', $habit) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded-md text-sm hover:bg-green-600">Complete</button>
                            </form>
                            <form action="{{ route('habits.check-in', $habit) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="missed">
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-md text-sm hover:bg-red-600">Miss</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 mb-6">No habits scheduled for today. <a href="{{ route('habits.create') }}" class="text-blue-600 hover:underline">Create one!</a></p>
                @endforelse

                <!-- All Habits Section (similar to previous habits.index) -->
                <h3 class="text-xl font-semibold text-gray-800 mb-4 mt-8">My Habits</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($allHabits as $habit)
                        <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition">
                            <h3 class="text-xl font-semibold text-gray-800">{{ $habit->title }}</h3>
                            <p class="text-gray-600 text-sm mt-1">{{ $habit->description }}</p>
                            <div class="mt-4 flex justify-between items-center">
                                <span class="text-xs font-semibold px-2 py-1 bg-gray-100 rounded">{{ ucfirst($habit->frequency) }}</span>
                                <div class="flex space-x-2">
                                    <a href="{{ route('habits.show', $habit->id) }}" class="text-blue-500 hover:text-blue-700">View</a>
                                    <a href="{{ route('habits.edit', $habit->id) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                                    <!-- Delete form -->
                                    <form action="{{ route('habits.destroy', $habit->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this habit?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-10 text-gray-500">No habits created yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
