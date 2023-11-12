<?php

namespace App\Http\Controllers\Api;

use App\Enum\Currency;
use App\Enum\TimePeriod;
use App\Http\Requests\PriceHistoryPeriodRequest;
use App\Service\PriceHistoryInterface;
use Illuminate\Http\JsonResponse;

class PriceController
{
    public function getHistoryPeriod(
        PriceHistoryPeriodRequest $request,
        PriceHistoryInterface $priceHistory
    ): JsonResponse {
        $query      = $request->validated();
        $timeShift  = $query['timeShift'] ?? 0;
        $timePeriod = $query['timePeriod'] ?? TimePeriod::DAILY->value;
        $currencies = $query['currencies'] ?? Currency::getAllValues();

        $data     = $priceHistory->getData($timePeriod, $timeShift, $currencies);
        $urls     = $priceHistory->getPaginationUrls($timePeriod, $timeShift, $currencies);
        $response = [
            'data' => $data,
            ...$urls
        ];

        return response()->json($response);
    }
}
