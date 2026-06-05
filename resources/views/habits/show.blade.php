<x-app-layout>
    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm mx-4 sm:mx-0">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <a href="{{ route('habits.index') }}" class="text-xs font-bold text-gray-400 uppercase tracking-widest hover:text-black transition flex items-center mb-4">
                            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Habits
                        </a>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $habit->title }}</h1>
                        <p class="text-gray-500 mt-2 leading-relaxed">{{ $habit->description }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('habits.edit', $habit->id) }}" class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 hover:text-black transition">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form action="{{ route('habits.destroy', $habit->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-400 hover:text-red-600 transition">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-center">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Frequency</p>
                        <p class="text-sm font-bold text-gray-900">{{ ucfirst($habit->frequency) }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-center">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Target</p>
                        <p class="text-sm font-bold text-gray-900">{{ $habit->target_count }}x</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-center">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Started</p>
                        <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($habit->start_date)->format('M j') }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-center">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status</p>
                        <p class="text-sm font-bold text-green-600">Active</p>
                    </div>
                </div>

                <!-- Habit Statistics (Simulated for UI) -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900">Statistics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-5 border border-gray-100 rounded-2xl flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center text-orange-500">
                                    <i class="fa-solid fa-fire text-xs"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Current Streak</span>
                            </div>
                            <span class="font-bold text-gray-900">0 days</span>
                        </div>
                        <div class="p-5 border border-gray-100 rounded-2xl flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500">
                                    <i class="fa-solid fa-check-double text-xs"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Completion Rate</span>
                            </div>
                            <span class="font-bold text-gray-900">0%</span>
                        </div>
                    </div>
                </div>

                <div class="mt-10 p-6 {{ $habit->today_status === 'completed' ? 'bg-green-500' : ($habit->today_status === 'missed' ? 'bg-red-500' : 'bg-black') }} rounded-3xl text-white text-center transition-colors duration-300">
                    @if ($habit->today_status)
                        <p class="text-sm font-medium mb-4">You've marked this habit as <strong>{{ $habit->today_status }}</strong> for today.</p>
                        <form action="{{ route('habits.undo', $habit) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-white text-black font-bold py-3 rounded-xl hover:bg-gray-100 transition shadow-lg">
                                Undo Check-in
                            </button>
                        </form>
                    @else
                        <p class="text-sm font-medium mb-4">Ready to complete your habit for today?</p>
                        <div class="flex space-x-3">
                            <form action="{{ route('habits.check-in', $habit) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="w-full bg-white text-black font-bold py-3 rounded-xl hover:bg-gray-100 transition shadow-lg">
                                    Mark as Completed
                                </button>
                            </form>
                            <form action="{{ route('habits.check-in', $habit) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="missed">
                                <button type="submit" class="w-full bg-gray-800 text-white font-bold py-3 rounded-xl hover:bg-gray-700 transition shadow-lg border border-gray-700">
                                    Mark as Missed
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
