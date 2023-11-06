<?php

namespace App\Repository;

interface PriceHistoryRepositoryInterface
{
    public function store(array $data): void;
}
