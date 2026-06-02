<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8 px-4 sm:px-0">
                <h1 class="text-3xl font-bold text-gray-900">Leaderboard</h1>
                <p class="text-gray-600 mt-1">See how you rank against other users</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 sm:px-0">
                <div class="lg:col-span-2 space-y-8">
                    <!-- Your Ranking Card -->
                    <div class="bg-black text-white p-8 rounded-3xl shadow-xl flex items-center justify-between">
                        <div class="flex items-center space-x-6">
                            <div class="text-5xl font-black opacity-50">#{{ $currentUserRank }}</div>
                            <div>
                                <h3 class="text-2xl font-bold">{{ Auth::user()->name }} <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full ml-2 uppercase tracking-widest font-bold">You</span></h3>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span class="text-sm font-medium text-gray-400"><i class="fa-solid fa-star text-yellow-400 mr-1"></i> {{ Auth::user()->points_sum_points ?? 0 }} pts</span>
                                    <span class="text-sm font-medium text-gray-400"><i class="fa-solid fa-fire text-orange-400 mr-1"></i> 0 day streak</span>
                                </div>
                            </div>
                        </div>
                        <i class="fa-solid fa-ranking-star text-4xl text-white/10"></i>
                    </div>

                    <!-- Top Users List -->
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-900">Top Users</h2>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Global Ranking</span>
                        </div>
                        
                        <div class="divide-y divide-gray-50">
                            @foreach ($users as $index => $user)
                                <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition {{ $user->id == auth()->id() ? 'bg-gray-50' : '' }}">
                                    <div class="flex items-center space-x-6">
                                        <span class="w-8 text-center font-black text-gray-400 {{ $index < 3 ? 'text-black text-lg' : '' }}">
                                            @if($index == 0) 🥇 @elseif($index == 1) 🥈 @elseif($index == 2) 🥉 @else {{ $index + 1 }} @endif
                                        </span>
                                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center font-bold text-gray-600">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900">{{ $user->name }} @if($user->id == auth()->id()) <span class="text-[10px] bg-black text-white px-2 py-0.5 rounded-full ml-1 uppercase font-bold">You</span> @endif</h4>
                                            <p class="text-xs text-gray-400 font-medium">Rank #{{ $index + 1 }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-bold text-gray-900">{{ $user->total_points ?? 0 }}</span>
                                        <span class="text-xs font-bold text-gray-400 ml-1">pts</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Info Sidebar -->
                <div class="space-y-6">
                    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">How Rankings Work</h3>
                        <ul class="space-y-6">
                            <li class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-star text-yellow-400 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Total Points</p>
                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">Users are ranked primarily by their total accumulated points.</p>
                                </div>
                            </li>
                            <li class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-fire text-orange-400 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Streak Bonus</p>
                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">In case of a tie, streak length determines the higher ranking.</p>
                                </div>
                            </li>
                            <li class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-check-double text-blue-400 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Consistency</p>
                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">Complete habits daily to earn points and climb the leaderboard.</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100 text-center">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Your Progress</p>
                        <p class="text-sm text-gray-600 leading-relaxed mb-6">You need <span class="text-black font-bold">150 more points</span> to reach the next rank!</p>
                        <a href="{{ route('dashboard') }}" class="block w-full bg-white border border-gray-200 text-black font-bold py-3 rounded-xl hover:bg-gray-100 transition shadow-sm">
                            Keep Going
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
