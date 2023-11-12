<?php

namespace App\Service;

interface PriceHistoryInterface
{
    public function getData(string $timePeriod, int $timeShift, array $currencies): array;
}
