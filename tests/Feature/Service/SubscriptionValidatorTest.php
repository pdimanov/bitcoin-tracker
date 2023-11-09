<?php

namespace Tests\Feature\Service;

use App\DTO\BitcoinDto;
use App\Enum\Currency;
use App\Enum\TimeInterval;
use App\Models\PriceHistory;
use App\Models\Subscription;
use App\Repository\PriceHistoryRepository;
use App\Service\SubscriptionValidator;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscriptionValidatorTest extends TestCase
{
    private SubscriptionValidator $validator;

    public function setUp(): void
    {
        parent::setUp();

        $priceHistoryRepository = new PriceHistoryRepository();
        $this->validator        = new SubscriptionValidator($priceHistoryRepository);
    }

    /**
     * @dataProvider data_provider_for_should_notify_validate_for_fixed_price
     */
    public function test_should_notify_for_fixed_price(
        Subscription $subscription,
        BitcoinDto $bitcoinDto,
        bool $expected,
        string $message
    ): void {
        $result = $this->validator->shouldNotify($subscription, $bitcoinDto);

        $this->assertSame($expected, $result, $message);
    }

    /**
     * @dataProvider data_provider_for_validate_with_percentage_based_price
     */
    public function test_should_notify_with_percentage_based_price(
        Subscription $subscription,
        BitcoinDto $bitcoinDto,
        array $priceHistoryEntries,
        int $travel,
        bool $expected,
        string $message
    ): void {
        foreach ($priceHistoryEntries as $entry) {
            PriceHistory::create([
                'price'    => $entry['price'],
                'currency' => $entry['currency']
            ]);
        }
        $this->travel($travel)->week();
        $result = $this->validator->shouldNotify($subscription, $bitcoinDto);

        $this->assertSame($expected, $result, $message);
    }

    public static function data_provider_for_should_notify_validate_for_fixed_price(): array
    {
        return [
            [
                new Subscription([
                    'email'    => 'test@abv.bg',
                    'price'    => 100,
                    'currency' => Currency::EURO->value
                ]),
                new BitcoinDto(
                    120,
                    Currency::US_DOLLARS->value
                ),
                false,
                'Currency is not the same'
            ],
            [
                new Subscription([
                    'email'    => 'test@abv.bg',
                    'price'    => 100,
                    'currency' => Currency::EURO->value
                ]),
                new BitcoinDto(
                    120,
                    Currency::EURO->value
                ),
                true,
                'Incoming price is higher than the fixed subscribed price'
            ],
            [
                new Subscription([
                    'email'    => 'test@abv.bg',
                    'price'    => 100,
                    'currency' => Currency::EURO->value,
                ]),
                new BitcoinDto(
                    80,
                    Currency::EURO->value
                ),
                false,
                'Incoming price is lower than the fixed subscribed price'
            ],
        ];
    }

    public static function data_provider_for_validate_with_percentage_based_price(): array
    {
        return [
            [
                new Subscription([
                    'email'      => 'test@abv.bg',
                    'price'      => 100,
                    'currency'   => Currency::EURO->value,
                    'percentage' => 10,
                    'interval'   => TimeInterval::SIX_HOURS->value
                ]),
                new BitcoinDto(
                    120,
                    Currency::EURO->value
                ),
                [
                    [
                        'price'    => 100,
                        'currency' => Currency::EURO->value,
                    ],
                    [
                        'price'    => 80,
                        'currency' => Currency::EURO->value,
                    ]
                ],
                0,
                true,
                'In the interval the price difference is more than 10%'
            ],
            [
                new Subscription([
                    'email'      => 'test@abv.bg',
                    'price'      => 100,
                    'currency'   => Currency::US_DOLLARS->value,
                    'percentage' => 10,
                    'interval'   => TimeInterval::SIX_HOURS->value
                ]),
                new BitcoinDto(
                    120,
                    Currency::US_DOLLARS->value
                ),
                [
                    [
                        'price'    => 200,
                        'currency' => Currency::US_DOLLARS->value,
                    ],
                    [
                        'price'    => 195,
                        'currency' => Currency::US_DOLLARS->value,
                    ]
                ],
                0,
                false,
                'In the interval the price difference is less than 10%'
            ],
            [
                new Subscription([
                    'email'      => 'test@abv.bg',
                    'price'      => 100,
                    'currency'   => Currency::EURO->value,
                    'percentage' => 10,
                    'interval'   => TimeInterval::SIX_HOURS->value
                ]),
                new BitcoinDto(
                    120,
                    Currency::EURO->value
                ),
                [
                    [
                        'price'    => 100,
                        'currency' => Currency::EURO->value,
                    ],
                    [
                        'price'    => 80,
                        'currency' => Currency::EURO->value,
                    ]
                ],
                1,
                false,
                'The price difference is more than 10% but outside of the interval'
            ],
        ];
    }
}
