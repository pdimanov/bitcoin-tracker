<?php

namespace App\Service\DatetimePeriods;

use Carbon\Carbon;

class DailyPeriod implements TimePeriodInterface
{
    public function build(int $shift): array
    {
        return [
            'startDatetime' => Carbon::now()->subDays($shift + 1),
            'endDatetime'   => Carbon::now()->subDays($shift)
        ];
    }
}
