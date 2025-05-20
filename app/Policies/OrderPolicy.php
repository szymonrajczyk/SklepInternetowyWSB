<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Allow admins to do everything
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the order.
     */
    public function view(User $user, Order $order)
    {
        if ($user->id === $order->user_id) {
            return true;
        }

        if ($user->isSeller()) {
            if (!$order->relationLoaded('items.product')) {
                $order->load('items.product');
            }

            foreach ($order->items as $item) {
                if ($item->product && $item->product->seller_id === $user->id) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update the order status.
     */
    public function update(User $user, Order $order)
    {

        if ($user->isSeller()) {
            if (!$order->relationLoaded('items.product')) {
                $order->load('items.product');
            }

            foreach ($order->items as $item) {
                if ($item->product && $item->product->seller_id === $user->id) {
                    return true;
                }
            }
        }

        return false;
    }
}
