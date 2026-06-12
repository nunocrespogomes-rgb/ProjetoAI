<?php

namespace App\Http\Requests;

use App\Models\Color;
use Illuminate\Foundation\Http\FormRequest;

class UpdateColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('color'));
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'base_image' => ['nullable', 'image', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'O nome da cor é obrigatório.',
            'name.max'         => 'O nome não pode ter mais de 255 caracteres.',
            'base_image.image' => 'O ficheiro deve ser uma imagem.',
            'base_image.max'   => 'A imagem não pode ter mais de 4 MB.',
        ];
    }
}
