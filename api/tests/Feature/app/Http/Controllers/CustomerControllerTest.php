<?php

namespace Feature\app\Http\Controllers;

use App\Models\Customer;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testCustomerShouldBeCreatedWhenHasAllData ()
    {
        // Arrange
        $customer = Customer::factory()->make()->toArray();
        // Act
        $request = $this->post(route('postCustomer'), $customer);
        // Assert
        $request->assertStatus(201); // Created
        $this->assertDatabaseHas('customers', $customer);
    }

    public function testCustomerMustFailsWhenHasntName ()
    {
        // Arrange
        $customer = Customer::factory()->make()->toArray();
        unset($customer['name']);
        // Act
        $request = $this->post(route('postCustomer'), $customer);
        // Assert
        $request->assertStatus(422); // Unprocessable Entity
        $this->assertDatabaseMissing('customers', $customer);
    }

}
