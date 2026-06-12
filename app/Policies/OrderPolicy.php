<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{

    public function before(?User $user, string $ability): bool|null
    {
        if ($user === null) {
            return false; // deixa o middleware auth tratar
        }
        return null; // continua para o método específico
    }
    public function viewAny(User $user): bool
    {
        return $user->isCustomer() || $user->isEmployee() || $user->isAdmin();
    }

    public function view(User $user, Order $order): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->isEmployee()) {
            return in_array($order->status, ['pending', 'processing']);
        }
        if ($user->isCustomer()) {
            return $order->customer_id === $user->id;
        }
        return false;
    }

    public function close(User $user, Order $order): bool
    {
        return $user->isEmployee() || $user->isAdmin();
    }

    public function cancel(User $user, Order $order): bool
    {
        return $user->isAdmin();
    }

    public function downloadReceipt(User $user, Order $order): bool
    {
        return ($user->isCustomer() && $order->customer_id === $user->id) || $user->isAdmin();
    }
}
