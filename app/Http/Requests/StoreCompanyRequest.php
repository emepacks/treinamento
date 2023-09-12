<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
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
            'razao' => 'required|string|max:255|min:3',
            'cnpj' => 'required|string|size:14|unique:companies',
            'email' => 'required|email|max:255|unique:companies',
            'password' => 'required|string|max:255|min:6',
            'cep'=>'required|string|size:8',
            'street'=>'required|string|max:255',
            'neighborhood'=>'required|string|max:255',
            'city'=>'required|string|max:255',
            'state'=>'required|string|size:2',
        ];
    }


}
