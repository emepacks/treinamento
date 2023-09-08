<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:3',
            'cpf' => 'required|string|size:11|unique:users',
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255|min:6',
            'cep'=>'required|string|size:8',
            'street'=>'required|string|max:255',
            'neighborhood'=>'required|string|max:255',
            'city'=>'required|string|max:255',
            'state'=>'required|string|size:2',
        ];
    }
    public function attributes(): array
    {
        return [
            'name' => 'Nome',
            'cpf' => 'CPF',
            'email' => 'Email',
            'password' => 'Senha',
            'cep'=>'CEP',
            'street'=>'Rua',
            'neighborhood'=>'Bairro',
            'city'=>'Cidade',
            'state'=>'Estado',
        ];
    }
    public function messages(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'min' => 'O campo :attribute deve ter no mínimo :min caracteres',
            'max' => 'O campo :attribute deve ter no máximo :max caracteres',
            'size' => 'O campo :attribute deve ter :size caracteres',
            'email' => 'O campo :attribute deve ser um email válido',
            'unique' => 'O campo :attribute já está em uso',
            'string' => 'O campo :attribute deve ser uma string',

        ];
    }
}