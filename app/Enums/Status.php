<?php

namespace App\Enums;

enum Status: int
{
    case ACTIVE = 1;
    case INACTIVE = 2;

    public function name(): string
    {
        return match ($this) {
            static::ACTIVE => 'Ativo',
            static::INACTIVE => 'Inativo',
        };
    }
}
