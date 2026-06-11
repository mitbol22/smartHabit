<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create habits for the test user
        $habits = [
            ['title' => 'Morning Run', 'description' => 'Go for a 5km run every morning', 'frequency' => 'daily', 'target_count' => 1, 'priority' => 'high'],
            ['title' => 'Read 30 Pages', 'description' => 'Read at least 30 pages of a book', 'frequency' => 'daily', 'target_count' => 1, 'priority' => 'medium'],
            ['title' => 'Code for 2 Hours', 'description' => 'Spend at least 2 hours coding', 'frequency' => 'daily', 'target_count' => 1, 'priority' => 'high'],
            ['title' => 'Gym Workout', 'description' => 'Full body workout at the gym', 'frequency' => 'weekly', 'target_count' => 3, 'priority' => 'medium'],
            ['title' => 'Meditate', 'description' => '10 minutes of mindfulness meditation', 'frequency' => 'daily', 'target_count' => 1, 'priority' => 'low'],
        ];

        foreach ($habits as $habitData) {
            $habit = \App\Models\Habit::factory()->create(array_merge($habitData, ['user_id' => $user->id]));

            // Seed logs for the last 14 days
            $currentStreak = 0;
            for ($i = 13; $i >= 0; $i--) {
                $date = \Carbon\Carbon::today()->subDays($i);
                
                // Random status with a bias towards 'completed'
                $status = fake()->randomElement(['completed', 'completed', 'completed', 'missed', 'skipped']);
                
                if ($status === 'completed') {
                    $currentStreak++;
                } else {
                    $currentStreak = 0;
                }

                \App\Models\HabitLog::factory()->create([
                    'habit_id' => $habit->id,
                    'date' => $date->toDateString(),
                    'status' => $status,
                    'streak_count' => $currentStreak,
                ]);

                // Optionally seed points/penalties
                if ($status === 'completed') {
                    \App\Models\Point::factory()->create([
                        'user_id' => $user->id,
                        'habit_log_id' => \App\Models\HabitLog::latest('id')->first()->id,
                        'type' => 'reward',
                        'points' => 10,
                    ]);
                } elseif ($status === 'missed') {
                    \App\Models\Penalty::factory()->create([
                        'user_id' => $user->id,
                        'habit_log_id' => \App\Models\HabitLog::latest('id')->first()->id,
                        'penalty_type' => 'points_deduction',
                        'penalty_value' => 5,
                        'reason' => 'Missed habit: ' . $habit->title,
                    ]);
                    \App\Models\Point::factory()->create([
                        'user_id' => $user->id,
                        'habit_log_id' => \App\Models\HabitLog::latest('id')->first()->id,
                        'type' => 'penalty',
                        'points' => -5,
                    ]);
                }
            }
        }

        // Create some random other users with habits
        User::factory(5)->create()->each(function ($u) {
            \App\Models\Habit::factory(3)->create(['user_id' => $u->id])->each(function ($h) use ($u) {
                \App\Models\HabitLog::factory(5)->create([
                    'habit_id' => $h->id,
                    'date' => fake()->dateTimeBetween('-1 week', 'now')->format('Y-m-d'),
                ]);
            });
        });
    }
}
