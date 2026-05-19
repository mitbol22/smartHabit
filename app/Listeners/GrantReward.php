<?php

namespace App\Listeners;

use App\Events\HabitCompleted;
use App\Models\Point;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GrantReward
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
    public function handle(HabitCompleted $event): void
    {
        $log = $event->habitLog;

        Point::create([
            'user_id' => $log->habit->user_id,
            'habit_log_id' => $log->id,
            'type' => 'reward',
            'points' => 10,
        ]);
    }
}
