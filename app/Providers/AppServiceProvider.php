<?php

namespace App\Providers;

use App\Repository\CachedPriceHistoryRepository;
use App\Repository\PriceHistoryRepositoryInterface;
use App\Repository\SubscriptionRepository;
use App\Repository\SubscriptionRepositoryInterface;
use App\Service\Api\BitcoinClientInterface;
use App\Service\Api\BitfinexClient;
use App\Service\Api\Parser\BitcoinParserInterface;
use App\Service\Api\Parser\BitfinexParser;
use App\Service\PriceHistory;
use App\Service\PriceHistoryInterface;
use App\Service\SubscriptionValidator;
use App\Service\SubscriptionValidatorInterface;
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
        $this->app->bind(PriceHistoryRepositoryInterface::class, CachedPriceHistoryRepository::class);
        $this->app->bind(SubscriptionRepositoryInterface::class, SubscriptionRepository::class);
        $this->app->bind(SubscriptionValidatorInterface::class, SubscriptionValidator::class);
        $this->app->bind(PriceHistoryInterface::class, PriceHistory::class);
    }
}
