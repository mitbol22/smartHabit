<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Habit Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">{{ $habit->title }}</h3>
                <p class="text-gray-600 mb-6">{{ $habit->description }}</p>

                <div class="mb-4">
                    <span class="font-medium text-gray-700">Frequency:</span> {{ ucfirst($habit->frequency) }}
                </div>
                <div class="mb-4">
                    <span class="font-medium text-gray-700">Target Count:</span> {{ $habit->target_count }}
                </div>
                <div class="mb-4">
                    <span class="font-medium text-gray-700">Start Date:</span> {{ \Carbon\Carbon::parse($habit->start_date)->format('Y-m-d') }}
                </div>
                @if ($habit->end_date)
                    <div class="mb-6">
                        <span class="font-medium text-gray-700">End Date:</span> {{ \Carbon\Carbon::parse($habit->end_date)->format('Y-m-d') }}
                    </div>
                @endif

                <div class="flex justify-end">
                    <a href="{{ route('habits.edit', $habit->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 mr-2">Edit Habit</a>
                    <form action="{{ route('habits.destroy', $habit->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this habit?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Delete Habit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>