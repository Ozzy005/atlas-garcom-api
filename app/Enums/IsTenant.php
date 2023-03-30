<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum IsTenant: int
{
    use EnumMethods;

    case NOT = 0;
    case YES = 1;

    public function name(): string
    {
        return match ($this) {
            static::YES => 'Sim',
            static::NOT => 'Não',
        };
    }

    public function color(): string
    {
        return match ($this) {
            static::YES => '#21BA45',
            static::NOT => '#C10015',
        };
    }

    public function yes(): bool
    {
        return match ($this) {
            static::YES => true,
            static::NOT => false,
        };
    }

    public function not(): bool
    {
        return match ($this) {
            static::YES => false,
            static::NOT => true,
        };
    }
}
