<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cep'=> '29970000',
            'street' => fake()->streetAddress(),
            'neighborhood' => fake()->name(),
            'city' => fake()->city(),
            'state' => 'ES'
        ];
    }
}
