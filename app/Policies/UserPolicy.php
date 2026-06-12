<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    // -------------------------------------------------------------------------
    // UTILIZADORES ADMINISTRATIVOS
    // -------------------------------------------------------------------------

    public function viewAny(User $authUser): bool
    {
        return $authUser->isAdmin();
    }

    public function view(User $authUser, User $user): bool
    {
        return $authUser->isAdmin();
    }

    public function create(User $authUser): bool
    {
        return $authUser->isAdmin();
    }

    public function update(User $authUser, User $user): bool
    {
        return $authUser->isAdmin();
    }

    public function delete(User $authUser, User $user): bool
    {
        return $authUser->isAdmin();
    }

    public function toggleBlock(User $authUser, User $user): bool
    {
        return $authUser->isAdmin();
    }

    // -------------------------------------------------------------------------
    // CLIENTES
    // -------------------------------------------------------------------------

    public function viewAnyCustomer(User $authUser): bool
    {
        return $authUser->isAdmin();
    }

    // Admins NÃO podem ver perfil privado de clientes (enunciado)
    public function viewCustomer(User $authUser, User $customer): bool
    {
        return $authUser->isCustomer() && $authUser->id === $customer->id;
    }

    public function toggleBlockCustomer(User $authUser, User $customer): bool
    {
        return $authUser->isAdmin();
    }

    public function deleteCustomer(User $authUser, User $customer): bool
    {
        return $authUser->isAdmin();
    }

    // -------------------------------------------------------------------------
    // PERFIL PRÓPRIO
    // -------------------------------------------------------------------------

    // Funcionários não têm acesso ao próprio perfil (enunciado)
    public function viewOwnProfile(User $authUser, User $user): bool
    {
        return $authUser->isCustomer() && $authUser->id === $user->id;
    }

    public function updateOwnProfile(User $authUser, User $user): bool
    {
        return $authUser->isCustomer() && $authUser->id === $user->id;
    }

    // -------------------------------------------------------------------------
    // SENHA E FOTO
    // -------------------------------------------------------------------------

    public function changePassword(User $authUser, User $user): bool
    {
        return $authUser->id === $user->id;
    }

    public function deletePhoto(User $authUser, User $user): bool
    {
        return $authUser->isAdmin() || $authUser->id === $user->id;
    }
}
