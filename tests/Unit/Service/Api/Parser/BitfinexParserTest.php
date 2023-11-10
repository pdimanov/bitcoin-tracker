<?php

namespace Tests\Unit\Service\Api\Parser;

use App\DTO\BitcoinDto;
use App\Enum\Currency;
use App\Service\Api\BitfinexClient;
use App\Service\Api\Parser\BitfinexParser;
use Illuminate\Log\Logger;
use PHPUnit\Framework\TestCase;

class BitfinexParserTest extends TestCase
{
    private const INVALID_SYMBOL = 'invalidSymbol';

    private BitfinexParser $parser;

    private function setupMocks(string $loggerMessage = '')
    {
        $loggerMock   = \Mockery::mock(Logger::class);
        $loggerMock->shouldReceive('error')->with($loggerMessage);
        $this->parser = new BitfinexParser($loggerMock);
    }

    public function test_parse_with_empty_argument(): void
    {
        $this->setupMocks();
        $data = [];

        $result = $this->parser->parsePrice($data);

        $this->assertEmpty($result);
    }

    public function test_successful_parse_with_1_argument(): void
    {
        $this->setupMocks();
        $data = [
            [
                BitfinexClient::BITCOIN_DOLLAR_SYMBOL,
                123,
                111
            ]
        ];

        $result = $this->parser->parsePrice($data);

        $expected = [
            new BitcoinDto(123, Currency::US_DOLLARS->value),
        ];

        $this->assertEquals($expected, $result);
    }

    public function test_successful_parse_with_2_argument(): void
    {
        $this->setupMocks();
        $data = [
            [
                BitfinexClient::BITCOIN_DOLLAR_SYMBOL,
                123,
                111
            ],
            [
                BitfinexClient::BITCOIN_EURO_SYMBOL,
                234,
                222
            ]
        ];

        $result = $this->parser->parsePrice($data);

        $expected = [
            new BitcoinDto(123, Currency::US_DOLLARS->value),
            new BitcoinDto(234, Currency::EURO->value),
        ];

        $this->assertEquals($expected, $result);
    }

    public function test_parse_with_1_valid_argument_and_1_invalid_symbol(): void
    {
        $this->setupMocks('Unknown symbol: ' . self::INVALID_SYMBOL);
        $data = [
            [
                self::INVALID_SYMBOL,
                123,
                111
            ],
            [
                BitfinexClient::BITCOIN_EURO_SYMBOL,
                234,
                222
            ]
        ];

        $result = $this->parser->parsePrice($data);

        $expected = [
            new BitcoinDto(234, Currency::EURO->value),
        ];

        $this->assertEquals($expected, $result);
    }

    public function test_parse_with_1_valid_argument_and_1_negative_price(): void
    {
        $this->setupMocks('Price is negative?');
        $data = [
            [
                BitfinexClient::BITCOIN_DOLLAR_SYMBOL,
                -123,
                111
            ],
            [
                BitfinexClient::BITCOIN_EURO_SYMBOL,
                234,
                222
            ]
        ];

        $result = $this->parser->parsePrice($data);

        $expected = [
            new BitcoinDto(234, Currency::EURO->value),
        ];

        $this->assertEquals($expected, $result);
    }

    public function test_parse_with_1_valid_argument_and_1_unset_price(): void
    {
        $this->setupMocks('Price field is not set');
        $data = [
            [
                BitfinexClient::BITCOIN_DOLLAR_SYMBOL,
            ],
            [
                BitfinexClient::BITCOIN_EURO_SYMBOL,
                234,
                222
            ]
        ];

        $result = $this->parser->parsePrice($data);

        $expected = [
            new BitcoinDto(234, Currency::EURO->value),
        ];

        $this->assertEquals($expected, $result);
    }
}
