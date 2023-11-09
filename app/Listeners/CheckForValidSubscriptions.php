<?php

namespace App\Listeners;

use App\DTO\BitcoinDto;
use App\Events\NewBitcoinPricesFetched;
use App\Jobs\ProcessSubscriptionNotifications;
use App\Repository\SubscriptionRepositoryInterface;
use App\Service\SubscriptionValidatorInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class CheckForValidSubscriptions implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly SubscriptionRepositoryInterface $subscriptionRepository,
        private readonly SubscriptionValidatorInterface $subscriptionValidator
    ) {
    }

    /**
     * Handle the event.
     */
    public function handle(NewBitcoinPricesFetched $event): void
    {
        $bitcoinDtos   = $event->bitcoinDtos;
        $subscriptions = $this->subscriptionRepository->getValidSubscriptions($bitcoinDtos);

        if (empty($subscriptions)) {
            Log::info('No subscriptions matched.');
            return;
        }

        $dtos = $this->transformDtos($bitcoinDtos);

        $validSubscriptions = 0;
        foreach ($subscriptions as $subscription) {
            if ($this->subscriptionValidator->shouldNotify($subscription, $dtos[$subscription->currency])) {
                ProcessSubscriptionNotifications::dispatch($subscription);
                $validSubscriptions++;
            }
        }

        if ($validSubscriptions) {
            Log::info("Dispatched $validSubscriptions valid subscriptions");
        }
    }

    private function transformDtos(array $bitcoinDtos): array
    {
        $transformedArray = [];
        /** @var BitcoinDto $bitcoinDto */
        foreach ($bitcoinDtos as $bitcoinDto) {
            $transformedArray[$bitcoinDto->currency] = $bitcoinDto;
        }

        return $transformedArray;
    }
}
