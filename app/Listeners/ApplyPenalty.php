<?php

namespace App\Listeners;

use App\Events\HabitMissed;
use App\Models\Penalty;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ApplyPenalty
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(HabitMissed $event): void
    {
        $log = $event->habitLog;

        Penalty::create([
            'user_id' => $log->habit->user_id,
            'habit_log_id' => $log->id,
            'penalty_type' => 'points_deduction',
            'penalty_value' => 10,
            'reason' => 'Missed habit',
        ]);
    }
}
