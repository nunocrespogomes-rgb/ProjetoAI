<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    public function viewAny(User $user)
    {
        return in_array(strtoupper(trim($user->user_type)), ['C', 'F', 'A']);
    }

    public function view(User $user, Order $order)
    {
        $userType = strtoupper(trim($user->user_type));
        if ($userType === 'A') {
            return true;
        }
        if ($userType === 'C') {
            return $user->id === $order->customer_id;
        }
        if ($userType === 'F') {
            $orderStatus = strtolower(trim($order->status));
            return in_array($orderStatus, ['pending', 'processing', 'closed', 'paga', 'em_processamento', 'paid']);
        }
        return false;
    }

    public function close(User $user, Order $order)
    {
        return in_array(strtoupper(trim($user->user_type)), ['F', 'A']);
    }

    public function cancel(User $user, Order $order)
    {
        return strtoupper(trim($user->user_type)) === 'A';
    }

    public function downloadReceipt(User $user, Order $order)
    {
        if (strtoupper(trim($user->user_type)) === 'A') {
            return true;
        }
        return strtoupper(trim($user->user_type)) === 'C' && $user->id === $order->customer_id;
    }
}