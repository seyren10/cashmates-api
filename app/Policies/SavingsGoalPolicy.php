<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\SavingsGoal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SavingsGoalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SavingsGoal $savingsGoal): bool
    {
        return $savingsGoal->group->users->contains($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Group $group): bool
    {
        return $group->users()
            ->wherePivot('role', 'owner')
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SavingsGoal $savingsGoal): bool
    {
        return $savingsGoal->group->users()
            ->wherePivot('role', 'owner')
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SavingsGoal $savingsGoal): bool
    {
        return $this->update($user, $savingsGoal);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SavingsGoal $savingsGoal): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SavingsGoal $savingsGoal): bool
    {
        return false;
    }
}
