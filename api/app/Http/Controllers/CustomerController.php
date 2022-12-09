<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Rules;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function postCustomer(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'cpf' => [
                    'required',
                    'string',
                    new Rules\Cpf
                ],
                'email' => 'required|string',
                'birth_date' => 'required|date_format:Y-m-d',
                'address_cep' => 'required|string',
                'address_place' => 'required|string',
                'address_number' => 'required|string',
                'address_neighborhood' => 'required|string',
                'address_complement' => 'required|string',
                'address_city' => 'required|string',
            ]);

            $data = $request->only([
                'name',
                'cpf',
                'email',
                'birth_date',
                'address_cep',
                'address_place',
                'address_number',
                'address_neighborhood',
                'address_complement',
                'address_city',
            ]);

            $customer = new Customer($data);
            $customer->save();

            return response($data, 201);

        } catch (ValidationException $exception) {
            return response([
                'message' => $exception->getMessage(),
            ], 422);
        } catch (\Exception $exception) {
            return response([
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function getCustomers(Request $request)
    {
        try {
            $customers = Customer::all()->toArray();
            return response($customers, 200);
        } catch (\Exception $exception) {
            return response([
                'message' => 'Customers cannot be retrived',
            ], 500);
        }
    }
}
