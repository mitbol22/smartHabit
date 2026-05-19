<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Habits') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <div class="text-lg font-medium text-gray-900">Total Points: <span class="text-blue-600 font-bold">{{ $points }}</span></div>
                    <a href="{{ route('habits.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Create New Habit</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($habits as $habit)
                        <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition">
                            <h3 class="text-xl font-semibold text-gray-800">{{ $habit->title }}</h3>
                            <p class="text-gray-600 text-sm mt-1">{{ $habit->description }}</p>
                            <div class="mt-4 flex justify-between items-center">
                                <span class="text-xs font-semibold px-2 py-1 bg-gray-100 rounded">{{ ucfirst($habit->frequency) }}</span>
                                <div class="flex space-x-2">
                                    <a href="{{ route('habits.show', $habit->id) }}" class="text-blue-500 hover:text-blue-700">View</a>
                                    <a href="{{ route('habits.edit', $habit->id) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-10 text-gray-500">No habits yet. Start by creating one!</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>