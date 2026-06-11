<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penalty extends Model
{
    use HasFactory;
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
