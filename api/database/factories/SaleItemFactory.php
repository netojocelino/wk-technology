<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models as Model;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleItem>
 */
class SaleItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'sale_id' => Model\SalesOrder::factory(),
            'product_id' => Model\Product::factory(),
            'unit_price' => fake('pt_BR')->randomFloat(2, 25, 1000),
        ];
    }

}
