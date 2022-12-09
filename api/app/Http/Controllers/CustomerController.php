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

                        $onlyNumbersValue = preg_filter('/\D/', '', $value);
                        // soma dos primeiros [1...9] dígitos multiplicados por [10..2]
                        // d_1 * 10 + d_2 * 9 + ... + d_9 * 2 := P1
                        $accumulatorFirstDigit = 0;
                        for ($i = 0; $i < 9; $i++) {
                            $intSubValue = \substr($onlyNumbersValue, $i, 1);
                            $intCasted = \intval($intSubValue) * (10 - $i);
                            $accumulatorFirstDigit += $intCasted;
                        }
                        // multiplica P1 por 10 e divide por 11
                        // P1 * 10 / 11 := P2
                        $modulusP1 = ($accumulatorFirstDigit * 10) % 11;
                        $modulusP1 = ($modulusP1 == 10) ? 0 : $modulusP1;

                        // compara o resto da divisão de P2 com o primeiro dígito do código verificador
                        //     caso resto seja 10 considera valor como 0
                        $firstDigitInvalid = $modulusP1 != \substr($onlyNumbersValue, 9, 1);

                        // resto de P2 = CPF_10 ==> primeira parte válida
                        if ($firstDigitInvalid) {
                            $fails('The ' . $attribute . ' is incorrect.');
                        }

                        // soma dos primeiros [1..9] digitos e o primeiro verificador multiplicado por [11..2]
                        // d_1 * 11 + d_2 * 10 + ... + d_1 * 3 + dv_1 * 2 := P1
                        $accumulatorSecondDigit = 0;
                        for ($i = 0; $i < 10; $i++) {
                            $intSubValue = \substr($onlyNumbersValue, $i, 1);
                            $intCasted = \intval($intSubValue) * (11 - $i);
                            $accumulatorSecondDigit += $intCasted;
                        }

                        // multiplica P1 por 10 e divide por 11
                        // P1 * 10 / 11 := P2
                        $modulusP1 = ($accumulatorSecondDigit * 10) % 11;
                        $modulusP1 = ($modulusP1 == 10) ? 0 : $modulusP1;

                        // compara o resto da divisão de P2 com o primeiro dígito do código verificador
                        //     caso resto seja 10 considera valor como 0
                        $secondDigitInvalid = $modulusP1 != \substr($onlyNumbersValue, 10, 1);

                        // resto de P2 = CPF_10 ==> primeira parte válida
                        if ($secondDigitInvalid) {
                            $fails('The ' . $attribute . ' is incorrect.');
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
