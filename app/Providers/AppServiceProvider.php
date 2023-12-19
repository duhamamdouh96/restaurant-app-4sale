<?php

namespace App\Providers;

use App\Common\Enums\CheckoutMethod;
use App\Common\Enums\Message;
use App\Common\Services\CheckoutService;
use App\Common\Services\ServiceOnlyCheckout;
use App\Common\Services\TaxAndServiceCheckout;
use App\Exceptions\UnsupportedCheckoutMethodException;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CheckoutService::class, function () {
            switch (request()->checkout_method ?? 'taxAndService') {
                case CheckoutMethod::WITH_TAX_AND_SERVICE:
                    return new TaxAndServiceCheckout;
                case CheckoutMethod::WITH_SERVICE_ONLY:
                    return new ServiceOnlyCheckout;
                default:
                    throw new UnsupportedCheckoutMethodException();
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
