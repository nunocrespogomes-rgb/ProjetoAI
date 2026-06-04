<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {       
        // 1. Validação dos dados 
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        // Usamos uma Transaction para garantir que ambas as tabelas são preenchidas com sucesso
        return DB::transaction(function () use ($input) {
            
            // 2. REQUISITO G1: Criar o utilizador na tabela 'users' com encriptação e tipo correto
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']), // Obrigatoriamente encriptada
                'user_type' => 'C',  // REQUISITO: Todo o registo público é obrigatoriamente um Cliente ('C')
                'blocked' => 0,  // Começa com a conta ativa
                'gender' => 'M',    
            ]);

            // 3. REQUISITO ESSENCIAL: Criar a linha correspondente na tabela 'customers'
            Customer::create([
                'id' => $user->id, // O ID do customer tem de ser RIGUAL ao ID do user (Chave Estrangeira)
                // Os restantes campos (nif, endereço) ficam nulos para o cliente preencher mais tarde no perfil
            ]);

            return $user;
        });
    }
}

