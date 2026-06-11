<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Habit extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'frequency',
        'target_count',
        'priority',
        'start_date',
        'end_date',
    ];
    public function logs()
    {
        return $this->hasMany(HabitLog::class);
    }
}
