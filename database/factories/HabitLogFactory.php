<?php

namespace Database\Factories;

use App\Models\HabitLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HabitLog>
 */
class HabitLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'habit_id' => \App\Models\Habit::factory(),
            'date' => fake()->date(),
            'status' => fake()->randomElement(['completed', 'missed', 'skipped']),
            'streak_count' => fake()->numberBetween(0, 10),
        ];
    }
}
