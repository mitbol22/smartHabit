<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    protected $fillable = [
        'user_id',
        'habit_log_id',
        'penalty_type',
        'penalty_value',
        'reason',
    ];

    public function habitLog()
    {
        return $this->belongsTo(HabitLog::class);
    }
}
