<?php

namespace App\Http\Controllers;

use App\Models\Customer;
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
                    function ($attribute, $value, $fails) {
                        $hasRightSize = \strlen($value) === \strlen('xxxxxxxxxxx');
                        $hasRightSize |= \strlen($value) === \strlen('xxx.xxx.xxx-xx');

                        if (!$hasRightSize) {
                            $fails('The '.$attribute.' is invalid, with size invalid.');
                        }

                        $matchFormat = \preg_match('/[0-9]{3}.?[0-9]{3}.?[0-9]{3}-?[0-9]{2}/', $value);

                        if (!$matchFormat) {
                            $fails('The '.$attribute.' is invalid format.');
                        }
                    }
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
}
