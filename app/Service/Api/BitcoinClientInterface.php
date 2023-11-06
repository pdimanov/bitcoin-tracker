<?php

namespace App\Service\Api;

use Illuminate\Http\Client\Response;

interface BitcoinClientInterface
{
    public function getTickers(array $symbols = []): array;
}
