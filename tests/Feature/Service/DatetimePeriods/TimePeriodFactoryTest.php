<?php

namespace Tests\Feature\Service\DatetimePeriods;

use App\Enum\TimePeriod;
use App\Service\DatetimePeriods\DailyPeriod;
use App\Service\DatetimePeriods\TimePeriodFactory;
use App\Service\DatetimePeriods\WeeklyPeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TimePeriodFactoryTest extends TestCase
{
    /**
     * @dataProvider date_provider_for_test_initialize
     */
    public function test_initialize($period, $expected): void
    {
        $factory = new TimePeriodFactory();
        $result = $factory->initialize($period);

        $this->assertSame($expected, $result::class);
    }

    public function test_initialize_with_unsupported_period(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unsupported time period type: unsupported');

        $factory = new TimePeriodFactory();
        $factory->initialize('unsupported');
    }

    public static function date_provider_for_test_initialize(): array
    {
        return [
            [
                TimePeriod::DAILY->value,
                DailyPeriod::class
            ],
            [
                TimePeriod::WEEKLY->value,
                WeeklyPeriod::class
            ]
        ];
    }
}
