<?php

namespace Tests\Feature;

use App\Common\Enums\CheckoutMethod;
use Tests\TestCase;
use App\Models\Order;
use App\Models\Meal;
use App\Models\OrderDetail;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class CheckoutTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->authenticate();
        $this->user = auth()->user();
        $this->order = $this->initiate();
        $this->reservation = $this->initiateReservation();
        $this->meals = $this->initiateMeals();
    }

    public function initiate()
    {
        return Order::factory()->for(
            Reservation::factory(1, ['customer_id' => auth()->user()->id,])->forTable(1, 1, ['capacity' => $this->faker->numberBetween(1, 20)])
        )->has(
            OrderDetail::factory()->count(3)->forMeal()
        )->create([
            'customer_id' => auth()->user()->id,
        ]);
    }

    public function initiateReservation()
    {
        return
            Reservation::factory(1, ['customer_id' => auth()->user()->id,])
                ->forTable(1, 1, ['capacity' => $this->faker->numberBetween(1, 20)])
            ->create();
    }

    public function initiateMeals()
    {
        return Meal::factory(20, [
            'available_quantity' => $this->faker->numberBetween(1, 10)
        ])->create();
    }

    /** @test */
    public function customer_can_create_an_order()
    {
        dd($this->reservation[0]);
        $response = $this->post(route('customers.order', [
            'reservation_id' => $this->reservation[0]->id,
            'table_id' => $this->reservation[0]->table_id,
            'meals'  => $this->meals->take(2)->pluck('id')->toArray(),
        ]));

        $this->assertDatabaseHas('orders', [
            'reservation_id' => $this->reservation[0]->id,
        ]);

        $response->assertOk();
    }

    /** @test */
    public function it_has_empty_bottles_return_false_when_not_send_in_request()
    {
        $this->post(route(
            'customers.checkout',
            [
                'checkout_method' => CheckoutMethod::WITH_TAX_AND_SERVICE,
            ]
        ))->assertJsonFragment(['order_id' => false])->assertOk();
    }
}
