<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum RoleType: int
{
    use EnumMethods;

    case ROLE = 1;
    case MODULE = 2;

    public function name(): string
    {
        return match ($this) {
            static::ROLE => 'Atribuição',
            static::MODULE => 'Módulo'
        };
    }

    public function color(): string
    {
        return match ($this) {
            static::ROLE => '#008080',
            static::MODULE => '#0000ff'
        };
    }
}
