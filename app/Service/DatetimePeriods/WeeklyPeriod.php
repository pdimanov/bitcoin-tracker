<?php

namespace App\Service\DatetimePeriods;

use Carbon\Carbon;

class WeeklyPeriod implements TimePeriodInterface
{
    public function build(int $shift): array
    {
        return [
            'startDatetime' => Carbon::now()->subWeeks($shift + 1),
            'endDatetime'   => Carbon::now()->subWeeks($shift)
        ];
    }
}
