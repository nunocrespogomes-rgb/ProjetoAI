<?php

namespace App\Policies;

use App\Models\TshirtImage;
use App\Models\User;

class CustomTshirtImagePolicy
{
    /**
     * Apenas clientes podem gerir imagens personalizadas.
     */
    public function viewAny(User $user): bool
    {
        return $user->isCustomer();
    }

    public function create(User $user): bool
    {
        return $user->isCustomer();
    }

    /**
     * Apenas o cliente dono da imagem pode ver, editar, apagar ou aceder ao ficheiro.
     */
    public function view(User $user, TshirtImage $tshirtImage): bool
    {
        return $user->isCustomer() && $user->id === $tshirtImage->customer_id;
    }

    public function update(User $user, TshirtImage $tshirtImage): bool
    {
        return $user->isCustomer() && $user->id === $tshirtImage->customer_id;
    }

    public function delete(User $user, TshirtImage $tshirtImage): bool
    {
        return $user->isCustomer() && $user->id === $tshirtImage->customer_id;
    }
    public function file(User $user, TshirtImage $tshirt): bool
    {
        if ($user->isAdmin() || $user->isEmployee()) {
            return true;
        }

        return $user->isCustomer() && (int) $tshirt->customer_id === (int) $user->id;
    }

}
