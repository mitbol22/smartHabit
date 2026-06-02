<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HabitForge') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col md:flex-row">
            <!-- Left Side: Branding (Hidden on mobile) -->
            <div class="hidden md:flex md:w-1/2 bg-black text-white p-12 flex-col justify-center items-center text-center">
                <div class="mb-8">
                    <i class="fa-solid fa-circle-dot text-6xl"></i>
                    <h1 class="text-4xl font-bold mt-4 tracking-tight">HabitForge</h1>
                </div>
                <h2 class="text-2xl font-semibold mb-6">Build discipline, not just habits.</h2>
                <div class="max-w-md bg-white/10 p-6 rounded-xl border border-white/20">
                    <p class="text-gray-300 text-lg leading-relaxed">
                        Track your daily habits, maintain streaks, and stay accountable with our unique penalty system.
                    </p>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="w-full md:w-1/2 flex items-center justify-center p-8 bg-white">
                <div class="w-full max-w-md">
                    <div class="md:hidden text-center mb-8">
                        <i class="fa-solid fa-circle-dot text-4xl text-black"></i>
                        <h1 class="text-2xl font-bold mt-2">HabitForge</h1>
                    </div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
