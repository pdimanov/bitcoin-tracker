<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PriceTest extends TestCase
{
    /**
     * @dataProvider data_provider_for_test_get_history_bitcoin_prices
     */
    public function test_get_history_bitcoin_prices(array $bitfinexClientResponse, array $expected): void
    {
        Http::fake([
            "https://api-pub.bitfinex.com/v2/*" => Http::response($bitfinexClientResponse),
        ]);

        $response = $this->get(route('api.price.historyPeriod'));
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals($expected['data'], $data['data']);
        $this->assertStringContainsString($expected['previousUrl'], $data['previousUrl']);
    }

    public static function data_provider_for_test_get_history_bitcoin_prices(): array
    {
        return [
            [
                [
                    ["tBTCEUR", 34817, null, 34851, null, null, null, null, null, null, null, null, 1699725614000],
                    ["tBTCUSD", 37115, null, 37116, null, null, null, null, null, null, null, null, 1699725614000],
                    ["tBTCEUR", 34843, null, 34875, null, null, null, null, null, null, null, null, 1699722014000],
                    ["tBTCUSD", 37133, null, 37134, null, null, null, null, null, null, null, null, 1699722014000],
                    ["tBTCEUR", 34772, null, 34800, null, null, null, null, null, null, null, null, 1699718414000],
                    ["tBTCUSD", 37072, null, 37073, null, null, null, null, null, null, null, null, 1699718414000]
                ],
                [
                    'data'        => [
                        'periods' => ['2023-11-11 18:00:14', '2023-11-11 17:00:14', '2023-11-11 16:00:14'],
                        'EUR'     => [34817, 34843, 34772],
                        'USD'     => [37115, 37133, 37072]
                    ],
                    'previousUrl' => '/api/price/history-period?timePeriod=day&timeShift=1&currencies=EUR%2CUSD'
                ]
            ]
        ];
    }
}
