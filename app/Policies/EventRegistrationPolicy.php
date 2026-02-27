<?php

namespace App\Policies;

use App\Models\Event_registration;
use App\Models\User;

class EventRegistrationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    public function view(User $user, Event_registration $eventRegistration): bool
    {
        return $user->isAdmin() || $eventRegistration->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user !== null;
    }

    public function update(User $user, Event_registration $eventRegistration): bool
    {
        return $user->isAdmin() || $eventRegistration->user_id === $user->id;
    }

    public function delete(User $user, Event_registration $eventRegistration): bool
    {
        return $user->isAdmin() || $eventRegistration->user_id === $user->id;
    }
}
