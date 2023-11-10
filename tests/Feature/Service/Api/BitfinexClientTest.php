<?php

namespace Tests\Feature\Service\Api;

use App\DTO\BitcoinDto;
use App\Enum\Currency;
use App\Service\Api\BitfinexClient;
use App\Service\Api\Parser\BitfinexParser;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BitfinexClientTest extends TestCase
{
    private BitfinexClient $client;

    public function setUp(): void
    {
        parent::setUp();

        $loggerMock   = \Mockery::mock(Logger::class);
        $parser       = new BitfinexParser($loggerMock);
        $this->client = new BitfinexClient($parser);
    }

    public function test_successful_get_current_price_with_default_params(): void
    {
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

        $response = $this->client->getCurrentPrice();

        $this->assertEquals([
            new BitcoinDto(123, Currency::US_DOLLARS->value),
            new BitcoinDto(234, Currency::EURO->value)
        ], $response);
    }

    public function test_successful_get_current_price(): void
    {
        Http::fake([
            'https://api-pub.bitfinex.com/v2/*' => Http::response([
                [
                    BitfinexClient::BITCOIN_DOLLAR_SYMBOL,
                    123
                ]
            ])
        ]);

        $response = $this->client->getCurrentPrice([Currency::US_DOLLARS->value]);

        $this->assertEquals([new BitcoinDto(123, Currency::US_DOLLARS->value)], $response);
    }

    public function test_failing_get_current_price(): void
    {
        Http::fake([
            'https://api-pub.bitfinex.com/v2/*' => Http::response([], 500)
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('API call was not successful');

        $this->client->getCurrentPrice();
    }
}
