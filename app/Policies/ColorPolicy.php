<?php

namespace App\Policies;

use App\Models\Color;
use App\Models\User;

class ColorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEmployee();
    }

    public function view(User $user, Color $color): bool
    {
        return $user->isAdmin() || $user->isEmployee();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Color $color): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Color $color): bool
    {
        return $user->isAdmin();
    }
}
