<?php

namespace App\Enums;

enum Months: int
{
    case JANUARY = 1;
    case FEBRUARY = 2;
    case MARCH = 3;
    case APRIL = 4;
    case MAY = 5;
    case JUNE = 6;
    case JULY = 7;
    case AUGUST = 8;
    case SEPTEMBER = 9;
    case OCTOBER = 10;
    case NOVEMBER = 11;
    case DECEMBER = 12;

    public function name(): string
    {
        return match ($this) {
            static::JANUARY => 'Janeiro',
            static::FEBRUARY => 'Fevereiro',
            static::MARCH => 'Março',
            static::APRIL => 'Abril',
            static::MAY => 'Maio',
            static::JUNE => 'Junho',
            static::JULY => 'Julho',
            static::AUGUST => 'Agosto',
            static::SEPTEMBER => 'Setembro',
            static::OCTOBER => 'Outubro',
            static::NOVEMBER => 'Novembro',
            static::DECEMBER => 'Dezembro',
        };
    }
}
