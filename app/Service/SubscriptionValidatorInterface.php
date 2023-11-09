<?php

namespace App\Service;

use App\DTO\BitcoinDto;
use App\Models\Subscription;

interface SubscriptionValidatorInterface
{
    public function shouldNotify(Subscription $subscription, BitcoinDto $bitcoinDto): bool;
}
