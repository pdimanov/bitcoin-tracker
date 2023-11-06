<?php

namespace App\DTO;

class BitcoinDto
{
    public function __construct(
        public readonly int $price,
        public readonly string $currency
    ) {
    }
}
