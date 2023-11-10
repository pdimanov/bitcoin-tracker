<?php

namespace App\Service\Utilities;

class CacheKeyCreator
{
    public static function createLatestPriceByCurrencyKey(string $currency): string
    {
        return "latestPriceHistory-$currency";
    }

    public static function createHistoryPeriodKey(string $period, int $shift, array $currencies): string
    {
        $currencies = implode(',', $currencies);

        return "historyPeriod-$period-$shift-$currencies";
    }
}
