<?php

namespace Database\Factories;

use App\Models\Habit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Habit>
 */
class HabitFactory extends Factory
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
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'frequency' => fake()->randomElement(['daily', 'weekly']),
            'target_count' => fake()->numberBetween(1, 7),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'start_date' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'end_date' => null,
        ];
    }
}
