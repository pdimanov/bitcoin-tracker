<?php

namespace App\Service\Api;

use Carbon\Carbon;

interface BitcoinClientInterface
{
    public function getCurrentPrice(array $currencies = []): array;

    public function getHistoryBetweenPeriod(array $currencies, Carbon $start, ?Carbon $end = null): array;
}
