<?php

namespace Tests;

use App\Models\Customer;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function authenticate($customer = null)
    {
        if (!$customer) {
            $customer = Customer::factory()->create();
        }

        return $this->actingAs($customer, 'customer');
    }
}
