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

    public function testPostSalesOrderMustFailsWhenHasntCustomer ()
    {
        $saleOrderArray = [
            'sale_date' => '2022-12-12',
        ];

        $request = $this->post(route('postSalesOrder'), $saleOrderArray);

        // Assert
        $request->assertStatus(422);
        $request->assertJsonFragment([ 'message' => 'The customer id field is required.' ]);

        $this->assertDatabaseMissing('sales_orders', $saleOrderArray);
    }

    public function testPostSalesOrderMustFailsWhenCustomerDoesNotExists ()
    {
        $saleOrderArray = [
            'sale_date' => '2022-12-12',
            'customer_id' => '99997541',
        ];

        $request = $this->post(route('postSalesOrder'), $saleOrderArray);

        // Assert
        $request->assertStatus(422);
        $request->assertJsonFragment([ 'message' => 'The selected customer id is invalid.' ]);

        $this->assertDatabaseMissing('sales_orders', $saleOrderArray);
    }

}
