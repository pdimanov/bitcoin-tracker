<?php

namespace Tests\Feature\Listeners;

use App\DTO\BitcoinDto;
use App\Enum\Currency;
use App\Events\NewBitcoinPricesFetched;
use App\Listeners\CheckForValidSubscriptions;
use App\Models\Subscription;
use App\Repository\PriceHistoryRepository;
use App\Repository\SubscriptionRepository;
use App\Service\SubscriptionValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CheckForValidSubscriptionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_subsriptions_for_both_currencies_get_returned(): void
    {
        $this->freezeTime(function (Carbon $time) {
            $this->assertDatabaseMissing('subscriptions', [
                'last_notified' => Carbon::now()
            ]);
            Subscription::create([
                'email'    => 'test@abv.bg',
                'price'    => 90,
                'currency' => Currency::US_DOLLARS->value,
            ]);
            Subscription::create([
                'email'    => 'test@abv.bg',
                'price'    => 70,
                'currency' => Currency::EURO->value
            ]);
            $eventData              = [
                new BitcoinDto(100, Currency::US_DOLLARS->value),
                new BitcoinDto(80, Currency::EURO->value)
            ];
            $event                  = new NewBitcoinPricesFetched($eventData);
            $subscriptionRepository = new SubscriptionRepository();
            $priceHistoryRepository = new PriceHistoryRepository();
            $subscriptionValidator  = new SubscriptionValidator($priceHistoryRepository);
            $listener               = new CheckForValidSubscriptions($subscriptionRepository, $subscriptionValidator);

            $listener->handle($event);
            $this->assertDatabaseHas('subscriptions', [
                'last_notified' => Carbon::now()
            ]);
        });
    }
}
