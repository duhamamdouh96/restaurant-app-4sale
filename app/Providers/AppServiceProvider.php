<?php

namespace App\Providers;

use App\Common\Enums\CheckoutMethod;
use App\Common\Enums\Message;
use App\Common\Services\CheckoutService;
use App\Common\Services\ServiceOnlyCheckout;
use App\Common\Services\TaxAndServiceCheckout;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CheckoutService::class, function () {
            switch (request()->checkout_method) {
                case CheckoutMethod::WITH_TAX_AND_SERVICE:
                    return new TaxAndServiceCheckout;
                case CheckoutMethod::WITH_SERVICE_ONLY:
                    return new ServiceOnlyCheckout;
                default:
                    throw new \RuntimeException(Message::UNSUPPORTED_CHECKOUT_METHOD);
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
