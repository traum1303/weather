<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Temperature>
 */
class TemperatureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'city_id' => City::query()->inRandomOrder()->take(1)->first()->id,
            'temperature' => bcdiv(fake()->numberBetween(-5000, 5000), 100, 2),
        ];
    }
}
