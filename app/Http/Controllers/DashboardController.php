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
        $today = Carbon::today();
        $todayStr = $today->toDateString();

        // Eager load only today's log for each habit and sort by priority
        $allHabits = $user->habits()->with(['logs' => function($query) use ($todayStr) {
            $query->whereDate('date', $todayStr);
        }])
        ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
        ->get();

        $totalHabits = $allHabits->count();
        $pointsBalance = $user->points()->sum('points');

        // Calculate Current Streak
        $currentStreak = 0;
        $lastCompletedLog = $user->habitLogs()
                                 ->where('status', 'completed')
                                 ->latest('date')
                                 ->first();
        if ($lastCompletedLog) {
            $currentStreak = $lastCompletedLog->streak_count;
        }
        
        // Calculate Weekly Penalties
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $weeklyPenalties = $user->penalties()
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum('penalty_value');

        // Filter Habits due Today
        $todayHabits = $allHabits->filter(function ($habit) use ($today) {
            // Check if habit is daily
            if ($habit->frequency === 'daily') {
                return true;
            }
            // Check if habit is weekly and due on this day of the week
            if ($habit->frequency === 'weekly') {
                return Carbon::parse($habit->start_date)->lte($today)
                       && Carbon::parse($habit->start_date)->dayOfWeek === $today->dayOfWeek
                       && ($habit->end_date === null || Carbon::parse($habit->end_date)->gte($today));
            }
            return false;
        });

        // Add today's status to each habit for easier access in view
        $todayHabits->each(function($habit) {
            $habit->today_status = $habit->logs->first()?->status;
        });

        $quotes = [
            ['text' => 'Discipline is the bridge between goals and accomplishment.', 'author' => 'Jim Rohn'],
            ['text' => 'Motivation is what gets you started. Habit is what keeps you going.', 'author' => 'Jim Ryun'],
            ['text' => 'We are what we repeatedly do. Excellence, then, is not an act, but a habit.', 'author' => 'Will Durant'],
            ['text' => 'The secret of your future is hidden in your daily routine.', 'author' => 'Mike Murdock'],
            ['text' => 'It is not what we do once in a while that shapes our lives. It is what we do consistently.', 'author' => 'Anthony Robbins'],
            ['text' => 'Success is the sum of small efforts, repeated day-in and day-out.', 'author' => 'Robert Collier'],
            ['text' => 'Your habits will determine your future.', 'author' => 'Jack Canfield'],
            ['text' => 'Good habits are as addictive as bad habits, and a lot more rewarding.', 'author' => 'Harvey Mackay'],
            ['text' => 'First we make our habits, then our habits make us.', 'author' => 'John Dryden'],
            ['text' => 'Success is a few simple disciplines, practiced every day.', 'author' => 'Jim Rohn'],
        ];

        $randomQuote = $quotes[array_rand($quotes)];
        
        return view('dashboard', compact(
            'totalHabits',
            'currentStreak',
            'pointsBalance',
            'weeklyPenalties',
            'todayHabits',
            'allHabits',
            'randomQuote'
        ));
    }
}