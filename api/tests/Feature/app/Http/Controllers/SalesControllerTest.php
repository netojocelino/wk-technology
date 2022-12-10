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

    public function testPostSalesShouldBeCreatedSuccessfullyWithOneItem ()
    {
        // Arrange
        $customer = Model\Customer::factory()->create();
        $product = Model\Product::factory()->create();

        $saleOrderArray = Model\SalesOrder::factory([
            'customer_id' => $customer->id,
        ])->make()->toArray();

        $saleItems = Model\SaleItem::factory([
            'sale_id' => null,
            'product_id' => $product->id,
        ])->make()->toArray();
        unset($saleItems['sale_id']);

        $saleOrderArray['items'] = [$saleItems];

        // Act
        $request = $this->post(route('postSalesOrder'), $saleOrderArray);
        $content = json_decode($request->getContent());
        unset($saleOrderArray['items']);

        // Assert
        $request->assertStatus(201);
        $request->assertJsonFragment([ 'id' => $content->id, 'customer_id' => $customer->id ]);

        $this->assertDatabaseHas('sales_orders', $saleOrderArray);
        $this->assertDatabaseHas('sale_items', [
            'product_id' => $saleItems['product_id'],
            'unit_price' => $saleItems['unit_price'],
        ]);
    }

    public function testPostSalesMustFailWhenProductNotExists ()
    {
        // Arrange
        $customer = Model\Customer::factory()->create();
        $saleOrderArray = Model\SalesOrder::factory([
            'customer_id' => $customer->id,
        ])->make()->toArray();

        $saleItem = [
            'product_id' => '213456',
            'unit_price' => '12315.92'
        ];

        $saleOrderArray['items'] = [$saleItem];

        $expected = [
            'message' => 'The selected items.0.product_id is invalid.',
        ];

        // Act
        $request = $this->post(route('postSalesOrder'), $saleOrderArray);

        unset($saleOrderArray['items']);

        // Assert
        $request->assertStatus(422);
        $request->assertContent(json_encode($expected));

        $this->assertDatabaseMissing('sales_orders', $saleOrderArray);
        $this->assertDatabaseMissing('sale_items', [
            'product_id' => $saleItem['product_id'],
            'unit_price' => $saleItem['unit_price'],
        ]);
    }

}
