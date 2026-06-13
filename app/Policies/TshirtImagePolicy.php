<?php

namespace App\Policies;

use App\Models\TshirtImage;
use App\Models\User;

class TshirtImagePolicy
{

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEmployee() || $user->isCustomer();
    }

    public function view(User $user, TshirtImage $tshirt): bool
    {
        return $user->isAdmin() || $user->isEmployee();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, TshirtImage $tshirt): bool
    {


            return  $user->isAdmin() && $tshirt->customer_id === null;



    }

    public function delete(User $user, TshirtImage $tshirt): bool
    {
        return  $user->isAdmin() && $tshirt->customer_id === null;
    }


}
