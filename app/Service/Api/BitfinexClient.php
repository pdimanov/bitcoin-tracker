<?php

namespace App\Service\Api;

use Illuminate\Support\Facades\Http;

class BitfinexClient implements BitcoinClientInterface
{
    private const API_BASE_URL = 'https://api-pub.bitfinex.com';
    private const API_VERSION = 'v2';
    private const API_TICKERS_ENDPOINT = 'tickers';
    public const BITCOIN_DOLLAR_SYMBOL = 'tBTCUSD';
    public const BITCOIN_EURO_SYMBOL = 'tBTCEUR';

    public function getTickers(array $symbols = []): array
    {
        if (empty($symbols)) {
            $symbols = $this->getDefaultSymbols();
        }

        $symbols = implode(',', $symbols);

        $params = [
            'page' => self::API_TICKERS_ENDPOINT
        ];

        $response = Http::withUrlParameters(
            array_merge(
                $this->getBaseUrlParameters(),
                $params
            )
        )
            ->withQueryParameters([
                'symbols' => $symbols
            ])
            ->acceptJson()
            ->get('{+baseUrl}/{version}/{page}');

        if (!$response->successful()) {
            throw new \Exception('API call was not successful');
        }

        return $response->json();
    }

    private function getDefaultSymbols(): array
    {
        return [
            self::BITCOIN_DOLLAR_SYMBOL,
            self::BITCOIN_EURO_SYMBOL
        ];
    }

    private function getBaseUrlParameters(): array
    {
        return [
            'baseUrl' => self::API_BASE_URL,
            'version' => self::API_VERSION,
        ];
    }
}
