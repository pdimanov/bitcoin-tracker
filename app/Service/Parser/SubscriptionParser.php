<?php

namespace App\Service\Parser;

use App\Service\SubscriptionCalculatorInterface;
use App\Service\Utilities\TimeIntervalBuilder;

class SubscriptionParser implements SubscriptionParserInterface
{
    public function __construct(
        private readonly SubscriptionCalculatorInterface $subscriptionCalculator,
        private readonly TimeIntervalBuilder $intervalBuilder
    ) {
    }

    public function parse($data): array
    {
        $subscription             = [];
        $subscription['currency'] = $data['currency'];
        $subscription['email']    = $data['email'];

        if ($data['isPercentageBased']) {
            $subscription['price'] = $this->subscriptionCalculator->calculatePriceWithPercentage(
                $data['pricePercentage'],
                $data['currency']
            );
            $subscription['expiration_date'] = $this->intervalBuilder->create($data['interval']);
        } else {
            $subscription['price'] = $data['price'];
        }

        return $subscription;
    }
}
