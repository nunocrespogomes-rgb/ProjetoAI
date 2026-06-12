<?php

namespace App\Http\Requests;

use App\Models\Price;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', Price::first() ?? new Price());
    }

    public function rules(): array
    {
        return [
            'unit_price_catalog'          => ['required', 'numeric', 'min:0.01', 'max:999999.99', 'decimal:0,2'],
            'unit_price_own'              => ['required', 'numeric', 'min:0.01', 'max:999999.99', 'decimal:0,2'],
            'unit_price_catalog_discount' => ['required', 'numeric', 'min:0.01', 'max:999999.99', 'decimal:0,2'],
            'unit_price_own_discount'     => ['required', 'numeric', 'min:0.01', 'max:999999.99', 'decimal:0,2'],
            'qty_discount'                => ['required', 'integer', 'min:1', 'max:999999'],
        ];
    }

    public function messages(): array
    {
        return [
            'unit_price_catalog.required'          => 'O preço de catálogo é obrigatório.',
            'unit_price_catalog.numeric'           => 'O preço de catálogo deve ser um número.',
            'unit_price_catalog.min'               => 'O preço de catálogo deve ser no mínimo 0.01.',
            'unit_price_own.required'              => 'O preço de imagem própria é obrigatório.',
            'unit_price_own.numeric'               => 'O preço de imagem própria deve ser um número.',
            'unit_price_own.min'                   => 'O preço de imagem própria deve ser no mínimo 0.01.',
            'unit_price_catalog_discount.required' => 'O preço de catálogo com desconto é obrigatório.',
            'unit_price_catalog_discount.min'      => 'O preço de catálogo com desconto deve ser no mínimo 0.01.',
            'unit_price_own_discount.required'     => 'O preço de imagem própria com desconto é obrigatório.',
            'unit_price_own_discount.min'          => 'O preço de imagem própria com desconto deve ser no mínimo 0.01.',
            'qty_discount.required'                => 'A quantidade mínima para desconto é obrigatória.',
            'qty_discount.integer'                 => 'A quantidade mínima deve ser um número inteiro.',
            'qty_discount.min'                     => 'A quantidade mínima deve ser pelo menos 1.',
        ];
    }
}
