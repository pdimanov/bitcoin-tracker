<?php

namespace App\Repository;

use App\Models\PriceHistory;
use App\Service\Utilities\CacheKeyCreator;
use Illuminate\Support\Facades\Cache;

class CachedPriceHistoryRepository implements PriceHistoryRepositoryInterface
{
    public function __construct(
        private readonly PriceHistoryRepository $priceHistoryRepository,
    ) {
    }

    public function store(array $data): PriceHistory
    {
        return $this->priceHistoryRepository->store($data);
    }

    public function getLatestByCurrency(string $currency): PriceHistory|null
    {
        $cacheKey = CacheKeyCreator::createLatestPriceByCurrencyKey($currency);

        return Cache::remember($cacheKey, 15, function () use ($currency) {
            return $this->priceHistoryRepository->getLatestByCurrency($currency);
        });
    }

    public function getMaxAndMinPricesBetweenIntervalByCurrency(
        string $currency,
        \DateTime $start,
        ?\DateTime $end = null
    ): PriceHistory {
        return $this->priceHistoryRepository->getMaxAndMinPricesBetweenIntervalByCurrency($currency, $start, $end);
    }
}
