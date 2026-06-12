<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomTshirtImageRequest extends FormRequest
{
    public function authorize(): bool
    {

        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome da imagem é obrigatório.',
            'name.max'      => 'O nome não pode ultrapassar 255 caracteres.',
            'image.image'   => 'O ficheiro deve ser uma imagem.',
            'image.mimes'   => 'São aceites apenas ficheiros jpg, jpeg, png ou webp.',
            'image.max'     => 'A imagem não pode ultrapassar 4MB.',
        ];
    }
}
