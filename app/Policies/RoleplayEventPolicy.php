<?php

namespace App\Policies;

use App\Models\Roleplay_event;
use App\Models\User;

class RoleplayEventPolicy
{
    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    public function view(User $user, Roleplay_event $roleplayEvent): bool
    {
        return $user !== null;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Roleplay_event $roleplayEvent): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Roleplay_event $roleplayEvent): bool
    {
        return $user->isAdmin();
    }
}
