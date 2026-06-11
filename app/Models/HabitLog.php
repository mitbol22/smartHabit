<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HabitLog extends Model
{
    use HasFactory;
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
