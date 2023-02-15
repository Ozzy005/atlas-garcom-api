<?php

namespace App\Exceptions;

use Exception;

class HttpException extends Exception
{
    private readonly array $errors;

    public function __construct($message = 'Erro interno do servidor!', $errors = [], $code = 500)
    {
        parent::__construct($message, $code);
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
