<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HabitLogController extends Controller
{
    public function store(Request $request, Habit $habit)
    {
        // Simple auth check
        if ($habit->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if log for today already exists
        $existingLog = $habit->logs()->whereDate('date', now()->toDateString())->first();
        if ($existingLog) {
            return back()->with('error', 'You have already recorded a status for today.');
        }

        $request->validate([
            'status' => 'required|in:completed,missed,skipped',
        ]);

        $log = $habit->logs()->create([
            'date' => now()->toDateString(),
            'status' => $request->status,
            'streak_count' => 0, // Placeholder
        ]);

        if ($request->status === 'missed') {
            event(new \App\Events\HabitMissed($log));
        } elseif ($request->status === 'completed') {
            event(new \App\Events\HabitCompleted($log));
        }

        return back()->with('success', 'Check-in recorded.');
    }

    public function destroy(Habit $habit)
    {
        if ($habit->user_id !== Auth::id()) {
            abort(403);
        }

        $log = $habit->logs()->whereDate('date', now()->toDateString())->first();

        if ($log) {
            $log->points()->delete();
            $log->penalties()->delete();
            $log->delete();
            return back()->with('success', 'Check-in undone.');
        }

        return back()->with('error', 'No check-in found for today to undo.');
    }
}
