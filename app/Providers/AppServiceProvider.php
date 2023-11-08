<?php

namespace App\Providers;

use App\Repository\PriceHistoryRepository;
use App\Repository\PriceHistoryRepositoryInterface;
use App\Repository\SubscriptionRepository;
use App\Repository\SubscriptionRepositoryInterface;
use App\Service\Api\BitcoinClientInterface;
use App\Service\Api\BitfinexClient;
use App\Service\Api\Parser\BitcoinParserInterface;
use App\Service\Api\Parser\BitfinexParser;
use App\Service\Parser\SubscriptionParser;
use App\Service\Parser\SubscriptionParserInterface;
use App\Service\SubscriptionCalculator;
use App\Service\SubscriptionCalculatorInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(BitcoinClientInterface::class, BitfinexClient::class);
        $this->app->bind(BitcoinParserInterface::class, BitfinexParser::class);
        $this->app->bind(PriceHistoryRepositoryInterface::class, PriceHistoryRepository::class);
        $this->app->bind(SubscriptionCalculatorInterface::class, SubscriptionCalculator::class);
        $this->app->bind(SubscriptionParserInterface::class, SubscriptionParser::class);
        $this->app->bind(SubscriptionRepositoryInterface::class, SubscriptionRepository::class);
    }
}
