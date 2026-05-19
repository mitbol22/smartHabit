<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'frequency',
        'target_count',
        'start_date',
        'end_date',
    ];
    public function logs()
    {
        return $this->hasMany(HabitLog::class);
    }
}
