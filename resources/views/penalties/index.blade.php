<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penalty History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Penalty Summary -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                    <div class="bg-blue-100 p-4 rounded-lg shadow-sm">
                        <p class="text-sm text-blue-700">Penalties This Week</p>
                        <p class="text-xl font-bold text-blue-900">{{ $penalties->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])->count() }}</p>
                    </div>
                    <div class="bg-red-100 p-4 rounded-lg shadow-sm">
                        <p class="text-sm text-red-700">Weekly Points Lost</p>
                        <p class="text-xl font-bold text-red-900">{{ $weeklyPointsLost }}</p>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-lg shadow-sm">
                        <p class="text-sm text-purple-700">Total Points Lost</p>
                        <p class="text-xl font-bold text-purple-900">{{ $totalPointsLost }}</p>
                    </div>
                </div>

                <h3 class="text-xl font-semibold text-gray-800 mb-4">Detailed Penalty History</h3>
                @forelse ($penalties as $penalty)
                    <div class="border-b border-gray-200 py-4 flex justify-between items-center">
                        <div>
                            <p class="text-lg font-medium text-gray-900">{{ $penalty->penalty_type }} (-{{ $penalty->penalty_value }} points)</p>
                            <p class="text-sm text-gray-600">Reason: {{ $penalty->reason }}</p>
                            <p class="text-xs text-gray-500">Habit: {{ $penalty->habitLog->habit->title }} on {{ $penalty->created_at->format('Y-m-d') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-10">No penalties recorded yet. Keep up the good work!</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>