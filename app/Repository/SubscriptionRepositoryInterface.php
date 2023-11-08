<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;

interface SubscriptionRepositoryInterface
{
    public function getValidSubscriptions(array $bitcoinDtos): Collection;
}
