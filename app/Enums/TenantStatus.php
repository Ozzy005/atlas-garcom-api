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
}
