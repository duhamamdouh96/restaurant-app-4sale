<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meal>
 */
class MealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->text(50),
            'description' => $this->faker->text(100),
            'price' => rand(100, 500),
            'available_quantity' => $this->faker->numberBetween(1, 20),
            'discount' => rand(10, 50)
        ];
    }
}
