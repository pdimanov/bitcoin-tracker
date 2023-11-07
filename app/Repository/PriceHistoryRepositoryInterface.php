<?php

namespace App\Repository;

use App\Models\PriceHistory;

interface PriceHistoryRepositoryInterface
{
    public function store(array $data): void;

    public function getLatestPriceByCurrency(string $currency): PriceHistory|null;
}
