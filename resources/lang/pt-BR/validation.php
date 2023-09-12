<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */


    'required' => 'O campo :attribute é obrigatório',
    'min' => 'O campo :attribute deve ter no mínimo :min caracteres',
    'max' => 'O campo :attribute deve ter no máximo :max caracteres',
    'size' => 'O campo :attribute deve ter :size caracteres',
    'email' => 'O campo :attribute deve ser um email válido',
    'unique' => 'O campo :attribute já está em uso',
    'string' => 'O campo :attribute deve ser uma string',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'email' => 'email',
        'password' => 'senha',
        'name' => 'nome',
        'cpf' => 'CPF',
        'cep'=>'CEP',
        'street'=>'rua',
        'neighborhood'=>'bairro',
        'city'=>'cidade',
        'state'=>'estado',
        'razao' => 'razão social',
        'cnpj' => 'CNPJ',
    ],
];
