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

    public function testGetProductsShouldReturnOnlyThreeRows ()
    {
        // Arrange
        Product::factory()->count(3)->create();

        // Act
        $products = $this->get(route('getProducts'));

        $content = json_decode($products->getContent(), true);

        // Assert
        $this->assertIsArray($content);
        $this->assertEquals(3, count($content));
        $this->assertArrayHasKey('id', $content[0]);
    }

    public function testGetProductsMustReturnNoRows ()
    {
        // Arrange
        // Act
        $products = $this->get(route('getProducts'));
        $content = json_decode($products->getContent(), true);

        // Assert
        $this->assertIsArray($content);
        $this->assertEquals(0, count($content));
    }

    public function testGetProductByValidIdMustReturnOneRow ()
    {
        // Arrange
        $product = Product::factory()->create()->toArray();
        $id = $product['id'];
        // Act
        $response = $this->get(route('getProduct', [ 'id' => $id ]));

        $content = json_decode($response->getContent(), true);

        // Assert
        $response->assertStatus(200);
        $this->assertIsArray($content);
        $this->assertEquals($product['id'], $content['id']);
        $this->assertEquals($product['name'], $content['name']);
        $this->assertEquals($product['unit_price'], $content['unit_price']);
    }

    public function testGetProductByInvalidIdMustReturnEmpty ()
    {
        // Arrange
        $id = 'not-exists-99999999999999';
        // Act
        $response = $this->get(route('getProduct', [ 'id' => $id ]));

        $content = json_decode($response->getContent(), true);

        // Assert
        $response->assertStatus(404);
        $this->assertIsArray($content);
        $this->assertEquals($content['message'], 'Product cannot exists.');
    }

}
