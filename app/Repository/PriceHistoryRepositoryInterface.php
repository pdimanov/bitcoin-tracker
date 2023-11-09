<?php

namespace App\Repository;

use App\Models\PriceHistory;

interface PriceHistoryRepositoryInterface
{
    public function store(array $data): PriceHistory;

    public function getLatestByCurrency(string $currency): PriceHistory|null;

    public function getMaxAndMinPricesBetweenIntervalByCurrency(
        string $currency,
        \DateTime $start,
        ?\DateTime $end = null
    ): PriceHistory;
}
