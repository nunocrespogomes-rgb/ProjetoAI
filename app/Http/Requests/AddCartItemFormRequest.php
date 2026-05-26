<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCartItemFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'qty' => (int) $this->qty,
        ]);
    }

    public function rules(): array
    {
        return [
            'tshirt_image_id' => 'required|integer|exists:tshirt_images,id',
            'color_code' => 'required|string|exists:colors,code',
            'size' => 'required|in:XS,S,M,L,XL',
            'qty' => 'required|integer|min:1|max:100',
        ];
    }
}
