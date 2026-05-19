<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Total Completed and Missed (from all habits combined)
        $totalCompletedLogs = $user->habitLogs()->where('status', 'completed')->count();
        $totalMissedLogs = $user->habitLogs()->where('status', 'missed')->count();
        $totalCheckIns = $totalCompletedLogs + $totalMissedLogs;

        $successRate = $totalCheckIns > 0 ? round(($totalCompletedLogs / $totalCheckIns) * 100, 2) : 0;

        // Longest Streak
        $longestStreak = $user->habitLogs()->where('status', 'completed')->max('streak_count') ?? 0;
        
        // Current Streak
        $currentStreak = 0;
        $lastCompletedLog = $user->habitLogs()
                                 ->where('status', 'completed')
                                 ->latest('date')
                                 ->first();
        if ($lastCompletedLog) {
            $currentStreak = $lastCompletedLog->streak_count;
        }

        // 7-Day Habit Completion Trend
        $completionTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $completedOnDate = $user->habitLogs()->where('status', 'completed')->whereDate('date', $date)->count();
            $missedOnDate = $user->habitLogs()->where('status', 'missed')->whereDate('date', $date)->count();
            $completionTrend[] = [
                'date' => $date->format('M d'),
                'completed' => $completedOnDate,
                'missed' => $missedOnDate,
            ];
        }


        return view('analytics.index', compact(
            'totalCompletedLogs',
            'totalMissedLogs',
            'successRate',
            'longestStreak',
            'currentStreak',
            'completionTrend'
        ));
    }
}
