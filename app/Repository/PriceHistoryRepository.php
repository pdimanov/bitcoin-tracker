<?php

namespace App\Repository;

use App\Models\PriceHistory;
use Illuminate\Support\Carbon;

class PriceHistoryRepository implements PriceHistoryRepositoryInterface
{
    public function store(array $data): PriceHistory
    {
        return PriceHistory::create($data);
    }

    public function getLatestByCurrency(string $currency): PriceHistory|null
    {
        return PriceHistory::where('currency', $currency)
            ->latest()
            ->first();
    }

    public function getMaxAndMinPricesBetweenIntervalByCurrency(
        string $currency,
        \DateTime $start,
        ?\DateTime $end = null
    ): PriceHistory {
        if (is_null($end)) {
            $end = Carbon::now();
        }

        return PriceHistory::query()
            ->selectRaw('MAX(price) as maxPrice, MIN(price) as minPrice')
            ->where('currency', $currency)
            ->whereBetween('created_at', [$start, $end])
            ->first();
    }
}
