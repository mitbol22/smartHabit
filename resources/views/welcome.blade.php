<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>HabitForge - Build discipline, not just habits.</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,800&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#FDFDFC] text-gray-900">
        <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-black selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="flex items-center justify-between py-10">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-dot text-3xl text-black"></i>
                        <span class="font-bold text-2xl tracking-tighter">HabitForge</span>
                    </div>
                    @if (Route::has('login'))
                        <nav class="flex items-center space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="font-bold text-black hover:opacity-70 transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="font-bold text-gray-500 hover:text-black transition">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-black text-white px-6 py-2 rounded-xl font-bold hover:bg-gray-800 transition shadow-lg">Get Started</a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </header>

                <main class="mt-20 text-center">
                    <div class="inline-block px-4 py-1.5 mb-6 bg-gray-100 rounded-full">
                        <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Revolutionizing Habit Formation</span>
                    </div>
                    <h1 class="text-6xl md:text-8xl font-black tracking-tighter mb-8 leading-[0.9]">
                        Build discipline, <br><span class="text-gray-300 italic">not just habits.</span>
                    </h1>
                    <p class="text-xl text-gray-500 max-w-2xl mx-auto mb-12 leading-relaxed">
                        The ultimate accountability partner. Track daily habits, maintain streaks, and stay motivated with our unique reward and penalty system.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('register') }}" class="w-full sm:w-auto bg-black text-white px-10 py-4 rounded-2xl font-bold text-lg hover:bg-gray-800 transition shadow-2xl">
                            Start for Free
                        </a>
                    </div>
                </main>

                <footer class="mt-40 py-10 border-t border-gray-100 text-center">
                    <p class="text-sm text-gray-400 font-medium">
                        &copy; {{ date('Y') }} HabitForge. Built with Laravel 12.
                    </p>
                </footer>
            </div>
        </div>
    </body>
</html>
