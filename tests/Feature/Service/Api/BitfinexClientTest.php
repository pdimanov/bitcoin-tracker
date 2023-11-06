<?php

namespace Tests\Feature\Service\Api;

use App\Service\Api\BitfinexClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BitfinexClientTest extends TestCase
{
    public function test_getTickers(): void
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

        $client = new BitfinexClient();

        $response = $client->getTickers();

        $this->assertSame([
            BitfinexClient::BITCOIN_DOLLAR_SYMBOL,
            123
        ], $response[0]);

        $this->assertSame([
            BitfinexClient::BITCOIN_EURO_SYMBOL,
            234
        ], $response[1]);
    }
}
