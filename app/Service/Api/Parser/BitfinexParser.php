<?php

namespace App\Service\Api\Parser;

use App\DTO\BitcoinDto;
use App\Enum\Currency;
use App\Service\Api\BitfinexClient;
use Psr\Log\LoggerInterface;

class BitfinexParser implements BitcoinParserInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function parse(array $data): array
    {
        $parsedData = [];
        foreach ($data as $currencyData) {
            try {
                $dto = new BitcoinDto(
                    $this->validatePrice($currencyData[1] ?? null),
                    $this->parseCurrency($currencyData[0])
                );

                $parsedData[] = $dto;
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
                continue;
            }
        }

        return $parsedData;
    }

    private function validatePrice(?int $price = null): int
    {
        if (is_null($price)) {
            throw new \Exception('Price field is not set');
        }

        if ($price < 0) {
            throw new \Exception('Price is negative?');
        }

        return $price;
    }

    private function parseCurrency(string $symbol): string
    {
        $currency = match ($symbol) {
            BitfinexClient::BITCOIN_EURO_SYMBOL => Currency::EURO->value,
            BitfinexClient::BITCOIN_DOLLAR_SYMBOL => Currency::US_DOLLARS->value,
            default => false
        };

        if (!$currency) {
            throw new \Exception('Unknown symbol: ' . $symbol);
        }

        return $currency;
    }
}
