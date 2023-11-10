<?php

namespace App\Service\Api;

use App\Enum\Currency;
use App\Service\Api\Parser\BitcoinParserInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BitfinexClient implements BitcoinClientInterface
{
    private const API_BASE_URL                  = 'https://api-pub.bitfinex.com';
    private const API_VERSION                   = 'v2';
    private const API_TICKERS_ENDPOINT          = 'tickers';
    private const API_TICKERS_HISTORY_ENDPOINT  = 'tickers/hist';
    private const API_TICKERS_HISTORY_MAX_LIMIT = 250;
    public const  BITCOIN_DOLLAR_SYMBOL         = 'tBTCUSD';
    public const  BITCOIN_EURO_SYMBOL           = 'tBTCEUR';

    public function __construct(
        private readonly BitcoinParserInterface $parser
    ) {
    }

    public function getCurrentPrice(array $currencies = []): array
    {
        $urlParams = [
            'page' => self::API_TICKERS_ENDPOINT
        ];

        $queryParams = [
            'symbols' => $this->prepareSymbols($currencies)
        ];

        $data = $this->returnGetHttpJsonResult($urlParams, $queryParams);

        return $this->parser->parsePrice($data);
    }

    public function getHistoryBetweenPeriod(array $currencies, Carbon $start, ?Carbon $end = null): array
    {
        $urlParams = [
            'page' => self::API_TICKERS_HISTORY_ENDPOINT
        ];

        $queryParams = [
            'symbols' => $this->prepareSymbols($currencies),
            'start'   => $this->parseDatetimeToMs($start),
            'end'     => $this->parseDatetimeToMs($end),
            'limit'   => self::API_TICKERS_HISTORY_MAX_LIMIT
        ];

        $limiter = 0;
        $result  = [];
        do {
            $shouldContinueFetching = false;
            $data                   = $this->returnGetHttpJsonResult($urlParams, $queryParams);
            if (count($data) == self::API_TICKERS_HISTORY_MAX_LIMIT) {
                $shouldContinueFetching = true;
            }
            $result = array_merge($result, $data);

            $lastResultDatetime = end($data)[12];
            // Bitfinex starts from end to beginning, so we need to update the end query
            $queryParams['end'] = $lastResultDatetime - 1; // Decrement the timestamp so that we don't include the last result

            $limiter++;
            if ($limiter == 5) {
                Log::error('Limiter in do while loop was reached. Endless loop?');
                break;
            }
        } while ($shouldContinueFetching);

        return $this->parser->parseHistoryPriceData($result);
    }

    private function prepareSymbols(array $currencies = []): string
    {
        if (empty($currencies)) {
            $symbols = $this->getDefaultSymbols();
        } else {
            $symbols = $this->mapCurrernciesToSymbols($currencies);
        }

        return implode(',', $symbols);
    }

    private function parseDatetimeToMs(Carbon $datetime): int
    {
        return $datetime->getTimestampMs();
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

    private function mapCurrernciesToSymbols(array $currencies): array
    {
        $symbols = [];
        foreach ($currencies as $currency) {
            $symbols[] = $this->mapCurrencyToSymbol($currency);
        }

        return $symbols;
    }

    private function mapCurrencyToSymbol(string $currency): string
    {
        if (!in_array($currency, Currency::getAllValues())) {
            throw new \Exception("Unsupported currency: $currency");
        }

        return match ($currency) {
            Currency::EURO->value => self::BITCOIN_EURO_SYMBOL,
            Currency::US_DOLLARS->value => self::BITCOIN_DOLLAR_SYMBOL
        };
    }

    private function returnGetHttpJsonResult(array $urlParams, array $queryParams)
    {
        $response = Http::withUrlParameters(
            array_merge(
                $this->getBaseUrlParameters(),
                $urlParams
            )
        )
            ->withQueryParameters($queryParams)
            ->acceptJson()
            ->get('{+baseUrl}/{version}/{page}');

        if (!$response->successful()) {
            throw new \Exception('API call was not successful: ' . $response->body());
        }

        return $response->json();
    }
}
