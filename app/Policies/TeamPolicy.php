<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Team $team): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Team $team): bool
    {
        return false;
    }
    public function view(User $user, Team $team)
    {
        return $user->id === $team->user_id;
    }

    public function update(User $user, Team $team)
    {
        return $user->id === $team->user_id;
    }

    public function delete(User $user, Team $team)
    {
        return $user->id === $team->user_id;
    }
}
