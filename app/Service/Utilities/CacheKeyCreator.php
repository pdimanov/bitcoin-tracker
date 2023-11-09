<?php

namespace App\Service\Utilities;

class CacheKeyCreator
{
    public static function createLatestPriceHistoryByCurrency(string $currency): string
    {
        return 'latestPriceHistory-' . $currency;
    }
}
