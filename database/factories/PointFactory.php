<?php

namespace Database\Factories;

use App\Models\Point;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Point>
 */
class PointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'habit_log_id' => \App\Models\HabitLog::factory(),
            'type' => fake()->randomElement(['reward', 'penalty']),
            'points' => fake()->numberBetween(5, 50),
        ];
    }
}
