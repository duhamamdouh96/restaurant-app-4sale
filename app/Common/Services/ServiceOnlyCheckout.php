<?php

namespace App\Common\Services;

use App\Models\Order;
use Carbon\Carbon;

class ServiceOnlyCheckout implements CheckoutService
{
    public function checkout(string $method = 'serviceOnly', Order $order)
    {
        $order->update([
            'paid_amount' => $this->calculateTotal($order->total),
            'paid_at' => Carbon::now(),
            'checkout_method' => 'serviceOnly',
            'service_percentage' => $this->servicePercentage(),
        ]);

        return $order;
    }

    protected function calculateTotal(float $total)
    {
        return $total + $this->calculateService($total);
    }

    protected static function servicePercentage()
    {
        return 20;
    }

    protected function calculateService($total) : float
    {
        return (float) ($this->servicePercentage() / 100) * $total;
    }
}
