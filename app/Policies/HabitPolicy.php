<?php

namespace App\Policies;

use App\Models\Habit;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class HabitPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Any authenticated user can view habits
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Habit $habit): bool
    {
        return $user->id === $habit->user_id; // Only the owner can view their habit
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create habits
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Habit $habit): bool
    {
        return $user->id === $habit->user_id; // Only the owner can update their habit
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Habit $habit): bool
    {
        return $user->id === $habit->user_id; // Only the owner can delete their habit
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Habit $habit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Habit $habit): bool
    {
        return false;
    }
}
