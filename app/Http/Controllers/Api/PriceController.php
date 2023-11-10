<?php

namespace App\Http\Controllers\Api;

use App\Enum\Currency;
use App\Enum\TimeInSeconds;
use App\Enum\TimePeriod;
use App\Http\Requests\PriceHistoryPeriodRequest;
use App\Service\Api\BitcoinClientInterface;
use App\Service\DatetimePeriods\TimePeriodFactory;
use App\Service\Utilities\CacheKeyCreator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class PriceController
{
    public function getHistoryPeriod(
        PriceHistoryPeriodRequest $request,
        BitcoinClientInterface $client,
        TimePeriodFactory $timePeriodFactory
    ): JsonResponse {
        $query      = $request->validated();
        $timeShift  = $query['timeShift'] ?? 0;
        $timePeriod = $query['timePeriod'] ?? TimePeriod::DAILY->value;
        $currencies = $query['currencies'] ?? Currency::getAllValues();

        $cacheKey = CacheKeyCreator::createHistoryPeriodKey($timePeriod, $timeShift, $currencies);
        $data     = Cache::remember(
            $cacheKey,
            TimeInSeconds::ONE_HOUR->value,
            function () use ($timeShift, $timePeriod, $currencies, $client, $timePeriodFactory) {
                $timePeriodClass = $timePeriodFactory->initialize($timePeriod);
                $timePeriods     = $timePeriodClass->build($timeShift);
                return $client->getHistoryBetweenPeriod(
                    $currencies,
                    $timePeriods['startDatetime'],
                    $timePeriods['endDatetime']
                );
            }
        );

        $urls     = $this->buildHistoryPeriodPaginationUrls($timePeriod, $timeShift, $currencies);
        $response = [
            'data' => $data,
            ...$urls
        ];

        return response()->json($response);
    }

    private function buildHistoryPeriodPaginationUrls(string $timePeriod, int $timeShift, array $currencies): array
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
