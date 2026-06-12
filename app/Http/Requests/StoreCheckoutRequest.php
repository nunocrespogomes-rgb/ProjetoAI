<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'nif'          => ['required', 'digits:9'],
            'address'      => ['required', 'string', 'max:255'],
            'payment_type' => ['required', 'in:Visa,PayPal,MB WAY'],
            'notes'        => ['nullable', 'string', 'max:1000'],
            'payment_ref'  => ['required', 'string', $this->paymentRefRule()],
        ];
    }

    public function messages(): array
    {
        return [
            'nif.required'          => 'O NIF é obrigatório.',
            'nif.digits'            => 'O NIF deve ter exatamente 9 dígitos.',
            'address.required'      => 'A morada é obrigatória.',
            'payment_type.required' => 'O método de pagamento é obrigatório.',
            'payment_type.in'       => 'O método de pagamento deve ser Visa, PayPal ou MB WAY.',
            'payment_ref.required'  => 'A referência de pagamento é obrigatória.',
        ];
    }

    private function paymentRefRule(): \Closure
    {
        return function ($attribute, $value, $fail) {
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
        };
    }
}
