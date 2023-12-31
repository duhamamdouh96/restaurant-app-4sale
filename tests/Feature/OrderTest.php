<?php

namespace Tests\Feature;

use App\Common\Enums\RouteName;
use Tests\TestCase;
use App\Models\Meal;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class OrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $customer;
    public $order;
    public $reservation;
    public $meals;

    public function setUp(): void
    {
        parent::setUp();
        $this->authenticate();
        $this->customer = auth()->user();
        $this->reservation = $this->initiateReservation();
        $this->meals = $this->initiateMeals();
    }

    public function initiateReservation()
    {
        return Reservation::factory(1, ['customer_id' => auth()->id()])->forTable()->create();
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
        $response = $this->actingAs($this->customer, 'sanctum')->post(route(RouteName::CUSTOMER_ORDER, [
            'reservation_id' => $this->reservation[0]->id,
            'table_id' => $this->reservation[0]->table_id,
            'meals'  => [$this->meals[0]->id, $this->meals[1]->id]
        ]));

        $this->assertDatabaseHas('orders', [
            'reservation_id' => $this->reservation[0]->id,
        ]);

        $response->assertOk()->assertSee(['data.reservation.id' => $this->reservation[0]->id]);
    }
}
