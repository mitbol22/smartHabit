<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLog;
use App\Models\Point;
use App\Models\Penalty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $allHabits = $user->habits;

        $totalHabits = $allHabits->count();
        $pointsBalance = $user->points()->sum('points');

        // Calculate Current Streak
        $currentStreak = 0;
        $lastCompletedLog = $user->habitLogs()
                                 ->where('status', 'completed')
                                 ->latest('date')
                                 ->first();
        if ($lastCompletedLog) {
            $currentStreak = $lastCompletedLog->streak_count; // This uses the streak_count stored in the log
        }
        
        // Calculate Weekly Penalties
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $weeklyPenalties = $user->penalties()
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum('penalty_value');

        // Filter Habits due Today
        $today = Carbon::today();
        $todayHabits = $allHabits->filter(function ($habit) use ($today) {
            // Check if habit is daily
            if ($habit->frequency === 'daily') {
                return true;
            }
            // Check if habit is weekly and due on this day of the week
            if ($habit->frequency === 'weekly') {
                // Ensure it's active for this week and matches the day of week
                return Carbon::parse($habit->start_date)->lte($today) // Habit has started
                       && Carbon::parse($habit->start_date)->dayOfWeek === $today->dayOfWeek // Matches day of week
                       && ($habit->end_date === null || Carbon::parse($habit->end_date)->gte($today)); // Not ended yet
            }
            return false;
        });
        
        return view('dashboard', compact(
            'totalHabits',
            'currentStreak',
            'pointsBalance',
            'weeklyPenalties',
            'todayHabits',
            'allHabits'
        ));
    }
}