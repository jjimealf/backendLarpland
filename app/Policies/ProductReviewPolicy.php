<?php

namespace App\Policies;

use App\Models\Product_Review;
use App\Models\User;

class ProductReviewPolicy
{
    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    public function view(User $user, Product_Review $productReview): bool
    {
        return $user !== null;
    }

    public function create(User $user): bool
    {
        return $user !== null;
    }

    public function update(User $user, Product_Review $productReview): bool
    {
        return $user->isAdmin() || $productReview->user_id === $user->id;
    }

    public function delete(User $user, Product_Review $productReview): bool
    {
        return $user->isAdmin() || $productReview->user_id === $user->id;
    }
}
