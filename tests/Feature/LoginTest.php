<?php

namespace Tests\Feature;

use App\Common\Enums\Message;
use App\Common\Enums\RouteName;
use App\Models\Customer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function initiate($customer = [])
    {
        return Customer::factory()->create($customer);
    }

    /** @test */
    public function it_sends_customer_token_on_correct_credentials()
    {
        $customer = $this->initiate();

        $response = $this->post(route(RouteName::CUSTOMER_LOGIN), [
            'email' => $customer->email,
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertSee(['data.email' => $customer->email])
            ->assertJsonStructure([
                'token',
                'data' => [
                    'name',
                    'email',
                ],
            ]);
    }

    /** @test */
    public function it_throw_error_wrong_credentials()
    {
        $customer = $this->initiate();

        $response = $this->post(route(RouteName::CUSTOMER_LOGIN), [
            'email' => $customer->email,
            'password' => '123456',
        ]);

        $response->assertStatus(401)
            ->assertJson(function(AssertableJson $json) {
                $json->where('message', Message::WRONG_CREDENTIALS)->etc();
            });
    }

    /** @test */
    public function it_throw_error_empty_email_field()
    {
        $response = $this->post(route(RouteName::CUSTOMER_LOGIN), [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors('email');
    }

    /** @test */
    public function it_validates_case_sensitive_email()
    {
        $customer = $this->initiate([
            'email' => 'customer@mail.com',
        ]);

        $response = $this->post(route(RouteName::CUSTOMER_LOGIN), [
            'email' => 'Customer@mail.com',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertSee(['data.email' => $customer->email])
            ->assertJsonStructure([
                'token',
                'data' => [
                    'name',
                    'email',
                ],
            ]);
    }
}
