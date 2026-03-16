<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Regra customizada para validar CPF brasileiro.
 * Verifica formato e validade matematica do CPF.
 */
class ValidaCPF implements ValidationRule
{
    /**
     * Executa a validacao do CPF.
     * Aceita CPF formatado (000.000.000-00) ou apenas digitos (00000000000).
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove formatacao (pontos e hifen) para trabalhar apenas com digitos
        $cpf = preg_replace('/[^0-9]/', '', (string) $value);

        // CPF deve ter exatamente 11 digitos
        if (strlen($cpf) !== 11) {
            $fail('O :attribute deve ter 11 dígitos.');
            return;
        }

        // Rejeita CPFs com todos os digitos iguais (ex: 111.111.111-11) - sao invalidos
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $fail('O :attribute informado é inválido.');
            return;
        }

        // Calcula e valida o primeiro digito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += (int) $cpf[$i] * (10 - $i);
        }
        $digito1 = ($soma % 11 < 2) ? 0 : (11 - ($soma % 11));

        if ((int) $cpf[9] !== $digito1) {
            $fail('O :attribute informado é inválido.');
            return;
        }

        // Calcula e valida o segundo digito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += (int) $cpf[$i] * (11 - $i);
        }
        $digito2 = ($soma % 11 < 2) ? 0 : (11 - ($soma % 11));

        if ((int) $cpf[10] !== $digito2) {
            $fail('O :attribute informado é inválido.');
            return;
        }
    }
}
