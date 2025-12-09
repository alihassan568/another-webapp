<?php

namespace App\Utilities;

trait EnumHelpers
{
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function first(): string
    {
        return self::values()[0];
    }

    public static function array(): array
    {
        return array_combine(self::values(), self::names());
    }

    public static function isValid(string $value): bool
    {
        $values = array_map('strtolower', self::values());

        return in_array(strtolower($value), $values);
    }
}
