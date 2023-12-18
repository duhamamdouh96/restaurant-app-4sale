<?php

namespace App\Policies;

use App\Exceptions\OrderNotAuthorizedException;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function own(Customer $customer, Order $order)
    {
        if ($order->customer_id != $customer->id) {
            throw new OrderNotAuthorizedException();
        }

        return true;
    }
}
