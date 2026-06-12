<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Category::class);
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255', 'unique:categories,name'],
            'image_file' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'O nome da categoria é obrigatório.',
            'name.unique'      => 'Já existe uma categoria com este nome.',
            'name.max'         => 'O nome não pode ter mais de 255 caracteres.',
            'image_file.image' => 'O ficheiro deve ser uma imagem.',
            'image_file.max'   => 'A imagem não pode ter mais de 2 MB.',
        ];
    }
}
