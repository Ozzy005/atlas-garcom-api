<?php

namespace App\Traits;

trait EnumMethods
{
    public static function all()
    {
        $enums = [];
        foreach (self::cases() as $enum) {
            $enums[] = ['id' => $enum->value, 'name' => $enum->name()];
        }

        return $enums;
    }
}
