<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
            'name' => 'string|max:255|min:3',
            'password' => 'string|max:255|min:6',
            'cep'=>'string|size:8',
            'street'=>'string|max:255',
            'neighborhood'=>'string|max:255',
            'city'=>'string|max:255',
            'state'=>'string|size:2',
            'image'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
}
