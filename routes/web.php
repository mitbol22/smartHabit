<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('habits', HabitController::class);
    Route::post('/habits/{habit}/check-in', [App\Http\Controllers\HabitLogController::class, 'store'])->name('habits.check-in');
    Route::get('/analytics', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/penalties', [App\Http\Controllers\PenaltyController::class, 'index'])->name('penalties.index');
});

require __DIR__.'/auth.php';
