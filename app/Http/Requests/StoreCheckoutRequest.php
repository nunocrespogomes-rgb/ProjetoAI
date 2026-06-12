<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nif' => ['required', 'digits:9'],
            'address' => ['required', 'string', 'max:255'],
            'payment_type' => ['required', 'in:Visa,PayPal,MB WAY'],
            'notes' => ['nullable', 'string', 'max:1000'],

            'payment_ref' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $type = $this->input('payment_type');

                    if ($type === 'Visa' && !preg_match('/^4[0-9]{15}$/', $value)) {
                        $fail('O formato para Visa deve ter 16 dígitos e começar por 4.');
                    }

                    if ($type === 'PayPal' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail('O formato para PayPal deve ser um e-mail válido.');
                    }

                    if ($type === 'MB WAY' && !preg_match('/^9[0-9]{8}$/', $value)) {
                        $fail('O formato para MB WAY deve ter 9 dígitos e começar por 9.');
                    }
                },
            ],
        ];
    }
}
