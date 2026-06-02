<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8 px-4 sm:px-0">
                <h1 class="text-3xl font-bold text-gray-900">Penalty History</h1>
                <p class="text-gray-600 mt-1">Track missed habits and accountability</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10 px-4 sm:px-0">
                <!-- Penalties This Week -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">This Week</p>
                        <p class="text-3xl font-bold text-black mt-1">{{ $penalties->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])->count() }}</p>
                        <p class="text-xs text-gray-400 mt-1">Penalties issued</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-calendar-week text-gray-400"></i>
                    </div>
                </div>

                <!-- Weekly Points Lost -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between border-l-4 border-l-red-500">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Weekly Lost</p>
                        <p class="text-3xl font-bold text-red-600 mt-1">-{{ $weeklyPointsLost }}</p>
                        <p class="text-xs text-gray-400 mt-1">Points deducted</p>
                    </div>
                    <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-red-400 font-bold">
                        <i class="fa-solid fa-arrow-trend-down"></i>
                    </div>
                </div>

                <!-- Total Lost -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Lost</p>
                        <p class="text-3xl font-bold text-black mt-1">{{ $totalPointsLost }}</p>
                        <p class="text-xs text-gray-400 mt-1">All-time deductions</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-ghost text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Detailed History -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mx-4 sm:mx-0">
                <div class="p-6 border-b border-gray-50">
                    <h3 class="text-xl font-bold text-gray-900">Penalty History</h3>
                </div>
                
                <div class="divide-y divide-gray-50">
                    @forelse ($penalties as $penalty)
                        <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-500">
                                    <i class="fa-solid fa-minus text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ str_replace('_', ' ', ucfirst($penalty->penalty_type)) }}</h4>
                                    <p class="text-sm text-gray-500">
                                        For: <span class="font-medium text-gray-700">{{ $penalty->habitLog->habit->title ?? 'Deleted Habit' }}</span>
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $penalty->created_at->format('M j, Y \a\t g:i A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-black text-red-600">-{{ $penalty->penalty_value }}</span>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Points</p>
                            </div>
                        </div>
                    @empty
                        <div class="p-20 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fa-solid fa-shield-heart text-gray-200 text-4xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">No penalties yet.</h3>
                            <p class="text-gray-500">Keep up the good work! You haven't missed any habits yet.</p>
                        </div>
                    @endforelse
                </div>
                
                @if($penalties->hasPages())
                    <div class="p-6 bg-gray-50">
                        {{ $penalties->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
