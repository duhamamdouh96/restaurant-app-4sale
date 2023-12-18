<?php

namespace App\Common\Services;

use App\Models\Order;

interface CheckoutService
{
    public function checkout(string $method, Order $order);
}
