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

    public function testCustomerMustFailsWhenCpfhasWrongFormat ()
    {
        // Arrange
        $customer = Customer::factory()->make([
            'cpf' => 'xxx.xxx.xxx-xx'
        ])->toArray();
        // Act
        $request = $this->post(route('postCustomer'), $customer);
        // Assert
        $request->assertStatus(422); // Unprocessable Entity
        $this->assertDatabaseMissing('customers', $customer);
    }

    public function testCustomerMustFailsWhenCpfHasWrongSize ()
    {
        // Arrange
        $customer = Customer::factory()->make([
            'cpf' => '014421010 08'
        ])->toArray();
        // Act
        $request = $this->post(route('postCustomer'), $customer);
        // Assert
        $request->assertStatus(422); // Unprocessable Entity
        $this->assertDatabaseMissing('customers', $customer);
    }

    public function testCustomerMustFailsWhenOnlyNumbersCpfIsInvalid ()
    {
        // Arrange
        $customer = Customer::factory()->make([
            'cpf' => '01442101018'
        ])->toArray();
        // Act
        $request = $this->post(route('postCustomer'), $customer);
        // Assert
        $request->assertStatus(422); // Unprocessable Entity
        $this->assertDatabaseMissing('customers', $customer);
    }

    public function testCustomerMustFailsWhenWithSymbolsCpfIsInvalid ()
    {
        // Arrange
        $customer = Customer::factory()->make([
            'cpf' => '014.421.010-18'
        ])->toArray();
        // Act
        $request = $this->post(route('postCustomer'), $customer);
        // Assert
        $request->assertStatus(422); // Unprocessable Entity
        $this->assertDatabaseMissing('customers', $customer);
    }

}
