<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitLog extends Model
{
    protected $fillable = [
        'habit_id',
        'date',
        'status',
        'streak_count',
    ];

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }

    public function points()
    {
        return $this->hasMany(Point::class);
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }
}
