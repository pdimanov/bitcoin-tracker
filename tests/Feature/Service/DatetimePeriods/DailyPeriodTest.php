<?php

namespace Tests\Feature\Service\DatetimePeriods;

use App\Service\DatetimePeriods\DailyPeriod;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DailyPeriodTest extends TestCase
{
    /**
     * @testWith [0]
     *           [1]
     *           [4]
     */
    public function test_build($shift): void
    {
        $this->freezeTime(function () use ($shift) {
            $expectedStartDatetime = Carbon::now()->subDays($shift + 1);
            $expectedEndDatetime   = Carbon::now()->subDays($shift);
            $expected              = [
                'startDatetime' => $expectedStartDatetime,
                'endDatetime'   => $expectedEndDatetime
            ];

            $dailyPeriod = new DailyPeriod();
            $result       = $dailyPeriod->build($shift);

            $this->assertEquals($expected, $result);
        });
    }
}
