<?php

namespace App\Enums;

use App\Traits\EnumMethods;

enum TenantStatus: int
{
    use EnumMethods;

    case ACTIVE = 1;
    case DEFAULTER = 2;
    case SUSPENDED = 3;
    case CANCELED = 4;

    public function name(): string
    {
        return match ($this) {
            static::ACTIVE => 'Ativo',
            static::DEFAULTER => 'Inadimplente',
            static::SUSPENDED => 'Suspenso',
            static::CANCELED => 'Cancelado'
        };
    }

    public function color(): string
    {
        return match ($this) {
            static::ACTIVE => '#21BA45',
            static::DEFAULTER => '#808080',
            static::SUSPENDED => '#800080',
            static::CANCELED => '#C10015',
        };
    }
}
