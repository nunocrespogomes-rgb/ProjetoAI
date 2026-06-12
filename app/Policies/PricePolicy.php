<?php

namespace App\Policies;

use App\Models\Price;
use App\Models\User;

class PricePolicy
{
    public function view(User $user, Price $price): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Price $price): bool
    {
        return $user->isAdmin();
    }
}
