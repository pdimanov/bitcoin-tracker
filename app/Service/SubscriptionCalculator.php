<?php

namespace App\Service;

use App\Repository\PriceHistoryRepositoryInterface;
use App\Service\Utilities\Calculator;

class SubscriptionCalculator implements SubscriptionCalculatorInterface
{
    public function __construct(
        private readonly PriceHistoryRepositoryInterface $priceHistoryRepository,
        private readonly Calculator $calculator
    ) {
    }

    public function calculatePriceWithPercentage(int $percent, string $currency): int
    {
        $latestPrice = $this->priceHistoryRepository->getLatestPriceByCurrency($currency);

        if (!$latestPrice) {
            throw new \Exception('Latest price with currency ' . $currency . ' not found.');
        }

        return $this->calculator->sumBaseWithPercent($latestPrice->price, $percent);
    }
}
