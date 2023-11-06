<?php

namespace App\Service\Api;

use App\Service\Api\Parser\BitcoinParserInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class BitfinexClient implements BitcoinClientInterface
{
    private const API_BASE_URL = 'https://api-pub.bitfinex.com';
    private const API_VERSION = 'v2';
    private const API_TICKER_ENDPOINT = 'ticker';
    private const API_TICKERS_ENDPOINT = 'tickers';
    public const BITCOIN_DOLLAR_SYMBOL = 'tBTCUSD';
    public const BITCOIN_EURO_SYMBOL = 'tBTCEUR';

    public function __construct(
        private readonly Http $client,
    ) {
    }

    public function getTicker(string $symbol): Response
    {
        return $this->client::withUrlParameters([
            'endpoint' => self::API_BASE_URL,
            'version'  => self::API_VERSION,
            'page'     => self::API_TICKER_ENDPOINT,
            'symbol'   => $symbol
        ])->get('{+endpoint}/{version}/{page}/{symbol}');
    }

    public function getTickers(array $symbols = []): Response
    {
        if (empty($symbols)) {
            $symbols = $this->getDefaultSymbols();
        }

        $symbols = implode(',', $symbols);

        return $this->client::withUrlParameters([
            'endpoint' => self::API_BASE_URL,
            'version'  => self::API_VERSION,
            'page'     => self::API_TICKERS_ENDPOINT
        ])->withQueryParameters([
            'symbols' => $symbols
        ])->get('{+endpoint}/{version}/{page}');
    }

    private function getDefaultSymbols(): array
    {
        return [
            self::BITCOIN_DOLLAR_SYMBOL,
            self::BITCOIN_EURO_SYMBOL
        ];
    }
}
