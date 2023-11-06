<?php

namespace App\Repository;

use App\Models\PriceHistory;

class PriceHistoryRepository implements PriceHistoryRepositoryInterface
{
    public function store(array $data): void
    {
        PriceHistory::create($data);
    }
}
