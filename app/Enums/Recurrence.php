<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum Recurrence: int
{
    use EnumMethods;

    case MONTHLY = 1;
    case QUARTERLY = 3;
    case SEMIANNUALLY = 6;
    case ANNUALLY = 12;

    public function name(): string
    {
        return match ($this) {
            static::MONTHLY => 'Mensal',
            static::QUARTERLY => 'Trimestral',
            static::SEMIANNUALLY => 'Semestral',
            static::ANNUALLY => 'Anual'
        };
    }

    public function color(): string
    {
        return match ($this) {
            static::MONTHLY => '#008080',
            static::QUARTERLY => '#0000ff',
            static::SEMIANNUALLY => '#800080',
            static::ANNUALLY => '#800000'
        };
    }
}
