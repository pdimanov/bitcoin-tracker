<?php

namespace App\Service\DatetimePeriods;

use Carbon\Carbon;

interface TimePeriodInterface
{
    /**
     * @param int $shift
     * @return array{
     *     startDatetime: Carbon,
     *     endDatetime: Carbon
     * }
     */
    public function build(int $shift): array;
}
