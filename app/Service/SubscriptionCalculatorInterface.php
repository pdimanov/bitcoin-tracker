<?php

namespace App\Service;

interface SubscriptionCalculatorInterface
{
    public function calculatePriceWithPercentage(float $percent, string $currency): int;
}
