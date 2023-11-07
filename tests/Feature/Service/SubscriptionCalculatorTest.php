<?php

namespace Tests\Feature\Service;

use App\Enum\Currency;
use App\Models\PriceHistory;
use App\Repository\PriceHistoryRepository;
use App\Service\SubscriptionCalculator;
use App\Service\Utilities\Calculator;
use Tests\TestCase;

class SubscriptionCalculatorTest extends TestCase
{
    private SubscriptionCalculator $subscriptionCalculator;

    public function setUp(): void
    {
        parent::setUp();

        $repo = new PriceHistoryRepository();
        $calculator = new Calculator();
        $this->subscriptionCalculator = new SubscriptionCalculator($repo, $calculator);
    }

    public function test_calculate_price_with_percentage_throws_exception(): void
    {
        $repoMock = \Mockery::mock(PriceHistoryRepository::class);
        $repoMock->shouldReceive('getLatestPriceByCurrency')->andReturn(null);
        $calculator = new Calculator();
        $this->subscriptionCalculator = new SubscriptionCalculator($repoMock, $calculator);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Latest price with currency EUR not found.');

        $this->subscriptionCalculator->calculatePriceWithPercentage(1, Currency::EURO->value);
    }

    public function test_calculate_price_with_percentage(): void
    {
        PriceHistory::create([
            'price' => 100,
            'currency' => Currency::EURO->value
        ]);

        $result = $this->subscriptionCalculator->calculatePriceWithPercentage(20, Currency::EURO->value);

        $this->assertSame(120, $result);
    }
}
