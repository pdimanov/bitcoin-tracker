<?php

namespace Tests\Feature\Console\Commands;

use App\Enum\Currency;
use App\Repository\PriceHistoryRepository;
use App\Repository\PriceHistoryRepositoryInterface;
use App\Service\Api\BitcoinClientInterface;
use App\Service\Api\BitfinexClient;
use App\Service\Api\Parser\BitcoinParserInterface;
use App\Service\Api\Parser\BitfinexParser;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FetchCurrentBitcoinPriceTest extends TestCase
{
    public function test_bitfinex_fetching_and_saving_of_bitcoin_price(): void
    {
        $this->assertDatabaseMissing('price_histories', [
            'price'    => 123,
            'currency' => Currency::US_DOLLARS->value
        ]);
        $this->assertDatabaseMissing('price_histories', [
            'price'    => 234,
            'currency' => Currency::EURO->value
        ]);

        Http::fake([
            'https://api-pub.bitfinex.com/v2/*' => Http::response([
                [
                    BitfinexClient::BITCOIN_DOLLAR_SYMBOL,
                    123
                ],
                [
                    BitfinexClient::BITCOIN_EURO_SYMBOL,
                    234
                ]
            ])
        ]);

        $client     = new BitfinexClient();
        $loggerMock = \Mockery::mock(Logger::class);
        $parser     = new BitfinexParser($loggerMock);
        $repository = new PriceHistoryRepository();

        $this->app->instance(BitcoinClientInterface::class, $client);
        $this->app->instance(BitcoinParserInterface::class, $parser);
        $this->app->instance(PriceHistoryRepositoryInterface::class, $repository);

        $this->artisan('app:fetch-current-bitcoin-price');

        $this->assertDatabaseHas('price_histories', [
            'price'    => 123,
            'currency' => Currency::US_DOLLARS->value
        ]);
        $this->assertDatabaseHas('price_histories', [
            'price'    => 234,
            'currency' => Currency::EURO->value
        ]);
    }
}
