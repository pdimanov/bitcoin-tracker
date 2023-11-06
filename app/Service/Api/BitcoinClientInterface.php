<?php

namespace App\Service\Api;

use Illuminate\Http\Client\Response;

interface BitcoinClientInterface
{
    public function getTicker(string $symbol): Response;
    public function getTickers(array $symbols = []): Response;
}
