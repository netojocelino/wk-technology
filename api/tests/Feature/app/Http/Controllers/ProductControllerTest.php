<?php

namespace Feature\app\Http\Controllers;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testPostProductShouldBeCreatedSuccessfully ()
    {
        // Arrange
        $productArray = Product::factory()->make()->toArray();
        // Act
        $request = $this->post(route('postProduct'), $productArray);
        // Assert
        $request->assertStatus(201);
        $this->assertDatabaseHas('products', $productArray);
    }

    public function testPostProductMustFailWhenHasNoName ()
    {
        // Arrange
        $productArray = [
            'unit_price' => 420.00,
        ];
        // Act
        $request = $this->post(route('postProduct'), $productArray);
        // Assert
        $request->assertStatus(422);
    }

    public function testPostProductMustFailWhenHasNoUnitPrice ()
    {
        // Arrange
        $productArray = [
            'name' => 'Some Fantastic Product',
        ];
        // Act
        $request = $this->post(route('postProduct'), $productArray);
        // Assert
        $request->assertStatus(422);
    }

}
