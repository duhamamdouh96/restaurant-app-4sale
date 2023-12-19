<?php

namespace Tests\Feature;

use App\Common\Enums\RouteName;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

class RegisterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function customer_can_register_by_email()
    {
        $customer = [
            'name' => 'customer name goes here',
            'email' => 'customer@mail.mail',
            'phone' => '0'.$this->faker->numerify('#########'),
            'password' => Hash::make('123456'),
        ];

        $response = $this->post(route(RouteName::CUSTOMER_REGISTER), $customer);

        $response->assertOk()
            ->assertSee(['data.email' => $customer['email']])
            ->assertJsonStructure([
                'token',
                'data' => [
                    'name',
                    'email'
                ]
            ]);
    }

    /** @test */
    public function it_validates_invalid_format_when_register_by_email()
    {
        $response = $this->post(route(RouteName::CUSTOMER_REGISTER), [
            'name' => 'customer name goes here',
            'email' => 'customer',
            'phone' => '0'.$this->faker->numerify('#########'),
            'password' => Hash::make('123456'),
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors('email');
    }

    /** @test */
    public function it_throw_error_validation_when_empty_email_and_password()
    {
        $response = $this->post(route(RouteName::CUSTOMER_REGISTER), [
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email')
            ->assertJsonValidationErrors('password');
    }
}
