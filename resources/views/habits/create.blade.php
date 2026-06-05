<x-app-layout>
    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm mx-4 sm:mx-0">
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Create New Habit</h1>
                    <p class="text-gray-500 mt-1">Add a new habit to track and build discipline</p>
                </div>

                <form action="{{ route('habits.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Habit Name -->
                        <div>
                            <label for="title" class="text-sm font-bold text-gray-700 block mb-2">Habit Name *</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                class="w-full bg-gray-50 border-gray-200 rounded-xl focus:border-black focus:ring-black px-4 py-3"
                                placeholder="e.g. Morning Workout" required>
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="text-sm font-bold text-gray-700 block mb-2">Description</label>
                            <textarea name="description" id="description" rows="3" 
                                class="w-full bg-gray-50 border-gray-200 rounded-xl focus:border-black focus:ring-black px-4 py-3"
                                placeholder="Optional description of your habit">{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Frequency -->
                            <div>
                                <label for="frequency" class="text-sm font-bold text-gray-700 block mb-2">Frequency</label>
                                <select name="frequency" id="frequency" 
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl focus:border-black focus:ring-black px-4 py-3">
                                    <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                </select>
                            </div>

                            <!-- Time (Placeholder/Simulated as per mockup) -->
                            <div>
                                <label for="start_date" class="text-sm font-bold text-gray-700 block mb-2">Start Date</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', date('Y-m-d')) }}" 
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl focus:border-black focus:ring-black px-4 py-3">
                                @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                            <!-- Target Count -->
                            <div>
                                <label for="target_count" class="text-sm font-bold text-gray-700 block mb-2">Target Count</label>
                                <input type="number" name="target_count" id="target_count" value="{{ old('target_count', 1) }}" min="1"
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl focus:border-black focus:ring-black px-4 py-3">
                                @error('target_count') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-widest">Times per frequency</p>
                            </div>

                            <!-- Priority -->
                            <div>
                                <label for="priority" class="text-sm font-bold text-gray-700 block mb-2">Priority</label>
                                <select name="priority" id="priority" 
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl focus:border-black focus:ring-black px-4 py-3">
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                                @error('priority') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                            <!-- Active Status -->
                            <div class="flex items-center space-x-3 h-[50px]">
                                <span class="text-sm font-bold text-gray-700">Active</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="active" value="1" class="sr-only peer" checked disabled>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-black"></div>
                                </label>
                            </div>
                        </div>

                        <div class="pt-8 border-t border-gray-50 flex items-center justify-end space-x-4">
                            <a href="{{ route('habits.index') }}" class="text-sm font-bold text-gray-500 hover:text-black transition">Cancel</a>
                            <button type="submit" class="bg-black text-white px-10 py-3 rounded-xl font-bold hover:bg-gray-800 transition shadow-lg">
                                Create
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
