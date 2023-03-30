<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum Status: int
{
    use EnumMethods;

    case ACTIVE = 1;
    case INACTIVE = 2;

    public function name(): string
    {
        return match ($this) {
            static::ACTIVE => 'Ativo',
            static::INACTIVE => 'Inativo',
        };
    }

    public function color(): string
    {
        return match ($this) {
            static::ACTIVE => '#21BA45',
            static::INACTIVE => '#C10015',
        };
    }

    public function active(): bool
    {
        return match ($this) {
            static::ACTIVE => true,
            static::INACTIVE => false,
        };
    }

    public function notActive(): bool
    {
        return match ($this) {
            static::ACTIVE => false,
            static::INACTIVE => true,
        };
    }
}
