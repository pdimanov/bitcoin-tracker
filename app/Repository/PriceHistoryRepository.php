<?php

namespace App\Repository;

use App\Models\PriceHistory;

class PriceHistoryRepository implements PriceHistoryRepositoryInterface
{
    public function store(array $data): void
    {
        PriceHistory::create($data);
    }

    public function getLatestPriceByCurrency(string $currency): PriceHistory|null
    {
        return PriceHistory::where('currency', $currency)
            ->latest()
            ->first();
    }
}
