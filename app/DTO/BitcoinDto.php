<?php

namespace App\DTO;

use Carbon\Carbon;

class BitcoinDto
{
    public function __construct(
        public readonly int $price,
        public readonly string $currency,
        public readonly ?Carbon $dateTime = null
    ) {
    }
}
