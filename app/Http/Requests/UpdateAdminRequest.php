<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('administrative'));
    }

    public function rules(): array
    {
        $userId = $this->route('administrative')?->id;

        return [
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email,' . $userId],
            'gender'     => ['required', 'in:M,F'],
            'user_type'  => ['required', 'in:A,F'],
            'photo_file' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'O nome é obrigatório.',
            'email.required'     => 'O e-mail é obrigatório.',
            'email.email'        => 'O e-mail não é válido.',
            'email.unique'       => 'Já existe um utilizador com este e-mail.',
            'gender.required'    => 'O género é obrigatório.',
            'gender.in'          => 'O género deve ser Masculino ou Feminino.',
            'user_type.required' => 'O tipo de utilizador é obrigatório.',
            'user_type.in'       => 'O tipo deve ser Administrador ou Funcionário.',
            'photo_file.image'   => 'O ficheiro deve ser uma imagem.',
            'photo_file.max'     => 'A imagem não pode ter mais de 2 MB.',
        ];
    }
}
