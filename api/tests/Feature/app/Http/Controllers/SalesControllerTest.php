<?php

namespace Feature\app\Http\Controllers;

use App\Models as Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SalesOrderControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testPostSalesShouldBeCreatedSuccessfully()
    {
        // Arrange
        $saleOrderArray = Model\SalesOrder::factory()->make()->toArray();
        // Act
        $request = $this->post(route('postSalesOrder'), $saleOrderArray);
        $content = json_decode($request->getContent());
        // Assert
        $request->assertStatus(201);
        $request->assertJsonFragment([ 'id' => $content->id ]);

        $this->assertDatabaseHas('sales_orders', $saleOrderArray);
        $this->assertDatabaseHas('customers', [ 'id' => $content->customer_id ]);
    }

}
