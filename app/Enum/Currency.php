<?php

namespace App\Enum;

enum Currency: string
{
    case EURO       = 'EUR';
    case US_DOLLARS = 'USD';

    public static function getAllValues(): array
    {
        $values = [];
        foreach (self::cases() as $case) {
            $values[] = $case->value;
        }
        return $values;
    }
}
