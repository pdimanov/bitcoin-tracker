<?php

namespace App\Service\Utilities;

class Calculator
{
    public function sumBaseWithPercent(int $base, int $percentage): int
    {
        return $base + floor(($percentage / 100) * $base);
    }
}
