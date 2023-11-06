<?php

namespace App\Service\Api\Parser;

interface BitcoinParserInterface
{
    public function parse(array $data): array;
}
