<?php

namespace App\Common\Services;

use App\Models\Order;
use Carbon\Carbon;

class TaxAndServiceCheckout implements CheckoutService
{
    public function checkout(string $method = 'taxAndService', Order $order)
    {
        $order->update([
            'paid_amount' => $this->calculateTotal($order->total),
            'paid_at' => Carbon::now(),
        ]);

        return $order;
    }

    protected function calculateTotal(float $total)
    {
        return $total + $this->calculateTax($total) + $this->calculateService($total);
    }

    protected static function taxPercentage()
    {
        return 14;
    }

    protected static function servicePercentage()
    {
        return 20;
    }

    protected function calculateTax($total) : float
    {
        return (float) ($this->taxPercentage() / 100) * $total;
    }

    protected function calculateService($total) : float
    {
        return (float) ($this->servicePercentage() / 100) * $total;
    }
}
