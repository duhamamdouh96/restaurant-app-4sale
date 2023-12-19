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
    // public function customer_can_reserve_a_table()
    // {
    //     $table = $this->initiateTable()[0];
    //     $from = $this->faker->time('h:i a');

    //     $response = $this->post(route(RouteName::CUSTOMER_RESERVE, [
    //         'guests_count' => $table->capacity,
    //         'date'  => $this->faker->date('Y-m-d', 'now'),
    //         'from'  => $from,
    //         'to'  => Carbon::parse($from)->addHour()->format('h:i a'),
    //     ]));

    //     $this->assertDatabaseHas('reservations', [
    //         'customer_id' => auth()->id(),
    //         'table_id' => $table->id,
    //         'guests_count' => $table->capacity
    //     ]);

    //     $response->assertOk();
    // }

    /** @test */
    public function it_throws_an_error_if_guests_count_greater_than_table_capacity()
    {
        $this->initiateTable(3)[0];

        $from = $this->faker->time('h:i a');
        $response = $this->post(route(RouteName::CUSTOMER_RESERVE, [
            'guests_count' => 5,
            'date'  => $this->faker->date('Y-m-d', 'now'),
            'from'  => $from,
            'to'  => Carbon::parse($from)->addHour()->format('h:i a'),
        ]));

        $response->assertStatus(401)
            ->assertJson(function(AssertableJson $json) {
                $json->where('message', Message::RESERVATION_NOT_AVAILAIBLE)->etc();
            });
    }
}
