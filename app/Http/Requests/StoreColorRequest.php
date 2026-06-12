<?php

namespace App\Http\Requests;

use App\Models\Color;
use Illuminate\Foundation\Http\FormRequest;

class StoreColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Color::class);
    }

    public function rules(): array
    {
        return [
            'code'       => ['required', 'string', 'max:30', 'unique:colors,code'],
            'name'       => ['required', 'string', 'max:255'],
            'base_image' => ['nullable', 'image', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required'    => 'O código da cor é obrigatório.',
            'code.max'         => 'O código não pode ter mais de 30 caracteres.',
            'code.unique'      => 'Já existe uma cor com este código.',
            'name.required'    => 'O nome da cor é obrigatório.',
            'name.max'         => 'O nome não pode ter mais de 255 caracteres.',
            'base_image.image' => 'O ficheiro deve ser uma imagem.',
            'base_image.max'   => 'A imagem não pode ter mais de 4 MB.',
        ];
    }
}
