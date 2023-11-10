<?php

namespace App\Service\DatetimePeriods;

use App\Enum\TimePeriod;

class TimePeriodFactory
{
    public function initialize(string $type): TimePeriodInterface
    {
        $timePeriodClassInstance = match ($type) {
            TimePeriod::DAILY->value => new DailyPeriod(),
            TimePeriod::WEEKLY->value => new WeeklyPeriod(),
            default => false
        };

        if (!$timePeriodClassInstance) {
            throw new \Exception("Unsupported time period type: $type");
        }

        return $timePeriodClassInstance;
    }
}
