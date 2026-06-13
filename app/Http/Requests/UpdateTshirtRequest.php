<?php

namespace App\Http\Requests;

use App\Models\TshirtImage;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTshirtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('tshirt'));
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'image_file'  => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'O nome é obrigatório.',
            'name.max'             => 'O nome não pode ter mais de 255 caracteres.',
            'category_id.required' => 'A categoria é obrigatória.',
            'category_id.exists'   => 'A categoria selecionada não existe.',
            'image_file.image'     => 'O ficheiro deve ser uma imagem.',
            'image_file.max'       => 'A imagem não pode ter mais de 2 MB.',
        ];
    }
}
