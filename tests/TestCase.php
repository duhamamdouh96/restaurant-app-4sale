<?php

namespace Tests;

use App\Models\Customer;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeaders([
            'Accept' => 'application/json',
        ]);
    }

    protected function authenticate($customer = null)
    {
        if (!$customer) {
            $customer = Customer::factory()->create();
        }

        return Sanctum::actingAs($customer);
    }
}
