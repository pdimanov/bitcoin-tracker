<?php

namespace App\Service\Api\Parser;

use App\DTO\BitcoinDto;

interface BitcoinParserInterface
{
    /**
     * @param array $data
     * @return array<int, BitcoinDto>
     */
    public function parsePrice(array $data): array;

    /**
     * @param array $data
     * @return array<int, BitcoinDto>
     */
    public function parseHistoryPriceData(array $data): array;
}
