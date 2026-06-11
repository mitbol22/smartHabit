<?php

namespace Database\Factories;

use App\Models\Penalty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Penalty>
 */
class PenaltyFactory extends Factory
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
            'penalty_type' => fake()->randomElement(['points_deduction', 'streak_reset', 'warning']),
            'penalty_value' => fake()->numberBetween(5, 20),
            'reason' => fake()->sentence(),
        ];
    }
}
