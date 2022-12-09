<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function postCustomer(Request $request)
    {
        try {
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
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}
