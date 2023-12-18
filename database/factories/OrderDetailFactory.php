<?php

namespace Database\Factories;

use App\Models\Meal;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $meal = Meal::factory()->create();

        return [
            'order_id' => Order::factory(),
            'meal_id' => $meal->id,
            'amount_to_pay' => $meal->calculatePriceAfterDiscount()
        ];
    }
}
