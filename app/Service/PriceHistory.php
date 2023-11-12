<?php

namespace App\Service;

use App\Enum\TimeInSeconds;
use App\Service\Api\BitcoinClientInterface;
use App\Service\DatetimePeriods\TimePeriodFactory;
use App\Service\Utilities\CacheKeyCreator;
use Illuminate\Support\Facades\Cache;

class PriceHistory implements PriceHistoryInterface
{
    public function __construct(
        private readonly TimePeriodFactory $timePeriodFactory,
        private readonly BitcoinClientInterface $client
    ) {
    }

    public function getData(string $timePeriod, int $timeShift, array $currencies): array
    {
        $cacheKey = CacheKeyCreator::createHistoryPeriodKey($timePeriod, $timeShift, $currencies);

        return Cache::remember(
            $cacheKey,
            TimeInSeconds::ONE_HOUR->value,
            function () use ($timeShift, $timePeriod, $currencies,) {
                $timePeriodClass = $this->timePeriodFactory->initialize($timePeriod);
                $timePeriods     = $timePeriodClass->build($timeShift);
                return $this->client->getHistoryBetweenPeriod(
                    $currencies,
                    $timePeriods['startDatetime'],
                    $timePeriods['endDatetime']
                );
            }
        );
    }

    public function getPaginationUrls(string $timePeriod, int $timeShift, array $currencies): array
    {
        $urls                = [];
        $urls['previousUrl'] = $this->buildHistoryPeriodUrl($timePeriod, $timeShift + 1, $currencies);
        if ($timeShift > 0) {
            $urls['nextUrl'] = $this->buildHistoryPeriodUrl($timePeriod, $timeShift - 1, $currencies);
        }

        return $urls;
    }

    private function buildHistoryPeriodUrl(string $timePeriod, int $timeShift, array $currencies): string
    {
        return route('api.price.historyPeriod', [
            'timePeriod' => $timePeriod,
            'timeShift'  => $timeShift,
            'currencies' => implode(',', $currencies)
        ]);
    }
}
