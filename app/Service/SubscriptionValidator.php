<?php

namespace App\Service;

use App\DTO\BitcoinDto;
use App\Models\PriceHistory;
use App\Models\Subscription;
use App\Repository\PriceHistoryRepositoryInterface;
use Illuminate\Support\Carbon;

class SubscriptionValidator implements SubscriptionValidatorInterface
{
    public function __construct(
        private readonly PriceHistoryRepositoryInterface $priceHistoryRepository
    ) {
    }

    public function shouldNotify(Subscription $subscription, BitcoinDto $bitcoinDto): bool
    {
        if ($subscription->currency !== $bitcoinDto->currency) {
            return false;
        }

        if (is_null($subscription->percentage)) {
            return $this->isValueBasedSubscriptionValid($subscription, $bitcoinDto);
        }

        return $this->isPercentageBasedSubscriptionValid($subscription);
    }

    private function isValueBasedSubscriptionValid(Subscription $subscription, BitcoinDto $bitcoinDto): bool
    {
        if ($bitcoinDto->price >= $subscription->price) {
            return true;
        }

        return false;
    }

    private function isPercentageBasedSubscriptionValid(Subscription $subscription): bool
    {
        $startOfInterval = Carbon::now()->subHours($subscription->interval);
        $prices          = $this->priceHistoryRepository->getMaxAndMinPricesBetweenIntervalByCurrency(
            $subscription->currency,
            $startOfInterval
        );

        if ($prices->maxPrice == $prices->minPrice) {
            return false;
        }

        $percentageDifference = $this->calculatePercentageDifference($prices);
        if ($percentageDifference >= $subscription->percentage) {
            return true;
        }

        return false;
    }

    private function calculatePercentageDifference(PriceHistory $prices)
    {
        return (($prices->maxPrice - $prices->minPrice) / $prices->minPrice) * 100;
    }
}
