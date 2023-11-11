<?php

namespace Tests\Unit\Service\Utilities;

use App\Service\Utilities\CacheKeyCreator;
use PHPUnit\Framework\TestCase;

class CacheKeyCreatorTest extends TestCase
{
    public function test_create_latest_price_by_currency_key(): void
    {
        $cacheKey = CacheKeyCreator::createLatestPriceByCurrencyKey('EUR');

        $this->assertSame('latestPriceHistory-EUR', $cacheKey);
    }

    /**
     * @testWith ["week", 0, ["EUR"],       "historyPeriod-week-0-EUR"]
     *           ["day",  1, ["EUR"],       "historyPeriod-day-1-EUR"]
     *           ["day",  1, ["EUR","USD"], "historyPeriod-day-1-EUR,USD"]
     */
    public function test_create_history_period_key($period, $shift, $currencies, $expected): void
    {
        $cacheKey = CacheKeyCreator::createHistoryPeriodKey($period, $shift, $currencies);

        $this->assertSame($expected, $cacheKey);
    }
}
