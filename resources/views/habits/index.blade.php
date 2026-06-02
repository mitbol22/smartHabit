<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 px-4 sm:px-0 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Habits</h1>
                    <p class="text-gray-600 mt-1">Manage your daily and weekly habits</p>
                </div>
                <a href="{{ route('habits.create') }}" class="inline-flex items-center justify-center bg-black text-white px-6 py-3 rounded-xl font-bold hover:bg-gray-800 transition shadow-sm space-x-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Add New Habit</span>
                </a>
            </div>

            <!-- Habits Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4 sm:px-0">
                @forelse ($habits as $habit)
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:border-black transition duration-200 group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center group-hover:bg-black group-hover:text-white transition duration-200">
                                <i class="fa-solid fa-bolt text-lg"></i>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-widest px-3 py-1 bg-gray-100 text-gray-500 rounded-full">
                                {{ $habit->frequency }}
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $habit->title }}</h3>
                        <p class="text-gray-500 text-sm mb-6 line-clamp-2 leading-relaxed">
                            {{ $habit->description }}
                        </p>
                        
                        <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                            <div class="flex -space-x-2">
                                <!-- Placeholder for streak visualization or priority -->
                                <span class="text-xs font-bold text-orange-500 bg-orange-50 px-3 py-1 rounded-full">
                                    Priority: High
                                </span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('habits.edit', $habit) }}" class="text-gray-400 hover:text-black transition">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('habits.destroy', $habit) }}" method="POST" onsubmit="return confirm('Delete this habit?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-20 rounded-3xl border border-dashed border-gray-200 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-calendar-plus text-gray-200 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No habits yet.</h3>
                        <p class="text-gray-500 max-w-xs mx-auto mb-8">Create your first habit to get started on your discipline journey!</p>
                        <a href="{{ route('habits.create') }}" class="bg-black text-white px-8 py-3 rounded-xl font-bold hover:bg-gray-800 transition shadow-lg inline-flex items-center space-x-2">
                            <i class="fa-solid fa-plus text-xs"></i>
                            <span>Create Your First Habit</span>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
