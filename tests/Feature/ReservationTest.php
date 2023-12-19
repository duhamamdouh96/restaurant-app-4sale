<?php

namespace Tests\Feature;

use App\Common\Enums\Message;
use App\Common\Enums\RouteName;
use Tests\TestCase;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;

class ReservationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $customer;
    public $tables;

    public function setUp(): void
    {
        parent::setUp();
        $this->authenticate();
        $this->customer = auth()->user();
    }

    public function initiateTable($capacity = null)
    {
        return Table::factory(5, ['capacity' => $capacity ?? $this->faker->numberBetween(1, 20)])->create();
    }

    /** @test */
    public function customer_can_reserve_a_table()
    {
        $table = $this->initiateTable()[0];
        $date = $this->faker->dateTimeBetween('+1 days', '+2 years')->format('Y-m-d');
        $from = Carbon::parse($date)->addHour()->format('h:i a');

        $response = $this->actingAs($this->customer, 'sanctum')->post(route(RouteName::CUSTOMER_RESERVE, [
            'guests_count' => $table->capacity,
            'date'  => $date,
            'from'  => $from,
            'to'  => Carbon::parse($from)->addHour()->format('h:i a'),
        ]));

        $this->assertDatabaseHas('reservations', [
            'customer_id' => auth()->id(),
            'table_id' => $table->id,
            'guests_count' => $table->capacity
        ]);

        $response->assertOk()->assertSee(['data.guests_count' => $table->capacity]);
    }

    /** @test */
    public function it_throws_an_error_if_guests_count_greater_than_table_capacity()
    {
        $this->initiateTable(3)[0];

        $date = $this->faker->dateTimeBetween('+1 days', '+2 years')->format('Y-m-d');
        $from = Carbon::parse($date)->addHour()->format('h:i a');

        $response = $this->actingAs($this->customer, 'sanctum')->post(route(RouteName::CUSTOMER_RESERVE, [
            'guests_count' => 5,
            'date'  => $date,
            'from'  => $from,
            'to'  => Carbon::parse($from)->addHour()->format('h:i a'),
        ]));

        $response->assertStatus(401)->assertSee(['message' => Message::RESERVATION_NOT_AVAILAIBLE]);
    }
}
