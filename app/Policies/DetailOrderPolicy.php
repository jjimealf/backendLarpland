<?php

namespace App\Policies;

use App\Models\Detail_Order;
use App\Models\User;

class DetailOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    public function view(User $user, Detail_Order $detailOrder): bool
    {
        return $user->isAdmin() || $detailOrder->order?->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user !== null;
    }

    public function update(User $user, Detail_Order $detailOrder): bool
    {
        return $user->isAdmin() || $detailOrder->order?->user_id === $user->id;
    }

    public function delete(User $user, Detail_Order $detailOrder): bool
    {
        return $user->isAdmin() || $detailOrder->order?->user_id === $user->id;
    }
}
