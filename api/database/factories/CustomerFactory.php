<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake('pt_BR')->name(),
            'cpf' => fake('pt_BR')->unique()->cpf(),
            'email' => fake('pt_BR')->email(),
            'birth_date' => fake('pt_BR')->dateTimeBetween('-30 years', '-18 years')->format('Y-m-d'),
            'address_cep' => fake('pt_BR')->postcode(),
            'address_place' => fake('pt_BR')->streetAddress(),
            'address_number' => fake('pt_BR')->buildingNumber(),
            'address_neighborhood' => fake('pt_BR')->streetName(),
            'address_complement' => fake('pt_BR')->streetName(),
            'address_city' => fake('pt_BR')->address(),
        ];
    }
}
