<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class Cpf implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {

        $hasRightSize = \strlen($value) === \strlen('xxxxxxxxxxx');
        $hasRightSize |= \strlen($value) === \strlen('xxx.xxx.xxx-xx');

        if (!$hasRightSize) {
            $fail('The '.$attribute.' is invalid, with size invalid.');
        }

        $matchFormat = \preg_match('/[0-9]{3}.?[0-9]{3}.?[0-9]{3}-?[0-9]{2}/', $value);

        if (!$matchFormat) {
            $fail('The '.$attribute.' is invalid format.');
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
            $fail('The ' . $attribute . ' is incorrect.');
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
            $fail('The ' . $attribute . ' is incorrect.');
        }
    }
}
