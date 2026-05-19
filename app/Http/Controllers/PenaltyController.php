<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PenaltyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $penalties = $user->penalties()->with('habitLog.habit')->get();

        // Calculate Weekly Penalties (points lost)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $weeklyPointsLost = $user->penalties()
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum('penalty_value');

        // Calculate Total Points Lost
        $totalPointsLost = $user->penalties()->sum('penalty_value');

        return view('penalties.index', compact('penalties', 'weeklyPointsLost', 'totalPointsLost'));
    }
}
