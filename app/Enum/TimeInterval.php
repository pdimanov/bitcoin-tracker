<?php

namespace App\Enum;

enum TimeInterval: int
{
    case ONE_HOUR = 1;
    case SIX_HOURS = 6;
    case TWENTY_FOUR_HOURS = 24;

    public function label(): string
    {
        return match($this) {
            TimeInterval::ONE_HOUR => '1 Hour',
            TimeInterval::SIX_HOURS => '6 Hours',
            TimeInterval::TWENTY_FOUR_HOURS => '24 Hours',
        };
    }
}
