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

    'accepted' => 'O atributo :attribute deve ser aceito.',
    'accepted_if' => 'O atributo :attribute deve ser aceito quando :other é :value.',
    'active_url' => 'O atributo :attribute não é uma URL válida.',
    'after' => 'O atributo :attribute deve ser uma data depois de :date.',
    'after_or_equal' => 'O atributo :attribute deve ser uma data após ou igual a :date.',
    'alpha' => 'O atributo :attribute deve conter apenas letras.',
    'alpha_dash' => 'O atributo :attribute deve conter apenas letras, números, traços e sublinhados.',
    'alpha_num' => 'O atributo :attribute deve conter apenas letras e números.',
    'array' => 'O atributo :attribute deve ser um array.',
    'ascii' => 'O atributo :attribute deve conter apenas caracteres alfanuméricos de largura única e símbolos.',
    'before' => 'O atributo :attribute deve ser uma data antes de :date.',
    'before_or_equal' => 'O atributo :attribute deve ser uma data antes ou igual a :date.',
    'between' => [
        'array' => 'O atributo :attribute deve ter entre :min e :max itens.',
        'file' => 'O atributo :attribute deve ser entre :min e :max kilobytes.',
        'numeric' => 'O atributo :attribute deve estar entre :min e :max.',
        'string' => 'O atributo :attribute deve ter entre :min e :max caracteres.',
    ],
    'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
    'confirmed' => 'A confirmação do atributo :attribute não corresponde.',
    'current_password' => 'A senha está incorreta.',
    'date' => 'O atributo :attribute não é uma data válida.',
    'date_equals' => 'O atributo :attribute deve ser uma data igual a :date.',
    'date_format' => 'O atributo :attribute não corresponde ao formato :format.',
    'decimal' => 'O atributo :attribute deve ter :decimal casas decimais.',
    'declined' => 'O atributo :attribute deve ser recusado.',
    'declined_if' => 'O :attribute deve ser recusado quando :other é :value.',
    'different' => 'O :attribute e :other devem ser diferentes.',
    'digits' => 'O :attribute deve ter :digits dígitos.',
    'digits_between' => 'O :attribute deve ter entre :min e :max dígitos.',
    'dimensions' => 'O :attribute tem dimensões de imagem inválidas.',
    'distinct' => 'O campo :attribute tem um valor duplicado.',
    'doesnt_end_with' => 'O :attribute não pode terminar com um dos seguintes: :values.',
    'doesnt_start_with' => 'O :attribute não pode começar com um dos seguintes: :values.',
    'email' => 'O :attribute deve ser um endereço de e-mail válido.',
    'ends_with' => 'O :attribute deve terminar com um dos seguintes: :values.',
    'enum' => 'O :attribute selecionado é inválido.',
    'exists' => 'O :attribute selecionado é inválido.',
    'file' => 'O :attribute deve ser um arquivo.',
    'filled' => 'O campo :attribute deve ter um valor.',
    'gt' => [
        'array' => 'O :attribute deve ter mais que :value itens.',
        'file' => 'O :attribute deve ser maior que :value kilobytes.',
        'numeric' => 'O :attribute deve ser maior que :value.',
        'string' => 'O :attribute deve ser maior que :value caracteres.',
    ],
    'gte' => [
        'array' => 'O :attribute deve ter :value itens ou mais.',
        'file' => 'O :attribute deve ser maior ou igual a :value kilobytes.',
        'numeric' => 'O :attribute deve ser maior ou igual a :value.',
        'string' => 'O :attribute deve ser maior ou igual a :value caracteres.',
    ],
    'image' => 'O :attribute deve ser uma imagem.',
    'in' => 'O :attribute selecionado é inválido.',
    'in_array' => 'O campo :attribute não existe em :other.',
    'integer' => 'O :attribute deve ser um número inteiro.',
    'ip' => 'O :attribute deve ser um endereço IP válido.',
    'ipv4' => 'O :attribute deve ser um endereço IPv4 válido.',
    'ipv6' => 'O :attribute deve ser um endereço IPv6 válido.',
    'json' => 'O :attribute deve ser uma string JSON válida.',
    'lowercase' => 'O :attribute deve estar em minúsculas.',
    'lt' => [
        'array' => 'O :attribute deve ter menos de :value itens.',
        'file' => 'O :attribute deve ser menor que :value kilobytes.',
        'numeric' => 'O :attribute deve ser menor que :value.',
        'string' => 'O :attribute deve ter menos de :value caracteres.',
    ],
    'lte' => [
        'array' => 'O :attribute não deve ter mais de :value itens.',
        'file' => 'O :attribute deve ser menor ou igual a :value kilobytes.',
        'numeric' => 'O :attribute deve ser menor ou igual a :value.',
        'string' => 'O :attribute deve ter menos ou igual a :value caracteres.',
    ],
    "mac_address" => "O atributo :attribute deve ser um endereço MAC válido.",
    "max" => [
        "array" => "O atributo :attribute não deve ter mais do que :max itens.",
        "file" => "O atributo :attribute não deve ser maior do que :max kilobytes.",
        "numeric" => "O atributo :attribute não deve ser maior do que :max.",
        "string" => "O atributo :attribute não deve ser maior do que :max caracteres.",
    ],
    "max_digits" => "O atributo :attribute não deve ter mais do que :max dígitos.",
    "mimes" => "O atributo :attribute deve ser um arquivo do tipo: :values.",
    "mimetypes" => "O atributo :attribute deve ser um arquivo do tipo: :values.",
    "min" => [
        "array" => "O atributo :attribute deve ter pelo menos :min itens.",
        "file" => "O atributo :attribute deve ser pelo menos :min kilobytes.",
        "numeric" => "O atributo :attribute deve ser pelo menos :min.",
        "string" => "O atributo :attribute deve ser pelo menos :min caracteres.",
    ],
    'min_digits' => 'O atributo :attribute deve ter pelo menos :min dígitos.',
    'missing' => 'O campo :attribute deve estar ausente.',
    'missing_if' => 'O campo :attribute deve estar ausente quando :other é :value.',
    'missing_unless' => 'O campo :attribute deve estar ausente a menos que :other seja :value.',
    'missing_with' => 'O campo :attribute deve estar ausente quando :values está presente.',
    'missing_with_all' => 'O campo :attribute deve estar ausente quando :values estão presentes.',
    'multiple_of' => 'O atributo :attribute deve ser múltiplo de :value.',
    'not_in' => 'O atributo selecionado :attribute é inválido.',
    'not_regex' => 'O formato do atributo :attribute é inválido.',
    'numeric' => 'O atributo :attribute deve ser um número.',
    'password' => [
        'letters' => 'O atributo :attribute deve conter pelo menos uma letra.',
        'mixed' => 'O atributo :attribute deve conter pelo menos uma letra maiúscula e uma minúscula.',
        'numbers' => 'O atributo :attribute deve conter pelo menos um número.',
        'symbols' => 'O atributo :attribute deve conter pelo menos um símbolo.',
        'uncompromised' => 'O :attribute fornecido apareceu em uma vazamento de dados. Por favor, escolha um :attribute diferente.',
    ],
    'present' => 'O campo :attribute deve estar presente.',
    'prohibited' => 'O campo :attribute é proibido.',
    'prohibited_if' => 'O campo :attribute é proibido quando :other é :value.',
    'prohibited_unless' => 'O campo :attribute é proibido a menos que :other esteja em :values.',
    'prohibits' => 'O campo :attribute proíbe que :other esteja presente.',
    'regex' => 'O formato do campo :attribute é inválido.',
    'required' => 'O campo :attribute é obrigatório.',
    'required_array_keys' => 'O campo :attribute deve conter entradas para: :values.',
    'required_if' => 'O campo :attribute é obrigatório quando :other é :value.',
    'required_if_accepted' => 'O campo :attribute é obrigatório quando :other é aceito.',
    'required_unless' => 'O campo :attribute é obrigatório a menos que :other esteja em :values.',
    'required_with' => 'O campo :attribute é obrigatório quando :values está presente.',
    'required_with_all' => 'O campo :attribute é obrigatório quando :values estão presentes.',
    'required_without' => 'O campo :attribute é obrigatório quando :values não está presente.',
    'required_without_all' => 'O campo :attribute é obrigatório quando nenhum de :values está presente.',
    'same' => 'O :attribute e :other devem corresponder.',
    'size' => [
        'array' => 'O :attribute deve conter :size itens.',
        'file' => 'O :attribute deve ser :size kilobytes.',
        'numeric' => 'O :attribute deve ser :size.',
        'string' => 'O :attribute deve ser :size caracteres.',
    ],
    'starts_with' => 'O :attribute deve começar com um dos seguintes: :values.',
    'string' => 'O :attribute deve ser uma string.',
    'timezone' => 'O :attribute deve ser uma fuso horário válido.',
    'unique' => 'O :attribute já foi utilizado.',
    'uploaded' => 'O :attribute falhou ao ser enviado.',
    'uppercase' => 'O :attribute deve estar em maiúsculas.',
    'url' => 'O :attribute deve ser uma URL válida.',
    'ulid' => 'O :attribute deve ser um ULID válido.',
    'uuid' => 'O :attribute deve ser um UUID válido.',

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

    'attributes' => [],

];
