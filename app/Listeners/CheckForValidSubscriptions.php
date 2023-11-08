<?php

namespace App\Listeners;

use App\Events\NewBitcoinPricesFetched;
use App\Jobs\ProcessSubscriptionNotifications;
use App\Repository\SubscriptionRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class CheckForValidSubscriptions implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly SubscriptionRepositoryInterface $subscriptionRepository
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

        Log::info('Found ' . count($subscriptions) . ' subscribers ready to be notified');
        foreach ($subscriptions as $subscription) {
            ProcessSubscriptionNotifications::dispatch($subscription);
        }
    }
}
