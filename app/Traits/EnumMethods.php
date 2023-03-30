<?php

namespace App\Traits;

trait EnumMethods
{
    public static function all(): array
    {
        $enums = [];
        foreach (self::cases() as $enum) {
            $enums[] = [
                'id' => $enum->value,
                'name' => $enum->name(),
                'color' => $enum->color()
            ];
        }

        return $enums;
    }
}
