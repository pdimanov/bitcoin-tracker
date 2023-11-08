<?php

namespace App\Service\Utilities;

class Calculator
{
    public function sumBaseWithPercent(int $base, float $percentage): int
    {
        return $base + floor(($percentage / 100) * $base);
    }
}
