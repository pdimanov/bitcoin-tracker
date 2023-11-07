<?php

namespace App\Service\Parser;

interface SubscriptionParserInterface
{
    public function parse($data): array;
}
