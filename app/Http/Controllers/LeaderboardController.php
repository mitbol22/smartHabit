<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index()
    {
        // Get users ordered by total points
        $users = User::withSum('points as total_points', 'points')
            ->orderByDesc('total_points')
            ->get();

        // Calculate rank for current user
        $currentUserRank = $users->search(function ($user) {
            return $user->id === auth()->id();
        });
        
        $currentUserRank = $currentUserRank !== false ? $currentUserRank + 1 : 'N/A';

        return view('leaderboard.index', [
            'users' => $users,
            'currentUserRank' => $currentUserRank,
        ]);
    }
}
