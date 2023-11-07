<?php

namespace App\Service;

interface SubscriptionCalculatorInterface
{
    public function calculatePriceWithPercentage(int $percent, string $currency): int;
}
